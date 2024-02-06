<?php

/**
 * Setup a field type
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Field;

use ThemePlate\Core\Field;

class ColorField extends Field {

	public function render( $value ): void {

		$default = $this->get_config( 'default' );

		if ( $this->get_config( 'repeatable' ) && is_array( $default ) ) {
			$default = $default[0];
		}

		echo '<input
				type="text"
				name="' . esc_attr( $this->get_config( 'name' ) ) . '"
				id="' . esc_attr( $this->get_config( 'id' ) ) . '"
				class="themeplate-color-picker"
				value="' . esc_attr( $value ) . '"
				' . ( $default ? ' data-default-color="' . esc_attr( $default ) . '"' : '' );
		if ( ! empty( $this->get_config( 'options' ) ) ) {
			$values = wp_json_encode( $this->get_config( 'options' ) );

			echo ' data-palettes="' . esc_attr( $values ) . '"';
		}
		echo ' />';

	}

}
