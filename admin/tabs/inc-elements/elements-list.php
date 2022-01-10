<ul id="ext_column_sortable" class="ext_column_sortable">
    <?php
    /**
     * Here was $ext_elements
     * I have added new $meta_enable_elements_array
     * 
     * Because: In new version, We will show only Enabled Col here
     * 
     * 
     */
    foreach( $ext_elements as $keyword => $title ){  //Here was $ext_elements in new: $meta_enable_elements_array
        $updated_title = isset( $updated_elements_array[$keyword] ) ? $updated_elements_array[$keyword] : $title;
        if( $meta_enable_elements_array && !empty( $meta_enable_elements_array ) && is_array( $meta_enable_elements_array ) ){
            $enabled_class = $checked_attribute = '';
            if( in_array( $keyword, array_keys( $meta_enable_elements_array ) ) ){
                $enabled_class = 'enabled first-time-enabled';
                $checked_attribute = ' checked="checked"';
            }
        }else{
            $enabled_class = 'enabled first-time-enabled';
            $checked_attribute = ' checked="checked"';
            if( !in_array( $keyword, $default_elements_array ) ){
                $enabled_class = $checked_attribute = '';
            }
        }
        $readOnly = ( $keyword == 'check' ? 'readonly' : false );
    ?>
    <li class="ext_sortable_peritem <?php echo esc_attr( $enabled_class ); ?> column_keyword_<?php echo esc_attr( $keyword ); ?>">
        <span title="<?php esc_attr_e( 'Move Handle', 'ext_pro' );?>" class="handle"></span>
        <div class="ext_shortable_data">
            <input placeholder="<?php echo esc_attr( $keyword ); ?>" 
                   name="ext_elements_array<?php echo esc_attr( $_device_name ); ?>[<?php echo esc_attr( $keyword ); ?>]"  
                   data-column_title="<?php echo esc_attr__( $updated_title,'ext_pro' ); ?>" 
                   data-keyword="<?php echo esc_attr( $keyword ); ?>" 
                   class="colum_data_input <?php echo esc_attr( $keyword ); ?>" 
                   type="text" 
                   title="<?php echo esc_attr__( 'Click for Expand/Collapse. Edit your Column Title here.', 'ext_pro' ); ?>"
                   value="<?php echo htmlentities( $updated_title ); ?>" <?php echo esc_attr( $readOnly ); ?>>
            
            <?php
            //Type Maintenance
            $type = isset( $elements_settings[$keyword]['type'] ) && !empty( $elements_settings[$keyword]['type'] ) ? $elements_settings[$keyword]['type'] : 'default';
            $type_name = isset( $elements_settings[$keyword]['type_name'] ) && !empty( $elements_settings[$keyword]['type_name'] ) ? $elements_settings[$keyword]['type_name'] : __( 'Default', 'ext_pro' );
            ?>
            <input type="hidden" name="ext_elements_settings<?php echo esc_attr( $_device_name ); ?>[<?php echo esc_attr( $keyword ); ?>][type]" value="<?php echo esc_attr( $type ); ?>">
            <input type="hidden" name="ext_elements_settings<?php echo esc_attr( $_device_name ); ?>[<?php echo esc_attr( $keyword ); ?>][type_name]" value="<?php echo esc_attr( $type_name ); ?>">
            
            <span class="ext_colunm_type">
            <?php if( !empty( $type ) &&  $type !== 'default'){ ?>
                <i><?php echo esc_html__( $type_name); ?> </i>: 
            <?php } echo esc_html__( $keyword ); ?>
            </span>
            <?php 
            
            if( in_array($keyword,$additional_collumn) ){
            ?>
                <span title='Remove this Column' onclick="return confirm('Deleting this Column\nAre you sure?');" class='ext_column_cross'>X</span>
            <?php
            }
            ?>
            
            
            <div class="ext_column_setting_single_extra">
                <?php 
                /**
                 * Adding Extra Features Based on Specific keyword
                 * 
                 * *****************************
                 * @HOOKED at includes/functions.php
                 * exto_column_setting_form_thumbnails ext_thumbnails_column_add 10,2
                 */
                do_action( 'exto_column_setting_form_' . $keyword, $_device_name, $elements_settings, $ext_elements, $updated_elements_array, $post ); ?>
            </div>
            <span data-key="<?php echo esc_attr( $keyword ); ?>" class="extra_all_on_off on_now"><i class="on_off_icon"></i>Expand</span>
            <div class="ext_column_setting_extra for_all extra_all_<?php echo esc_attr( $keyword ); ?>" data-ext_column_setting_extra="extra_all_<?php echo esc_attr( $keyword ); ?>">
                <?php do_action( 'exto_column_setting_form_inside_' . $keyword, $_device_name, $elements_settings, $ext_elements, $updated_elements_array, $post ); ?>
                <?php 
         
                do_action( 'exto_column_setting_form', $keyword, $_device_name, $elements_settings, $ext_elements, $updated_elements_array, $post ); ?>
                
            </div>
            <div class="ext_column_setting_extra for_all profeatures-message">
                <?php do_action( 'wpo_pro_feature_message', 'column_extra' ); ?>
            </div>
            

        </div>
        <span class="ext_column_arrow ext_arrow_top" data-target="prev" data-keyword="<?php echo esc_attr( $keyword ); ?>">&uArr;</span>
        <span class="ext_column_arrow ext_arrow_down" data-target="next" data-keyword="<?php echo esc_attr( $keyword ); ?>">&dArr;</span>
        <span title="<?php esc_attr_e( 'Move Handle', 'ext_pro' );?>" class="handle checkbox_handle">
            <input name="ext_enabled_elements_array<?php echo esc_attr( $_device_name ); ?>[<?php echo esc_attr( $keyword ); ?>]" 
                   value="<?php echo esc_attr( $keyword ); ?>" 
                   title="<?php echo esc_html__( 'Active Inactive Column', 'ext_pro' );?>" 
                   class="checkbox_handle_input <?php echo esc_attr( $enabled_class ); ?>" 
                   type="checkbox" 
                   data-column_keyword="<?php echo esc_attr( $keyword ); ?>" <?php echo esc_attr( $checked_attribute ); ?>>
        </span>
    </li>
    <?php
    
    }
    ?>

</ul>
