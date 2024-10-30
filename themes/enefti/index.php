<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package enefti
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
?>

    <!-- Breadcrumbs -->
    <?php echo enefti_header_title_breadcrumbs(); ?>

    <!-- Page content -->
    <div class="high-padding">
        <!-- Blog content -->
        <div class="container blog-posts">
            <div class="row">

                <?php if (  class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                    <?php if ( $enefti_redux['enefti_blog_layout'] == 'enefti_blog_left_sidebar' && is_active_sidebar( $sidebar )) { ?>
                        <div class="col-md-4 sidebar-content">
                            <?php dynamic_sidebar( $sidebar ); ?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="<?php echo esc_attr($class); ?> main-content">
                    <?php if ( have_posts() ) : ?>
                        <div class="row">
                            <?php /* Start the Loop */ ?>
                            <?php while ( have_posts() ) : the_post(); ?>

                                <?php
                                    /* Include the Post-Format-specific template for the content.
                                     * If you want to override this in a child theme, then include a file
                                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                     */
                                    get_template_part( 'content', get_post_format() );
                                ?>

                            <?php endwhile; ?>

                            <div class="enefti-pagination pagination col-md-12">             
                                <?php enefti_pagination(); ?>
                            </div>
                        </div>

                    <?php else : ?>

                        <?php get_template_part( 'content', 'none' ); ?>

                    <?php endif; ?>
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
    </div>

<?php get_footer(); ?>