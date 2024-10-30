<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     1.6.4
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header( 'shop' ); ?>
<?php
$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'enefti_single_post_pic1200x500' );
$side = "";
$class = "col-md-12";

if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
    if ( $enefti_redux['enefti_single_product_layout'] == 'enefti_shop_fullwidth' ) {
        $class = "col-md-12";
    }elseif ( $enefti_redux['enefti_single_product_layout'] == 'enefti_shop_right_sidebar' or $enefti_redux['enefti_single_product_layout'] == 'enefti_shop_left_sidebar') {
        $class = "col-md-9";
        if ( $enefti_redux['enefti_single_product_layout'] == 'enefti_shop_right_sidebar' ) {
            $side = "right";
        }else{
            $side = "left";
        }
    }
}
?>
<?php 
    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {        
        if ( enefti_redux('enefti_layout_version') == 'main') {
            $prod_template = 'single-product';
        }else if( enefti_redux('enefti_layout_version') == 'project'){
            $prod_template = 'single-project';
        }else if( enefti_redux('enefti_layout_version') == 'third'){
            $prod_template = 'single-v3';
        }
    } else { 
        $prod_template = 'single-product';
    }
    if(class_exists('Mt_Freelancer_Mode')) {
        if((get_option("freelancer_enabled") == "yes")) {
            $mtfm = 'mtfm ';
        } else {
            $mtfm = '';
        }
    } else {
        $mtfm = '';
    }
?>
<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
    <?php if ($enefti_redux['enefti_layout_version'] == 'main' or $enefti_redux['enefti_layout_version'] == 'project' or $enefti_redux['enefti_layout_version'] == 'third') { ?>
        <!-- Breadcrumbs -->
        <div class="enefti-single-product-v1 <?php echo esc_attr($prod_template) ?>">
            <div class="enefti-breadcrumbs">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <h1><?php the_title(); ?></h1>
                            </div>
                            <div class="col-md-12">
                                <ol class="breadcrumb">
                                    <?php enefti_breadcrumb(); ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>             
            <?php
                /**
                 * woocommerce_before_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                 * @hooked woocommerce_breadcrumb - 20
                 */
                do_action( 'woocommerce_before_main_content' );
            ?>
                <!-- Page content -->
            <div class="high-padding">
                <!-- Blog content -->
                <div class="container blog-posts">
                    <div class="row">
                        <?php if ( $side == 'left' ) { ?>
                            <div class="col-md-3 sidebar-content">
                                <?php
                                    /**
                                     * woocommerce_sidebar hook
                                     *
                                     * @hooked woocommerce_get_sidebar - 10
                                     */
                                    do_action( 'woocommerce_sidebar' );
                                ?>
                            </div>
                        <?php } ?>
                        <div class="<?php echo esc_attr($class); ?> <?php echo esc_attr($mtfm); ?>main-content">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php wc_get_template_part( 'content', ''.esc_attr($prod_template).'' ); ?>
                            <?php endwhile; // end of the loop. ?>
                        </div>
                        <?php if ( $side == 'right' ) { ?>
                        <div class="col-md-3 sidebar-content">
                            <?php //dynamic_sidebar( $sidebar ); ?>
                            <?php
                                /**
                                 * woocommerce_sidebar hook
                                 *
                                 * @hooked woocommerce_get_sidebar - 10
                                 */
                                do_action( 'woocommerce_sidebar' );
                            ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
                /**
                 * woocommerce_after_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );
            ?>
        <?php get_footer( 'shop' ); ?>

        </div>
    <?php } else { ?>
        <div class="enefti-single-product-v2"> 
            <div class="single-product-header">
                <div class="article-details relative text-center">         

                    <?php the_post_thumbnail( 'enefti_single_prod_2' ); ?>         
                    <div class="header-title-blog text-center">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    <div class="header-title-blog-box">
                                        <h1><?php the_title(); ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                /**
                 * woocommerce_before_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                 * @hooked woocommerce_breadcrumb - 20
                 */
                do_action( 'woocommerce_before_main_content' );
            ?>
                <!-- Page content -->
            <div class="high-padding">
                <!-- Blog content -->
                <div class="container blog-posts">
                    <div class="row">
                        <?php if ( $side == 'left' ) { ?>
                            <div class="col-md-3 sidebar-content">
                                <?php
                                    /**
                                     * woocommerce_sidebar hook
                                     *
                                     * @hooked woocommerce_get_sidebar - 10
                                     */
                                    do_action( 'woocommerce_sidebar' );
                                ?>
                            </div>
                        <?php } ?>
                        <div class="<?php echo esc_attr($class); ?> <?php echo esc_attr($mtfm); ?> main-content">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php wc_get_template_part( 'content', 'single-product' ); ?>
                            <?php endwhile; // end of the loop. ?>
                        </div>
                        <?php if ( $side == 'right' ) { ?>
                        <div class="col-md-3 sidebar-content">
                            <?php //dynamic_sidebar( $sidebar ); ?>
                            <?php
                                /**
                                 * woocommerce_sidebar hook
                                 *
                                 * @hooked woocommerce_get_sidebar - 10
                                 */
                                do_action( 'woocommerce_sidebar' );
                            ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php
                /**
                 * woocommerce_after_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );
            ?>
            <?php get_footer( 'shop' ); ?>
        </div>
    <?php } ?>
<?php }else{ ?>
    <!-- Breadcrumbs -->
    <div class="enefti-single-product-v1">
        <div class="enefti-breadcrumbs">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1><?php the_title(); ?></h1>
                    </div>
                    <div class="col-md-12">
                        <ol class="breadcrumb">
                            <?php enefti_breadcrumb(); ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>   
        <?php
            /**
             * woocommerce_before_main_content hook
             *
             * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
        ?>
            <!-- Page content -->
        <div class="high-padding">
            <!-- Blog content -->
            <div class="container blog-posts">
                <div class="row">
                    <?php if ( $side == 'left' ) { ?>
                        <div class="col-md-3 sidebar-content">
                            <?php
                                /**
                                 * woocommerce_sidebar hook
                                 *
                                 * @hooked woocommerce_get_sidebar - 10
                                 */
                                do_action( 'woocommerce_sidebar' );
                            ?>
                        </div>
                    <?php } ?>
                    <div class="<?php echo esc_attr($class); ?> main-content">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php wc_get_template_part( 'content', 'single-product' ); ?>
                        <?php endwhile; // end of the loop. ?>
                    </div>
                    <?php if ( $side == 'right' ) { ?>
                    <div class="col-md-3 sidebar-content">
                        <?php //dynamic_sidebar( $sidebar ); ?>
                        <?php
                            /**
                             * woocommerce_sidebar hook
                             *
                             * @hooked woocommerce_get_sidebar - 10
                             */
                            do_action( 'woocommerce_sidebar' );
                        ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
            /**
             * woocommerce_after_main_content hook
             *
             * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
             */
            do_action( 'woocommerce_after_main_content' );
        ?>
    <?php get_footer( 'shop' ); ?>

    </div>
<?php } ?>