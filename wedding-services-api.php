<?php

/**
 * Plugin Name: Lost Lens Media Services API
 * Plugin URI: #
 * Description: Scrapes web page prices and descriptions from the pricing tables
 * Version: 1.0.0
 * Author: David K
 * Author URI: #
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

$DATA_LOADER = __DIR__ . '/data-loader/get-wp-page-data.php';
require_once $DATA_LOADER;


// Registering a custom REST API route
add_action('rest_api_init', 'register_custom_routes');

function register_custom_routes() {
    $routes = array(
        array(
            'route' => '/services',
            'callback' => array('DataService', 'get_all_services_data'),
        ),
        array(
            'route' => '/services/wedding',
            'callback' => array('DataService', 'get_wedding_services_data'),
        ),
        array(
            'route' => '/packages',
            'callback' => array('DataService', 'get_wedding_packages_data'),
        ),
    );

    foreach ($routes as $route) {
        $endpoint = $route['route'];
        $callback = $route['callback'];
        register_rest_route('custom/v1', $endpoint, array(
            'methods' => 'GET',
            'callback' => $callback,
        ));
    }
}
