<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Helper\FieldsHelper;
use ThemePlate\Core\Helper\FormHelper;

class FieldsHelperTest extends TestCase {
	public function for_getting_type(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with a text type field' => array(
				'text',
				'string',
			),
			'with any type of field' => array(
				'any',
				'string',
			),
			'with a group type field' => array(
				'group',
				'object',
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_getting_type
	 */
	public function test_getting_type( string $type, string $expected ): void {
		$field = FormHelper::make_field( 'test', compact( 'type' ) );

		$this->assertSame( $expected, FieldsHelper::get_type( $field ) );
	}
	public function for_getting_default(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with a text type field' => array(
				'text',
				array(),
				'',
			),
			'with a text have default' => array(
				'text',
				array( 'default' => 'test' ),
				'test',
			),
			'with a group no fields' => array(
				'group',
				array(),
				'',
			),
			'with a group has fields' => array(
				'group',
				array(
					'fields' => array(
						'test' => array( 'type' => 'text' ),
					),
				),
				array( 'test' => '' ),
			),
			'with fields have default' => array(
				'group',
				array(
					'default' => array(
						'test' => 'this',
					),
					'fields' => array(
						'test' => array(
							'type' => 'text',
						),
						'another' => array(
							'type' => 'text',
							'default' => 'one',
						),
					),
				),
				array(
					'test' => 'this',
					'another' => 'one',
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_getting_default
	 */
	public function test_getting_default( string $type, array $config, $expected ): void {
		$field = FormHelper::make_field( 'test', array_merge( $config, compact( 'type' ) ) );

		$this->assertSame( $expected, FieldsHelper::get_default( $field ) );
	}
}
