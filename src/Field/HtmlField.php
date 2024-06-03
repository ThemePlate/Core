<?php

/**
 * Setup a field type
 *
 * @package ThemePlate
 * @since 0.1.0
 */

namespace ThemePlate\Core\Field;

use ThemePlate\Core\Field;

class HtmlField extends Field {

	private function handle( $value ): string {

		if ( is_string( $value ) ) {
			return $value;
		}

		if ( is_scalar( $value ) || null === $value ) {
			return (string) $value;
		}

		return wp_json_encode( $value );

	}


	public function render( $value ): void {

		echo wp_kses_post( $this->handle( $value ) );

	}

}
