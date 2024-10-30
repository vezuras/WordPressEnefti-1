<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();

if ( $woocommerce_loop['columns'] == 2 ) {
	$classes[] = '';
}elseif ( $woocommerce_loop['columns'] == 3 ) {
	$classes[] = '';
}elseif ( $woocommerce_loop['columns'] == 4 ) {
	$classes[] = '';
}

if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';
?>
<li <?php post_class( $classes ); ?>>
	<div class="products-wrapper">
	<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>

	

		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>

			<div class="woocommerce-title-metas">
				<div class="title-wishlist-wrapper">
					<h3 class="archive-product-title">
						<a href="<?php echo esc_url(get_the_permalink()); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
					<div class="product-wishlist">
			            <?php if ( class_exists( 'YITH_WCWL' ) ) { ?>
			                <div class="component wishlist">
			                    <?php echo do_shortcode( "[yith_wcwl_add_to_wishlist]" ); ?>
			                </div>
			            <?php } ?>
		        	</div>
		        </div>

			<?php
			/**
			 * woocommerce_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			/**
			 * woocommerce_after_shop_loop_item_title hook.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );

			/**
			 * woocommerce_after_shop_loop_item hook.
			 *
			 * @hooked woocommerce_template_loop_product_link_close - 5
			 * @hooked woocommerce_template_loop_add_to_cart - 10
			 */
			//do_action( 'woocommerce_after_shop_loop_item' );
			?>
		</div>
	</div>
</li>