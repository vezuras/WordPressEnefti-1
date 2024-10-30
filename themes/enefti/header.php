<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php 
#Redux global variable
global $enefti_redux;
?>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) { ?>
        <link rel="shortcut icon" href="<?php echo esc_url(enefti_redux('enefti_favicon', 'url')); ?>">
    <?php } ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    /**
    * Since WordPress 5.2
    */
    if ( function_exists( 'wp_body_open' ) ) {
        wp_body_open();
    }

    /**
    * Login/Register popup hooked
    */
    do_action('enefti_after_body_open_tag');

    if (enefti_redux('mt_preloader_status')) {
        echo  '<div class="mt_preloader_holder">
            <div class="mt_preloader">
                <div class="outer-ring center"></div>
                <div class="inner-ring center"></div>
            </div>
        </div>';
    } 

    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
        if ($enefti_redux['enefti-enable-popup'] == true) {
            echo enefti_popup_modal(); 
        }
    }?>
    <div class="modeltheme-overlay"></div>
        
    <div id="page" class="hfeed site">
    <?php
        if (is_page()) {
            $mt_header_custom_variant = get_post_meta( get_the_ID(), 'enefti_header_custom_variant', true );
            if (isset($mt_header_custom_variant) && $mt_header_custom_variant != '') {
                get_template_part( 'templates/header-template'.esc_html($mt_header_custom_variant) );
            }else{
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    // DIFFERENT HEADER LAYOUT TEMPLATES
                    if ($enefti_redux['header_layout'] == 'first_header') {
                        // Header Layout #1
                        get_template_part( 'templates/header-template1' );
                    }elseif ($enefti_redux['header_layout'] == 'second_header') {
                        // Header Layout #2
                        get_template_part( 'templates/header-template2' );
                    }elseif ($enefti_redux['header_layout'] == 'third_header') {
                        // Header Layout #3
                        get_template_part( 'templates/header-template3' );
                    }elseif ($enefti_redux['header_layout'] == 'fourth_header') {
                        // Header Layout #4
                        get_template_part( 'templates/header-template4' );
                    }elseif ($enefti_redux['header_layout'] == 'fifth_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template5' );
                    }elseif ($enefti_redux['header_layout'] == 'sixth_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template6' );
                    }elseif ($enefti_redux['header_layout'] == 'seventh_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template7' );
                    }elseif ($enefti_redux['header_layout'] == 'eighth_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template8' );
                    }elseif ($enefti_redux['header_layout'] == 'ninth_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template9' );
                    }else{
                        // if no header layout selected show header layout #1
                        get_template_part( 'templates/header-template1' );
                    } 
                }else{
                    // if no header layout selected show header layout #1
                    get_template_part( 'templates/header-template2' );
                }
            }
        }else{
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                // DIFFERENT HEADER LAYOUT TEMPLATES
                if ($enefti_redux['header_layout'] == 'first_header') {
                    // Header Layout #1
                    get_template_part( 'templates/header-template1' );
                }elseif ($enefti_redux['header_layout'] == 'second_header') {
                    // Header Layout #5
                    get_template_part( 'templates/header-template2' );
                }elseif ($enefti_redux['header_layout'] == 'third_header') {
                    // Header Layout #5
                    get_template_part( 'templates/header-template3' );
                }elseif ($enefti_redux['header_layout'] == 'fourth_header') {
                        // Header Layout #4
                        get_template_part( 'templates/header-template4' );
                }elseif ($enefti_redux['header_layout'] == 'fifth_header') {
                    // Header Layout #5
                    get_template_part( 'templates/header-template5' );
                }elseif ($enefti_redux['header_layout'] == 'sixth_header') {
                    // Header Layout #5
                    get_template_part( 'templates/header-template6' );
                }elseif ($enefti_redux['header_layout'] == 'seventh_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template7' );
                }elseif ($enefti_redux['header_layout'] == 'eighth_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template8' );
                }elseif ($enefti_redux['header_layout'] == 'ninth_header') {
                        // Header Layout #5
                        get_template_part( 'templates/header-template9' );
                }else{
                    // if no header layout selected show header layout #1
                    get_template_part( 'templates/header-template2' );
                }
            }else{
                // if no header layout selected show header layout #1
                get_template_part( 'templates/header-template2' );
            }
        }