<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayfulInventoryAdmin
 *
 * @author Moritz
 */
class PlayfulInventoryAdmin {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

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
//        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
//        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
        // Add the options page and menu item.
//        add_action('admin_init', array($this, 'register_settings'));
        // Add the options page and menu item.
//        add_action( 'admin_menu', array( $this, 'register_settings' ), 99 );
//        add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 99 );
    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
