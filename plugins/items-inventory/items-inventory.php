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
 * @PlayfulWP-plugin
 * PFWP-Plugin Name:       Items and Inventory
 * Plugin URI:        @TODO
 * Description:       Adds an inventory to each user and lets you add items
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
$plugin_path = plugin_dir_path( __FILE__ );
require_once( $plugin_path . 'public/PlayfulInventory.php' );

// Add everything in the inc folder
foreach ( glob( $plugin_path . 'inc/*.php' ) as $file ) {
    require $file;
}

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'PlayfulInventory', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PlayfulInventory', 'deactivate' ) );



PlayfulInventory::get_instance();


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
if ( is_admin() && (!defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
    require_once( $plugin_path . 'admin/PlayfulInventoryAdmin.php' );
    PlayfulCharacterAdmin::get_instance();
}
?>