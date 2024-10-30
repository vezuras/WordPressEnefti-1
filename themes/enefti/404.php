<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package enefti
 */

get_header(); ?>

	<!-- Page content -->
	<div id="primary" class="content-area">
	    <main id="main" class="container blog-posts high-padding site-main">
	        <div class="col-md-12 main-content">
				<section class="error-404 not-found">
					<div class="page-content text-center">
						<?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
							<img src="<?php echo esc_url(enefti_redux('img_404', 'url')); ?>" alt="<?php echo esc_attr__('Not Found','enefti'); ?>">
						<?php } else { 
							?> <img src="<?php echo esc_url(get_template_directory_uri() . '/images/404.png'); ?>" alt="<?php echo esc_attr__('Not Found','enefti'); ?>">
						<?php } ?>
						<p class="text-center"><?php esc_html_e( 'Sorry! The page you were looking for could not be found. Try searching for it or browse through our website.', 'enefti' ); ?></p>
						<a class="vc_button_404" href="<?php echo esc_url(get_site_url()); ?>"><?php esc_html_e( 'Back to Home', 'enefti' ); ?></a>
					</div>
				</section>
			</div>
		</main>
	</div>

<?php get_footer(); ?>