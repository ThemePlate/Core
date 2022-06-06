<?php

/**
 * Setup a field type
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Field;

use ThemePlate\Core\Field;
use ThemePlate\Core\Helper\MainHelper;
use WP_Query;
use WP_Term_Query;
use WP_User_Query;

class TypeField extends Field {

	public function render( $value ): void {

		$config_options = $this->get_config( 'options' );

		switch ( $this->get_config( 'type' ) ) {
			default:
			case 'post':
				$action   = 'tp_posts';
				$defaults = array( 'post_type' => $this->get_config( 'type' ) );

				if ( MainHelper::is_sequential( $config_options ) ) {
					$config_options = array( 'post_type' => $config_options );
				}

				break;
			case 'user':
				$action   = 'tp_users';
				$defaults = array( 'role' => '' );

				if ( MainHelper::is_sequential( $config_options ) ) {
					$config_options = array( 'role' => $config_options );
				}

				break;
			case 'term':
				$action   = 'tp_terms';
				$defaults = array( 'taxonomy' => null );

				if ( MainHelper::is_sequential( $config_options ) ) {
					$config_options = array( 'taxonomy' => $config_options );
				}

				break;
		}

		$args = MainHelper::fool_proof( $defaults, $config_options );

		echo '<select disabled><option>Loading values...</option></select>';
		echo '<select class="themeplate-select2 select2-hidden-accessible"
				name="' . esc_attr( $this->get_config( 'name' ) ) . ( $this->get_config( 'multiple' ) ? '[]' : '' ) . '"
				id="' . esc_attr( $this->get_config( 'id' ) ) . '"' .
				( $this->get_config( 'multiple' ) ? ' multiple="multiple"' : '' ) .
				( $this->get_config( 'none' ) ? ' data-none="true"' : '' ) .
				( $this->get_config( 'required' ) ? ' required="required"' : '' ) .
				'>';

		if ( $value ) {
			foreach ( (array) $value as $item ) {
				echo '<option value="' . esc_attr( $item ) . '" selected="selected">' . esc_html( $item ) . '</option>';
			}
		}

		echo '</select>';
		echo '<div class="select2-options"
				data-action="' . esc_attr( $action ) . '"
				data-options="' . esc_attr( wp_json_encode( $args, JSON_NUMERIC_CHECK ) ) . '"
				data-value="' . esc_attr( wp_json_encode( $value, JSON_NUMERIC_CHECK ) ) . '"
				></div>';

	}


	private static int $count      = 10;
	private static array $prefixes = array();

	// phpcs:disable WordPress.Security.NonceVerification
	public static function get_posts(): void {

		$return   = array(
			'results'    => array(),
			'pagination' => array(
				'more' => false,
			),
		);
		$defaults = array(
			's'              => $_GET['search'] ?? '',
			'fields'         => 'ids',
			'posts_per_page' => isset( $_GET['ids__in'] ) ? -1 : self::$count,
			'post__in'       => $_GET['ids__in'] ?? '',
		);

		if ( is_array( $_GET['options']['post_type'] ) && 1 < count( $_GET['options']['post_type'] ) ) {
			$defaults['orderby'] = array(
				'post_type' => 'ASC',
			);
		}

		$query = new WP_Query( array_merge( $defaults, $_GET['options'], $_GET['page'] ) );

		if ( $_GET['page']['paged'] < $query->max_num_pages ) {
			$return['pagination']['more'] = true;
		}

		foreach ( $query->posts as $post ) {
			$return['results'][] = array(
				'id'   => $post,
				'text' => self::get_prefix( $post, $_GET['options'] ) . get_the_title( $post ),
			);
		}

		echo wp_json_encode( $return );

		wp_die();

	}


	private static function get_prefix( int $id, array $options ): string {

		$prefix = '';

		if ( is_array( $options['post_type'] ) && 1 < count( $options['post_type'] ) ) {
			$type = get_post_type( $id );

			if ( ! array_key_exists( $type, self::$prefixes ) ) {
				$object                  = get_post_type_object( $type );
				self::$prefixes[ $type ] = $object->labels->singular_name;
			}

			$prefix = self::$prefixes[ $type ] . ' | ';
		}

		return $prefix;

	}


	public static function get_users(): void {

		$return   = array(
			'results'    => array(),
			'pagination' => array(
				'more' => false,
			),
		);
		$defaults = array(
			'search'  => isset( $_GET['search'] ) ? '*' . $_GET['search'] . '*' : '',
			'fields'  => array( 'ID', 'display_name' ),
			'number'  => isset( $_GET['ids__in'] ) ? -1 : self::$count,
			'include' => $_GET['ids__in'] ?? '',
		);
		$query    = new WP_User_Query( array_merge( $defaults, $_GET['options'], $_GET['page'] ) );

		if ( $_GET['page']['paged'] < ceil( $query->get_total() / self::$count ) ) {
			$return['pagination']['more'] = true;
		}

		foreach ( $query->get_results() as $user ) {
			$return['results'][] = array(
				'id'   => $user->ID,
				'text' => $user->display_name,
			);
		}

		echo wp_json_encode( $return );

		wp_die();

	}


	public static function get_terms(): void {

		$return   = array(
			'results'    => array(),
			'pagination' => array(
				'more' => false,
			),
		);
		$offset   = ( $_GET['page']['paged'] > 0 ) ? self::$count * ( $_GET['page']['paged'] - 1 ) : 1;
		$defaults = array(
			'search'  => $_GET['search'] ?? '',
			'fields'  => 'id=>name',
			'number'  => isset( $_GET['ids__in'] ) ? 0 : self::$count,
			'include' => $_GET['ids__in'] ?? '',
			'offset'  => $offset,
		);
		$total    = wp_count_terms( $_GET['options']['taxonomy'] );
		$query    = new WP_Term_Query( array_merge( $defaults, $_GET['options'] ) );

		if ( ! is_wp_error( $total ) && $_GET['page']['paged'] < ceil( $total / self::$count ) ) {
			$return['pagination']['more'] = true;
		}

		foreach ( $query->get_terms() as $id => $name ) {
			$return['results'][] = array(
				'id'   => $id,
				'text' => $name,
			);
		}

		echo wp_json_encode( $return );

		wp_die();

	}

}