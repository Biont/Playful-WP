<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form action="options.php" method="post">
        <?php settings_fields('playful-wp-plugins'); ?>


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
                <p>
                    <?php $active = (in_array($plugin['File'], $active_plugins)) ?>

                <tr id="akismet" class="<?php echo ($active) ? 'active' : 'inactive' ?>">
                    <th scope="row" class="check-column">
                        <label class="screen-reader-text" for="checkbox_daab5d2d514cf7d293376be3ded708f0">Akismet auswählen</label>
                        <input type="checkbox" name="available_plugins[<?php echo $index ?>]" value="<?php echo $plugin['Name'] ?>"></th>
                    <td class="plugin-title">
                        <strong><?php echo $plugin['Name'] ?></strong>
                        <div class="row-actions visible">
                            <?php if (!$active): ?>
                                <span class="activate"><a href="<?php echo add_query_arg(array('action' => 'activate', 'plugin' => $plugin['File'])) ?>" title="Aktiviere dieses Plugin" class="edit">Aktivieren</a> | </span>
                            <?php else: ?>
                                <span class="deactivate"><a href="<?php echo add_query_arg(array('action' => 'deactivate', 'plugin' => $plugin['File'])) ?>" title="Deaktiviere dieses Plugin" class="edit">Deaktivieren</a> | </span>

                            <?php endif; ?>
                            <span class="edit">
                                <a href="<?php echo add_query_arg(array('action' => 'edit', 'plugin' => $plugin['File'])) ?>" title="Öffne diese Datei im Plugin-Editor" class="edit">
                                    Bearbeiten
                                </a> |
                            </span>
                            <span class="delete">
                                <a href="<?php echo add_query_arg(array('action' => 'delete', 'plugin' => $plugin['File'])) ?>" title="Dieses Plugin löschen" class="delete">
                                    Löschen
                                </a>
                            </span>
                        </div>
                    </td>
                    <td class="column-description desc">
                        <div class="plugin-description">
                            <?php echo $plugin['Description'] ?>
                        </div>
                        <div class="active update second plugin-version-author-uri">
                            <?php printf(__('Version %s'), $plugin['Version']) ?> |
                            Von <a href="<?php echo $plugin['AuthorURI'] ?>" title="<?php echo esc_attr__('Visit author homepage') ?>"><?php echo $plugin['Author'] ?></a> |
                            <a href="<?php echo $plugin['PluginURI'] ?>" title="<?php echo __('Visit plugin site') ?>"><?php echo __('Visit plugin site') ?></a>
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
