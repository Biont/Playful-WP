<?php add_thickbox(); ?>
<div id="all_inventory_items" style="display:none;">
    <div id="all_inventory_items_content">

    </div>
    <p>
        This is my hidden content! It will appear in ThickBox when the link is clicked.


    </p>
</div>

<a id="all_inventory_items_button" href="#TB_inline?width=600&height=550&inlineId=all_inventory_items" class="thickbox"><?php echo __('All Items', $this->plugin_slug) ?></a>


<div id="character_inventory">
    <?php
    $inventory = get_user_inventory();
    ?>
</div>


<script id="thickbox_item" type="text/html">
    <div class="inventory-item">
        <span class="name">{{ title }}</span>
        <img src="{{ thumbnail }}">
        <input type="hidden" name="item_ids[]" value="{{ id }}">
        <p>{{ content }}</p>
    </div>
</script>

<script id="metabox_item" type="text/html">
    <div class="metabox-item">
        <span class="name">{{ title }}</span>
        <img src="{{ thumbnail }}">
        <input type="hidden" name="item_ids[]" value="{{ id }}">
        <p>{{ content }}</p>
    </div>
</script>