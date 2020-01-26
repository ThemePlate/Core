<?php

/**
 * Autoloaded functions
 * 
 * @package ThemePlate
 * @since 0.1.0
 */


if ( ! function_exists( 'themeplate_ajax_actions' ) ) {
	function themeplate_ajax_actions() {
		add_action( 'wp_ajax_tp_posts', array( 'ThemePlate\Core\Field\Object', 'get_posts' ) );
		add_action( 'wp_ajax_tp_users', array( 'ThemePlate\Core\Field\Object', 'get_users' ) );
		add_action( 'wp_ajax_tp_terms', array( 'ThemePlate\Core\Field\Object', 'get_terms' ) );
	}

	themeplate_ajax_actions();
}