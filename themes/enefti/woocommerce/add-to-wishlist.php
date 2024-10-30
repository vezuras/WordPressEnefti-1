<?php
/**
 * Add to wishlist template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

global $product;
?>

<div class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo esc_attr($product_id); ?>">
    <div class="yith-wcwl-add-button <?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'hide': 'show' ?>" style="display:<?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'none': 'block' ?>">

        <?php yith_wcwl_get_template( 'add-to-wishlist-' . esc_html($template_part) . '.php', $var ); ?>

    </div>

    <div class="yith-wcwl-wishlistaddedbrowse hide">
        <a href="<?php echo esc_url( $wishlist_url )?>" data-toggle="tooltip" data-tooltip="<?php echo esc_attr__('Add to Wishlist Wishlist', 'enefti'); ?>" data-placement="top" title="<?php echo esc_attr__('Browse Wishlist', 'enefti'); ?>">
            <?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', '<i class="fa fa-heart"></i>' )?>
        </a>
    </div>

    <div class="yith-wcwl-wishlistexistsbrowse <?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'show' : 'hide' ?>" style="display:<?php echo esc_attr( $exists && ! $available_multi_wishlist ) ? 'block' : 'none' ?>">
        <a href="<?php echo esc_url( $wishlist_url ) ?>" data-tooltip="<?php echo esc_attr__('Check Wishlist', 'enefti'); ?>" class="button">
            <?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', '<i class="fa fa-heart"></i>' )?>
        </a>
    </div>

    <div style="clear:both"></div>
    <div class="yith-wcwl-wishlistaddresponse"></div>

</div>