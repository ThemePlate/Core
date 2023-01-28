<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Field;
use ThemePlate\Core\Helper\FormHelper;

class FormHelperTest extends TestCase {
	public function test_make_field(): void {
		$namespace = 'ThemePlate\Core\Field';

		foreach ( glob( dirname( __FILE__, 3 ) . '/src/Field/*.php', GLOB_MARK ) as $file ) {
			$base  = basename( $file, '.php' );
			$type  = strtolower( str_replace( 'Field', '', $base ) );
			$field = FormHelper::make_field( 'test', compact( 'type' ) );

			$this->assertInstanceOf( Field::class, $field );
			$this->assertSame( $namespace . '\\' . $base, get_class( $field ) );
		}
	}

	public function test_make_field_with_undefined_type(): void {
		$field = FormHelper::make_field( 'wanted', array() );

		$this->assertInstanceOf( FormHelper::get_field_class( Field::DEFAULTS['type'] ), $field );
	}
}
