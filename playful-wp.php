<?php

/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Playful-WP
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 *
 * @wordpress-plugin
 * Plugin Name:       Playful-WP
 * Plugin URI:        @TODO
 * Description:       A plugin that extends WordPress with several gamification features like character-creation, achievements, quests and inventories.
 * Version:           1.0.0
 * Author:            Moritz Meißelbach
 * Author URI:        http://www.github.com/Biont
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 * WordPress-Plugin-Boilerplate: v2.6.1
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */

$plugin_path = plugin_dir_path(__FILE__);
require_once( $plugin_path . 'functions.php' );
require_once( $plugin_path . 'public/PlayfulWP.php' );
require_once( $plugin_path . 'lib/taxonomy_meta.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array('PlayfulWP', 'activate'));
register_deactivation_hook(__FILE__, array('PlayfulWP', 'deactivate'));

add_action('plugins_loaded', array('PlayfulWP', 'get_instance'));



/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX )) {

    require_once( $plugin_path . 'admin/PlayfulWPAdmin.php' );
    require_once( $plugin_path . 'admin/PlayfulWPSettings.php' );
    add_action('plugins_loaded', array('PlayfulWPAdmin', 'get_instance'));
}
