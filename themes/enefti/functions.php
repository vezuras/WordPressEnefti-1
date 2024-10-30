<?php
/**
 * enefti functions and definitions
 *
 * @package enefti
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
    $content_width = 640; /* pixels */
}

if ( ! function_exists( 'enefti_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function enefti_setup() {

    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on enefti, use a find and replace
     * to change 'enefti' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'enefti', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-header' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'woocommerce', array(
        'gallery_thumbnail_image_width' => 200,
        'woocommerce_thumbnail' => 768,
    ));
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    remove_theme_support( 'widgets-block-editor' );

    
    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary' => esc_html__( 'Header Navigation', 'enefti' )
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );

    /*
     * Enable support for Post Formats.
     */
    add_theme_support( 'post-formats', array(
        'aside', 'image', 'video', 'quote', 'link',
    ) );

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'enefti_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );
}
endif; // enefti_setup
add_action( 'after_setup_theme', 'enefti_setup' );

/**
 * Register widget area.
 *
 */
if (!function_exists('enefti_widgets_init')) {
    function enefti_widgets_init() {

        global $enefti_redux;

        register_sidebar( array(
            'name'          => esc_html__( 'Sidebar', 'enefti' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Used on Blog and Single Post', 'enefti' ),
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ) );
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar( array(
                'name'          => esc_html__( 'WooCommerce sidebar', 'enefti' ),
                'id'            => 'woocommerce',
                'description'   => esc_html__( 'Used on WooCommerce pages', 'enefti' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
        }

        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            register_sidebar( array(
                'name'          => esc_html__( 'Footer Top', 'enefti' ),
                'id'            => 'footer-top',
                'description'   => esc_html__( 'Listed above the regular widgets footer.', 'enefti' ),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ) );
            if (isset($enefti_redux['dynamic_sidebars']) && !empty($enefti_redux['dynamic_sidebars'])){
                foreach ($enefti_redux['dynamic_sidebars'] as &$value) {
                    $id           = str_replace(' ', '', $value);
                    $id_lowercase = strtolower($id);
                    if ($id_lowercase) {
                        register_sidebar( array(
                            'name'          => esc_html($value),
                            'id'            => esc_html($id_lowercase),
                            'description'   => esc_html($value),
                            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                            'after_widget'  => '</aside>',
                            'before_title'  => '<h3 class="widget-title">',
                            'after_title'   => '</h3>',
                        ) );
                    }
                }
            }

            // Footer Widgets Row 1
            if (isset($enefti_redux['enefti_number_of_footer_columns'])) {
                for ($i=1; $i <= intval( $enefti_redux['enefti_number_of_footer_columns'] ) ; $i++) { 
                    register_sidebar( array(
                        'name'          => esc_html__( 'Footer Row 1, Sidebar ', 'enefti' ).esc_html($i),
                        'id'            => 'footer_column_'.esc_html($i),
                        'description'   => esc_html__( 'Footer sidebar to show widgets by different column grid.', 'enefti' ),
                        'before_widget' => '<aside id="%1$s" class="widget vc_column_vc_container %2$s">',
                        'after_widget'  => '</aside>',
                        'before_title'  => '<h3 class="widget-title">',
                        'after_title'   => '</h3>',
                    ) );
                }
            }

            // Footer Widgets Row 2
            if ($enefti_redux['enefti-enable-footer-widgets-row2'] != '') {
                if (isset($enefti_redux['enefti_number_of_footer_columns_row2'])) {
                    for ($i=1; $i <= intval( $enefti_redux['enefti_number_of_footer_columns_row2'] ) ; $i++) { 
                        register_sidebar( array(
                            'name'          => esc_html__( 'Footer Row 2, Sidebar ', 'enefti' ).esc_html($i),
                            'id'            => 'footer_column_row2'.esc_html($i),
                            'description'   => esc_html__( 'Footer sidebar to show widgets by different column grid.', 'enefti' ),
                            'before_widget' => '<aside id="%1$s" class="widget vc_column_vc_container %2$s">',
                            'after_widget'  => '</aside>',
                            'before_title'  => '<h3 class="widget-title">',
                            'after_title'   => '</h3>',
                        ) );
                    }
                }
            }

            // Footer Widgets Row 3
            if ($enefti_redux['enefti-enable-footer-widgets-row3']) {
                if (isset($enefti_redux['enefti_number_of_footer_columns_row3'])) {
                    for ($i=1; $i <= intval( $enefti_redux['enefti_number_of_footer_columns_row2'] ) ; $i++) { 
                        register_sidebar( array(
                            'name'          => esc_html__( 'Footer Row 3, Sidebar ', 'enefti' ).esc_html($i),
                            'id'            => 'footer_column_row3'.esc_html($i),
                            'description'   => esc_html__( 'Footer sidebar to show widgets by different column grid.', 'enefti' ),
                            'before_widget' => '<aside id="%1$s" class="widget vc_column_vc_container %2$s">',
                            'after_widget'  => '</aside>',
                            'before_title'  => '<h3 class="widget-title">',
                            'after_title'   => '</h3>',
                        ) );
                    }
                }
            }
        }
    }
    add_action( 'widgets_init', 'enefti_widgets_init' );
}


/**
 * Enqueue scripts and styles.
 */
if (!function_exists('enefti_scripts')) {
    function enefti_scripts() {

        //STYLESHEETS
        wp_enqueue_style( 'font-awesome47', get_template_directory_uri().'/css/font-awesome.min.css' );
        wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/css/bootstrap.css' );
        wp_enqueue_style( 'enefti-media-screens', get_template_directory_uri().'/css/media-screens.css' );
        wp_enqueue_style( 'owl-carousel', get_template_directory_uri().'/css/owl.carousel.css' );
        wp_enqueue_style( 'owl-theme', get_template_directory_uri().'/css/owl.theme.css' );
        wp_enqueue_style( 'animate', get_template_directory_uri().'/css/animate.css' );
        wp_enqueue_style( 'simple-line-icons', get_template_directory_uri().'/css/simple-line-icons.css' );
        wp_enqueue_style( 'enefti-styles', get_template_directory_uri().'/css/style.css' );
        wp_enqueue_style( 'enefti-style', get_stylesheet_uri() );
        wp_enqueue_style( 'enefti-gutenberg-frontend', get_template_directory_uri().'/css/gutenberg-frontend.css' );
        wp_enqueue_style( 'dataTables', get_template_directory_uri().'/css/dataTables.min.css' );
        wp_enqueue_style( "nice-select", get_template_directory_uri()."/css/nice-select.css" );
        if (class_exists('WCFM') ) {
            wp_enqueue_style( 'jquery-datetimepicker', get_template_directory_uri().'/css/jquery.datetimepicker.min.css' );
        }

        //SCRIPTS
        wp_enqueue_script( 'modernizr-custom', get_template_directory_uri() . '/js/modernizr.custom.js', array('jquery'), '2.6.2', true );
        wp_enqueue_script( 'dataTables', get_template_directory_uri() . '/js/dataTables.min.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'jquery-nice-select', get_template_directory_uri() . '/js/jquery.nice-select.min.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'classie', get_template_directory_uri() . '/js/classie.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'jquery-form', get_template_directory_uri() . '/js/jquery.form.js', array('jquery'), '3.51', true );
        wp_enqueue_script( 'jquery-validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'), '1.13.1', true );
        wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/js/jquery.sticky.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'uisearch', get_template_directory_uri() . '/js/uisearch.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'jquery-appear', get_template_directory_uri() . '/js/count/jquery.appear.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'jquery-countTo', get_template_directory_uri() . '/js/count/jquery.countTo.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'modernizr-viewport', get_template_directory_uri() . '/js/modernizr.viewport.js', array('jquery'), '2.6.2', true );
        wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '3.3.1', true );
        wp_enqueue_script( 'animate', get_template_directory_uri() . '/js/animate.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'jquery-plugin', get_template_directory_uri() . '/js/countdown/jquery.plugin.min.js', array('jquery'), '2.1.0', true );
        wp_enqueue_script( 'jquery-countdown', get_template_directory_uri() . '/js/countdown/jquery.countdown.js', array('jquery'), '2.1.0', true );
        wp_enqueue_script( 'cookie', get_template_directory_uri() . '/js/jquery.cookie.min.js', array('jquery'), '1.0.0', true );
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script( 'jquery-match-height', get_template_directory_uri() . '/js/jquery.matchHeight.js', array('jquery'), '1.0.0', true );
        }
        // Color picker for dokan
        if (class_exists('WCFM')) {
            wp_enqueue_script( 'jquery-datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.full.min.js', array('jquery'), '1.0.0', true );
        }

        // GRID LIST TOGGLE
        if ( class_exists( 'WooCommerce' ) ) {
            if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
                wp_enqueue_script( 'custom-woocommerce', get_template_directory_uri() . '/js/custom-woocommerce.js', array('jquery'), '1.0.0', true );
                wp_enqueue_style( 'dashicons' );
            }
        }

        wp_enqueue_script( 'enefti-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '1.0.0', true );
        wp_enqueue_script( 'enefti-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array('jquery'), '20130115', true );
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }
    add_action( 'wp_enqueue_scripts', 'enefti_scripts' );
}



/**
 * Load jQuery datepicker.
 *
 * By using the correct hook you don't need to check `is_admin()` first.
 * If jQuery hasn't already been loaded it will be when we request the
 * datepicker script.
 */
function enefti_enqueue_datepicker() {
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_register_style( 'jquery-ui', get_template_directory_uri(). '/css/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );  
}
add_action( 'wp_enqueue_scripts', 'enefti_enqueue_datepicker' );

/**
 * Enqueue scripts and styles for admin dashboard.
 */
if (!function_exists('enefti_enqueue_admin_scripts')) {
    function enefti_enqueue_admin_scripts( $hook ) {
        wp_enqueue_style( 'admin-style-css', get_template_directory_uri().'/css/admin-style.css' );
        if ( 'post.php' == $hook || 'post-new.php' == $hook ) {
            wp_enqueue_style( 'enefti-admin-style', get_template_directory_uri().'/css/admin-style.css' );
        }
    }
    add_action('admin_enqueue_scripts', 'enefti_enqueue_admin_scripts');
}


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';


/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


/**
 * Include the TGM_Plugin_Activation class.
 */
require get_template_directory().'/inc/tgm/include_plugins.php';
/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
add_action( 'vc_before_init', 'enefti_vcSetAsTheme' );
function enefti_vcSetAsTheme() {
    vc_set_as_theme( true );
}


add_action( 'vc_base_register_front_css', 'enefti_enqueue_front_css_foreever' );

function enefti_enqueue_front_css_foreever() {
    wp_enqueue_style( 'js_composer_front' );
}

/* ========= LOAD - REDUX - FRAMEWORK ===================================== */
require_once(get_template_directory() . '/redux-framework/enefti-config.php');

// CUSTOM FUNCTIONS
require_once(get_template_directory() . '/inc/custom-functions.php');
require_once(get_template_directory() . '/inc/custom-functions.header.php');
require_once get_template_directory() . '/inc/custom-functions.gutenberg.php';
require_once get_template_directory() . '/inc/custom-functions.popup.php';
if (class_exists( 'WooCommerce' )) {
    require_once get_template_directory() . '/inc/custom-functions.woocommerce.php';
}
require_once get_template_directory() . '/inc/helpers.php';

/* ========= CUSTOM COMMENTS ===================================== */
require get_template_directory() . '/inc/custom-comments.php';

/* ========= RESIZE IMAGES ===================================== */
add_image_size( 'enefti_member_pic350x350',        350, 350, true );
add_image_size( 'enefti_collections149x100',        149, 100, true );
add_image_size( 'enefti_testimonials_pic110x110',  110, 110, true );
add_image_size( 'enefti_portfolio_pic400x400',     400, 400, true );
add_image_size( 'enefti_portfolio_230x350',     230, 350, true );
add_image_size( 'enefti_product_simple_285x380',     295, 390, true );
add_image_size( 'enefti_featured_post_pic500x230', 500, 230, true );
add_image_size( 'enefti_related_post_pic500x300',  500, 300, true );
add_image_size( 'enefti_post_pic700x450',          700, 450, true );
add_image_size( 'enefti_cat_pic500x500',          500, 500, true );
add_image_size( 'enefti_portfolio_pic500x350',     500, 350, true );
add_image_size( 'enefti_portfolio_pic700x450',     700, 450, true );
add_image_size( 'enefti_single_post_pic1200x500',   1200, 500, true );
add_image_size( 'enefti_single_prod_2',   1200, 200, true );
add_image_size( 'enefti_posts_1100x600',     1100, 600, true );
add_image_size( 'enefti_post_widget_pic70x70',     70, 70, true );
add_image_size( 'enefti_pic100x75',                100, 75, true );


/* ========= LIMIT POST CONTENT ===================================== */
function enefti_excerpt_limit($string, $word_limit) {
    $words = explode(' ', $string, ($word_limit + 1));
    if(count($words) > $word_limit) {
        array_pop($words);
    }
    return implode(' ', $words);
}

/* ========= BREADCRUMBS ===================================== */
if (!function_exists('enefti_breadcrumb')) {
    function enefti_breadcrumb() {
        global $enefti_redux;

         if (  class_exists( 'ReduxFrameworkPlugin' ) ) {
            if ( !$enefti_redux['enefti-enable-breadcrumbs'] ) {
               return false;
            }
        }

        $delimiter = '';
        //text for the 'Home' link
        $name = esc_html__("Home", "enefti");
            if (!is_home() && !is_front_page() || is_paged()) {
                global $post;
                global $product;
                $home = home_url();
                echo '<li><a href="' . esc_url($home) . '">' . esc_html($name) . '</a></li> ' . esc_html($delimiter) . '';
            if (is_category()) {
                global $wp_query;
                $cat_obj = $wp_query->get_queried_object();
                $thisCat = $cat_obj->term_id;
                $thisCat = get_category($thisCat);
                $parentCat = get_category($thisCat->parent);
                    if ($thisCat->parent != 0)
                echo(get_category_parents($parentCat, true, '' . esc_html($delimiter) . ''));
                echo   '<li class="active">' . esc_html(single_cat_title('', false)) .  '</li>';
            } elseif (is_day()) {
                echo '<li><a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . esc_html(get_the_time('Y')) . '</a></li> ' . esc_html($delimiter) . '';
                echo '<li><a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . get_the_time('F') . '</a></li> ' . esc_html($delimiter) . ' ';
                echo  '<li class="active">' . get_the_time('d') . '</li>';
            } elseif (is_month()) {
                echo '<li><a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a></li> ' . esc_html($delimiter) . '';
                echo  '<li class="active">' . get_the_time('F') . '</li>';
            } elseif (is_year()) {
                echo  '<li class="active">' . get_the_time('Y') . '</li>';
            } elseif (is_attachment()) {
                echo  '<li class="active">';
                the_title();
                echo '</li>';
            } elseif (class_exists( 'WooCommerce' ) && is_shop()) {
                echo  '<li class="active">';
                echo esc_html__('Shop','enefti');
                echo '</li>';
            }elseif (class_exists('WooCommerce') && is_product()) {
                if (get_the_category()) {
                    $cat = get_the_category();
                    $cat = $cat[0];
                    echo '<li>' . get_category_parents($cat, true, ' ' . esc_html($delimiter) . '') . '</li>';
                }
                echo  '<li class="active">';
                the_title();
                echo  '</li>';
            } elseif (is_single()) {
                if (get_the_category()) {
                    $cat = get_the_category();
                    $cat = $cat[0];
                    echo '<li>' . get_category_parents($cat, true, ' ' . esc_html($delimiter) . '') . '</li>';
                }
                echo  '<li class="active">';
                the_title();
                echo  '</li>';
            } elseif (is_page() && !$post->post_parent) {
                echo  '<li class="active">';
                the_title();
                echo  '</li>';
            } elseif (is_page() && $post->post_parent) {
                $parent_id = $post->post_parent;
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    $breadcrumbs[] = '<li><a href="' . esc_url(get_permalink($page->ID)) . '">' . get_the_title($page->ID) . '</a></li>';
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                foreach ($breadcrumbs as $crumb)
                    echo  wp_kses($crumb, 'link') . ' ' . esc_html($delimiter) . ' ';
                echo  '<li class="active">';
                the_title();
                echo  '</li>';
            } elseif (is_search()) {
                echo  '<li class="active">' . get_search_query() . '</li>';
            } elseif (is_tag()) {
                echo  '<li class="active">' . single_tag_title( '', false ) . '</li>';
            } elseif (is_author()) {
                global $author;
                $userdata = get_userdata($author);
                echo  '<li class="active">' . esc_html($userdata->display_name) . '</li>';
            } elseif (is_404()) {
                echo  '<li class="active">' . esc_html__('404 Not Found','enefti') . '</li>';
            }
            if (get_query_var('paged')) {
                if (is_home() || is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                    echo  '<li class="active">';
                echo esc_html__('Page','enefti') . ' ' . get_query_var('paged');
                if (is_home() || is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
                    echo  '</li>';
            }
        }
    }
}
// Ensure cart contents update when products are added to the cart via AJAX
if (!function_exists('enefti_woocommerce_header_add_to_cart_fragment')) {
    function enefti_woocommerce_header_add_to_cart_fragment( $fragments ) {
        ob_start();
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'View your shopping cart','enefti' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'enefti' ), WC()->cart->cart_contents_count ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a>
        <?php
        $fragments['a.cart-contents'] = ob_get_clean();
        return $fragments;
    } 
    add_filter( 'woocommerce_add_to_cart_fragments', 'enefti_woocommerce_header_add_to_cart_fragment' );
}


// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
if (!function_exists('enefti_woocommerce_header_add_to_cart_fragment_qty_only')) {
    function enefti_woocommerce_header_add_to_cart_fragment_qty_only( $fragments ) {
        ob_start();
        ?>
        <span class="cart-contents_qty"><?php echo sprintf ( esc_html__('(%d)', 'enefti'), WC()->cart->get_cart_contents_count() ); ?></span>
        <?php
        $fragments['span.cart-contents_qty'] = ob_get_clean();
        return $fragments;
    } 
    add_filter( 'woocommerce_add_to_cart_fragments', 'enefti_woocommerce_header_add_to_cart_fragment_qty_only' );
}

add_filter( 'woocommerce_widget_cart_is_hidden', 'enefti_always_show_cart', 40, 0 );
function enefti_always_show_cart() {
    return false;
}


// SINGLE PRODUCT
// Unhook functions
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
// Hook functions
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 50 );

if ( !function_exists( 'enefti_show_whislist_button_on_single' ) ) {
    function enefti_show_whislist_button_on_single() {
        if ( class_exists( 'YITH_WCWL' ) ) {
            echo '<div class="wishlist-container">';
                echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
            echo '</div>';
        }
    }
    if ( class_exists( 'YITH_WCWL' ) ) {
        add_action( 'woocommerce_single_product_summary', 'enefti_show_whislist_button_on_single', 4 );
    }
}

/* ========= PAGINATION ===================================== */
if ( ! function_exists( 'enefti_pagination' ) ) {
    function enefti_pagination($query = null) {

        if (!$query) {
            global $wp_query;
            $query = $wp_query;
        }
        
        $big = 999999999; // need an unlikely integer
        $current = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : '1');
        echo paginate_links( 
            array(
                'base'          => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'        => '?paged=%#%',
                'current'       => max( 1, $current ),
                'total'         => $query->max_num_pages,
                'prev_text'     => esc_html__('&#171;','enefti'),
                'next_text'     => esc_html__('&#187;','enefti'),
            ) 
        );
    }
}

function enefti_search_form( $form ) {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url( '/' )) . '" ><input type="hidden" name="post_type" value="post"><label><input type="text" class="search-field" placeholder="'.esc_attr__('Search ...', 'enefti').'" name="s" id="s" /></label>
    <button type="submit" class="search-submit"><i class="fa fa-search" aria-hidden="true"></i></button>
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'enefti_search_form', 100 );

/* ========= SEARCH FOR POSTS ONLY ===================================== */
function enefti_search_filter($query) {
    if ($query->is_search && !isset($_GET['post_type'])) {
        $query->set('post_type', 'post');
    }
    return $query;
}
if( !is_admin() ){
    add_filter('pre_get_posts','enefti_search_filter');
}

/* ========= CHECK FOR PINGBACKS ===================================== */
function enefti_post_has( $type, $post_id ) {
    $comments = get_comments('status=approve&type=' . esc_html($type) . '&post_id=' . esc_html($post_id) );
    $comments = separate_comments( $comments );
    return 0 < count( $comments[ $type ] );
}

/* ========= REGISTER FONT-AWESOME TO REDUX ===================================== */
if (!function_exists('enefti_register_fontawesome_to_redux')) {
    function enefti_register_fontawesome_to_redux() {
        wp_register_style( 'font-awesome', get_template_directory_uri().'/css/font-awesome.min.css', array(), time(), 'all' );  
        wp_enqueue_style( 'font-awesome' );
    }
    add_action( 'redux/page/redux_demo/enqueue', 'enefti_register_fontawesome_to_redux' );
}

// KSES ALLOWED HTML
if (!function_exists('enefti_kses_allowed_html')) {
    function enefti_kses_allowed_html($tags, $context) {
      switch($context) {
        case 'link': 
            $tags = array( 
                'a' => array(
                    'href' => array(),
                    'class' => array(),
                    'title' => array(),
                    'target' => array(),
                    'rel' => array(),
                    'data-commentid' => array(),
                    'data-postid' => array(),
                    'data-belowelement' => array(),
                    'data-respondelement' => array(),
                    'data-replyto' => array(),
                    'aria-label' => array(),
                ),
                'img' => array(
                    'src' => array(),
                    'alt' => array(),
                    'style' => array(),
                    'height' => array(),
                    'width' => array(),

                ),
            );
            return $tags;
        break;

        case 'icon':
            $tags = array(
                'i' => array(
                    'class' => array(),
                ),
            );
            return $tags;
        break;
        
        default: 
            return $tags;
      }
    }
    add_filter( 'wp_kses_allowed_html', 'enefti_kses_allowed_html', 10, 2);
}

/* Custom functions for woocommerce */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

if (!function_exists('enefti_woocommerce_show_top_custom_block')) {
    function enefti_woocommerce_show_top_custom_block() {
        $args = array();
        global $product;
        global $enefti_redux;
        echo '<div class="thumbnail-and-details">';    
                  
            wc_get_template( 'loop/sale-flash.php' );
            
            echo '<div class="overlay-container">';
                echo '<div class="hover-container">';
                    echo '<div class="component add-to-cart">';
                        woocommerce_template_loop_add_to_cart();
                    echo '</div>';
                echo '</div>';
            echo '</div>';

            echo '<a class="woo_catalog_media_images" title="'.the_title_attribute('echo=0').'" href="'.esc_url(get_the_permalink(get_the_ID())).'">'.woocommerce_get_product_thumbnail();
                if (class_exists('ReduxFrameworkPlugin')) {
	                if (enefti_redux('enefti-archive-secondary-image-on-hover') != '0' && enefti_redux('enefti-archive-secondary-image-on-hover') != '') {
		                // SECONDARY IMAGE (FIRST IMAGE FROM WOOCOMMERCE PRODUCT GALLERY)
		                $product = wc_get_product( get_the_ID() );
		                $attachment_ids = $product->get_gallery_image_ids();

		                if ( is_array( $attachment_ids ) && !empty($attachment_ids) ) {
		                    $first_image_url = wp_get_attachment_image_url( $attachment_ids[0], 'enefti_portfolio_pic400x400' );
		                    echo '<img class="woo_secondary_media_image" src="'.esc_url($first_image_url).'" alt="'.the_title_attribute('echo=0').'" />';
		                }
	                }
                }
            echo '</a>';
        echo '</div>';
    }
    add_action( 'woocommerce_before_shop_loop_item_title', 'enefti_woocommerce_show_top_custom_block' );
}


if (!function_exists('enefti_woocommerce_show_price_and_review')) {
    function enefti_woocommerce_show_price_and_review() {
        $args = array();
        global $product;
        global $enefti_redux;
        $price = esc_html(get_post_meta(get_the_id($product), '_mtnft_currency_price', true));

        echo '<div class="details-container">';
            echo '<div class="details-price-container details-item">';
                echo '<div class="details-price-wrapper">';
                    echo '<span>Reserve Price</span>';
                    if(class_exists( 'Mtnft_Vite' )) {
                        if($price) {
                            echo mtnft_frontend_crypto_price();
                        } else {
                            wc_get_template( 'loop/price.php' );
                        }
                    } else {
                        // Price & texts
                        wc_get_template( 'loop/price.php' );
                    }
                echo '</div>';
            echo '</div>';
        echo '</div>';

        echo '<div class="bottom-components-list">';
            // Add to cart button
            echo '<div class="component add-to-cart">';
                echo woocommerce_template_loop_add_to_cart();
            echo '</div>';
            // Wishlist button
            if ( class_exists( 'YITH_WCWL' ) ) {
                echo '<div class="component wishlist">';
                    echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
                echo '</div>';
            }
            // Quick View button
            if ( class_exists( 'YITH_WCQV' ) ) {
                echo '<div class="component quick-view">';
                    echo '<a href="'.esc_url('#') .'" class="button yith-wcqv-button" data-tooltip="'.esc_attr__('Quickview', 'enefti').'" data-product_id="' . esc_attr(yit_get_prop( $product, 'id', true )) . '"><i class="fa fa-search"></i></a>';
                echo '</div>';
            }
        echo '</div>';
    }
    add_action( 'woocommerce_after_shop_loop_item_title', 'enefti_woocommerce_show_price_and_review' );
}



function enefti_woocommerce_get_sidebar() {
    global $enefti_redux;

    if ( is_shop() || is_product_category() || is_product_tag() ) {
        if (is_active_sidebar($enefti_redux['enefti_shop_layout_sidebar'])) {
            dynamic_sidebar( $enefti_redux['enefti_shop_layout_sidebar'] );
        }else{
            if (is_active_sidebar('woocommerce')) {
                dynamic_sidebar( 'woocommerce' );
            } 
        }
    }elseif ( is_product() ) {
        if (is_active_sidebar($enefti_redux['enefti_single_shop_sidebar'])) {
            dynamic_sidebar( $enefti_redux['enefti_single_shop_sidebar'] );
        }else{
            if (is_active_sidebar('woocommerce')) {
                dynamic_sidebar( 'woocommerce' );
            }
        }
    }
}
add_action ( 'woocommerce_sidebar', 'enefti_woocommerce_get_sidebar' );


/*
 * Return a new number of maximum columns for shop archives
 * @param int Original value
 * @return int New number of columns
 */
add_filter( 'loop_shop_columns', 'enefti_wc_loop_shop_columns', 1, 13 );
function enefti_wc_loop_shop_columns( $number_columns ) {
    global $enefti_redux;

    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
        if ( $enefti_redux['enefti-shop-columns'] ) {
            return $enefti_redux['enefti-shop-columns'];
        }else{
            return 3;
        }
    }else{
        return 3;
    }
}

