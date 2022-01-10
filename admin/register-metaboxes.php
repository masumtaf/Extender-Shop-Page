<?php
/**
 * All metabox will control from here
 * This page added at 4.1.1 date: 19.1.2019
 * 
 * @since 1.0.0
 * @author Saiful Islam<codersaiful@gmail.com>
 */

if( !function_exists( 'ext_register_metaboxs' ) ){
    /**
     * Our total metabox or register_meta_box_cb will controll from here. 
     * 
     * @since 4.1.1
     */
    function ext_register_metaboxs(){
        add_meta_box( 'ext_metabox_id', 'Shortcode', 'ext_shortcode_metabox_render', 'ext_shop_product', 'normal' );
        add_meta_box( 'ext_shortcode_configuration_metabox_id', 'Shop Loop Configure', 'ext_shortcode_configuration_metabox_render', 'ext_shop_product', 'normal' );
    }
}


if( !function_exists( 'ext_shortcode_metabox_render' ) ){
    function ext_shortcode_metabox_render(){
        global $post;
        $curent_post_id = $post->ID;
        $post_title = preg_replace( '/[#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]/',"$1", $post->post_title );
        echo '<div class="ext-shortcode-box-inside">';
        echo '<input type="text" value="[ext-woo-shop id=\'' . $curent_post_id . '\' name=\'' . $post_title . '\']" class="ext_auto_select_n_copy ext_meta_box_shortcode mb-text-input mb-field" id="ext_metabox_copy_content" readonly>'; // class='ext_auto_select_n_copy'
        echo '<a style="display:none;"  class="button button-primary ext_copy_button_metabox" data-target_id="ext_metabox_copy_content">Copy</a>';
        echo '<p style="color: #007692;font-weight:bold;display:none; padding-left: 12px;" class="ext_metabox_copy_content"></p>';
        echo '</div>';
        ?>
<div class="ext-tips-github">
    <p>
        <b><?php echo esc_html__( 'Tips:', 'ext_pro' ); ?></b>
        <span>
            <?php echo esc_html__( 'If you want to be a Contributor, Go to ', 'ext_pro' ); ?>
            <a target="_blank" href="https://github.com/codersaiful/woo-product-table"><?php echo esc_html__( 'Github Repo', 'ext_pro' ); ?></a>.
            | 
            <?php echo esc_html__( 'Any Ideas? Please ', 'ext_pro' ); ?>
            <a target="_blank" href="https://github.com/codersaiful/woo-product-table/discussions/new"><?php echo esc_html__( 'Send your Suggestion or Idea', 'ext_pro' ); ?></a>
            
        </span>
    </p>
</div>
        <?php
    }
}

if( !function_exists( 'ext_shortcode_configuration_metabox_render' ) ){
    //Now start metabox for shortcode Generator
    function ext_shortcode_configuration_metabox_render(){
        global $post;
        /**
         * Filter Hook was not working from theme's function file, so need this filter inside function
         */
        Extender_Shop_Page::$ext_elements = apply_filters( 'ext_default_elements', Extender_Shop_Page::$ext_elements );
        Extender_Shop_Page::$ext_default_enabled_elements = apply_filters( 'ext_default_enabled_elements', Extender_Shop_Page::$ext_default_enabled_elements );
        echo '<pre>';
        print_r(Extender_Shop_Page::$ext_elements);
        echo '</pre>';
       // print_r(Extender_Shop_Page::$ext_default_enabled_elements);
        echo '<input type="hidden" name="ext_shortcode_nonce_value" value="' . wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />'; //We have to remove it later
        include __DIR__ . '/shop-product-form.php';
        ?> 
        <br style="clear: both;">
        <?php
    }
}

