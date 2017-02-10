<?php

/**
 *
 */
function fafWooSupport() {
    add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'fafWooSupport' );

/**
 * @param $post
 */
function timber_set_product( $post ) {
    global $product;
    if ( is_woocommerce() ) {
        $product = get_product($post->ID);
    }
}

/**
 * @param $post
 */
function getWooProductImage($post)
{
    $product = wc_get_product($post->ID);

    $imgIds = $product->get_gallery_attachment_ids();
    $imgId = reset($imgIds);

    if (empty($imgId)) {
        $imgId = get_post_thumbnail_id($post);
    }

    $data['src'] = wp_get_attachment_image_url($imgId, 'thumbnail');

    return $data;
}

/**
 * remove breadcrumb
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

/**
 * remove default preview
 */
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');
/**
 *
 */
//remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail');