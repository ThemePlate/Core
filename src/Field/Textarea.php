<?php

/**
 * Setup a field type
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Field;

class Textarea {

	public static function render( $field ) {

		$defaults = array( 'rows' => 4 );
		$attrs    = ThemePlate_Helper_Main::fool_proof( $defaults, $field['options'] );
		echo '<textarea name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" ';
		foreach ( $attrs as $attr => $value ) {
			echo esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
		}
		echo ( $field['required'] ? ' required="required"' : '' ) . '>' . esc_textarea( $field['value'] ) . '</textarea>';

	}

}
