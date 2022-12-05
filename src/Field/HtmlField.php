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

	public function render( $value ): void {

		echo wp_kses_post( $this->get_config( 'default' ) );

	}

}
