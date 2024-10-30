<?php
/*
* Template Name: Blog
*/
get_header(); 
#Redux global variable
global $enefti_redux;

$class = "col-md-12";
$sidebar = "sidebar-1";

if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
    if ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_fullwidth' ) {
        $class = "col-md-12";
    }elseif ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_right_sidebar' or $enefti_redux['enefti_blog_layout'] == 'enefti_blog_left_sidebar') {
        $class = "col-md-8";
    }
    // Check if active sidebar
    $sidebar = $enefti_redux['enefti_blog_layout_sidebar'];
}else{
    $class = "col-md-8";
}
if (!is_active_sidebar( $sidebar )) {
    $class = "col-md-12";
}
$breadcrumbs_status = 'no';
$breadcrumbs_on_off = get_post_meta( get_the_ID(), 'breadcrumbs_on_off', true );
?>
<?php if (isset($breadcrumbs_on_off) && $breadcrumbs_on_off == 'yes') { ?>

    <!-- Breadcrumbs -->
    <?php echo enefti_header_title_breadcrumbs(); ?>
    
<?php $breadcrumbs_status = 'yes'; ?>
<?php } ?>
<!-- Page content -->
  
    <?php
    wp_reset_postdata();
    $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
    $args = array(
        'post_type'        => 'post',
        'post_status'      => 'publish',
        'paged'            => $paged,
    );
    $posts = new WP_Query( $args );
    ?>
    <!-- Blog content -->
    <div class="container blog-posts breadcrumbs_status-<?php echo esc_attr($breadcrumbs_status); ?>">
        <div class="row">
            <?php if (  class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                <?php if ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_left_sidebar' && is_active_sidebar( $sidebar )) { ?>
                    <div class="col-md-4 sidebar-content">
                        <?php dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>
            <?php } ?>
            <div class="<?php echo esc_attr($class); ?> main-content">
                <div class="row">
                    <?php if ( $posts->have_posts() ) : ?>
                        <?php /* Start the Loop */ ?>
                        <?php
                        while ( $posts->have_posts() ) : $posts->the_post(); ?>
                        <?php
                            /* Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'content', get_post_format() );
                        ?>
                        <?php endwhile; ?>
                        <?php echo '<div class="clearfix"></div>'; ?>
                    <?php else : ?>
                        <?php get_template_part( 'content', 'none' ); ?>
                    <?php endif; ?>

                    <div class="clearfix"></div>
                    <?php 
                    $wp_query = new WP_Query($args);
                    global  $wp_query;
                    if ($wp_query->max_num_pages != 1) { ?>                
                        <div class="modeltheme-pagination-holder col-md-12">           
                            <div class="modeltheme-pagination pagination">           
                                <?php the_posts_pagination(); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (  class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                <?php if ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_right_sidebar' && is_active_sidebar( $sidebar )) { ?>
                    <div class="col-md-4 sidebar-content sidebar-content-right-side">
                        <?php  dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>
            <?php }else{ ?>
                <?php if ( is_active_sidebar( $sidebar )) { ?>
                    <div class="col-md-4 sidebar-content sidebar-content-right-side">
                        <?php  dynamic_sidebar( $sidebar ); ?>
                    </div>
                <?php } ?>                    
            <?php } ?>
        </div>
    </div>

<?php
get_footer();
?>