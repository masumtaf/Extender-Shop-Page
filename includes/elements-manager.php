<?php

$shop_loop_row_loc = EXT_INCLUDES . 'shoop-elements-row.php';
$shop_loop_row_loc = apply_filters( 'ext_elements_row_loc', $shop_loop_row_loc, $elements_settings,$ext_shop_keywords, $args, $shop_ID, $product );
if( file_exists( $shop_loop_row_loc ) ){
    include $shop_loop_row_loc;
}
