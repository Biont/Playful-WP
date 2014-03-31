<?php

function pfwp_add_settings_tab($tabname, $function) {
    $hookname = 'pfwp_settings_tab_' . $tabname;
    PlayfulWPAdmin::get_instance()->
            add_action($hookname, $function);
}

function pfwp_get_settings_tabs() {

}

//function pfwp_register_activation_hook($file, $callback) {
//    add_action('activate_' . $file, $callback);
//}
//
//function pfwp_register_deactivation_hook($file, $callback) {
//    add_action('deactivate_' . $file, $callback);
//}
//
//function pfwp_register_uninstall_hook($file, $callback) {
//    add_action('uninstall_' . $file, $callback);
//}
?>
