<?php
$default_elements_array = Extender_Shop_Page::$ext_default_enabled_elements;

$ext_elements = Extender_Shop_Page::$ext_elements;

$for_add =  $meta_elements_array = $updated_elements_array = get_post_meta( $post->ID, 'ext_elements_array', true );

if( !$meta_elements_array && empty( $meta_elements_array ) ){
    $for_add = $updated_elements_array = Extender_Shop_Page::$ext_elements;
}
if( $updated_elements_array && !empty( $updated_elements_array ) && !empty( $ext_elements ) ){
    $ext_elements = array_merge( $ext_elements, $updated_elements_array );
}

$meta_enable_elements_array = get_post_meta( $post->ID, 'ext_enabled_elements_array', true );
if( $meta_enable_elements_array && !empty( $meta_enable_elements_array ) && !empty( $ext_elements ) ){
    $ext_elements = array_merge($meta_enable_elements_array,$ext_elements);
}

$elements_settings = get_post_meta( $post->ID, 'ext_elements_settings', true );
if( empty( $elements_settings ) ){
    $elements_settings = array();
}

echo '<pre>';
print_r(get_post_meta( $post->ID, 'ext_elements_array', true ));
echo '</pre>';

$additional_collumn = array_diff(array_keys($for_add), array_keys( Extender_Shop_Page::$ext_elements ));

if( is_array( $meta_enable_elements_array ) && !empty( $meta_enable_elements_array ) ){
   
    $final_elements_arr = $meta_enable_elements_array;
}else{
   $final_elements_arr = $default_elements_array; 
}

if( !is_array( $final_elements_arr ) ){
    return;
}


/**
 * Some input name keyword as variable
 */
$enabled_elements_array_name = 'ext_enabled_elements_array';
$_device_name = '';
?>

<div class="inside-column-settings-wrapper">
    <div class="inside-column-setting-header">
        <h2><?php echo esc_html__( 'Devicewise Column Setting', 'wpt_pro' ); ?></h2>
        <br>
    </div>
    
    <div id="inside-desktop" class="inside_tab_content tab-content tab-content-active">
<?php 
$enabled_elements_array = $enabled_elements_array_name;

$availe_column_list_file = __DIR__ . '/inc-elements/available-elements-list.php';
include $availe_column_list_file;


$column_list_file = __DIR__ . '/inc-elements/elements-list.php';
include $column_list_file;


?>
    </div>
</div>

    
<?php 

    