global $enefti_redux;

if ( isset($enefti_redux['enefti-enable-related-products']) && !$enefti_redux['enefti-enable-related-products'] ) {
   remove_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 50 );
}

if ( !function_exists( 'enefti_related_products_args' ) ) {
    add_filter( 'woocommerce_output_related_products_args', 'enefti_related_products_args' );
    function enefti_related_products_args( $args ) {
        global $enefti_redux;

        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            $args['posts_per_page'] = $enefti_redux['enefti-related-products-number'];
        }else{
            $args['posts_per_page'] = 4;
        }
        $args['columns'] = 4;
        return $args;
    }
}

/* search */
if (!function_exists('enefti_search_form_ajax_fetch')) {
    add_action( 'wp_footer', 'enefti_search_form_ajax_fetch' );
    function enefti_search_form_ajax_fetch() { ?>
        <script type="text/javascript">
         function fetchs(){

             jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                data: { action: 'enefti_search_form_data_fetch', keyword: jQuery('#keyword').val() },
                success: function(data) {
                    jQuery('#datafetch').html( data );
                }
            });

        }
        </script>
    <?php
    }
}


// the ajax function
if (!function_exists('enefti_search_form_data_fetch')) {
    add_action('wp_ajax_enefti_search_form_data_fetch' , 'enefti_search_form_data_fetch');
    add_action('wp_ajax_nopriv_enefti_search_form_data_fetch','enefti_search_form_data_fetch');
    function enefti_search_form_data_fetch(){
        if (  esc_attr( $_POST['keyword'] ) == null ) { die(); }
            $the_query = new WP_Query( array( 'post_type'=> 'product', 'post_per_page' =>  get_option('posts_per_page'), 's' => esc_attr( $_POST['keyword'] ) ) );
            $count_tax = 0;
            if( $the_query->have_posts() ) : ?>
                <ul class="search-result">           
                    <?php while( $the_query->have_posts() ): $the_query->the_post();  $post_type = get_post_type_object( get_post_type() ); ?>   
                        <?php $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ),'enefti_post_widget_pic70x70' ); ?>             
                        <li>
                            <a href="<?php echo esc_url( get_permalink() ); ?>">
                                <?php if($thumbnail_src) { ?>
                                    <?php the_post_thumbnail( 'enefti_post_widget_pic70x70' ); ?>
                                <?php } ?>
                                <?php the_title(); ?>
                            </a>
                        </li>             
                    <?php endwhile; ?>
                </ul>       
                <?php wp_reset_postdata();  
            
            endif;
        die();
    }
}

