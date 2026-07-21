<?php

/**
 * @package ThemePlate
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThemePlate\Core\Helper\MetaHelper;

/**
 * @phpstan-import-type FConfig from \ThemePlate\Core\Field
 */
class MetaHelperTest extends TestCase {
	public function for_should_display(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with callback returning false' => array(
				array(
					'on_cb' => fn(): bool => false,
				),
				'',
				false,
			),
			'with callback returning true' => array(
				array(
					'on_cb' => fn(): bool => true,
				),
				'',
				true,
			),
			'with not wanted id' => array(
				array(
					'on_id' => array( 'test' ),
				),
				'tester',
				false,
			),
			'with the wanted id' => array(
				array(
					'on_id' => array( 'tester' ),
				),
				'tester',
				true,
			),
			'with the wanted id but falsy callback' => array(
				array(
					'on_cb' => fn(): bool => false,
					'on_id' => array( 'tester' ),
				),
				'tester',
				false,
			),
			'with not wanted id but truthy callback' => array(
				array(
					'on_cb' => fn(): bool => true,
					'on_id' => array( 'test' ),
				),
				'tester',
				true,
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @return FConfig
	 */
	protected function transform_should_display( string $type, array $config ): array {
		$value = (array) array_combine(
			array_map(
				fn( string $key, string $type ): string => $type . '_' . $key,
				array_keys( $config ),
				array_fill( 0, count( $config ), $type )
			),
			array_values( $config )
		);

		/** @var FConfig $value */
		return $value;
	}

	/**
	 * @dataProvider for_should_display
	 */
	public function test_should_display_show( array $config, string $current_id, bool $should_display ): void {
		if ( $should_display ) {
			$this->assertTrue( MetaHelper::should_display( $this->transform_should_display( 'show', $config ), $current_id ) );
		} else {
			$this->assertFalse( MetaHelper::should_display( $this->transform_should_display( 'show', $config ), $current_id ) );
		}
	}

	/**
	 * @dataProvider for_should_display
	 */
	public function test_should_display_hide( array $config, string $current_id, bool $should_display ): void {
		if ( ! $should_display ) {
			$this->assertTrue( MetaHelper::should_display( $this->transform_should_display( 'hide', $config ), $current_id ) );
		} else {
			$this->assertFalse( MetaHelper::should_display( $this->transform_should_display( 'hide', $config ), $current_id ) );
		}
	}

	public function callable_callback(): bool {
		return true;
	}

	public function for_combined_should_display(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'show cb true and hide cb false' => array(
				array(
					'show_on_cb' => fn(): bool => true,
					'hide_on_cb' => fn(): bool => false,
				),
				'',
				true,
			),
			'show cb true and hide cb true' => array(
				array(
					'show_on_cb' => fn(): bool => true,
					'hide_on_cb' => fn(): bool => true,
				),
				'',
				false,
			),
			'show cb false and hide cb false' => array(
				array(
					'show_on_cb' => fn(): bool => false,
					'hide_on_cb' => fn(): bool => false,
				),
				'',
				false,
			),
			'show cb false and hide cb true' => array(
				array(
					'show_on_cb' => fn(): bool => false,
					'hide_on_cb' => fn(): bool => true,
				),
				'',
				false,
			),
			'show id match and hide id no match' => array(
				array(
					'show_on_id' => array( 'tester' ),
					'hide_on_id' => array( 'other' ),
				),
				'tester',
				true,
			),
			'show id match and hide id match' => array(
				array(
					'show_on_id' => array( 'tester' ),
					'hide_on_id' => array( 'tester' ),
				),
				'tester',
				false,
			),
			'show id no match and hide id no match' => array(
				array(
					'show_on_id' => array( 'other' ),
					'hide_on_id' => array( 'another' ),
				),
				'tester',
				false,
			),
			'show cb true and hide id no match' => array(
				array(
					'show_on_cb' => fn(): bool => true,
					'hide_on_id' => array( 'other' ),
				),
				'tester',
				true,
			),
			'show cb true and hide id match' => array(
				array(
					'show_on_cb' => fn(): bool => true,
					'hide_on_id' => array( 'tester' ),
				),
				'tester',
				false,
			),
			'show id match and hide cb true' => array(
				array(
					'show_on_id' => array( 'tester' ),
					'hide_on_cb' => fn(): bool => true,
				),
				'tester',
				false,
			),
			'show id match and hide cb false' => array(
				array(
					'show_on_id' => array( 'tester' ),
					'hide_on_cb' => fn(): bool => false,
				),
				'tester',
				true,
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_combined_should_display
	 */
	public function test_combined_should_display( array $config, string $current_id, bool $expected ): void {
		$this->assertSame( $expected, MetaHelper::should_display( $config, $current_id ) );
	}

	public function for_normalize_options(): array {
		$callable = fn(): bool => true;

		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with callable value' => array(
				array(
					'show_on' => array( $callable ),
				),
				array(
					'show_on_cb' => $callable,
				),
			),
			'with callable callback' => array(
				array(
					'show_on' => array( $this, 'callable_callback' ),
				),
				array(
					'show_on_cb' => array( $this, 'callable_callback' ),
				),
			),
			'with key-value pair for ID' => array(
				array(
					'show_on' => array(
						'key' => 'id',
						'value' => 911,
					),
				),
				array(
					'show_on_id' => array( 911 ),
				),
			),
			'with multiple value for ID' => array(
				array(
					'show_on' => array(
						'key' => 'id',
						'value' => array( 911, 4688 ),
					),
				),
				array(
					'show_on_id' => array( 911, 4688 ),
				),
			),
			'with key-value pair for JS' => array(
				array(
					'show_on' => array(
						'key' => '#field_id',
						'value' => 'test',
					),
				),
				array(
					'show_on' => array(
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
			),
			'with multiple key-value pair' => array(
				array(
					'show_on' => array(
						$callable,
						array(
							'key' => 'id',
							'value' => array( 911, 4688 ),
						),
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
				array(
					'show_on' => array(
						$callable,
						array(
							'key' => 'id',
							'value' => array( 911, 4688 ),
						),
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @param array<string, mixed> $container
	 * @param array<string, mixed> $expected
	 *
	 * @dataProvider for_normalize_options
	 */
	public function test_normalize_options( array $container, array $expected ): void {
		$this->assertSame( $expected, MetaHelper::normalize_options( $container ) );
	}

	public function for_render_options(): array {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'with callable data' => array(
				array(
					'show_on_cb' => 'date',
					'hide_on_cb' => 'date',
				),
			),
			'with ID data' => array(
				array(
					'show_on_id' => array( 911 ),
					'hide_on_id' => array( 911 ),
				),
			),
			'with key-value pair for JS show' => array(
				array(
					'show_on' => array(
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
			),
			'with key-value pair for JS hide' => array(
				array(
					'hide_on' => array(
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
			),
			'with multiple key-value pair show' => array(
				array(
					'show_on' => array(
						array(
							'key' => 'id',
							'value' => array( 911, 4688 ),
						),
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
			),
			'with multiple key-value pair hide' => array(
				array(
					'hide_on' => array(
						array(
							'key' => 'id',
							'value' => array( 911, 4688 ),
						),
						array(
							'key' => '#field_id',
							'value' => 'test',
						),
					),
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider for_render_options
	 */
	public function test_render_options( array $container ): void {
		ob_start();
		MetaHelper::render_options( $container );

		$output = ob_get_clean();

		$this->assertIsString( $output );

		if ( array_key_exists( 'show_on', $container ) ) {
			$this->assertStringContainsString( 'data-show', $output );
		} elseif ( array_key_exists( 'hide_on', $container ) ) {
			$this->assertStringContainsString( 'data-hide', $output );
		} else {
			$this->assertEmpty( $output );
		}
	}
}
