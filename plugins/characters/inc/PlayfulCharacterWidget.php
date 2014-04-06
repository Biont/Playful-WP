<?php
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

/**
 * Playful Character widget class.
 * Allows users to login and displays character information to loggen in users
 *
 * @extends WP_Widget
 */

class PlayfulCharacterWidget extends WP_Widget {

    private $instance = '';
    private $user = null;
    private $options = array();

    /**
     * Sidebar_Login_Widget function.
     *
     * @access public
     * @return void
     */
    public function PlayfulCharacterWidget() {
        /* Widget settings. */
        $widget_ops = array( 'description' => __( 'Displays a login area in the sidebar.', 'playful_character_widget' ) );

        /* Create the widget. */
        $this->WP_Widget( 'wp_sidebarlogin', __( 'Playful Character', 'playful_character_widget' ), $widget_ops );
    }

    /**
     * define_options function.
     *
     * @access public
     * @return void
     */
    public function define_options() {
        // Define options for widget
        $this->options = array(
            'logged_out_title' => array(
                'label' => __( 'Logged-out title', 'playful_character_widget' ),
                'default' => __( 'Login', 'playful_character_widget' ),
                'type' => 'text'
            ),
            'logged_out_links' => array(
                'label' => __( 'Links', 'playful_character_widget' ) . ' (' . __( '<code>Text | HREF</code>', 'playful_character_widget' ) . ')',
                'default' => '',
                'type' => 'textarea'
            ),
            'show_lost_password_link' => array(
                'label' => __( 'Show lost password link', 'playful_character_widget' ),
                'default' => 1,
                'type' => 'checkbox'
            ),
            'show_register_link' => array(
                'label' => __( 'Show register link', 'playful_character_widget' ),
                'default' => 1,
                'description' => sprintf( __( '<a href="%s">Anyone can register</a> must be enabled.', 'playful_character_widget' ), admin_url( 'options-general.php' ) ),
                'type' => 'checkbox'
            ),
            'login_redirect_url' => array(
                'label' => __( 'Login Redirect URL', 'playful_character_widget' ),
                'default' => '',
                'type' => 'text',
                'placeholder' => 'Current page URL'
            ),
            'break-1' => array(
                'type' => 'break'
            ),
            'logged_in_title' => array(
                'label' => __( 'Logged-in title', 'playful_character_widget' ),
                'default' => __( 'Welcome %username%', 'playful_character_widget' ),
                'type' => 'text'
            ),
            'logged_in_links' => array(
                'label' => __( 'Links', 'playful_character_widget' ) . ' (' . __( '<code>Text | HREF | Capability</code>', 'playful_character_widget' ) . ')',
                'description' => sprintf( __( '<a href="%s">Capability</a> (optional) refers to the type of user who can view the link.', 'playful_character_widget' ), 'http://codex.wordpress.org/Roles_and_Capabilities' ),
                'default' => "Dashboard | %admin_url%\nProfile | %admin_url%/profile.php\nLogout | %logout_url%",
                'type' => 'textarea'
            ),
            'show_avatar' => array(
                'label' => __( 'Show logged-in user avatar', 'playful_character_widget' ),
                'default' => 1,
                'type' => 'checkbox'
            ),
            'logout_redirect_url' => array(
                'label' => __( 'Logout Redirect URL', 'playful_character_widget' ),
                'default' => '',
                'type' => 'text',
                'placeholder' => 'Current page URL'
            ),
            'show_race_class' => array(
                'label' => __( 'Show Race and Class info', 'playful_character_widget' ),
                'default' => 1,
                'type' => 'checkbox'
            ),
        );
    }

