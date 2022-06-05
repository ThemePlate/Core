<?php

/**
 * @package ThemePlate
 */

namespace Tests\Integration;

use ThemePlate\Core\Helper\Form;
use WP_UnitTestCase;

class FormTest extends WP_UnitTestCase {
	public function test_enqueue_assets(): void {
		Form::enqueue_assets( 'test' ); // custom hook suffix
		$this->assertTrue( wp_script_is( 'themeplate-script' ) );

		Form::enqueue_assets( 'test' ); // firing again is short-circuited
		$this->assertTrue( wp_script_is( 'themeplate-script' ) );
		wp_dequeue_script( 'themeplate-script' ); // force dequeue

		Form::enqueue_assets( 'post.php' ); // in a classic editing screen
		$this->assertTrue( wp_script_is( 'themeplate-show-hide-classic' ) );
		wp_dequeue_script( 'themeplate-script' ); // force dequeue

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['post'] = $this->factory()->post->create_and_get();

		Form::enqueue_assets( 'post.php' ); // in a gutenberg editing screen
		$this->assertTrue( wp_script_is( 'themeplate-show-hide-gutenberg' ) );
		wp_dequeue_script( 'themeplate-script' ); // force dequeue
	}
}
