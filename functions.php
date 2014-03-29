<?php

function pfwp_add_settings_tab($tabname, $function) {
    $hookname = 'pfwp_settings_tab_' . $tabname;
    PlayfulWPAdmin::get_instance()->
            add_action($hookname, $function);
}

function pfwp_get_settings_tabs() {

}

?>
