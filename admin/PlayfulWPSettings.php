<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayfulWPSettings
 *
 * @author Moritz
 */
class PlayfulWPSettings {

    protected static $instance = null;
    protected $settings_tabs = array();

    private function __construct() {
        // Add the options page and menu item.
        add_action('admin_init', array($this, 'register_settings'));

        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        /*
         * @TODO :
         *
         * - Uncomment following lines if the admin class should only be available for super admins
         */
        /* if( ! is_super_admin() ) {
          return;
          } */

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function add_settings_tab() {

    }

    /**
     * Register the settings for this plugin
     *
     */
    public function register_settings() {

        register_setting('playful-wp-settings', 'new_option_name');
        register_setting('playful-wp-settings', 'some_other_option');
        register_setting('playful-wp-settings', 'option_etc');
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         * @TODO:
         *
         * - Change 'Page Title' to the title of your plugin admin page
         * - Change 'Menu Text' to the text for menu item for the plugin settings page
         * - Change 'manage_options' to the capability you see fit
         *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
         */
        $this->plugin_screen_hook_suffix = add_options_page(
                __('Playful WP Settings', $this->plugin_slug), __('PlayfulWP', $this->plugin_slug), 'manage_options', $this->plugin_slug, array($this, 'display_plugin_admin_page')
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {

        $available_plugins = PlayfulWPAdmin::get_instance()->get_available_plugins();
        include_once( 'views/admin.php' );
    }

}

?>
