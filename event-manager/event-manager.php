<?php
/*
 * Plugin Name: Event Manager
 * Description: Wtyczka stworzona w ramach zadania rekrutacyjnego na stanowisko Junior WordPress Developer w Develtio
 * Version: 20.11.2025
 * Requires at least: 6.4
 * Requires PHP: 8.1
 * Author: Szymon Kempisty (kempistyszymon@gmail.com)
 * Text Domain: event-manager
 * Requires Plugins: advanced-custom-fields
 */

if (!defined('ABSPATH')){
	exit;
}

define('EM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('EM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once EM_PLUGIN_DIR . 'includes/cpt-registration.php';
require_once EM_PLUGIN_DIR . 'includes/acf-fields.php';
require_once EM_PLUGIN_DIR . 'includes/ajax-handlers.php';






function em_load_single_template($em_template){
	if(is_singular('event')){
		$em_template = EM_PLUGIN_DIR. 'templates/single-event.php';
	}

	return $em_template;
}


 
add_action( 'init', 'em_register_taxonomy' );
add_action( 'init', 'em_register_post_type' );
add_action('acf/init', 'em_acf_add_local_field_group');
add_filter( 'single_template', 'em_load_single_template' );
add_action( 'wp_enqueue_scripts', 'em_load_assets' );

function em_load_assets() {
    wp_enqueue_style('em-style', EM_PLUGIN_URL . 'assets/css/style.css', array(), '1.0');
    wp_enqueue_script(
        'event-register',
        EM_PLUGIN_URL . 'assets/js/event-register.js',
        true 
    );
    wp_localize_script( 'event-register', 'em_ajax_object', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce('em_registration_nonce')
	));
}

// Aktywacja pluginu 
function em_plugin_activate() { 
	em_register_post_type(); 
    em_register_taxonomy();
	em_acf_add_local_field_group();
	em_load_single_template();
	flush_rewrite_rules(); // Odświeżanie permalinków
}
register_activation_hook( __FILE__, 'em_plugin_activate' );