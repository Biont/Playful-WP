<?php add_thickbox(); ?>
<div id="all_inventory_items" style="display:none;">
    <div id="all_inventory_items_content">

    </div>
    <p>
        This is my hidden content! It will appear in ThickBox when the link is clicked.


    </p>
</div>

<a id="all_inventory_items_button" href="#TB_inline?width=600&height=550&inlineId=all_inventory_items" class="thickbox"><?php echo __('All Items', $this->plugin_slug) ?></a>
<?php
$inventory = get_user_inventory();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

