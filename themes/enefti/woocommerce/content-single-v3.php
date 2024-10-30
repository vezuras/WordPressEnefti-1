<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php post_class('row'); ?>>
	<div class="col-md-12 thumbnails-summary">
		<div class="row">

			<?php 
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
				<?php if (enefti_redux('enefti-enable-general-info') == true) { 
	        		   $class ='col-md-4';
	        		   $related_class='col-md-12'; ?>
				<?php }else{
				        $class ='col-md-5';
				        $related_class='col-md-12';
				}
			}else{
				$class ='col-md-5';
				$related_class='col-md-12';
			} ?>

			<div class="<?php echo esc_attr($class); ?> product-thumbnails">
				<?php
					/**
					 * woocommerce_before_single_product_summary hook.
					 *
					 * @hooked woocommerce_show_product_sale_flash - 10
					 * @hooked woocommerce_show_product_images - 20
					 */
					do_action( 'woocommerce_before_single_product_summary' );
				?>
			</div>

			<?php if (enefti_redux('enefti-enable-general-info') == true) { 
        		$class_desc ='col-md-5'; 
        	}else{
        		$class_desc ='col-md-7'; 
        	} ?>

			<div class="summary entry-summary <?php echo esc_attr($class_desc); ?>">
				<div><?php do_action('woocommerce_enefti_meta_after_title'); ?></div>
				<?php
					/**
					 * woocommerce_single_product_summary hook.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
				?>

			</div><!-- .summary -->

			<?php if (enefti_redux('enefti-enable-general-info') == true) {  ?>
        		   <div class="col-md-3 product-general-info">
        		   	<?php do_action('woocommerce_enefti_vendor_section'); ?>
        		   	<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
        		   			
                            <div class="single-general-info col-md-12">
                            	<div class="col-md-3 info-img">
	                            	<?php if(enefti_redux('enefti-enable-general-img1','url')){ ?>
	                                	<img src="<?php echo esc_url(enefti_redux('enefti-enable-general-img1','url')); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>" />
	                          		<?php } ?>
	                          	</div>
	                          	<div class="col-md-9 ">
                            		<p><?php echo enefti_redux('enefti-enable-general-desc1'); ?></p>
                            	</div>
                            </div>
                            <?php if(enefti_redux('enefti-enable-general-desc2')){ ?>
                            <div class="single-general-info col-md-12">
                            	<div class="col-md-3 info-img">
	                            	<?php if(enefti_redux('enefti-enable-general-img2','url')){ ?>
	                                	<img src="<?php echo esc_url(enefti_redux('enefti-enable-general-img2','url')); ?>" alt="<?php echo esc_attr(get_bloginfo()); ?>" />
	                          		<?php } ?>
	                          	</div>
	                          	<div class="col-md-9">
                            		<p><?php echo enefti_redux('enefti-enable-general-desc2'); ?></p>
                            	</div>
                            </div>
                        	<?php } ?>
                    <?php } ?>
        		   </div>
			<?php } ?>

		</div>
	</div><!-- .summary -->

	
	<div class="<?php echo esc_attr($related_class); ?> tabs-related">
	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>
	</div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