if( !function_exists( 'ext_configuration_metabox_save_meta' ) ){
    function ext_configuration_metabox_save_meta( $post_id, $post ) { // save the data
        
        /*
        * We need to verify this came from our screen and with proper authorization,
        * because the save_post action can be triggered at other times.
        */

        if ( ! isset( $_POST['ext_shortcode_nonce_value'] ) ) { // Check if our nonce is set.
            return;
        }

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times
        if( ! wp_verify_nonce( $_POST['ext_shortcode_nonce_value'], plugin_basename(__FILE__) ) ) {
            return;
        }
       
        /**
         * @Hook Filter: exto_on_save_global_post
         * To change/Modify $_POST
         * Before Save Data on Database by update_post_meta() func
         * @since 6.1.0.5
         * @Hook_Version: 6.1.0.5
         */

        $save_elements_array = array(
            'ext_elements_array' => 'ext_elements_array',
            'ext_enabled_elements_array' => 'ext_enabled_elements_array',
            'ext_elements_settings' => 'ext_elements_settings',
            'ext_basics' => 'ext_basics',
            'ext_conditions' => 'ext_conditions',
            'ext_pagination' => 'ext_pagination',
            'ext_config' => 'ext_config',
        );

        $save_elements_array = apply_filters( 'ext_save_elements_array', $save_elements_array, $post_id, $post );

        if( ! is_array( $save_elements_array ) || ( is_array( $save_elements_array ) && count( $save_elements_array ) < 1 )){
            return;
        }

        /**
         * @Hook Action: exto_on_save_post_before_update_meta
         * To change data Just before update_post_meta() of our Product Table Form Data
         * @since 6.1.0.5
         * @Hook_Version: 6.1.0.5
         */
        add_action( 'ext_on_save_post_before_update_meta', $post_id );
        
        /**
         * In Filter, Availabe Tabs:
         * tabs: column_array,column_array_tablet,column_array_mobile,enabled_column_array,
         * enabled_column_array_tablet,enabled_column_array_mobile,
         * column_settings,column_settings_tablet,column_settings_mobile,
         * basics,table_style,conditions,mobile,search_n_filter,pagination,config
         * 
         * @since 2.9.1
         */
        $filtar_args = array(
            'ext_elements_array' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
            'ext_enabled_elements_array' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
            'ext_elements_settings' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
            'ext_basics' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
            'ext_conditions' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
            'ext_pagination' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
            'ext_config' => array(
                'filter' => FILTER_SANITIZE_STRING,
                'flags' => FILTER_REQUIRE_ARRAY,
            ),
        );
        
        $submitte_data = filter_input_array( INPUT_POST, $filtar_args );
        $submitte_data = array_filter( $submitte_data );

        /**
         * To removed empty/false value from full array
         * currently it's inactivated
         * 
         * @since 1.0.4.1
         */
        //$submitte_data = ext_array_filter_recursive( $submitte_data );
        
        foreach( $save_elements_array as $element ){
            
            /**
             * Already Filtered using filter_input_arry/filter_var_array
             * 
             * @since 2.9.1
             */
            $elements_settings_data = isset( $submitte_data[$element] ) ? $submitte_data[$element] : false; //XSS OK
            
            /**
             * Hook before save tab data
             * @Hooked: ext_data_manipulation_on_save at admin/functions.php
             */
            $elements_settings_data = apply_filters( 'ext_elements_data_on_save', $elements_settings_data, $element, $post_id, $save_elements_array );
            
            /**
             * Hook for Individual Tab data save.
             * 
             * Only for customer use at this moment.
             */
            $elements_settings_data = apply_filters( 'ext_elements_data_save_' . $element, $elements_settings_data, $post_id, $save_elements_array );
            update_post_meta( $post_id, $element, $elements_settings_data );
        }

        /**
         * @Hook Action: exto_on_save_post
         * To change data when Form will save.
         * @since 6.1.0.5
         * @Hook_Version: 6.1.0.5
         */
        add_action( 'ext_on_save_post', $post_id );

    }
}
add_action( 'save_post', 'ext_configuration_metabox_save_meta', 10, 2 ); // 