<?php

/**
 * Setup custom forms
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core;

use ThemePlate\Core\Helper\Main;
use ThemePlate\Core\Helper\Meta;

abstract class Form {

	protected ?Fields $fields = null;
	protected array $defaults = array(
		'description' => '',
		'data_prefix' => '',
		'style'       => '',
		'show_on'     => array(),
		'hide_on'     => array(),
		'context'     => 'normal',
		'priority'    => 'default',
	);
	protected array $config;
	protected string $title;


	public function __construct( string $title, array $config = array() ) {

		$this->title  = $title;
		$this->config = $this->check( $config );

	}


	abstract protected function initialize( array &$config ): void;


	abstract protected function fields_group_key(): string;


	abstract protected function maybe_nonce_fields(): void;


	abstract protected function should_display_field( Field $field ): bool;


	abstract protected function get_field_value( Field $field );


	protected function check( array $config ): array {

		$config = Main::fool_proof( $this->defaults, $config );
		$config = Meta::normalize_options( $config );

		$config['form_id'] = sanitize_title( $this->title );

		$this->initialize( $config );

		return $config;

	}


	public function fields( array $list ): self {

		$this->fields = new Fields( $list );

		return $this;

	}


	public function layout_postbox(): void {

		global $wp_version;

		printf( '<div id="themeplate_%s" class="tpo postbox">', esc_attr( $this->config['form_id'] ) );

		if ( version_compare( $wp_version, '5.5', '<' ) ) {
			echo '<button type="button" class="handlediv button-link" aria-expanded="true">';
				/* translators: %s: metabox title */
				echo '<span class="screen-reader-text">' . esc_html( sprintf( __( 'Toggle panel: %s' ), $this->title ) ) . '</span>';
				echo '<span class="toggle-indicator" aria-hidden="true"></span>';
			echo '</button>';

			echo '<h2 class="hndle"><span>' . esc_html( $this->title ) . '</span></h2>';
		} else {
			echo '<div class="postbox-header">';
				echo '<h2 class="hndle"><span>' . esc_html( $this->title ) . '</span></h2>';

				echo '<div class="handle-actions hide-if-no-js">';
					echo '<button type="button" class="handlediv button-link" aria-expanded="true">';
						/* translators: %s: metabox title */
						echo '<span class="screen-reader-text">' . esc_html( sprintf( __( 'Toggle panel: %s' ), $this->title ) ) . '</span>';
						echo '<span class="toggle-indicator" aria-hidden="true"></span>';
					echo '</button>';
				echo '</div>';
			echo '</div>';
		}

			echo '<div class="inside">';
				$this->layout_inside();
			echo '</div>';
		echo '</div>';

	}


	public function layout_inside(): void {

		$prefix = $this->config['data_prefix'];

		$this->maybe_nonce_fields();
		Meta::render_options( $this->config );

		if ( ! empty( $this->config['description'] ) ) {
			echo '<p class="description">' . $this->config['description'] . '</p>'; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		echo '<div class="fields-container ' . esc_attr( $this->config['style'] ) . '">';

		if ( null !== $this->fields ) {
			foreach ( $this->fields->get_collection() as $field ) {
				if ( ! $this->should_display_field( $field ) ) {
					continue;
				}

				$field->set_id( $this->fields_group_key() . '_' . $field->data_key( $prefix ) );
				$field->set_name( $this->fields_group_key() . '[' . $field->data_key( $prefix ) . ']' );

				$this->fields->layout( $field, $this->get_field_value( $field ) );
			}
		}

		echo '</div>';

	}

}
