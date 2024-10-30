<?php
/**
 * @package enefti
 */
?>
<?php 
global $enefti_redux;

$thumbnail_class = 'col-md-12';
$post_details_class = 'col-md-12';
$type_class = 'list-view';

$master_class = 'col-md-12';
$image_size = 'enefti_posts_1100x600';

$thumbnail_class = 'full-width-part';
$post_details_class = 'full-width-part';

$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $image_size );
?>

<?php if (class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post grid-view col-md-12 list-view '); ?>>    
    <?php if($thumbnail_src) { ?>
    <div class="<?php echo esc_attr($thumbnail_class); ?> post-thumbnail">
        <a href="<?php echo esc_url(get_the_permalink()); ?>" class="relative">
            <?php if($thumbnail_src) { 
                echo '<img src="'. esc_url($thumbnail_src[0]) . '" alt="'.the_title_attribute('echo=0').'" />';
            } ?>
        </a>
    </div>
    <?php } ?>

    <div class="<?php echo esc_attr($post_details_class); ?> post-details">
        <h3 class="post-name row">
            <a href="<?php echo esc_url(get_the_permalink()); ?>" class="relative">
                <?php if (is_sticky()) { ?>
                    <i class="fa fa-bolt" aria-hidden="true"></i>
                <?php } ?>
                <?php the_title() ?>
            </a>
        </h3>
        
        <div class="post-category-comment-date row">
            <div class="article-details">
                <!-- POST AUTHOR -->
                <div class="article-detail-meta post-author">
                    <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) )); ?>">
                        <i class="icon-user"></i>
                        <?php echo esc_html(get_the_author()); ?>
                    </a>
                </div>
                <!-- POST CATEGORY -->
                <?php if (get_the_category()) { ?>
                    <div class="article-detail-meta post-categories post-author">
                        <?php echo get_the_term_list( get_the_ID(), 'category', '<i class="icon-tag"></i>', ', ' ); ?>
                    </div>
                <?php } ?>
                <!-- POST DATE -->
                <div class="article-detail-meta post-date">
                    <i class="icon-calendar"></i>
                    <?php echo esc_html(get_the_date()); ?>
                </div>
            </div>
        </div>
        <div class="post-excerpt row">
        <?php
            /* translators: %s: Name of current post */
            the_excerpt();
        ?>
        <div class="clearfix"></div>
        <p class="read-more-holder">
            <a href="<?php echo esc_url(get_the_permalink()); ?>" class="more-link">
                <?php echo esc_html__( 'Read More', 'enefti' ); ?>
            </a>
        </p>
        <div class="clearfix"></div>
        <?php
            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'enefti' ),
                'after'  => '</div>',
            ) );
        ?>
        </div>
    </div>
</article>
<?php }else{ ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('single-post grid-view '.esc_html($master_class).' '.esc_html($type_class)); ?>>    
    
    <?php if($thumbnail_src) { ?>
    <div class="<?php echo esc_attr($thumbnail_class); ?> post-thumbnail">
        <a href="<?php echo esc_url(get_the_permalink()); ?>" class="relative">
            <?php 
            $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $image_size );
            if($thumbnail_src) { 
                echo '<img src="'. esc_url($thumbnail_src[0]) . '" alt="'.the_title_attribute('echo=0').'" />';
            } ?>
        </a>
    </div>
    <?php } ?>

    <div class="<?php echo esc_attr($post_details_class); ?> post-details">
        <!-- POST TITLE -->
        <h3 class="post-name row">
            <a href="<?php echo esc_url(get_the_permalink()); ?>" class="relative">
                <?php if (is_sticky()) { ?>
                    <i class="fa fa-bolt" aria-hidden="true"></i>
                <?php } ?>
                <?php the_title(); ?>
            </a>
        </h3>
        
        <!-- POST METAS -->
        <div class="post-category-comment-date row">
            <div class="article-details">
                <!-- POST AUTHOR -->
                <div class="article-detail-meta post-author">
                    <a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) )); ?>">
                        <i class="icon-user"></i>
                        <?php echo esc_html(get_the_author()); ?>
                    </a>
                </div>
                <!-- POST CATEGORY -->
                <?php if (get_the_category()) { ?>
                    <div class="article-detail-meta post-categories post-author">
                        <?php echo get_the_term_list( get_the_ID(), 'category', '<i class="icon-tag"></i>', ', ' ); ?>
                    </div>
                <?php } ?>
                <!-- POST DATE -->
                <div class="article-detail-meta post-date">
                    <i class="icon-calendar"></i>
                    <?php echo esc_html(get_the_date()); ?>
                </div>
            </div>
        </div>
        <!-- POST EXCERPT -->
        <div class="post-excerpt row">
            <?php
                /* translators: %s: Name of current post */
                    the_excerpt();
            ?>
            
            <div class="clearfix"></div>
            <p>
                <a href="<?php echo esc_url(get_the_permalink()); ?>" class="more-link">
                    <?php echo esc_html__( 'Read More', 'enefti' ); ?>
                </a>
            </p>
            
            <div class="clearfix"></div>
            <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'enefti' ),
                    'after'  => '</div>',
                ) );
            ?>
        </div>
    </div>
</article>
<?php } ?>