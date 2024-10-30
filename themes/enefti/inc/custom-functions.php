<?php
defined( 'ABSPATH' ) || exit;

// Logo Source
if (!function_exists('enefti_logo_source')) {
    function enefti_logo_source(){
        
        // REDUX VARIABLE
        global $enefti_redux;
        // html VARIABLE
        $html = '';
        // Metaboxes
        $enefti_metabox_header_logo = get_post_meta( get_the_ID(), 'enefti_metabox_header_logo', true );
        $enefti_metabox_header_logo_sticky = get_post_meta( get_the_ID(), 'enefti_metabox_header_logo_sticky', true );
        if (is_page()) {
            if (isset($enefti_metabox_header_logo) && $enefti_metabox_header_logo != '') {
                if($enefti_metabox_header_logo) {
                    $html .='<img class="enefti-logo" src="'.esc_url($enefti_metabox_header_logo).'" alt="'.esc_attr(get_bloginfo()).'" />';
                    $html .='<img class="enefti-logo-sticky" src="'.esc_url($enefti_metabox_header_logo_sticky).'" alt="'.esc_attr(get_bloginfo()).'" />';
                } else { 
                    $html .='<img class="enefti-logo" src="'.esc_url($enefti_redux['enefti_logo']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
                    $html .='<img class="enefti-logo-sticky" src="'.esc_url($enefti_redux['enefti_logo_sticky']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
                }
            }else{
                if(!empty($enefti_redux['enefti_logo']['url'])){
                    $html .='<img class="enefti-logo" src="'.esc_url($enefti_redux['enefti_logo']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
                    if(isset($enefti_logo_sticky)){
                        $html .='<img class="enefti-logo-sticky" src="'.esc_url($enefti_redux['enefti_logo_sticky']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
                    }
                }else{ 
                    $html .= $enefti_redux['enefti_logo_text'];
                }
            }
        }else{
            if(!empty($enefti_redux['enefti_logo']['url'])){
                $html .='<img class="enefti-logo" src="'.esc_url($enefti_redux['enefti_logo']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
                $html .='<img class="enefti-logo-sticky" src="'.esc_url($enefti_redux['enefti_logo_sticky']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
            }elseif(isset($enefti_redux['enefti_logo_text'])){ 
                $html .= $enefti_redux['enefti_logo_text'];
            }else{
                $html .= esc_html(get_bloginfo());
            }
        }
        return $html; 
    }
}
// Logo Area
if (!function_exists('enefti_logo')) {
    function enefti_logo(){
    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
        global $enefti_redux;
        // html VARIABLE
        $html = '';
        $html .='<div class="logo logo-image">';
            $html .='<a href="'.esc_url(get_site_url()).'">';
                $html .= enefti_logo_source();
            $html .='</a>';
        $html .='</div>';
        return $html;
        // REDUX VARIABLE
     } else {
        global $enefti_redux;
        // html VARIABLE
        $html = '';
        $html .='<div class="logo logo-h">';
            $html .='<a href="'.esc_url(get_site_url()).'">';
                $html .= esc_html(get_bloginfo());
            $html .='</a>';
        $html .='</div>';
        return $html;
     } 
    }
}
// Add specific CSS class by filter
if (!function_exists('enefti_body_classes')) {
    function enefti_body_classes( $classes ) {
        global  $enefti_redux;
        $plugin_redux_status = '';
        if ( ! class_exists( 'ReduxFrameworkPlugin' ) ) {
            $plugin_redux_status = 'missing-redux-framework';
        }
        $plugin_modeltheme_status = '';
        if ( ! class_exists( 'ReduxFrameworkPlugin' ) ) {
            $plugin_modeltheme_status = 'missing-modeltheme-framework';
        }
        // CHECK IF FEATURED IMAGE IS FALSE(Disabled)
        $post_featured_image = '';
        if (is_single()) {
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                if ($enefti_redux['post_featured_image'] == false) {
                    $post_featured_image = 'hide_post_featured_image';
                }else{
                    $post_featured_image = '';
                }
            }
        }
        // CHECK IF THE NAV IS STICKY
        $is_nav_sticky = '';
        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
            if ($enefti_redux['is_nav_sticky'] == true) {
                // If is sticky
                $is_nav_sticky = 'is_nav_sticky';
            }else{
                // If is not sticky
                $is_nav_sticky = '';
            }
        }
        //TRANSPARENT HEADER
        $is_transparent = '';
        if (is_page()) {
            $mt_header_custom_transparent = get_post_meta( get_the_ID(), 'enefti_metabox_header_transparent', true );
            $is_transparent = '';
            if (isset($mt_header_custom_transparent) AND $mt_header_custom_transparent == 'yes') {
                $is_transparent = 'is_transparent';
            }else{
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    if ($mt_header_custom_transparent == 'yes') {
                        $is_transparent = 'is_transparent';
                    }else{
                        $is_transparent = '';
                    }
                }
            }
        }
        // DIFFERENT HEADER LAYOUT TEMPLATES
        $header_version = 'first_header';
        if (is_page()) {
            $mt_header_custom_variant = get_post_meta( get_the_ID(), 'enefti_header_custom_variant', true );
            $header_version = 'first_header';
            if (isset($mt_header_custom_variant) AND $mt_header_custom_variant != '') {
                if ($mt_header_custom_variant == '1') {
                    // Header Layout #1
                    $header_version = 'first_header';
                }elseif ($mt_header_custom_variant == '2') {
                    // Header Layout #2
                    $header_version = 'second_header';
                }elseif ($mt_header_custom_variant == '3') {
                    // Header Layout #3
                    $header_version = 'third_header';
                }elseif ($mt_header_custom_variant == '4') {
                    // Header Layout #4
                    $header_version = 'fourth_header';
                }elseif ($mt_header_custom_variant == '5') {
                    // Header Layout #5
                    $header_version = 'fifth_header';
                }elseif ($mt_header_custom_variant == '6') {
                    // Header Layout #6
                    $header_version = 'sixth_header';
                }elseif ($mt_header_custom_variant == '7') {
                    // Header Layout #7
                    $header_version = 'seventh_header';
                }elseif ($mt_header_custom_variant == '8') {
                    // Header Layout #8
                    $header_version = 'eighth_header';
                }elseif ($mt_header_custom_variant == '9') {
                    // Header Layout #8
                    $header_version = 'ninth_header';
                }else{
                    // if no header layout selected show header layout #1
                    $header_version = 'first_header';
                }
            }else{
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    if ($enefti_redux['header_layout'] == 'first_header') {
                        // Header Layout #1
                        $header_version = 'first_header';
                    }elseif ($enefti_redux['header_layout'] == 'second_header') {
                        // Header Layout #2
                        $header_version = 'second_header';
                    }elseif ($enefti_redux['header_layout'] == 'third_header') {
                        // Header Layout #3
                        $header_version = 'third_header';
                    }elseif ($enefti_redux['header_layout'] == 'fourth_header') {
                        // Header Layout #4
                        $header_version = 'fourth_header';
                    }elseif ($enefti_redux['header_layout'] == 'fifth_header') {
                        // Header Layout #5
                        $header_version = 'fifth_header';
                    }elseif ($enefti_redux['header_layout'] == 'sixth_header') {
                        // Header Layout #6
                        $header_version = 'sixth_header';
                    }elseif ($enefti_redux['header_layout'] == 'seventh_header') {
                        // Header Layout #7
                        $header_version = 'seventh_header';
                    }elseif ($enefti_redux['header_layout'] == 'eighth_header') {
                        // Header Layout #8
                        $header_version = 'eighth_header';
                    }elseif ($enefti_redux['header_layout'] == 'ninth_header') {
                        // Header Layout #8
                        $header_version = 'ninth_header';
                    }else{
                        // if no header layout selected show header layout #1
                        $header_version = 'first_header';
                    }
                }
            }
        }else{
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                if ($enefti_redux['header_layout'] == 'first_header') {
                    // Header Layout #1
                    $header_version = 'first_header';
                }elseif ($enefti_redux['header_layout'] == 'second_header') {
                    // Header Layout #2
                    $header_version = 'second_header';
                }elseif ($enefti_redux['header_layout'] == 'third_header') {
                    // Header Layout #3
                    $header_version = 'third_header';
                }elseif ($enefti_redux['header_layout'] == 'fourth_header') {
                    // Header Layout #4
                    $header_version = 'fourth_header';
                }elseif ($enefti_redux['header_layout'] == 'fifth_header') {
                    // Header Layout #5
                    $header_version = 'fifth_header';
                }elseif ($enefti_redux['header_layout'] == 'sixth_header') {
                    // Header Layout #6
                    $header_version = 'sixth_header';
                }elseif ($enefti_redux['header_layout'] == 'seventh_header') {
                    // Header Layout #7
                    $header_version = 'seventh_header';
                }elseif ($enefti_redux['header_layout'] == 'eighth_header') {
                    // Header Layout #8
                    $header_version = 'eighth_header';
                }elseif ($enefti_redux['header_layout'] == 'ninth_header') {
                    // Header Layout #8
                    $header_version = 'ninth_header';
                }else{
                    // if no header layout selected show header layout #1
                    $header_version = 'first_header';
                }
            }
        }

        $wc_vendors_status = '';
        if (class_exists('WC_Vendors')) {
            $wc_vendors_status = 'wc_vendors_active';
        }


        $mt_footer_row1 = '';
        $mt_footer_row2 = '';
        $mt_footer_row3 = '';
        $mt_footer_row4 = '';
        $mt_footer_bottom = '';
        
        $mt_footer_row1_status = get_post_meta( get_the_ID(), 'mt_footer_row1_status', true );
        $mt_footer_row2_status = get_post_meta( get_the_ID(), 'mt_footer_row2_status', true );
        $mt_footer_row3_status = get_post_meta( get_the_ID(), 'mt_footer_row3_status', true );
        $mt_footer_bottom_bar = get_post_meta( get_the_ID(), 'mt_footer_bottom_bar', true );

        if (isset($mt_footer_row1_status) && !empty($mt_footer_row1_status)) {
            $mt_footer_row1 = 'hide-footer-row-1';
        }
        if (isset($mt_footer_row2_status) && !empty($mt_footer_row2_status)) {
            $mt_footer_row2 = 'hide-footer-row-2';
        }
        if (isset($mt_footer_row3_status) && !empty($mt_footer_row3_status)) {
            $mt_footer_row3 = 'hide-footer-row-3';
        }
        if (isset($mt_footer_bottom_bar) && !empty($mt_footer_bottom_bar)) {
            $mt_footer_bottom = 'hide-footer-bottom';
        }


        $classes[] = esc_attr($mt_footer_row1) . ' ' . esc_attr($mt_footer_row2) . ' ' . esc_attr($mt_footer_row3) . ' ' . esc_attr($mt_footer_bottom) . ' ' . esc_attr($wc_vendors_status) . ' ' . esc_attr($plugin_modeltheme_status) . ' ' . esc_attr($plugin_redux_status) . ' ' . esc_attr($is_nav_sticky) . ' ' . esc_attr($header_version) . ' ' . esc_attr($is_transparent) . ' ' . esc_attr($post_featured_image) . ' ';

        return $classes;
    }
    add_filter( 'body_class', 'enefti_body_classes' );
}


