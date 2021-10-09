<?php

/**
 * Helper functions
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Helper;

class Meta {

	public static function should_display( array $meta_box, int $object_id ): bool {

		$check = true;

		foreach ( array( 'show', 'hide' ) as $key ) {
			if ( ! empty( $meta_box[ $key . '_on_cb' ] ) || ! empty( $meta_box[ $key . '_on_id' ] ) ) {
				$check = self::display_check( $object_id, $meta_box[ $key . '_on_cb' ], $meta_box[ $key . '_on_id' ] );

				if ( 'hide' === $key ) {
					$check = ! $check;
				}
			}
		}

		return $check;

	}


	private static function display_check( int $object_id, ?callable $callback, int $id ): bool {

		$result = true;

		if ( $callback ) {
			$result = $callback( $object_id );
		}

		if ( $id ) {
			$result = array_intersect( (array) $object_id, (array) $id );
		}

		return (bool) $result;

	}


	public static function normalize_options( array $container ): array {

		foreach ( array( 'show', 'hide' ) as $key ) {
			if ( ! empty( $container[ $key . '_on' ] ) ) {
				$container = self::option_check( $key . '_on', $container );
			}
		}

		return $container;

	}


	private static function option_check( string $type, array $container ): array {

		$additional = array(
			$type . '_cb' => null,
			$type . '_id' => 0,
		);
		$value      = $container[ $type ];

		if ( ! Main::is_sequential( $value ) ) {
			$container[ $type ] = array( $value );
			$value              = array( $value );
		}

		$container = array_merge( $additional, $container );

		if ( 1 === count( $value ) ) {
			if ( is_callable( $value[0] ) ) {
				$container[ $type . '_cb' ] = $value[0];
				unset( $container[ $type ] );
			} elseif ( isset( $value[0]['key'] ) && 'id' === $value[0]['key'] ) {
				$container[ $type . '_id' ] = $value[0]['value'];
				unset( $container[ $type ] );
			}
		}

		return $container;

	}


	public static function render_options( array $container ): void {

		if ( ! empty( $container['show_on'] ) || ! empty( $container['hide_on'] ) ) {
			echo '<div class="themeplate-options"';

			foreach ( array( 'show', 'hide' ) as $key ) {
				if ( ! empty( $container[ $key . '_on' ] ) ) {
					$value = wp_json_encode( $container[ $key . '_on' ], JSON_NUMERIC_CHECK );
					echo ' data-' . $key . '="' . esc_attr( $value ) . '"'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}

			echo '></div>';
		}

	}


	public static function display_column( int $object_id, array $args ): void {

		$value = get_metadata( $args['object_type'], $object_id, $args['id'], ! $args['repeatable'] );

		if ( ! $value ) {
			return;
		}

		if ( 1 === count( (array) $value ) ) {
			if ( $args['repeatable'] || $args['multiple'] ) {
				print_r( $value[0] ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			} else {
				echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			return;
		}

		echo '<ul>';

		foreach ( $value as $val ) {
			echo '<li>';
			print_r( $val ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions
			echo '</li>';
		}

		echo '</ul>';

	}

}
