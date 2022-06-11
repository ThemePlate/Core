<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Field\InputField;
use ThemePlate\Core\Helper\FormHelper;

class FieldTest extends TestCase {
	public function for_default_can_be_an_array(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with a group type field' => array(
				'group',
				array(),
				true,
			),
			'with a text type field' => array(
				'text',
				array(),
				false,
			),
			'with a text multiple' => array(
				'text',
				array( 'multiple' => true ),
				false,
			),
			'with a text repeatable' => array(
				'text',
				array( 'repeatable' => true ),
				true,
			),
			'with a select type field' => array(
				'select',
				array(),
				false,
			),
			'with a select multiple' => array(
				'select',
				array( 'multiple' => true ),
				true,
			),
			'with a select repeatable' => array(
				'select',
				array( 'repeatable' => true ),
				true,
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_default_can_be_an_array
	 */
	public function test_default_can_be_an_array( string $type, array $config, bool $can_have_multiple_value ): void {
		$default = array(
			'test' => 'me',
			'out'  => 'please',
		);
		$field   = FormHelper::make_field( 'test', array_merge( $config, compact( 'type', 'default' ) ) );

		if ( $can_have_multiple_value ) {
			$this->assertIsArray( $field->get_config( 'default' ) );
		} else {
			$this->assertIsString( $field->get_config( 'default' ) );
		}
	}
	public function for_maybe_adjust_value(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with a non-repeatable and string' => array(
				false,
				null,
				'test',
				'test',
			),
			'with a non-repeatable and array' => array(
				false,
				null,
				array( 'test' ),
				array( 'test' ),
			),
			'with a repeatable and string' => array(
				true,
				1,
				'test',
				array( 'test' ),
			),
			'with a repeatable and array' => array(
				true,
				1,
				array( 'test' ),
				array( 'test' ),
			),
			'with 3 repeatable and string' => array(
				true,
				3,
				'test',
				array( 'test', 'test', 'test' ),
			),
			'with 3 repeatable and array' => array(
				true,
				3,
				array( 'test' ),
				array( 'test', '', '' ),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_maybe_adjust_value
	 */
	public function test_maybe_adjust_value( bool $repeatable, ?int $minimum, $default, $expected_value ): void {
		$field = new InputField( 'test', compact( 'repeatable', 'minimum', 'default' ) );
		$value = $default;

		$field->maybe_adjust( $value );
		$this->assertSame( $expected_value, $value );
	}
}
