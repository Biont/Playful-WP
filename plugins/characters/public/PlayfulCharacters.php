<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PlayfulCharacters
 *
 * @author Moritz
 */
class PlayfulCharacters {

    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
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
        add_action('init', array($this, 'load_plugin_textdomain'));
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'add_tax_meta'));

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', array($this, 'activate_new_site'));

//        // Load public-facing style sheet and JavaScript.
//        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
//        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));


        /**
         * Load active plugins from settings and initialize them
         */
        /* Define custom functionality.
         * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
         */
        add_action('register_form', array($this, 'add_register_form'));
        add_filter('@TODO', array($this, 'filter_method_name'));
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
    public static function activate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
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
    public static function deactivate($network_wide) {

        if (function_exists('is_multisite') && is_multisite()) {

            if ($network_wide) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ($blog_ids as $blog_id) {

                    switch_to_blog($blog_id);
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
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    public function add_register_form() {

        $races = pfwp_get_option('character_races');
        $classes = pfwp_get_option('character_classes');
        ?>
        <p>
            <label for="char_name"><?php echo __('Character Name', $this->plugin_slug) ?></label>
            <input type="text" name="char_name">
        <p>
            <label for="race"><?php echo __('Race', $this->plugin_slug) ?></label>
            <?php
            $args = array(
                'hide_empty' => 0,
                'name' => 'race',
                'class' => 'race dropdown',
                'taxonomy' => 'races',
            );

            wp_dropdown_categories($args);
            ?>
        <p>
            <label for="class"><?php echo __('Class', $this->plugin_slug) ?></label>
            <?php
            $args = array(
                'hide_empty' => 0,
                'name' => 'class',
                'class' => 'class dropdown',
                'taxonomy' => 'classes',
            );

            wp_dropdown_categories($args);
            ?>

        </p>
        <?php
    }

    public function add_tax_meta() {
        // Make sure there's no errors when the plugin is deactivated or during upgrade
        if (!class_exists('RW_Taxonomy_Meta'))
            return;

        $meta_sections = array();

// First meta section
        $meta_sections[] = array(
            'title' => __('Additional Info', $this->plugin_slug), // section title
            'taxonomies' => array('races', 'classes'), // list of taxonomies. Default is array('category', 'post_tag'). Optional
            'id' => 'option_name', // ID of each section, will be the option name
            'fields' => array(// List of meta fields
// TEXT
                array(
                    'name' => 'Text', // field name
                    'desc' => 'Simple text field', // field description, optional
                    'id' => 'text', // field id, i.e. the meta key
                    'type' => 'text', // field type
                    'std' => 'Text', // default value, optional
                ),
// TEXTAREA
                array(
                    'name' => 'Textarea',
                    'id' => 'textarea',
                    'type' => 'textarea',
                ),
// SELECT
                array(
                    'name' => 'Select',
                    'id' => 'select',
                    'type' => 'select',
                    'options' => array(// Array of value => label pairs for radio options
                        'value1' => 'Label 1',
                        'value2' => 'Label 2'
                    ),
                ),
// RADIO
                array(
                    'name' => 'Radio',
                    'id' => 'radio',
                    'type' => 'radio',
                    'options' => array(// Array of value => label pairs for radio options
                        'value1' => 'Label 1',
                        'value2' => 'Label 2'
                    ),
                ),
// CHECKBOX
                array(
                    'name' => 'Checkbox',
                    'id' => 'checkbox',
                    'type' => 'checkbox',
                ),
            ),
        );

// Second meta section
        $meta_sections[] = array(
            'title' => 'Advanced Fields',
            'id' => 'option_name',
            'fields' => array(
// CHECKBOX LIST
                array(
                    'name' => 'Checkbox list',
                    'id' => 'checkbox_list',
                    'type' => 'checkbox_list',
                    'options' => array(// Array of value => label pairs for radio options
                        'value1' => 'Label 1',
                        'value2' => 'Label 2'
                    ),
                    'desc' => 'What do you do in free time?'
                ),
// WYSIWYG
                array(
                    'name' => 'WYSIWYG Editor',
                    'id' => 'wysiwyg',
                    'type' => 'wysiwyg',
                ),
// DATE PICKER
                array(
                    'name' => 'Date Picker',
                    'id' => 'date',
                    'type' => 'date',
                    'format' => 'd MM, yy', // Date format, default yy-mm-dd. Optional. See: http://goo.gl/po8vf
                ),
// TIME PICKER
                array(
                    'name' => 'Time Picker',
                    'id' => 'time',
                    'type' => 'time',
                    'format' => 'hh:mm:ss', // Time format, default hh:mm. Optional. See: http://goo.gl/hXHWz
                ),
// FILE
                array(
                    'name' => 'File',
                    'id' => 'file',
                    'type' => 'file',
                ),
// IMAGE
                array(
                    'name' => 'Image',
                    'id' => 'image',
                    'type' => 'image',
                ),
// COLOR PICKER
                array(
                    'name' => 'Color Picker',
                    'id' => 'color',
                    'type' => 'color',
                ),
            ),
        );

        foreach ($meta_sections as $meta_section) {
            new RW_Taxonomy_Meta($meta_section);
        }
    }

    public function register_post_types() {
        $labels = array(
            'name' => _x('Characters', 'post type general name', $this->plugin_slug),
            'singular_name' => _x('Character', 'post type singular name', $this->plugin_slug),
            'menu_name' => _x('Characters', 'admin menu', $this->plugin_slug),
            'name_admin_bar' => _x('Characters', 'add new on admin bar', $this->plugin_slug),
            'add_new' => _x('Add New', 'book', $this->plugin_slug),
            'add_new_item' => __('Add New Character', $this->plugin_slug),
            'new_item' => __('New Character', $this->plugin_slug),
            'edit_item' => __('Edit Character', $this->plugin_slug),
            'view_item' => __('View Character', $this->plugin_slug),
            'all_items' => __('All Characters', $this->plugin_slug),
            'search_items' => __('Search Characters', $this->plugin_slug),
            'parent_item_colon' => __('Parent Characters:', $this->plugin_slug),
            'not_found' => __('No characters found.', $this->plugin_slug),
            'not_found_in_trash' => __('No characters found in Trash.', $this->plugin_slug),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'character'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
        );

        register_post_type('character', $args);

        $labels = array(
            'name' => _x('Races', 'post type general name', $this->plugin_slug),
            'singular_name' => _x('Race', 'post type singular name', $this->plugin_slug),
            'menu_name' => _x('Races', 'admin menu', $this->plugin_slug),
            'all_items' => __('All Races', $this->plugin_slug),
            'edit_item' => __('Edit Race', $this->plugin_slug),
            'view_item' => __('View Race', $this->plugin_slug),
            'update_item' => __('Update Race', $this->plugin_slug),
            'add_new_item' => __('Add New Race', $this->plugin_slug),
            'new_item_name' => __('New Race', $this->plugin_slug),
            'parent_item' => __('Parent Races:', $this->plugin_slug),
            'parent_item_colon' => __('Parent Races:', $this->plugin_slug),
            'search_items' => __('Search Races', $this->plugin_slug),
            'popular_items' => __('Popular Races', $this->plugin_slug),
            'separate_items_with_commas' => __('Separate Races with commas', $this->plugin_slug),
            'add_or_remove_items' => __('Add or remove Races.', $this->plugin_slug),
            'choose_from_most_used' => __('Choose from most used Races.', $this->plugin_slug),
            'not_found' => __('No Races found.', $this->plugin_slug),
        );
        // create a new taxonomy
        register_taxonomy(
                'races', 'character', array(
            'label' => __('Race'),
            'labels' => $labels,
            'rewrite' => array('slug' => 'race'),
            'capabilities' => array(
                'manage__terms' => 'edit_posts',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'edit_posts'
            )
                )
        );
        $labels = array(
            'name' => _x('Classes', 'post type general name', $this->plugin_slug),
            'singular_name' => _x('Class', 'post type singular name', $this->plugin_slug),
            'menu_name' => _x('Classes', 'admin menu', $this->plugin_slug),
            'all_items' => __('All Classes', $this->plugin_slug),
            'edit_item' => __('Edit Classes', $this->plugin_slug),
            'view_item' => __('View Class', $this->plugin_slug),
            'update_item' => __('Update Class', $this->plugin_slug),
            'add_new_item' => __('Add New Class', $this->plugin_slug),
            'new_item_name' => __('New Class', $this->plugin_slug),
            'parent_item' => __('Parent Classes:', $this->plugin_slug),
            'parent_item_colon' => __('Parent Classes:', $this->plugin_slug),
            'search_items' => __('Search Classes', $this->plugin_slug),
            'popular_items' => __('Popular Classes', $this->plugin_slug),
            'separate_items_with_commas' => __('Separate Classes with commas', $this->plugin_slug),
            'add_or_remove_items' => __('Add or remove Classes.', $this->plugin_slug),
            'choose_from_most_used' => __('Choose from most used Classes.', $this->plugin_slug),
            'not_found' => __('No Classes found.', $this->plugin_slug),
        );
        // create a new taxonomy
        register_taxonomy(
                'classes', 'character', array(
            'label' => __('Class'),
            'labels' => $labels,
            'rewrite' => array('slug' => 'class'),
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
