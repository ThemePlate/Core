<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Fields;
use ThemePlate\Core\Helper\FieldsHelper;
use ThemePlate\Core\Helper\FormHelper;

class FieldsHelperTest extends TestCase {
	public function for_building_schema(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'without a group type field' => array(
				array(
					'test' => array( 'type' => 'text' ),
					'any' => array( 'type' => 'any' ),
				),
				'',
				array(
					'test' => array(
						'type' => 'string',
						'default' => '',
					),
					'any' => array(
						'type' => 'string',
						'default' => '',
					),
				),
			),
			'with a link type field' => array(
				array(
					'test' => array( 'type' => 'link' ),
				),
				'',
				array(
					'test' => array(
						'type' => 'object',
						'default' => array(
							'url' => '',
							'text' => '',
							'target' => '',
						),
						'properties' => array(
							'url' => array(
								'type' => 'string',
								'default' => '',
							),
							'text' => array(
								'type' => 'string',
								'default' => '',
							),
							'target' => array(
								'type' => 'string',
								'default' => '',
							),
						),
					),
				),
			),
			'with a link has default' => array(
				array(
					'test' => array(
						'type' => 'link',
						'default' => array(
							'url' => '#',
							'text' => 'Tester',
						),
					),
				),
				'',
				array(
					'test' => array(
						'type' => 'object',
						'default' => array(
							'url' => '#',
							'text' => 'Tester',
							'target' => '',
						),
						'properties' => array(
							'url' => array(
								'type' => 'string',
								'default' => '#',
							),
							'text' => array(
								'type' => 'string',
								'default' => 'Tester',
							),
							'target' => array(
								'type' => 'string',
								'default' => '',
							),
						),
					),
				),
			),
			'with a group no fields' => array(
				array(
					'test' => array( 'type' => 'text' ),
					'any' => array( 'type' => 'any' ),
					'group' => array( 'type' => 'group' ),
				),
				'',
				array(
					'test' => array(
						'type' => 'string',
						'default' => '',
					),
					'any' => array(
						'type' => 'string',
						'default' => '',
					),
				),
			),
			'with a group has fields' => array(
				array(
					'test' => array( 'type' => 'text' ),
					'any' => array( 'type' => 'any' ),
					'group' => array(
						'type' => 'group',
						'fields' => array(
							'another' => array( 'type' => 'text' ),
						),
					),
				),
				'',
				array(
					'test' => array(
						'type' => 'string',
						'default' => '',
					),
					'any' => array(
						'type' => 'string',
						'default' => '',
					),
					'group' => array(
						'type' => 'object',
						'default' => array(
							'another' => '',
						),
						'properties' => array(
							'another' => array(
								'type' => 'string',
								'default' => '',
							),
						),
					),
				),
			),
			'with a data prefix' => array(
				array(
					'test' => array( 'type' => 'text' ),
					'group' => array(
						'type' => 'group',
						'fields' => array(
							'any' => array( 'type' => 'text' ),
						),
					),
				),
				'my_',
				array(
					'my_test' => array(
						'type' => 'string',
						'default' => '',
					),
					'my_group' => array(
						'type' => 'object',
						'default' => array(
							'any' => '',
						),
						'properties' => array(
							'any' => array(
								'type' => 'string',
								'default' => '',
							),
						),
					),
				),
			),
			'with a multiple-able field' => array(
				array(
					'type' => array( 'type' => 'type' ),
					'mtype' => array(
						'type' => 'type',
						'multiple' => true,
					),
					'select' => array(
						'type' => 'select',
						'default' => 'test',
					),
					'mselect' => array(
						'type' => 'select',
						'multiple' => true,
						'default' => 'test',
					),
					'mfile' => array(
						'type' => 'file',
						'multiple' => true,
						'default' => array( 'tester' ),
					),
					'date' => array(
						'type' => 'date',
						'repeatable' => true,
						'default' => array( 'testing' ),
					),
					'checkbox' => array(
						'type' => 'date',
						'multiple' => true,
						'repeatable' => true,
						'options' => array(
							'try' => 'try',
							'this' => 'this',
							'one' => 'one',
						),
						'default' => array( 'try', 'one' ),
					),
				),
				'',
				array(
					'type' => array(
						'type' => 'string',
						'default' => '',
					),
					'mtype' => array(
						'type' => 'array',
						'default' => array( '' ),
						'items' => array( 'type' => 'string' ),
					),
					'select' => array(
						'type' => 'string',
						'default' => 'test',
					),
					'mselect' => array(
						'type' => 'array',
						'default' => array( 'test' ),
						'items' => array( 'type' => 'string' ),
					),
					'mfile' => array(
						'type' => 'array',
						'default' => array( 'tester' ),
						'items' => array( 'type' => 'string' ),
					),
					'date' => array(
						'type' => 'array',
						'default' => array( 'testing' ),
						'items' => array( 'type' => 'string' ),
					),
					'checkbox' => array(
						'type' => 'array',
						'default' => array( array( 'try', 'one' ) ),
						'items' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'string' ),
						),
					),
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_building_schema
	 */
	public function test_building_schema( array $fields, string $data_prefix, array $expected ): void {
		$schema = FieldsHelper::build_schema( new Fields( $fields ), $data_prefix );

		$this->assertSame( $expected, $schema );
	}

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
			'with a link type field' => array(
				'link',
				'object',
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

		$this->assertSame( $expected, FieldsHelper::get_schema_type( $field ) );
		FormHelperTest::render_no_issues( $field );
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
			'with a multi-file type' => array(
				'file',
				array( 'multiple' => true ),
				array( '' ),
			),
			'with a multi-file have default' => array(
				'file',
				array(
					'multiple' => true,
					'default' => 'test',
				),
				array( 'test' ),
			),
			'with a link type field' => array(
				'link',
				array(),
				array(
					'url' => '',
					'text' => '',
					'target' => '',
				),
			),
			'with a link have default' => array(
				'link',
				array( 'default' => array( 'url' => '#' ) ),
				array(
					'url' => '#',
					'text' => '',
					'target' => '',
				),
			),
			'with a group no fields' => array(
				'group',
				array(),
				array(),
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

		$this->assertSame( $expected, FieldsHelper::get_default_value( $field ) );
		FormHelperTest::render_no_issues( $field );
	}

	/**
	 * @dataProvider for_getting_default
	 */
	public function test_repeatable_getting_default( string $type, array $config, $expected ): void {
		$config['repeatable'] = true;

		$field = FormHelper::make_field( 'test', array_merge( $config, compact( 'type' ) ) );

		$this->assertSame( array( $expected ), FieldsHelper::get_default_value( $field ) );
		FormHelperTest::render_no_issues( $field );
	}

	public function for_group_with_defaults( bool $repeatable ): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'repeatable' => $repeatable,
			'type' => 'group',
			'default' => array(
				array(
					'test' => 'this',
					'another' => 'one',
				),
				array(
					'test' => 'again',
					'another' => 'time',
				),
			),
			'fields' => array(
				'test' => array(
					'type' => 'text',
				),
				'another' => array(
					'type' => 'text',
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	public function test_group_with_defaults(): void {
		$field = FormHelper::make_field(
			'test',
			$this->for_group_with_defaults( false ),
		);

		$this->assertSame(
			array(
				'test'    => 'this',
				'another' => 'one',
			),
			FieldsHelper::get_default_value( $field )
		);
		$this->assertSame(
			array(
				'test' => array(
					'type'       => 'object',
					'default'    => array(
						'test'    => 'this',
						'another' => 'one',
					),
					'properties' => array(
						'test'    => array(
							'type'    => 'string',
							'default' => 'this',
						),
						'another' => array(
							'type'    => 'string',
							'default' => 'one',
						),
					),
				),
			),
			FieldsHelper::build_schema( new Fields( array( $field ) ) )
		);
		FormHelperTest::render_no_issues( $field );
	}

	public function test_group_with_defaults_repeatable(): void {
		$field = FormHelper::make_field(
			'test',
			$this->for_group_with_defaults( true ),
		);

		$this->assertSame(
			array(
				array(
					'test'    => 'this',
					'another' => 'one',
				),
				array(
					'test'    => 'again',
					'another' => 'time',
				),
			),
			FieldsHelper::get_default_value( $field )
		);
		$this->assertSame(
			array(
				'test' => array(
					'type'    => 'array',
					'default' => array(
						array(
							'test'    => 'this',
							'another' => 'one',
						),
						array(
							'test'    => 'again',
							'another' => 'time',
						),
					),
					'items'   => array(
						'type'       => 'object',
						'properties' => array(
							'test'    => array(
								'type'    => 'string',
								'default' => 'this',
							),
							'another' => array(
								'type'    => 'string',
								'default' => 'one',
							),
						),
					),
				),
			),
			FieldsHelper::build_schema( new Fields( array( $field ) ) )
		);
		FormHelperTest::render_no_issues( $field );
	}

	public function for_link_with_defaults( bool $repeatable ): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'repeatable' => $repeatable,
			'type' => 'link',
			'default' => array(
				array(
					'url' => '#',
					'text' => 'first',
				),
				array( 'url' => '/second' ),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	public function test_link_with_defaults(): void {
		$field = FormHelper::make_field(
			'test',
			$this->for_link_with_defaults( false ),
		);

		$this->assertSame(
			array(
				'url'    => '#',
				'text'   => 'first',
				'target' => '',
			),
			FieldsHelper::get_default_value( $field )
		);
		$this->assertSame(
			array(
				'test' => array(
					'type'       => 'object',
					'default'    => array(
						'url'    => '#',
						'text'   => 'first',
						'target' => '',
					),
					'properties' => array(
						'url'    => array(
							'type'    => 'string',
							'default' => '#',
						),
						'text'   => array(
							'type'    => 'string',
							'default' => 'first',
						),
						'target' => array(
							'type'    => 'string',
							'default' => '',
						),
					),
				),
			),
			FieldsHelper::build_schema( new Fields( array( $field ) ) )
		);
		FormHelperTest::render_no_issues( $field );
	}

	public function test_link_with_defaults_repeatable(): void {
		$field = FormHelper::make_field(
			'test',
			$this->for_link_with_defaults( true ),
		);

		$this->assertSame(
			array(
				array(
					'url'    => '#',
					'text'   => 'first',
					'target' => '',
				),
				array(
					'url'    => '/second',
					'text'   => '',
					'target' => '',
				),
			),
			FieldsHelper::get_default_value( $field )
		);
		$this->assertSame(
			array(
				'test' => array(
					'type'    => 'array',
					'default' => array(
						array(
							'url'    => '#',
							'text'   => 'first',
							'target' => '',
						),
						array(
							'url'    => '/second',
							'text'   => '',
							'target' => '',
						),
					),
					'items'   => array(
						'type'       => 'object',
						'properties' => array(
							'url'    => array(
								'type'    => 'string',
								'default' => '#',
							),
							'text'   => array(
								'type'    => 'string',
								'default' => 'first',
							),
							'target' => array(
								'type'    => 'string',
								'default' => '',
							),
						),
					),
				),
			),
			FieldsHelper::build_schema( new Fields( array( $field ) ) )
		);
		FormHelperTest::render_no_issues( $field );
	}
}
