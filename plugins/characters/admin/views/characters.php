<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form action="options.php" method="post">
        <?php settings_fields('playful-characters-settings'); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __('Single Character', $this->plugin_slug) ?></th>
                <td><input type="checkbox" name="is_single_character" value="1" <?php echo (get_option('is_single_character', 0)) ? 'checked' : '' ?>/></td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php echo __('Maximum Characters per account', $this->plugin_slug) ?></th>
                <td><input type="number" name="character_limit" value="<?php echo get_option('character_limit', 3) ?>"  /></td>
            </tr>
        </table>

        <?php submit_button(); ?>

    </form>
    <!-- @TODO: Provide markup for your options page here. -->

</div>