    /**
     * replace_tags function.
     *
     * @access public
     * @param mixed $text
     * @return void
     */
    public function replace_tags( $text ) {

        if ( $this->user ) {
            $text = str_replace(
                    array( '%username%', '%userid%' ), array( ucwords( $this->user->display_name ), $this->user->ID ), $text
            );

            // Buddypress
            if ( function_exists( 'bp_loggedin_user_domain' ) ) {
                $text = str_replace(
                        array( '%buddypress_profile_url%' ), array( bp_loggedin_user_domain() ), $text
                );
            }

            // BBpress
            if ( function_exists( 'bbp_get_user_profile_url' ) ) {
                $text = str_replace(
                        array( '%bbpress_profile_url%' ), array( bbp_get_user_profile_url( $this->user->ID ) ), $text
                );
            }
        }

        $logout_redirect = wp_logout_url( empty( $this->instance[ 'logout_redirect_url' ] ) ? $this->current_url( 'nologout' ) : $this->instance[ 'logout_redirect_url' ]  );

        $text = str_replace(
                array( '%admin_url%', '%logout_url%' ), array( untrailingslashit( admin_url() ), apply_filters( 'playful_character_widget_logout_redirect', $logout_redirect ) ), $text
        );

        $text = do_shortcode( $text );

        return $text;
    }

    /**
     * show_links function.
     *
     * @access public
     * @param string $show (default: 'logged_in')
     * @return void
     */
    public function show_links( $show = 'logged_in', $links = array() ) {
        do_action( 'playful_character_widget_before_' . $show . '_links' );

        if ( !is_array( $links ) ) {
            $raw_links = array_map( 'trim', explode( "\n", $links ) );
            $links = array();
            foreach ( $raw_links as $link ) {
                $link = array_map( 'trim', explode( '|', $link ) );
                $link_cap = '';

                if ( sizeof( $link ) == 3 )
                    list( $link_text, $link_href, $link_cap ) = $link;
                elseif ( sizeof( $link ) == 2 )
                    list( $link_text, $link_href ) = $link;
                else
                    continue;

                // Check capability
                if ( !empty( $link_cap ) )
                    if ( !current_user_can( strtolower( $link_cap ) ) )
                        continue;

                $links[ sanitize_title( $link_text ) ] = array(
                    'text' => $link_text,
                    'href' => $link_href
                );
            }
        }

        if ( $show == 'logged_out' ) {
            if ( get_option( 'users_can_register' ) && !empty( $this->instance[ 'show_register_link' ] ) && $this->instance[ 'show_register_link' ] == 1 ) {

                if ( !is_multisite() ) {

                    $links[ 'register' ] = array(
                        'text' => __( 'Register', 'playful_character_widget' ),
                        'href' => apply_filters( 'playful_character_widget_register_url', site_url( 'wp-login.php?action=register', 'login' ) )
                    );
                } else {

                    $links[ 'register' ] = array(
                        'text' => __( 'Register', 'playful_character_widget' ),
                        'href' => apply_filters( 'playful_character_widget_register_url', site_url( 'wp-signup.php', 'login' ) )
                    );
                }
            }
            if ( !empty( $this->instance[ 'show_lost_password_link' ] ) && $this->instance[ 'show_lost_password_link' ] == 1 ) {

                $links[ 'lost_password' ] = array(
                    'text' => __( 'Lost Password', 'playful_character_widget' ),
                    'href' => apply_filters( 'playful_character_widget_lost_password_url', wp_lostpassword_url() )
                );
            }
        }

        $links = apply_filters( 'playful_character_widget_' . $show . '_links', $links );

        if ( !empty( $links ) && is_array( $links ) && sizeof( $links > 0 ) ) {
            echo '<ul class="pagenav playful_character_widget_links">';

            foreach ( $links as $id => $link )
                echo '<li class="' . esc_attr( $id ) . '-link"><a href="' . esc_url( $this->replace_tags( $link[ 'href' ] ) ) . '">' . wp_kses_post( $this->replace_tags( $link[ 'text' ] ) ) . '</a></li>';

            echo '</ul>';
        }

        do_action( 'playful_character_widget_after_' . $show . '_links' );
    }

