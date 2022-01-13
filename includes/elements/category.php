<?php
function ext_woo_loop_product_title() {
   
    $terms = get_the_terms( $product->ID, 'product_cat' );
    if ( $terms && ! is_wp_error( $terms ) ) :
    // only displayed if the product has at least one category
        $cat_links = array();
    foreach ( $terms as $term ) {
        $cat_links[] = '<a href="'.esc_url( home_url() ).'/product-category/'.$term->slug.'">'.$term->name.'</a>';
    }
    $on_cat = join( ", ", $cat_links );
    ?>
    <div class="label-group">
        <div class="categories-link"><?php echo $on_cat; ?></div>
    </div>
<?php endif;
}
ext_woo_loop_product_title();