// Mobile Dropdown Menu Button
if (!function_exists('enefti_burger_dropdown_button')) {
    function enefti_burger_dropdown_button(){
        if ( !class_exists( 'mega_main_init' ) ) {
        echo'<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>';
        }
    }
    add_action('enefti_burger_dropdown_button', 'enefti_burger_dropdown_button');
}


// Mobile Burger Aside variant
if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
    if ($enefti_redux['enefti_mobile_burger_select'] == 'sidebar') {
        if (!function_exists('enefti_burger_aside_button')) {
            function enefti_burger_aside_button(){
                if ( !class_exists( 'mega_main_init' ) ) { 
                    echo '<button id="aside-menu" type="button" class="navbar-toggle" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>';
                }
            }
        add_action('enefti_before_mobile_navigation_burger', 'enefti_burger_aside_button');
        }
    }
}

if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
    if ($enefti_redux['enefti_mobile_burger_select'] == 'sidebar') {
        if (!function_exists('enefti_burger_aside_menu')) {
            function enefti_burger_aside_menu(){

                global $enefti_redux;
                if( function_exists( 'YITH_WCWL' ) ){
                    $wishlist_url = YITH_WCWL()->get_wishlist_url();
                }else{
                    $wishlist_url = '#';
                }

                echo'<div class="mt-header">
                        <div class="header-aside">
                            <div class="aside-navbar">
                                <div class="aside-tabs">
                                    <a href="#mt-first-menu">'.esc_html__('Menu','enefti').'</a>
                                    <a href="#mt-second-menu">'.esc_html__('Categories','enefti').'</a>
                                </div>
                                <div class="nav-title">'.esc_html__('Menu','enefti').'</div>
                                    <div class="mt-nav-content">
                                        <div class="mt-first-menu">
                                            <div class="bot_nav_wrap">
                                                <ul class="menu nav navbar-nav pull-left nav-effect nav-menu">';
                                                    if ( has_nav_menu( 'primary' ) ) {
                                                    $defaults = array(
                                                        'menu'            => '',
                                                        'container'       => false,
                                                        'container_class' => '',
                                                        'container_id'    => '',
                                                        'menu_class'      => 'menu',
                                                        'menu_id'         => '',
                                                        'echo'            => true,
                                                        'fallback_cb'     => false,
                                                        'before'          => '',
                                                        'after'           => '',
                                                        'link_before'     => '',
                                                        'link_after'      => '',
                                                        'items_wrap'      => '%3$s',
                                                        'depth'           => 0,
                                                        'walker'          => ''
                                                    );
                                                    $defaults['theme_location'] = 'primary';
                                                    wp_nav_menu( $defaults );
                                                    }else{
                                                    echo '<p class="no-menu text-right">';
                                                        echo esc_html__('Primary navigation menu is missing.', 'enefti');
                                                    echo '</p>';
                                                    }   
                                                echo '</ul>
                                            </div>
                                        </div>
                                        <div class="mt-second-menu">';

                                        if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                                        echo'<div class="bot_nav_cat_inner">
                                                <div class="bot_nav_cat">
                                                    <ul class="bot_nav_cat_wrap">';
                                                    if ( has_nav_menu( 'category' ) ) {
                                                        $defaults = array(
                                                            'menu'            => '',
                                                            'container'       => false,
                                                            'container_class' => '',
                                                            'container_id'    => '',
                                                            'menu_class'      => 'menu',
                                                            'menu_id'         => '',
                                                            'echo'            => true,
                                                            'fallback_cb'     => false,
                                                            'before'          => '',
                                                            'after'           => '',
                                                            'link_before'     => '',
                                                            'link_after'      => '',
                                                            'items_wrap'      => '%3$s',
                                                            'depth'           => 0,
                                                            'walker'          => ''
                                                        );
                                                        $defaults['theme_location'] = 'category';
                                                        wp_nav_menu( $defaults );
                                                    }else{
                                                        echo '<p class="no-menu text-right">';
                                                            echo esc_html__('Category navigation menu is missing.', 'enefti');
                                                        echo '</p>';
                                                    }
                                            echo'</ul>
                                            </div>
                                        </div>';
                                       }
                                    echo '</div>
                                    </div>
                                    <div class="aside-footer">';
                                        if( function_exists( 'YITH_WCWL' ) ){
                                            echo '<a class="top-payment" href="'.esc_url($wishlist_url).'">
                                            <i class="fa fa-heart-o"></i>'.esc_html__('Wishlist', 'enefti').'</a>';
                                        }
                                    echo '</div>
                                </div>
                            </div>
                        </div>';
                echo '<div class="aside-bg"></div>';
            }
    add_action('enefti_after_mobile_navigation_burger', 'enefti_burger_aside_menu');
    }
}}


// Mobile Icons Top Group
if (!function_exists('enefti_header_mobile_icons_group')) {
    function enefti_header_mobile_icons_group(){

        if ( class_exists( 'ReduxFrameworkPlugin' ) ) { 
            if (enefti_redux('enefti_header_mobile_switcher_top') == true) {

                $cart_url = "#";
                if ( class_exists( 'WooCommerce' ) ) {
                    $cart_url = wc_get_cart_url();
                }
                #YITH Wishlist rul
                if( function_exists( 'YITH_WCWL' ) ){
                    $wishlist_url = YITH_WCWL()->get_wishlist_url();
                }else{
                    $wishlist_url = '#';
                }

                if (enefti_redux('enefti_header_mobile_switcher_top_search') == true) {
                    echo '<div class="mobile_only_icon_group search">
                                <a href="#" class="mt-search-icon">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </a>
                            </div>';
                }

                if(enefti_redux('is_popup_enabled') == true) {
                    if (is_user_logged_in() || is_account_page()) {
                        $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );;
                        $data_attributes = '';
                    }else{
                        $user_url = '#';
                        $data_attributes = 'data-modal="modal-log-in" class="modeltheme-trigger"';
                    }
                }else{
                    $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );;
                    $data_attributes = '';
                }

                if (enefti_redux('enefti_header_mobile_switcher_top_account') == true) {
                    echo '<div class="mobile_only_icon_group account">
                                <a href="' .esc_url($user_url). '" '.wp_kses_post($data_attributes).'>
                                    <i class="fa fa-user-circle"></i>
                                </a>
                        </div>';
               }
            }
        }

    }
    add_action('enefti_before_mobile_navigation_burger', 'enefti_header_mobile_icons_group');
}

