<?php

function get_user_inventory($id = '') {
    return PlayfulInventory::get_instance()->get_user_inventory($id);
}
