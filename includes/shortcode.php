<?php

$shortcode = Extender_Shop_Page::$shortcode;

add_shortcode( $shortcode , 'ext_shortcode_generator' );

if ( ! function_exists( 'ext_shortcode_generator' ) ){

     /**
     * Shortcode Generator for this plugin output.
     *
     * @package Extender shop
     * @version 1.0.0
     * @since   1.0.0
     * @param array $atts
     * @return string
     */
    function ext_shortcode_generator( $atts = false ) {

       //Getting Table
       $table_show = apply_filters( 'ext_custom_shop_show_top', true, $atts );
       if ( ! $table_show ){
           return;
       }

       $config_value = '';
       $_device = '';
       $html = '';

        //post type name
       $GLOBALS['ext_shop_product'] = 'Yes';

        /**
        * Set Variable $html to return
        * @package Extender Shop
        * @version 1.0.0
        * @since   1.0.0
         */

        $pairs = array('exclude' =>  false );

        extract( shortcode_atts( $pairs, $atts ) );

        $atts_id = isset( $atts['id'] ) && !empty( $atts['id'] ) ? (int) $atts['id'] : 0;

        $shop_status = get_post_status( $atts_id );


        
    if ( $atts_id && get_post_type( $atts_id ) == 'ext_shop_product' && $shop_status == 'publish' ) {
        $ID = $shop_ID = (int) $atts['id'];
        $GLOBALS['ext_shop_product'] = $ID;
        $_device = $ID;

        $enabled_elements_array = get_post_meta( $ID, 'ext_enabled_elements_array', true );

        if ( empty( $enabled_elements_array ) ) {
            return sprintf( '<p>' . esc_html( 'Table{ID: %s} Column Settings is not founded !', 'extender-shop-page' ). '</p>', $ID );
        }
        
        $elements_array         = get_post_meta( $ID, 'ext_elements_array', true );
        $elements_settings      = get_post_meta( $ID, 'ext_elements_settings', true );
        $basics                 = get_post_meta( $ID, 'ext_basics', true );
        $conditions             = get_post_meta( $ID, 'ext_conditions', true );
        $pagination             = get_post_meta( $ID, 'ext_pagination', true );
        $config_value           = $ID;

        if( is_array( $config_value ) ){
           array_unshift( $config_value, get_the_title( $ID ) ); //Added at V5.0
       }


        /**
        * Filter of common Array
        * @Hook Filter: exto_elements_arr
        */

       $elements_array = apply_filters( 'exto_elements_arr', $elements_array, $shop_ID, $atts, $elements_settings, $enabled_elements_array ); //Added at 6.0.25
       $enabled_elements_array = apply_filters( 'exto_enabled_elements_array', $enabled_elements_array, $shop_ID, $atts, $elements_settings, $elements_array ); //Added at 6.0.25
       $elements_settings = apply_filters( 'exto_elements_settings', $elements_settings, $shop_ID, $enabled_elements_array ); //Added at 6.0.25


       /**
        * Product Type featue added for provide Variation Product table 
        * 
        * @since 1.0.0
        */
    //    $product_type = isset( $basics['product_type'] ) && !empty( $basics['product_type'] ) ? $basics['product_type'] : false;
    //    if( $product_type ){
    //        unset( $enabled_column_array['category'] );
    //        unset( $enabled_column_array['tags'] );
    //        unset( $enabled_column_array['weight'] );
    //        unset( $enabled_column_array['length'] );
    //        unset( $enabled_column_array['width'] );
    //        unset( $enabled_column_array['height'] );
    //        unset( $enabled_column_array['rating'] );
    //        unset( $enabled_column_array['attribute'] );
    //        unset( $enabled_column_array['variations'] );
    //    }


        //For Advance and normal Version
        // $table_type = isset( $conditions['table_type'] ) ? $conditions['table_type'] : 'normal_table';//"advance_table"; //table_type
        // if($table_type != 'normal_table'){
        //     //unset( $enabled_column_array['price'] );
        //     unset( $enabled_column_array['variations'] );
        //     unset( $enabled_column_array['total'] );
        //     unset( $enabled_column_array['quantity'] );
        // }

       /**
        * Only for Message
        */
    //    if( isset( $enabled_column_array['message'] ) && $table_type != 'normal_table' ){
    //        /**
    //         * For ThirdParty Plugin Support, We have
    //         * Disable shortMesage from Column
    //         * and added it into Single Product.
    //         */
    //        unset( $enabled_column_array['message'] );
    //        add_action( 'woocommerce_before_add_to_cart_quantity', 'wext_add_custom_message_field' );
    //    }

       //Collumn Setting part
    //    $table_head = !isset( $basics['table_head'] ) ? true : false;

       $ext_shop_keywords = $enabled_elements_array;

    } else {
        return false;
    }

    
     /**
     * Args for wp_query()
     */
    $args = array(
        'posts_per_page' => 9,
        'post_type' => array('product'),
        'post_status'   =>  'publish',
        'meta_query' => array(),
        'wext_query_type' => 'default',
    );

    //shop ID added to Args 
    $args['ext_ID'] = $shop_ID;


    ob_start();

     /**
     * To Insert Content at Top of the Table, Just inside of Wrapper tag of Table
     * Available Args $table_ID, $args, $config_value, $atts;
     */
    do_action( 'ext_action_start_table', $shop_ID, $args, $elements_settings, $enabled_elements_array, $config_value, $atts );

    $html .= ob_get_clean();
      
     /**
     * To Show or Hide Table
     * Use following Filter
     */
    $shop_show = apply_filters( 'exto_shop_show', true,  $shop_ID, $args, $config_value, $atts );

    /**
     * Initialize Page Number
     */
    $page_number = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;

    /**
     * Do Detect Page number, When Table will be display.
     * 
     */
    $page_number = apply_filters( 'ext_page_number', $page_number, $shop_ID, $args, $elements_settings, $enabled_elements_array, $elements_array );
    $args['paged'] =( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : $page_number;

    /**
     * @Hook wpto_table_query_args to customize Query Args from any plugin.
     * Available Data/VAriable are: $args, $atts, $table_ID
     */
    $args = apply_filters( 'ext_shop_query_args', $args, $shop_ID, $atts, $elements_settings, $enabled_elements_array, $elements_array );
    $responsive = 'ext-responsive';

    /**
     * Why this array here, Actually we will send this data as dataAttribute of shop 's tag.
     * although function has called at bellow where this array need.
     */
    $shop_elements_generator_array = array(
        'args'                          => $args,
        'ext_shop_keywords'             => $ext_shop_keywords,
    );

    ob_start();

      /**
     * Action for before Table
     * @since 1.0.0
     */
    do_action( 'ext_action_before_shop_loop', $shop_ID, $args, $elements_settings, $enabled_elements_array, $config_value, $atts );
    $html .= ob_get_clean();

    $html .= '<div class="ext_woo_shoo_wrapper woocommerce">';
  
    if ( $enabled_elements_array && is_array( $enabled_elements_array ) && count( $enabled_elements_array ) >= 1) {
        foreach ( $enabled_elements_array as $key => $colunt_title ) {
            $updated_title = isset( $elements_array[$key] ) ? $elements_array[$key] : $colunt_title;
            $column_class = $key;
            // $html .= '<pre>';
            // $html .= $column_class;
            // $html .= '</pre>';

            $elements_dir_loc = EXT_INCLUDES . 'elements/';

            $elements_dir_loc = apply_filters( 'ext_elements_folder_dir', $elements_dir_loc, $key, $shop_ID, $enabled_elements_array );

            $elements_file = $elements_dir_loc . $key . '.php';

            $elements_file_admin = apply_filters( 'ext_elementsadmin_file_loc', $elements_file, $key, $shop_ID, $enabled_elements_array );

            $elements_file_of_admin = apply_filters( 'ext_admin_elements_file_loc_' . $key, $elements_file_admin, $shop_ID, $enabled_elements_array );
        
            global $product;

            // Ensure visibility.
            // if ( empty( $product ) || ! $product->is_visible() ) {
            //     return;
            // }

            if ( is_file( $elements_file_of_admin ) ) {
            
                /**
                 * Adding content to Any admin Tab of Form
                 */
                do_action( 'ext_admin_tab_' . $key, $shop_ID, $enabled_elements_array );
                ?>
           
                <?php
                //include $elements_file_of_admin; 
               
            }else{
                echo '<h2>' . $key . '.php ' . esc_html__( 'file is not found in tabs folder','extender-shop-page' ) . '</h2>';
            }

           
           
        }

      

      

        $html .= ext_shop_elements_generator( $shop_elements_generator_array );

    
        

        ob_start();
        /**
         * Action for After Table
         * @since 1.0.0
         */
        do_action( 'ext_action_after_shop', $shop_ID, $args, $elements_settings, $enabled_elements_array, $config_value, $atts );

        $html .= ob_get_clean();
        
        $html .= "</div>";
        return $html;
    }

    }
}


if ( ! function_exists( 'ext_shop_elements_generator' ) ) {

    function ext_shop_elements_generator( $shop_elements_generator_array ) {
        ob_start();
        $html = false;
        $html .= woocommerce_product_loop_start();
        $shop_ID = $shop_elements_generator_array['args']['ext_ID'];
        $args    = $shop_elements_generator_array['args'];
        $ext_shop_keywords = $shop_elements_generator_array['ext_shop_keywords'];

        $elements_array = get_post_meta( $shop_ID, 'ext_elements_array', true );
        $elements_settings = get_post_meta( $shop_ID, 'ext_elements_settings' , true);

      
        $elements_array = apply_filters( 'exto_column_arr', $elements_array, $shop_ID, null, $elements_settings, $ext_shop_keywords ); 
        $elements_settings = apply_filters( 'exto_column_settings', $elements_settings, $shop_ID, $ext_shop_keywords ); 
        
        /**
         * Adding Filter for Args inside Row Generator
         */
        $args = apply_filters( 'exto_shop_query_args_in_row', $args, $shop_ID, false, $elements_settings, false, false );
       
        $args['posts_per_page'] = is_numeric( $args['posts_per_page'] ) ? (int) $args['posts_per_page'] : $args['posts_per_page'];
        
        $product_loop = new WP_Query($args);
        $product_loop = apply_filters( 'exto_product_loop', $product_loop, $shop_ID, $args );
        if ( $product_loop->have_posts() ) {
            while ( $product_loop->have_posts() ) :
            $product_loop->the_post();
            global $product;
            $data = $product->get_data();
            $product_type = $product->get_type();
            $parent_id = $product->get_parent_id(); // Version 2.7.7
            
            (Int) $id = $data['id']; 

            $ext_each_row = false;

            do_action( 'ext_before_row', $elements_settings, $shop_ID, $product );
            $row_manager_loc = EXT_INCLUDES . 'elements-manager.php';
            $row_manager_loc = apply_filters( 'ext_row_manager_loc',$row_manager_loc, $elements_settings,$ext_shop_keywords, $args, $shop_ID, $product );
            // echo '<pre>';
            // print_r($row_manager_loc);
            // echo '</pre>';
            if( file_exists( $row_manager_loc ) ){
                include $row_manager_loc;
            }
            do_action( 'ext_after_row', $elements_settings, $shop_ID, $product );

           
            endwhile;
        }

        
        wp_reset_query();
       
        $html .= ob_get_clean();
        $html .= woocommerce_product_loop_end();
        return $html;
    }

}