// Mobile Icons Bottom Group
if (!function_exists('enefti_footer_mobile_icons_group')) {
    function enefti_footer_mobile_icons_group(){

        if ( class_exists( 'ReduxFrameworkPlugin' ) ) { 
            if (enefti_redux('enefti_header_mobile_switcher_footer') == true) {

                $cart_url = "#";
                if ( class_exists( 'WooCommerce' ) ) {
                    $cart_url = wc_get_cart_url();
                }

                #YITH Wishlist rul
                if( function_exists( 'YITH_WCWL' ) ){
                    $wishlist_url = YITH_WCWL()->get_wishlist_url();
                }else{
                    $wishlist_url = '#';
                }
                
                echo '<div class="mobile_footer_icon_wrapper">';
                    if (enefti_redux('enefti_header_mobile_switcher_footer_search') == true) {
                        echo '<div class="col-md-3 search">
                                    <a href="#" class="mt-search-icon">
                                        <i class="fa fa-search" aria-hidden="true"></i>'.esc_html__('Search','enefti').'
                                    </a>
                                </div>';
                    }
                    if (enefti_redux('enefti_header_mobile_switcher_footer_account') == true) {

                        if(enefti_redux('is_popup_enabled') == true) {
                            if (is_user_logged_in() || is_account_page()) {
                                $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );;
                                $data_attributes = '';
                            }else{
                                $user_url = '#';
                                $data_attributes = 'data-modal="modal-log-in" class="modeltheme-trigger"';
                            }
                        }else{
                            $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );;
                            $data_attributes = '';
                        }

                        echo '<div class="col-md-3 account">
                                    <a href="' .esc_url($user_url). '" '.wp_kses_post($data_attributes).'>
                                      <i class="fa fa-user"></i>'.esc_html__('Account','enefti').'
                                    </a>
                                </div>';
                    }
                echo '</div>';
            }
        }
    }
    add_action('enefti_before_footer_mobile_navigation', 'enefti_footer_mobile_icons_group');
}

