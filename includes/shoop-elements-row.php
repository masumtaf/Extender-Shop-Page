<?php
$ext_each_row = apply_filters( 'ext_elements_row_attr', $ext_each_row, $product, $shop_elements_generator_array, $elements_settings, $shop_ID );

$items_permanent_dir = EXT_INCLUDES . 'elements/';
$items_permanent_dir = apply_filters('ext_item_permanent_dir', $items_permanent_dir, $shop_ID, $product );
$items_directory = apply_filters('wpto_item_dir', $items_permanent_dir, $shop_ID, $product );
?>

<li <?php wc_product_class( '', $product ); ?>>


<?php 
foreach( $ext_shop_keywords as $keyword => $keyword_title ){
     
    if( is_string( $keyword ) ){
        $in_extra_manager = false;

        /**
         * Variable $setting for All Keyword/Items
         * User able get Diect setting Variable from Items file
         * Such: from action.php file Directory: WPT Plugin -> items -> action.php
         * Setting Declear For All Items (TD or Inside Item of TD
         * @since version 1.0.0
         */
        $settings = isset( $elements_settings[$keyword] ) ? $elements_settings[$keyword] : false;

          /**
         * @Hook Filter: wpto_keyword_settings_$keyword
         * Each Column/ Each Item/ Each Item has Indivisual Setting.\
         * User able to chagne Setting from Addon Plugin
         * 
         * Suppose Custom_Field.php file using following Setting
         * $settings = isset( $elements_settings[$keyword] ) ? $elements_settings[$keyword] : false;
         */
        $settings = apply_filters( 'ext_keyword_settings_' . $keyword, $settings, $elements_settings, $shop_ID, $product  );

          /**
         * New Feature, Mainly for detect File Name. 
         * Such: for custom_field type, Scrip will load custom_field.php file inside Items Directory
         * Default value: default or empty
         */
        $type = isset( $elements_settings[$keyword]['type'] ) && !empty( $elements_settings[$keyword]['type'] ) ? $elements_settings[$keyword]['type'] : 'default';

        /**
         * Type: It's a Type of column, Normally all type default, but can be different. such: custom_file, taxonomy, acf etc
        * @Hook Filter: wpto_column_type
        * When sole same type colum will open from one file, than use Type.
        * Such: For custom field, use one file named: custom_field.php
        * Same for Taxonomy.\
        * Like this, we can add new type: acf, when file will open from acf.php from items
        */
        $type = apply_filters( 'ext_elements_type', $type, $keyword, $shop_ID, $product, $settings, $elements_settings );

        /**
         * @Hook Filter: wpto_template_folder
         * Items Template folder location for Each Keyword,
         * such: product_title, product_id, content,shortcode etc
         * 
         * Abble to change Template Root Directory Based on $keyword, $column_type, $shop_ID, Global $product
         * 
         */
        $items_directory_1 = apply_filters('exto_template_folder', $items_directory,$keyword, $type, $shop_ID, $product, $settings, $elements_settings );

        /**
         * @Hook Filter: wpto_item_dir_type_($type) change directory based on Column type
         * Items folder Directory based on Colum Type.
         * Such: For all default type column, request file available in includes/items folder
         * but for other type, such: acf, custom_field,taxonomy, we can set another Directory location from Addons or fro Pro version
         */
        $items_directory_2 = apply_filters('exto_item_dir_type_' . $type, $items_directory_1, $shop_ID, $product, $settings, $elements_settings );

        
        $file_name = $type !== 'default' ? $type : $keyword;
        $file = $items_directory_2 . $file_name . '.php';

        $file = apply_filters( 'exto_template_loc', $file, $keyword, $type, $shop_ID, $product, $file_name, $elements_settings, $settings ); //@Filter Added 
        $file = apply_filters( 'exto_template_loc_type_' . $type, $file, $keyword, $shop_ID, $product, $file_name, $elements_settings, $settings ); //@Filter Added
        $file  = apply_filters( 'exto_template_loc_item_' . $keyword, $file, $shop_ID, $product, $file_name, $elements_settings, $settings ); //@Filter Changed added Args $fileName

        
        /**
         * File Template Final Filter 
         * We have created this to make a new features, Where user will able to load template from Theme's Directory
         * 
         * To Load a new template of item from Theme, Use following location
         * [YourTheme]/woo-product-table/items/[YourItemFileName].php
         * 
         * Functionality Added at includes/functions.php file.
         */
        $file = $requested_file = apply_filters( 'exto_item_final_loc', $file, $file_name, $items_directory_2, $keyword, $shop_ID, $settings, $items_permanent_dir );

        if( !file_exists( $file ) ){
            $file = $items_permanent_dir . 'default.php';
            $file = apply_filters( 'exto_defult_file_loc', $file, $keyword, $product, $settings);
        }

        include $file;
       
        if( isset( $elements_settings[$keyword]['items'] ) ){
            include __DIR__ . '/extra_items_manager.php';
        }

        do_action( 'exto_column_bottom', $keyword, $shop_ID, $settings, $elements_settings, $product );

      


    }
}
echo '</li>';