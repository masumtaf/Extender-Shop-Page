<?php

if ( ! function_exists('ext_custom_shop_page_cpt') ) {

/**
 * Create Custom post type for Shop Page. From now, we will store our shortcode or shortcode's value in this post as meta value
 * 
 * @package Extender Shop Page
* @version 1.0.0
* @since   1.0.0
* @link https://codex.wordpress.org/Post_Types See details at WordPress.org about Custom Post Type
*/
function ext_custom_shop_page_cpt() {
        
	$labels = array(
		'name'                  => _x( 'Ext Shop Product', 'Ext Shop Product', 'extender-shop-page' ),
		'singular_name'         => _x( 'Ext Shop Product', 'Ext Shop Product', 'extender-shop-page' ),
		'menu_name'             => __( 'Ext Shop Product', 'extender-shop-page' ),
		'name_admin_bar'        => __( 'Ext Shop Product', 'extender-shop-page' ),
		'archives'              => __( 'Ext Shop Product Archives', 'extender-shop-page' ),
		'attributes'            => __( 'Ext Shop Product Attributes', 'extender-shop-page' ),
		'parent_item_colon'     => __( 'Parent Shortcode:', 'extender-shop-page' ),
		'all_items'             => __( 'Ext Shop Product', 'extender-shop-page' ),
		'add_new_item'          => __( 'Add New', 'extender-shop-page' ),
		'add_new'               => __( 'Add New', 'extender-shop-page' ),
		'new_item'              => __( 'New Ext Shop Product', 'extender-shop-page' ),
		'edit_item'             => __( 'Edit Ext Shop Product', 'extender-shop-page' ),
		'update_item'           => __( 'Update Ext Shop Product', 'extender-shop-page' ),
		'view_item'             => __( 'View Ext Shop Product', 'extender-shop-page' ),
		'view_items'            => __( 'View Ext Shop Products', 'extender-shop-page' ),
		'search_items'          => __( 'Search Ext Shop Product', 'extender-shop-page' ),
		'not_found'             => __( 'Not found', 'extender-shop-page' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'extender-shop-page' ),
		'featured_image'        => __( 'Featured Image', 'extender-shop-page' ),
		'set_featured_image'    => __( 'Set featured image', 'extender-shop-page' ),
		'remove_featured_image' => __( 'Remove featured image', 'extender-shop-page' ),
		'use_featured_image'    => __( 'Use as featured image', 'extender-shop-page' ),
		'insert_into_item'      => __( 'Insert into Ext Shop Product', 'extender-shop-page' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Ext Shop Product', 'extender-shop-page' ),
		'items_list'            => __( 'Ext Shop Product list', 'extender-shop-page' ),
		'items_list_navigation' => __( 'Ext Shop Product list navigation', 'extender-shop-page' ),
		'filter_items_list'     => __( 'Filter Ext Shop Product list', 'extender-shop-page' ),
	);
	$args = array(
		'label'                 => __( 'Ext Shop Product', 'extender-shop-page' ),
		'description'           => __( 'Generate your shortcode for Ext Shop Product.', 'extender-shop-page' ),
		'labels'                => $labels,
		'supports'              => array('title'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 40,
        'menu_icon'             => 'dashicons-art',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
                'capability_type'       => 'post',
                'capabilities' => array(
                    'edit_post' => 'edit_ext_shop_product',
                    'edit_posts' => 'edit_ext_shop_products',
                    'edit_others_posts' => 'edit_others_ext_shop_products',
                    'publish_posts' => 'publish_ext_shop_products',
                    'read_post' => 'read_ext_shop_product',
                    'read_private_posts' => 'read_private_ext_shop_products',
                    'delete_post' => 'delete_ext_shop_product',
                ),
                "rewrite" => [ "slug" => "ext_shop_product", "with_front" => true ],
                'map_meta_cap' => true,
                'register_meta_box_cb'  => 'ext_register_metaboxs',
	);
	register_post_type( 'ext_shop_product', $args );

}
add_action( 'init', 'ext_custom_shop_page_cpt', 0 );

}

if( !function_exists( 'ext_shortcode_column_head' ) ){
    //Showing shortcode in All Shortcode page
    function ext_shortcode_column_head($default){
        if ( 'ext_shop_product' == get_post_type() ){
        $default['ext_shortcode'] = "Shortcode";
        }
        return $default;
    }
}
add_filter('manage_posts_columns', 'ext_shortcode_column_head');

if( !function_exists( 'ext_shortcode_column_content' ) ){
    function ext_shortcode_column_content($column_name, $post_id){
        if ($column_name == 'ext_shortcode') {
            $post_title = get_the_title( $post_id );
            $post_title = preg_replace( '/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/',"$1", $post_title );
            echo "<input style='display: inline-block;width:300px;' class='ext_auto_select_n_copy' type='text' value=\"[ext-woo-shop id='{$post_id}' name='{$post_title}']\" id='ext_shotcode_content_{$post_id}' readonly>";
            echo '<a style="font-size: 12px !important;padding: 4px 13px !important" class="button button-primary ext_copy_button_metabox" data-target_id="ext_shotcode_content_' . $post_id . '">'. esc_html__( 'Copy','extender-shop-page' ).'</a>';
            echo '<p style="color: green;font-weight:bold;display:none; padding-left: 12px;" class="ext_shotcode_content_' . $post_id . '"></p>';
        }  
    }
}
add_action('manage_posts_custom_column', 'ext_shortcode_column_content', 2, 2);


//Permalink Hiding Option
//add_filter( 'get_sample_permalink_html', 'ext_permalink_hiding' );
if( !function_exists( 'ext_permalink_hiding' ) ){
    function ext_permalink_hiding( $return ) {
        if ( 'ext_shop_product' == get_post_type() ){
            $return = '';
        }
        return $return;
    }
}


//Hiding Preview Button from all shortcode page
//add_filter( 'page_row_actions', 'ext_preview_button_hiding', 10, 2 );
if( !function_exists( 'ext_preview_button_hiding' ) ){
    function ext_preview_button_hiding( $actions, $post ) {

        if ( 'ext_shop_product' == get_post_type() ){
            unset( $actions['inline hide-if-no-js'] );
            unset( $actions['view'] );
        }
        return $actions;
    }
}
