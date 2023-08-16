<?php

/**
 * Plugin Name: SBWC Mini Cart Upsells
 * Plugin URI:
 * Description: WooCommerce Mini Cart Upsells in the style of Amazon
 * Version: 1.0.0
 * Author: WC Bessinger
 * Author URI: https://silverbackdev.co.za
 * License: GPL2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin path and url
define('SBWC_MINI_CART_UPSELLS_PATH', plugin_dir_path(__FILE__));
define('SBWC_MINI_CART_UPSELLS_URL', plugin_dir_url(__FILE__));

// Define parent theme name
define('SBWC_MINI_CART_UPSELLS_THEME_NAME', wp_get_theme(get_template())->get('Name'));

// Admin
require_once(SBWC_MINI_CART_UPSELLS_PATH . 'inc/admin.php');

// Frontend
require_once(SBWC_MINI_CART_UPSELLS_PATH . 'inc/front.php');