function enefti_add_editor_styles() {
    add_editor_style( 'css/custom-editor-style.css' );
}
add_action( 'admin_init', 'enefti_add_editor_styles' );


if (!function_exists('enefti_new_loop_shop_per_page')) {
    add_filter( 'loop_shop_per_page', 'enefti_new_loop_shop_per_page', 20 );
    function enefti_new_loop_shop_per_page( $cols ) {
      // $cols contains the current number of products per page based on the value stored on Options -> Reading
      // Return the number of products you wanna show per page.
      $cols = 9;
      return $cols;
    }
}

// KSES ALLOWED HTML
if (!function_exists('enefti_kses_allowed_html')) {
    function enefti_kses_allowed_html($tags, $context) {
      switch($context) {
        case 'link': 
          $tags = array( 
            'a' => array('href' => array()),
          );
          return $tags;
        default: 
          return $tags;
      }
    }
    add_filter( 'wp_kses_allowed_html', 'enefti_kses_allowed_html', 10, 2);
}

function enefti_redux($redux_meta_name1 = '',$redux_meta_name2 = ''){

    global  $enefti_redux;
    if (is_null($enefti_redux)) {
        return;
    }
    
    $html = '';
    if (isset($redux_meta_name1) && !empty($redux_meta_name2)) {
        $html = $enefti_redux[$redux_meta_name1][$redux_meta_name2];
    }elseif(isset($redux_meta_name1) && empty($redux_meta_name2)){
        $html = $enefti_redux[$redux_meta_name1];
    }
    
    return $html;
}

