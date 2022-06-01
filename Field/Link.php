<?php

/**
 * Setup a field type
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Field;

use ThemePlate\Core\Field;

class Link extends Field {

	public function render( $value ): void {

		echo '<div id="' . esc_attr( $this->get_config( 'id' ) ) . '" class="themeplate-link">';
		echo '<input type="button" class="button link-select" value="Select" />';
		echo '<input type="button" class="button link-remove' . ( empty( $value ) ? ' hidden' : '' ) . '" value="Remove" />';

		foreach ( array( 'url', 'text', 'target' ) as $attr ) {
			$value = $value[ $attr ] ?? '';

			echo '<input
				type="hidden"
				class="input-' . esc_attr( $attr ) . '"
				name="' . esc_attr( $this->get_config( 'name' ) ) . '[' . $attr . ']"
				value="' . esc_attr( $value ) .
				'">';
		}

		echo '<div class="link-holder">';

		if ( isset( $value['text'] ) ) {
			echo '<span>' . esc_html( $value['text'] ) . '</span>';
		}

		if ( isset( $value['url'] ) ) {
			echo '<a href="' . esc_url( $value['url'] ) . '" target="_blank">' . esc_html( $value['url'] ) . '</a>';
		}

		echo '</div>';
		echo '</div>';

	}

}
