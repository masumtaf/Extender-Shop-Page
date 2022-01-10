
<!-- Enable Active Collumn -->
<div class="add_switch_col_wrapper">
    <div class="section ultraaddons-panel add_new_column">
        <?php
        
        ?>
        <div class="section enable-available-cols switch-enable-available">
            <h2><?php echo esc_html__( 'Click from Following list to add as Column.', 'wpt_pro' ); ?></h2>
            <ul id="wpt-switch-list">
                <?php 
             
                $available_column_array = $ext_elements;
                asort($available_column_array);
                foreach( $available_column_array as $keyword => $title ){ 
                    $updated_title = isset( $updated_elements_array[$keyword] ) ? $updated_elements_array[$keyword] : $title;
                    if( $meta_enable_elements_array && !empty( $meta_enable_elements_array ) && is_array( $meta_enable_elements_array ) ){
                        $enabled_class = 'item-disabled';
                        $enabled_class = '';
                        if( in_array( $keyword, array_keys( $meta_enable_elements_array ) ) ){
                            $enabled_class = 'item-enabled';
                        }
                    }else{
                        $enabled_class = 'item-enabled';
                        if( !in_array( $keyword, $default_elements_array ) ){
                            $enabled_class = 'item-disabled';
                            $enabled_class = '';
                        }
                    }
                    
                ?>
                <li class="switch-enable-item switch-enable-item-<?php echo esc_attr( $keyword ); ?> <?php echo esc_attr( $enabled_class ); ?>" 
                    title="<?php echo esc_html( "key: $keyword & title: $updated_title" ); ?>"
                    data-column_keyword="<?php echo esc_attr( $keyword ); ?>">
                        <?php echo esc_html( $updated_title ); ?><i>[<?php echo esc_html( $keyword ); ?>]</i>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
