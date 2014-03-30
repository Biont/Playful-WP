<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>

<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form action="options.php" method="post">
        <?php settings_fields('playful-wp-settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">New Option Name</th>
                <td><input type="text" name="new_option_name" value="<?php echo get_option('new_option_name'); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Some Other Option</th>
                <td><input type="text" name="some_other_option" value="<?php echo get_option('some_other_option'); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Options, Etc.</th>
                <td><input type="text" name="option_etc" value="<?php echo get_option('option_etc'); ?>" /></td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
    <!-- @TODO: Provide markup for your options page here. -->

</div>

