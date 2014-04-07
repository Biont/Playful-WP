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

        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));

        add_action('admin_footer', array($this, 'add_inventory_ajax'));
        add_action('wp_ajax_inventory_box', array($this, 'return_inventory_ajax'));

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
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function add_meta_boxes() {
        add_meta_box('playful-inventory', __('Inventory', $this->plugin_slug), array($this, 'add_inventory_meta_box'), 'character');
    }

    public function add_inventory_meta_box() {
        include( 'views/inventory-meta-box.php' );
    }

    public function add_inventory_ajax() {
        ?>
        <script type="text/javascript" >
            jQuery(document).ready(function($) {
                $('#all_inventory_items_button').on('click', function() {

                    var data = {
                        action: 'inventory_box',
                        whatever: 1234
                    };
                    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                    $.post(ajaxurl, data, function(response) {

                        $('#all_inventory_items_content').html(response)
                    });
                });
                $('#all_inventory_items_content').on('click', '.inventory-item', function() {
                    alert($(this).data('item-id'));
                });


            });
        </script>
        <?php
    }

    function return_inventory_ajax() {
        global $wpdb; // this is how you get access to the database
        $args = array(
            'post_type' => 'item',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'caller_get_posts' => 1);

        $my_query = new WP_Query($args);
        if ($my_query->have_posts()) {
            while ($my_query->have_posts()) : $my_query->the_post();
                ?>
                <div class="inventory-item" data-item-id="<?php the_ID(); ?>">
                    <span class="inventory-item-title"><?php the_title(); ?></span>
                    <div class="inventory-item-info"></div>
                    <?php if (has_post_thumbnail()) the_post_thumbnail() ?>
                </div>
                <?php
            endwhile;
        }
        wp_reset_query();  // Restore global post data stomped by the_post().

        die(); // this is required to return a proper result
    }

}