// Top Header Banner
if (!function_exists('enefti_my_banner_header')) {
 function enefti_my_banner_header() {
    echo '<div class="enefti-top-banner text-center">
                <span class="discount-text">'.enefti_redux('discout_header_text').'</span>
                <div class="text-center row">';
                echo do_shortcode('[mt-addons-countdown insert_date="'.enefti_redux('discout_header_date').'"]');
          echo '</div>
          <a class="button btn" href="'.enefti_redux('discout_header_btn_link').'">'.enefti_redux('discout_header_btn_text').'</a>
          </div>';
}}

//GET HEADER TITLE/BREADCRUMBS AREA
if (!function_exists('enefti_header_title_breadcrumbs')) {
    function enefti_header_title_breadcrumbs(){
        echo '<div class="enefti-breadcrumbs">';
            echo '<div class="container">';
                echo '<div class="row">';
                    echo '<div class="col-md-12">';
                        if (is_singular('post')) {
                            echo '<h1>'.get_the_title().'</h1>';   
                        }elseif (is_page()) {
                            echo '<h1>'.get_the_title().'</h1>';
                        }elseif (is_singular('product')) {
                            echo '<h1>'.esc_html__( 'Our Shop', 'enefti' ) . get_search_query().'</h1>';
                        }elseif (is_search()) {
                            echo '<h1>'.esc_html__( 'Search Results for: ', 'enefti' ) . get_search_query().'</h1>';
                        }elseif (is_category()) {
                            echo '<h1>'.esc_html__( 'Category: ', 'enefti' ).' <span>'.single_cat_title( '', false ).'</span></h1>';
                        }elseif (is_tag()) {
                            echo '<h1>'.esc_html__( 'Tag: ', 'enefti' ) . single_tag_title( '', false ).'</h1>';
                        }elseif (is_author() || is_archive()) {
                            if (function_exists("is_shop") && is_shop()) {
                                echo '<h1>'.esc_html__( 'Explore All NFTs', 'enefti' ).'</h1>';
                            }else{
                                echo '<h1>'.get_the_archive_title().'</h1>';
                            }
                        }elseif (is_home()) {
                            echo '<h1>'.esc_html__( 'From the Blog', 'enefti' ).'</h1>';
                        }
                        
                    echo'</div>';
                    if(!function_exists('bcn_display')){
                        echo '<div class="col-md-12">';
                            echo '<ol class="breadcrumb">';
                                echo enefti_breadcrumb();
                            echo '</ol>';
                        echo '</div>';
                    } else {
                        echo '<div class="col-md-12">';
                            echo '<div class="breadcrumbs breadcrumbs-navxt" typeof="BreadcrumbList" vocab="https://schema.org/">';
                                echo bcn_display();
                            echo '</div>';
                        echo '</div>';
                    }

                echo'</div>';
            echo'</div>';
        echo'</div>';
    }
}