    /**
     * widget function.
     *
     * @access public
     * @param mixed $args
     * @param mixed $instance
     * @return void
     */
    public function widget( $args, $instance ) {

        // Filter can be used to conditonally hide the widget
        if ( !apply_filters( 'playful_character_widget_display', true ) )
            return;

        // Record $instance
        $this->instance = $instance;

        // Get user
        if ( is_user_logged_in() )
            $this->user = get_user_by( 'id', get_current_user_id() );

        $defaults = array(
            'logged_in_title' => !empty( $instance[ 'logged_in_title' ] ) ? $instance[ 'logged_in_title' ] : __( 'Welcome %username%', 'playful_character_widget' ),
            'logged_out_title' => !empty( $instance[ 'logged_out_title' ] ) ? $instance[ 'logged_out_title' ] : __( 'Login', 'playful_character_widget' ),
            'show_avatar' => isset( $instance[ 'show_avatar' ] ) ? $instance[ 'show_avatar' ] : 1,
            'show_race_class' => isset( $instance[ 'show_race_class' ] ) ? $instance[ 'show_race_class' ] : 1,
            'logged_in_links' => !empty( $instance[ 'logged_in_links' ] ) ? $instance[ 'logged_in_links' ] : array(),
            'logged_out_links' => !empty( $instance[ 'logged_out_links' ] ) ? $instance[ 'logged_out_links' ] : array()
        );

        $args = array_merge( $defaults, $args );

        extract( $args );

        echo $before_widget;

        do_action( 'playful_character_widget_start' );

        // Logged in user
        if ( is_user_logged_in() ) {

            $logged_in_title = $this->replace_tags( apply_filters( 'playful_character_widget_logged_in_title', $logged_in_title ) );

            if ( $logged_in_title )
                echo $before_title . $logged_in_title . $after_title;

            do_action( 'playful_character_widget_logged_in_content_start' );



            $this->show_links( 'logged_in', $logged_in_links );
            if ( has_character() ) {




                $character = get_the_character();
//                echo '<pre>' . print_r( $character ) . '</pre><p>';


                if ( $show_avatar == 1 ) {
                    echo '<div class="avatar_container">' . get_avatar( $this->user->ID, apply_filters( 'playful_character_widget_avatar_size', 38 ) ) . '</div>';

                    echo '<div class="avatar_container">' . get_the_post_thumbnail( $character->ID, 'thumbnail' ) . '</div>';
                }

                echo '<div>' . $character->post_title . '</div>';


                if ( $show_race_class == 1 ) {
                    // get taxonomies and display
                    foreach ( get_object_taxonomies( get_post( $character->ID ), 'objects' ) as $name => $tax ) {
                        $terms = get_the_terms( $character->ID, $name );
                        if ( FALSE !== $terms ) { // Taxonomy might not be set
                            foreach ( $terms as $term ) {
                                echo $tax->labels->singular_name . ': <a href="' . get_term_link( $term ) . '">' . $term->name . '</a><br>';
                            }
                        }
                    }
                } else {

                }
                echo '<p>';
                echo $character->post_content;
            } else {
                echo 'You do not have a character yet. create one?';
            }

            do_action( 'playful_character_widget_logged_in_content_end' );

            // Logged out user
        } else {

            $logged_out_title = $this->replace_tags( apply_filters( 'playful_character_widget_logged_out_title', $logged_out_title ) );

            if ( $logged_out_title )
                echo $before_title . $logged_out_title . $after_title;

            do_action( 'playful_character_widget_logged_out_content_start' );

            $redirect = empty( $instance[ 'login_redirect_url' ] ) ? $this->current_url( 'nologout' ) : $instance[ 'login_redirect_url' ];

            $login_form_args = apply_filters( 'playful_character_widget_form_args', array(
                'echo' => true,
                'redirect' => esc_url( apply_filters( 'playful_character_widget_login_redirect', $redirect ) ),
                'label_username' => __( 'Username', 'playful_character_widget' ),
                'label_password' => __( 'Password', 'playful_character_widget' ),
                'label_remember' => __( 'Remember Me', 'playful_character_widget' ),
                'label_log_in' => __( 'Login &rarr;', 'playful_character_widget' ),
                'remember' => true,
                'value_remember' => true
                    ) );

            wp_login_form( $login_form_args );

            $this->show_links( 'logged_out', $logged_out_links );

            do_action( 'playful_character_widget_logged_out_content_end' );
        }

        do_action( 'playful_character_widget_end' );

        echo $after_widget;
    }

