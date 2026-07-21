<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Field\TypeField;

class TypeFieldTest extends TestCase {
	public function test_render_keeps_type_restriction_without_options_config(): void {
		$field = new TypeField( 'test', array( 'type' => 'post' ) );

		ob_start();
		$field->render( $field->clone_value() );
		$output = ob_get_clean();

		$this->assertIsString( $output );
		$this->assertStringContainsString( esc_attr( '"post_type":["post"]' ), $output );
	}
}
