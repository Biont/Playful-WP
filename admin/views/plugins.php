<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <?php do_action('playful_wp_plugin_activation') ?>


    <form action="options.php" method="post">
        <?php settings_fields('playful-wp-plugins'); ?>
        <?php if (is_string($saved_config = get_option('pfwp_active_plugins', array()))) $saved_config = array() ?>
        <?php // var_dump($saved_config); ?>
        <?php var_dump($installed_plugins); ?>


        <table class="wp-list-table widefat plugins" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-1">Alle auswählen</label><input id="cb-select-all-1" type="checkbox"></th>
                    <th scope="col" id="name" class="manage-column column-name" style="">Plugin</th>
                    <th scope="col" id="description" class="manage-column column-description" style="">Beschreibung</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column" style=""><label class="screen-reader-text" for="cb-select-all-2">Alle auswählen</label><input id="cb-select-all-2" type="checkbox"></th><th scope="col" class="manage-column column-name" style="">Plugin</th><th scope="col" class="manage-column column-description" style="">Beschreibung</th>	</tr>
            </tfoot>

            <tbody id="the-list">

                <?php foreach ($installed_plugins as $index => $plugin): ?>

                    <?php $active = (in_array($plugin['Name'], $saved_config)) ?>

                    <tr id="akismet" class="<?php echo ($active) ? 'active' : 'inactive' ?>">
                        <th scope="row" class="check-column">
                            <label class="screen-reader-text" for="checkbox_daab5d2d514cf7d293376be3ded708f0">Akismet auswählen</label>
                            <input type="checkbox" name="available_plugins[<?php echo $index ?>]" value="<?php echo $plugin['Name'] ?>" <?php echo ($active) ? 'checked' : '' ?>></th>
                        <td class="plugin-title">
                            <strong><?php echo $plugin['Name'] ?></strong>
                            <div class="row-actions visible">
                                <span class="activate"><a href="" title="Aktiviere dieses Plugin" class="edit">Aktivieren</a> | </span>
                                <span class="edit"><a href="plugin-editor.php?file=akismet/akismet.php" title="Öffne diese Datei im Plugin-Editor" class="edit">Bearbeiten</a> | </span>
                                <span class="delete"><a href="" title="Dieses Plugin löschen" class="delete">Löschen</a></span>
                            </div>
                        </td>
                        <td class="column-description desc">
                            <div class="plugin-description">
                                <?php echo $plugin['Description'] ?>
                            </div>
                            <div class="active update second plugin-version-author-uri">Version 2.5.9 | Von <a href="http://automattic.com/wordpress-plugins/" title="Besuche die Homepage des Autors">Automattic</a> | <a href="http://akismet.com/?return=true" title="Besuch die Plugin-Seite">Besuch die Plugin-Seite</a>
                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>

                </tr>
            </tbody>
        </table>


        <?php submit_button(); ?>

    </form>
</div>
