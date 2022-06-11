<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
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
}