// Removing the WPBakery frontend editor
if (!function_exists('enefti_disable_wpbakery_frontend_editor')) {
    function enefti_disable_wpbakery_frontend_editor(){
        /**
        * Removes frontend editor
        */
        if ( function_exists( 'vc_disable_frontend' ) ) {
            vc_disable_frontend();
        }
    }
    add_action('vc_after_init', 'enefti_disable_wpbakery_frontend_editor');
}


if (!function_exists('enefti_account_login_lightbox')) {
    function enefti_account_login_lightbox(){
        if ( class_exists( 'WooCommerce' ) ) {
            if (!is_user_logged_in() && !is_account_page()) {
                ?>
                <div class="modeltheme-modal-holder">
                    <div class="modeltheme-overlay-inner"></div>
                    <div class="modeltheme-modal-container">
                        <div class="modeltheme-modal" id="modal-log-in">
                            <div class="modeltheme-content" id="login-modal-content">
                                <h3 class="relative text-center">
                                    <?php echo esc_html__('Access Your Account', 'enefti'); ?>
                                </h3>
                                <div class="modal-content row">
                                    <div class="col-md-12">
                                        <?php wc_get_template_part('myaccount/form-login'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              <?php
            }
        }
    }
    add_action('enefti_after_body_open_tag', 'enefti_account_login_lightbox');
}

/**
 * Minifying the CSS
  */
function enefti_minify_css($css){
  $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
  return $css;
}

if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
    if (!function_exists('enefti_social_share_buttons')) {
        function enefti_social_share_buttons() {
            
            // Get current page URL 
            $mt_url = esc_url(get_permalink());

            // Get current page title
            $mt_title = str_replace( ' ', '%20', get_the_title());
            
            // Get Post Thumbnail for pinterest
            $mt_thumb = enefti_get_the_post_thumbnail_src(get_the_post_thumbnail());

            $mt_thumb_url = '';
            if(!empty($mt_thumb)) {
                $mt_thumb_url = $mt_thumb[0];
            }

            // Construct sharing URL without using any script
            $twitter_url = 'https://twitter.com/intent/tweet?text='.esc_html($mt_title).'&amp;url='.esc_url($mt_url).'&amp;via='.esc_attr(get_bloginfo( 'name' ));
            $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u='.esc_url($mt_url);
            $whatsapp_url = 'https://api.whatsapp.com/send?text='.esc_html($mt_title) . ' ' . esc_url($mt_url);
            $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url='.esc_url($mt_url).'&amp;title='.esc_html($mt_title);
            if(!empty($mt_thumb)) {
                $pinterest_url = 'https://pinterest.com/pin/create/button/?url='.esc_url($mt_url).'&amp;media='.esc_url($mt_thumb_url).'&amp;description='.esc_html($mt_title);
            }else {
                $pinterest_url = 'https://pinterest.com/pin/create/button/?url='.esc_url($mt_url).'&amp;description='.esc_html($mt_title);
            }
            // Based on popular demand added Pinterest too
            $pinterest_url = 'https://pinterest.com/pin/create/button/?url='.esc_url($mt_url).'&amp;media='.esc_url($mt_thumb_url).'&amp;description='.esc_html($mt_title);
            $email_url = 'mailto:?subject='.esc_html($mt_title).'&amp;body='.esc_url($mt_url);

            $telegram_url = 'https://telegram.me/share/url?url=<'.esc_url($mt_url).'>&text=<'.esc_html($mt_title).'>';

            $social_shares = enefti_redux('enefti_social_share_links');

            if ($social_shares) {
                // The Visual Buttons
                echo '<div class="social-box"><div class="sharer-btn"><i class="fa fa-share-alt"></i></div>
                <div class="social-btn">';
                    if ($social_shares['twitter'] == 1) {
                        echo '<a class="col-2 sbtn s-twitter" href="'. esc_url($twitter_url) .'" target="_blank" rel="nofollow"></a>';
                    }
                    if ($social_shares['facebook'] == 1) {
                        echo '<a class="col-2 sbtn s-facebook" href="'.esc_url($facebook_url).'" target="_blank" rel="nofollow"></a>';
                    }
                    if ($social_shares['whatsapp'] == 1) {
                        echo '<a class="col-2 sbtn s-whatsapp" href="'.esc_url($whatsapp_url).'" target="_blank" rel="nofollow"></a>';
                    }
                    if ($social_shares['pinterest'] == 1) {
                        echo '<a class="col-2 sbtn s-pinterest" href="'.esc_url($pinterest_url).'" data-pin-custom="true" target="_blank" rel="nofollow"></a>';
                    }
                    if ($social_shares['linkedin'] == 1) {
                        echo '<a class="col-2 sbtn s-linkedin" href="'.esc_url($linkedin_url).'" target="_blank" rel="nofollow"></a>';
                    }
                    if ($social_shares['telegram'] == 1) {
                        echo '<a class="col-2 sbtn s-telegram" href="'.esc_url($telegram_url).'" target="_blank" rel="nofollow"></a>';
                    }
                    if ($social_shares['email'] == 1) {
                        echo '<a class="col-2 sbtn s-email" href="'.esc_url($email_url).'" target="_blank" rel="nofollow"></a>';
                    }
                echo '</div></div>';
            }
        }
        $social_share_locations = enefti_redux('enefti_social_share_locations');
        if ($social_share_locations['product'] == 1) {
            // single product page
            add_action( 'woocommerce_single_product_summary', 'enefti_social_share_buttons', 4);
        }
        if ($social_share_locations['post'] == 1) {
            // single post page
            add_action( 'enefti_after_single_post_metas', 'enefti_social_share_buttons');
        }
    }
}