    /**
     * current_url function.
     *
     * @access public
     * @param string $url (default: '')
     * @return void
     */
    private function current_url( $url = '' ) {
        $pageURL = force_ssl_admin() ? 'https://' : 'http://';
        $pageURL .= esc_attr( $_SERVER[ 'HTTP_HOST' ] );
        $pageURL .= esc_attr( $_SERVER[ 'REQUEST_URI' ] );

        if ( $url != "nologout" ) {
            if ( !strpos( $pageURL, '_login=' ) ) {
                $rand_string = md5( uniqid( rand(), true ) );
                $rand_string = substr( $rand_string, 0, 10 );
                $pageURL = add_query_arg( '_login', $rand_string, $pageURL );
            }
        }

        return esc_url_raw( $pageURL );
    }

    /**
     * update function.
     *
     * @see WP_Widget->update
     * @access public
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    function update( $new_instance, $old_instance ) {
        $this->define_options();

        foreach ( $this->options as $name => $option ) {
            if ( $option[ 'type' ] == 'break' )
                continue;

            $instance[ $name ] = strip_tags( stripslashes( $new_instance[ $name ] ) );
        }
        return $instance;
    }

    /**
     * form function.
     *
     * @see WP_Widget->form
     * @access public
     * @param array $instance
     * @return void
     */
    function form( $instance ) {
        $this->define_options();

        foreach ( $this->options as $name => $option ) {

            if ( $option[ 'type' ] == 'break' ) {
                echo '<hr style="border: 1px solid #ddd; margin: 1em 0" />';
                continue;
            }

            if ( !isset( $instance[ $name ] ) )
                $instance[ $name ] = $option[ 'default' ];

            if ( empty( $option[ 'placeholder' ] ) )
                $option[ 'placeholder' ] = '';

            echo '<p>';

            switch ( $option[ 'type' ] ) {
                case "text" :
                    ?>
                    <label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php echo wp_kses_post( $option[ 'label' ] ) ?>:</label>
                    <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>" placeholder="<?php echo esc_attr( $option[ 'placeholder' ] ); ?>" value="<?php echo esc_attr( $instance[ $name ] ); ?>" />
                    <?php
                    break;
                case "checkbox" :
                    ?>
                    <label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>" <?php checked( $instance[ $name ], 1 ) ?> value="1" /> <?php echo wp_kses_post( $option[ 'label' ] ) ?></label>
                    <?php
                    break;
                case "textarea" :
                    ?>
                    <label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php echo wp_kses_post( $option[ 'label' ] ) ?>:</label>
                    <textarea class="widefat" cols="20" rows="3" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>" placeholder="<?php echo esc_attr( $option[ 'placeholder' ] ); ?>"><?php echo esc_textarea( $instance[ $name ] ); ?></textarea>
                    <?php
                    break;
            }

            if ( !empty( $option[ 'description' ] ) )
                echo '<span class="description" style="display:block; padding-top:.25em">' . wp_kses_post( $option[ 'description' ] ) . '</span>';

            echo '</p>';
        }
    }

}

function register_playful_character_widget() {
    register_widget( 'PlayfulCharacterWidget' );
}

add_action( 'widgets_init', 'register_playful_character_widget' );