// Mobile Dropdown Menu Button
if (!function_exists('enefti_get_login_link')) {
    function enefti_get_login_link(){

        if(enefti_redux('is_popup_enabled') == true) {
            if (is_user_logged_in() || is_account_page()) {
                $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );;
                $data_attributes = '';
            }else{
                $user_url = '#';
                $data_attributes = 'data-modal="modal-log-in" class="modeltheme-trigger"';
            }
        }else{
            $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );;
            $data_attributes = '';
        }
        ?>

        <a href="<?php echo esc_url($user_url); ?>" <?php echo wp_kses_post($data_attributes); ?>>
            <?php esc_html_e('Sign In','enefti'); ?>
        </a>

        <?php 
    }
    add_action('enefti_login_link_a', 'enefti_get_login_link');
}

// Function to handle the thumbnail request
function enefti_get_the_post_thumbnail_src($img)
{
  return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}



/**
* Lists all active social media accounts from the theme panel
*/
if (!function_exists('enefti_social_media_accounts')) {
    function enefti_social_media_accounts($float = ''){
        ?>
        <!-- SOCIAL LINKS -->
        <ul class="enefti-social-links <?php echo esc_attr($float); ?>">
          <?php if ( enefti_redux('enefti_social_telegram') && enefti_redux('enefti_social_telegram') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_telegram') ) ?>"><i class="fab fa-telegram"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_fb') && enefti_redux('enefti_social_fb') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_fb') ) ?>"><i class="fab fa-facebook"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_tw') && enefti_redux('enefti_social_tw') != '' ) { ?>
            <li><a target="_blank" href="https://twitter.com/<?php echo esc_attr( enefti_redux('enefti_social_tw') ) ?>"><i class="fab fa-twitter"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_discord') && enefti_redux('enefti_social_discord') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_discord') ) ?>"><i class="fab fa-discord"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_pinterest') && enefti_redux('enefti_social_pinterest') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_pinterest') ) ?>"><i class="fab fa-pinterest"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_linkedin') && enefti_redux('enefti_social_linkedin') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_linkedin') ) ?>"><i class="fab fa-linkedin"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_skype') && enefti_redux('enefti_social_skype') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_skype') ) ?>"><i class="fab fa-skype"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_dribbble') && enefti_redux('enefti_social_dribbble') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_dribbble') ) ?>"><i class="fab fa-dribbble"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_deviantart') && enefti_redux('enefti_social_deviantart') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_deviantart') ) ?>"><i class="fab fa-deviantart"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_digg') && enefti_redux('enefti_social_digg') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_digg') ) ?>"><i class="fab fa-digg"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_flickr') && enefti_redux('enefti_social_flickr') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_flickr') ) ?>"><i class="fab fa-flickr"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_stumbleupon') && enefti_redux('enefti_social_stumbleupon') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_stumbleupon') ) ?>"><i class="fab fa-stumbleupon"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_tumblr') && enefti_redux('enefti_social_tumblr') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_tumblr') ) ?>"><i class="fab fa-tumblr"></i></a></li>
          <?php } ?>
          <?php if ( enefti_redux('enefti_social_vimeo') && enefti_redux('enefti_social_vimeo') != '' ) { ?>
            <li><a target="_blank" href="<?php echo esc_url( enefti_redux('enefti_social_vimeo') ) ?>"><i class="fab fa-vimeo-square"></i></a></li>
          <?php } ?>
        </ul>
        <?php
    }
    add_action('enefti_before_header_button', 'enefti_social_media_accounts');
}


