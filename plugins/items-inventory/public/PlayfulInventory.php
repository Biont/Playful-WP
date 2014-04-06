<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayfulInventory
 *
 * @author Moritz
 */
class PlayfulInventory {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;
    protected $current_character = null;

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

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since     1.0.0
     */
    private function __construct() {
        $this->plugin_slug = PlayfulWP::get_instance()->get_plugin_slug();

        // Load plugin text domain
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'add_tax_meta' ) );

        // Activate plugin when new blog is added
        add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

//        // Load public-facing style sheet and JavaScript.
//        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
//        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Fired when the plugin is activated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Activate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       activated on an individual blog.
     */
    public static function activate( $network_wide ) {

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {

            if ( $network_wide ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    self::single_activate();
                }

                restore_current_blog();
            } else {
                self::single_activate();
            }
        } else {
            self::single_activate();
        }
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @since    1.0.0
     *
     * @param    boolean    $network_wide    True if WPMU superadmin uses
     *                                       "Network Deactivate" action, false if
     *                                       WPMU is disabled or plugin is
     *                                       deactivated on an individual blog.
     */
    public static function deactivate( $network_wide ) {

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {

            if ( $network_wide ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {

                    switch_to_blog( $blog_id );
                    self::single_deactivate();
                }

                restore_current_blog();
            } else {
                self::single_deactivate();
            }
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate() {
        echo 'character active!!';
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate() {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        $domain = $this->plugin_slug;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
    }

    public function add_tax_meta() {

    }

    /**
     * Register the Character post type and class and race taxonomies
     */
    public function register_post_types() {
        $labels = array(
            'name' => _x( 'Characters', 'post type general name', $this->plugin_slug ),
            'singular_name' => _x( 'Character', 'post type singular name', $this->plugin_slug ),
            'menu_name' => _x( 'Characters', 'admin menu', $this->plugin_slug ),
            'name_admin_bar' => _x( 'Characters', 'add new on admin bar', $this->plugin_slug ),
            'add_new' => _x( 'Add New', 'book', $this->plugin_slug ),
            'add_new_item' => __( 'Add New Character', $this->plugin_slug ),
            'new_item' => __( 'New Character', $this->plugin_slug ),
            'edit_item' => __( 'Edit Character', $this->plugin_slug ),
            'view_item' => __( 'View Character', $this->plugin_slug ),
            'all_items' => __( 'All Characters', $this->plugin_slug ),
            'search_items' => __( 'Search Characters', $this->plugin_slug ),
            'parent_item_colon' => __( 'Parent Characters:', $this->plugin_slug ),
            'not_found' => __( 'No characters found.', $this->plugin_slug ),
            'not_found_in_trash' => __( 'No characters found in Trash.', $this->plugin_slug ),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'character' ),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
        );

        register_post_type( 'character', $args );


        if ( get_option( 'add_races', FALSE ) ) {

            $labels = array(
                'name' => _x( 'Race', 'post type general name', $this->plugin_slug ),
                'singular_name' => _x( 'Race', 'post type singular name', $this->plugin_slug ),
                'menu_name' => _x( 'Races', 'admin menu', $this->plugin_slug ),
                'all_items' => __( 'All Races', $this->plugin_slug ),
                'edit_item' => __( 'Edit Race', $this->plugin_slug ),
                'view_item' => __( 'View Race', $this->plugin_slug ),
                'update_item' => __( 'Update Race', $this->plugin_slug ),
                'add_new_item' => __( 'Add New Race', $this->plugin_slug ),
                'new_item_name' => __( 'New Race', $this->plugin_slug ),
                'parent_item' => __( 'Parent Races:', $this->plugin_slug ),
                'parent_item_colon' => __( 'Parent Races:', $this->plugin_slug ),
                'search_items' => __( 'Search Races', $this->plugin_slug ),
                'popular_items' => __( 'Popular Races', $this->plugin_slug ),
                'separate_items_with_commas' => __( 'Separate Races with commas', $this->plugin_slug ),
                'add_or_remove_items' => __( 'Add or remove Races.', $this->plugin_slug ),
                'choose_from_most_used' => __( 'Choose from most used Races.', $this->plugin_slug ),
                'not_found' => __( 'No Races found.', $this->plugin_slug ),
            );
            // create a new taxonomy
            register_taxonomy(
                    'races', 'character', array(
                'hierarchical' => true,
                'label' => __( 'Race' ),
                'labels' => $labels,
                'meta_box_cb' => array( 'PlayfulCharacterAdmin', 'taxonomy_dropdown_metabox' ),
                'rewrite' => array( 'slug' => 'race' ),
                'capabilities' => array(
                    'manage__terms' => 'edit_posts',
                    'edit_terms' => 'manage_categories',
                    'delete_terms' => 'manage_categories',
                    'assign_terms' => 'edit_posts'
                )
                    )
            );
        }
    }

}
