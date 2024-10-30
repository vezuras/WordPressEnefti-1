<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

global $product;
?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>" data-product-id="<?php echo esc_attr($product_id); ?>" data-product-type="<?php echo esc_attr($product_type); ?>" class="<?php echo esc_attr($link_classes); ?> button " data-tooltip="<?php echo esc_attr__('Add to Wishlist', 'enefti'); ?>">
    <?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', '<i class="fa fa-heart-o"></i>' )?>
</a>
<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ) ?>" class="ajax-loading" alt="<?php echo esc_attr__('loading', 'enefti'); ?>" width="16" height="16"/>