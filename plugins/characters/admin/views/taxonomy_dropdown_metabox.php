<?php
$defaults = array( 'taxonomy' => 'category' );
if ( !isset( $box[ 'args' ] ) || !is_array( $box[ 'args' ] ) )
    $args = array();
else
    $args = $box[ 'args' ];
extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );
$tax = get_taxonomy( $taxonomy );
?>
<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
    <?php $tax = get_taxonomy( $taxonomy ) ?>

    <label for="<?php echo $taxonomy ?>"><?php echo $tax->labels->singular_name ?></label>
    <?php
    $selected = wp_get_object_terms( $post->ID, $taxonomy, array_merge( $args, array( 'fields' => 'ids' ) ) );
    $selected = (!empty( $selected )) ? $selected[ 0 ] : 0;
    $args = array(
        'show_option_none' => ' - ',
        'hide_empty' => 0,
        'name' => 'tax_input[' . $taxonomy . '][]',
        'class' => $taxonomy . ' dropdown',
        'selected' => $selected,
        'taxonomy' => $taxonomy,
    );

    wp_dropdown_categories( $args );
    ?>
</div>
<?php