//GET Social Floating button
if (!function_exists('enefti_floating_social_button')) {
    function enefti_floating_social_button(){

        $link = '#';
        $fa_class = '';

        if (enefti_redux('enefti_fixed_social_btn_status') == true) {
            if (enefti_redux('enefti_fixed_social_btn_social_select') == 'telegram') {
                $link = enefti_redux('enefti_social_telegram');
                $fa_class = 'fab fa-telegram';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'facebook') {
                $link = enefti_redux('enefti_social_fb');
                $fa_class = 'fab fa-facebook';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'twitter') {
                $link = enefti_redux('enefti_social_tw');
                $fa_class = 'fab fa-twitter';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'youtube') {
                $link = enefti_redux('enefti_social_youtube');
                $fa_class = 'fab fa-youtube-play';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'pinterest') {
                $link = enefti_redux('enefti_social_pinterest');
                $fa_class = 'fab fa-pinterest-p';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'pinterest') {
                $link = enefti_redux('enefti_social_pinterest');
                $fa_class = 'fab fa-pinterest-p';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'linkedin') {
                $link = enefti_redux('enefti_social_linkedin');
                $fa_class = 'fab fa-linkedin';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'skype') {
                $link = enefti_redux('enefti_social_skype');
                $fa_class = 'fab fa-skype';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'instagram') {
                $link = enefti_redux('enefti_social_instagram');
                $fa_class = 'fab fa-instagram';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'dribbble') {
                $link = enefti_redux('enefti_social_dribbble');
                $fa_class = 'fab fa-dribbble';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'deviantart') {
                $link = enefti_redux('enefti_social_deviantart');
                $fa_class = 'fab fa-deviantart';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'digg') {
                $link = enefti_redux('enefti_social_digg');
                $fa_class = 'fab fa-digg';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'flickr') {
                $link = enefti_redux('enefti_social_flickr');
                $fa_class = 'fab fa-flickr';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'stumbleupon') {
                $link = enefti_redux('enefti_social_stumbleupon');
                $fa_class = 'fab fa-stumbleupon';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'tumblr') {
                $link = enefti_redux('enefti_social_tumblr');
                $fa_class = 'fab fa-tumblr';
            }elseif (enefti_redux('enefti_fixed_social_btn_social_select') == 'vimeo') {
                $link = enefti_redux('enefti_social_vimeo');
                $fa_class = 'fab fa-vimeo';
            }

            // custom tooltip text
            $tooltip_text = __('Connect on Telegram', 'enefti');
            if (enefti_redux('enefti_fixed_social_btn_text_custom_text') == '1') {
                $tooltip_text = enefti_redux('enefti_fixed_social_btn_text');
            }
            ?>

            <a data-toggle="tooltip" data-placement="top" title="<?php echo esc_attr($tooltip_text); ?>" class="enefti-floating-social-btn" target="_blank" href="<?php echo esc_url($link); ?>">
                <i class="<?php echo esc_attr($fa_class); ?>"></i>
            </a>

            <?php
        }           
    }
    add_action('enefti_before_back_to_top_button', 'enefti_floating_social_button');

}

