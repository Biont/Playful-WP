<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayfulCharacterAdmin
 *
 * @author Biont
 */
class PlayfulCharacterAdmin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;
    protected $settings = null;

    /**
     * All compatible plugins in the plugins folder
     *
     * @var array
     */
//    protected $installed_plugins = null;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct() {

        $plugin = PlayfulWP::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        // Load admin style sheet and JavaScript.
//        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
//        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        // Add an action link pointing to the options page.
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_slug . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));


        // Add the options page and menu item.
//        add_action('admin_init', array($this, 'register_settings'));
        // Add the options page and menu item.
        add_action('admin_menu', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_admin_menu'));


        /*
         * Define custom functionality.
         *
         * Read more about actions and filters:
         * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        add_action('@TODO', array($this, 'action_method_name'));
        add_filter('@TODO', array($this, 'filter_method_name'));
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

    public function register_settings() {

        register_setting('playful-characters-settings', 'is_single_character');
        register_setting('playful-characters-settings', 'character_limit');
        //Plugins
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_admin_menu() {

        $this->plugin_screen_hook_suffix = add_menu_page(
                __('Playful WP Settings', $this->plugin_slug), __('PlayfulWP', $this->plugin_slug), 'manage_options', 'playful_wp_settings', array($this, 'display_admin_page'), 'hurr', 19
        );

        add_submenu_page('playful_wp_settings', __('Playful WP Plugins', $this->plugin_slug), __('Characters', $this->plugin_slug), 'manage_options', 'playful_characters', array($this, 'display_character_page'));
    }

    public function display_character_page() {
        include_once( 'views/characters.php' );
    }

}
