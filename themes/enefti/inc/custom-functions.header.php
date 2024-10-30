<?php
defined( 'ABSPATH' ) || exit;

/**
CUSTOM HEADER FUNCTIONS
*/

/**
||-> FUNCTION: GET SITE FONTS
*/
if (!function_exists('enefti_get_site_fonts')) {
	function enefti_get_site_fonts(){
	    global  $enefti_redux;
	    $fonts_string = '';
	    if (isset($enefti_redux['google_fonts_select'])) {
	        $i = 0;
	        $len = count($enefti_redux['google_fonts_select']);
	        foreach(array_keys($enefti_redux['google_fonts_select']) as $key){
	            $font_url = str_replace(' ', '+', $enefti_redux['google_fonts_select'][$key]);
	            
	            if ($i == $len - 1) {
	                // last
	                $fonts_string .= $font_url;
	            }else{
	                $fonts_string .= $font_url . '|';
	            }
	            $i++;
	        }
	        
	    }else{
	        $fonts_string = 'Montserrat:regular,500,600,700,800,900,latin|Poppins:300,regular,500,600,700,latin-ext,latin,devanagari|Raleway:300,regular,500,600,bold,700';
	    }
	    // fonts url
	        $fonts_url = add_query_arg( 'family', $fonts_string, "//fonts.googleapis.com/css" );
	        // enqueue fonts
	        wp_enqueue_style( 'enefti-fonts', $fonts_url, array(), '1.0.0' );
	}
	add_action('wp_enqueue_scripts', 'enefti_get_site_fonts');
}

/**
||-> FUNCTION: GET DYNAMIC CSS
*/
if (!function_exists('enefti_dynamic_css')) {
	add_action('wp_enqueue_scripts', 'enefti_dynamic_css' );
	function enefti_dynamic_css(){
		wp_enqueue_style(
		   'enefti-custom-style',
		    get_template_directory_uri() . '/css/custom-editor-style.css'
		);
	   	
	    $html = '';
	   	if (is_page()) {
			$enefti_global_page_color_1 = get_post_meta( get_the_ID(), 'enefti_global_page_color_1', true );
			$enefti_global_page_color_2 = get_post_meta( get_the_ID(), 'enefti_global_page_color_2', true );
			$enefti_global_page_color_hover = get_post_meta( get_the_ID(), 'enefti_global_page_color_hover', true );
			list($r, $g, $b) = sscanf($enefti_global_page_color_1, "#%02x%02x%02x");
			if ((isset($enefti_global_page_color_1) && $enefti_global_page_color_1 != '') && (isset($enefti_global_page_color_2) && $enefti_global_page_color_2 != '') && (isset($enefti_global_page_color_hover) && $enefti_global_page_color_hover != '')) {
				$enefti_style_main_texts_color = $enefti_global_page_color_2;
				$enefti_style_main_backgrounds_color_gr1 = $enefti_global_page_color_1;
				$enefti_style_main_backgrounds_color_gr2 = $enefti_global_page_color_2;
				$enefti_style_main_backgrounds_color_hover = $enefti_global_page_color_hover;
				
				$back_to_top = $enefti_global_page_color_1;
				$html .= '	.back-to-top{
								background-color: '.esc_html($enefti_style_main_backgrounds_color_gr1).'; 
							}';
			}else{
				$enefti_style_main_texts_color = enefti_redux("enefti_style_main_texts_color");
				$enefti_style_main_backgrounds_color_gr1 = enefti_redux("enefti_style_main_backgrounds_color",'from');
				$enefti_style_main_backgrounds_color_gr2 = enefti_redux("enefti_style_main_backgrounds_color",'to');
				$enefti_style_main_backgrounds_color_hover = enefti_redux("enefti_style_main_backgrounds_color_hover");
				$enefti_style_semi_opacity_backgrounds = enefti_redux('enefti_style_semi_opacity_backgrounds', 'alpha');
			}
			
	   	}else{
			$enefti_style_main_texts_color = enefti_redux("enefti_style_main_texts_color");
			$enefti_style_main_backgrounds_color_gr1 = enefti_redux("enefti_style_main_backgrounds_color",'from');
			$enefti_style_main_backgrounds_color_gr2 = enefti_redux("enefti_style_main_backgrounds_color",'to');
			$enefti_style_main_backgrounds_color_hover = enefti_redux("enefti_style_main_backgrounds_color_hover");
			$enefti_style_semi_opacity_backgrounds = enefti_redux('enefti_style_semi_opacity_backgrounds', 'alpha');

	   	}

	    if ( !class_exists( 'ReduxFrameworkPlugin' ) ) {
			$enefti_style_main_texts_color = '#D01498';
			$enefti_style_main_backgrounds_color_gr1 = '#D01498';
			$enefti_style_main_backgrounds_color_gr2 = '#647ECB';
			$enefti_style_main_backgrounds_color_hover = '#ffffff';
			$enefti_style_semi_opacity_backgrounds = 'rgba(240, 34, 34, .90)';
	    	$fields_radius = '5';
	    }else{
	    	$fields_radius = enefti_redux('enefti_fields_styling_radius');
			$enefti_style_semi_opacity_backgrounds = enefti_redux('enefti_style_semi_opacity_backgrounds', 'alpha');
	    }

	    // PAGE PRELOADER BACKGROUND COLOR
    	$mt_page_preloader = get_post_meta( get_the_ID(), 'mt_page_preloader', true );
    	$mt_page_preloader_bg_color = get_post_meta( get_the_ID(), 'mt_page_preloader_bg_color', true );
    	if (isset($mt_page_preloader) && $mt_page_preloader == 'enabled' && isset($mt_page_preloader_bg_color)) {
        $html .= 'body .linify_preloader_holder{
					background-color: '.esc_html($mt_page_preloader_bg_color).';
        		}';
    	}elseif (enefti_redux('mt_preloader_status')) {
        $html .= 'body .linify_preloader_holder{
					background-color: '.enefti_redux('mt_preloader_status').';
        		}';
    	}
    	
    	// Custom fields styling: header main
    	$enefti_header_nav_links_color = get_post_meta( get_the_ID(), 'enefti_header_nav_links_color', true );
    	if (isset($enefti_header_nav_links_color) && !empty($enefti_header_nav_links_color)) {
		    $html .= '.header-v2 li.nav-menu-account, .header-v2 .my-account-navbar a, header #navbar .menu-item > a, header .navbar-nav .search_products a, header .navbar-default .navbar-nav > li > a, header li.nav-menu-account, header .my-account-navbar a, header .top-header .contact-header p, .mt-search-icon i{
		    	color: '.esc_attr($enefti_header_nav_links_color).';
		    }';
    	}

    	$enefti_header_nav_links_color_hover = get_post_meta( get_the_ID(), 'enefti_header_nav_links_color_hover', true );
    	if (isset($enefti_header_nav_links_color_hover) && !empty($enefti_header_nav_links_color_hover)) {
		    $html .= '.header-v2 li.nav-menu-account:hover, .header-v2 .my-account-navbar a:hover, header #navbar .menu-item > a:hover, header .navbar-nav .search_products a:hover, header .navbar-default .navbar-nav > li > a:hover, header li.nav-menu-account:hover, header .my-account-navbar a:hover, .mt-search-icon i{
		    	color: '.esc_attr($enefti_header_nav_links_color_hover).';
		    }';
    	}

    	// Custom Header Background (TOP)
    	$enefti_header_top_custom_background = get_post_meta( get_the_ID(), 'enefti_header_top_custom_background', true );
    	if (isset($enefti_header_top_custom_background) && !empty($enefti_header_top_custom_background)) {
		    $html .= 'body header.header-v2 .top-header, body header .top-header{
		    	background-color: '.esc_attr($enefti_header_top_custom_background).';
		    }';
    	}

    	// Custom Header Background (MAIN)
    	$enefti_header_main_custom_background = get_post_meta( get_the_ID(), 'enefti_header_main_custom_background', true );
    	if (isset($enefti_header_main_custom_background) && !empty($enefti_header_main_custom_background)) {
		    $html .= 'body header.header-v2 .navbar-default, body header .navbar-default{
		    	background-color: '.esc_attr($enefti_header_main_custom_background).';
		    }';
    	}

    	// Custom Header Background (MAIN/STICKY)
    	$enefti_header_main_custom_background_sticky = get_post_meta( get_the_ID(), 'enefti_header_main_custom_background_sticky', true );
    	if (isset($enefti_header_main_custom_background_sticky) && !empty($enefti_header_main_custom_background)) {
		    $html .= 'body header.header-v2 .is-sticky .navbar-default, body header .is-sticky .navbar-default{
		    	background-color: '.esc_attr($enefti_header_main_custom_background_sticky).';
	    	    box-shadow: 0px 0px 15px -1px rgb(0 0 0 / 15%);
		    }';
    	}

    	// Custom Footer Background color/bg
    	$enefti_footer_bg_image = get_post_meta( get_the_ID(), 'enefti_footer_bg_image', true );
    	if (isset($enefti_footer_bg_image) && !empty($enefti_footer_bg_image)) {
		    $html .= '#page footer.enefti-main-footer{
		    	background-image: url('.esc_attr($enefti_footer_bg_image).');
		    	background-repeat: no-repeat;
		    	background-size: cover;
		    	background-position: center;
		    }
		    footer.enefti-main-footer .footer {
			    background-color: transparent;
			}';
    	}
    	$enefti_footer_bg_color = get_post_meta( get_the_ID(), 'enefti_footer_bg_color', true );
    	if (isset($enefti_footer_bg_color) && !empty($enefti_footer_bg_color)) {
		    $html .= '#page footer.enefti-main-footer{
		    	background-color: '.esc_attr($enefti_footer_bg_color).';
		    }
		    footer.enefti-main-footer .footer {
			    background-color: transparent;
			}';
    	}
    	$enefti_footer_headings_color = get_post_meta( get_the_ID(), 'enefti_footer_headings_color', true );
    	if (isset($enefti_footer_headings_color) && !empty($enefti_footer_headings_color)) {
		    $html .= 'footer.enefti-main-footer .footer-top .widget-title, footer.enefti-main-footer p.copyright{
		    	color: '.esc_attr($enefti_footer_headings_color).';
		    }';
    	}
    	$enefti_footer_texts_color = get_post_meta( get_the_ID(), 'enefti_footer_texts_color', true );
    	if (isset($enefti_footer_texts_color) && !empty($enefti_footer_texts_color)) {
		    $html .= '.footer-top .widget_nav_menu li:before, footer.enefti-main-footer .menu .menu-item a, footer.enefti-main-footer ul li, footer.enefti-main-footer ol li, footer.enefti-main-footer p, footer.enefti-main-footer p span, footer .widget_enefti_social_icons a{
			    	color: '.esc_attr($enefti_footer_texts_color).' !important;
			    }';
    	}
    	$enefti_footer_texts_color_hover = get_post_meta( get_the_ID(), 'enefti_footer_texts_color_hover', true );
    	if (isset($enefti_footer_texts_color_hover) && !empty($enefti_footer_texts_color_hover)) {
		    $html .= 'footer.enefti-main-footer .menu .menu-item a:hover, footer .widget_enefti_social_icons a:hover{
		    	color: '.esc_attr($enefti_footer_texts_color_hover).' !important;
		    }';
    	}
    	$enefti_footer_bottom_border = get_post_meta( get_the_ID(), 'enefti_footer_bottom_border', true );
    	if (isset($enefti_footer_bottom_border) && !empty($enefti_footer_bottom_border)) {
		    $html .= 'footer.enefti-main-footer .footer.footer-copyright {
				    border-color: '.esc_attr($enefti_footer_bottom_border).';
				}';
    	}
    	
	   	// CUSTOM CSS
	   	if (enefti_redux('enefti_css_editor') != '') {
	    	$html .= enefti_redux('enefti_css_editor');
	   	}
	    // THEME OPTIONS STYLESHEET - Responsive SmartPhones
	    if ( class_exists( 'ReduxFrameworkPlugin' ) ) {

			// Responsive
		    $html .= '
		    			@media only screen and (max-width: 767px) {
		    				body h1,
		    				body h1 span{
		    					font-size: '.enefti_redux('enefti_heading_h1_smartphones', 'font-size').' !important;
		    					line-height: '.enefti_redux('enefti_heading_h1_smartphones', 'line-height').' !important;
		    				}
		    				body h2{
		    					font-size: '.enefti_redux('enefti_heading_h2_smartphones', 'font-size').' !important;
		    					line-height: '.enefti_redux('enefti_heading_h2_smartphones', 'line-height').' !important;
		    				}
		    				body h3{
		    					font-size: '.enefti_redux('enefti_heading_h3_smartphones', 'font-size').' !important;
		    					line-height: '.enefti_redux('enefti_heading_h3_smartphones', 'line-height').' !important;
		    				}
		    				body h4{
		    					font-size: '.enefti_redux('enefti_heading_h4_smartphones', 'font-size').' !important;
		    					line-height: '.enefti_redux('enefti_heading_h4_smartphones', 'line-height').' !important;
		    				}
		    				body h5{
		    					font-size: '.enefti_redux('enefti_heading_h5_smartphones', 'font-size').' !important;
		    					line-height: '.enefti_redux('enefti_heading_h5_smartphones', 'line-height').' !important;
		    				}
		    				body h6{
		    					font-size: '.enefti_redux('enefti_heading_h6_smartphones', 'font-size').' !important;
		    					line-height: '.enefti_redux('enefti_heading_h6_smartphones', 'line-height').' !important;
		    				}
		    				.mega-menu-inline .menu-item-has-children{
		    					display: inline-block !important;
		    				}
		    			}
		    			';
		    // THEME OPTIONS STYLESHEET - Responsive Tablets
		    $html .= '
	    			@media only screen and (min-width: 768px) and (max-width: 1024px) {
	    				body h1,
	    				body h1 span{
	    					font-size: '.enefti_redux('enefti_heading_h1_tablets', 'font-size').' !important;
	    					line-height: '.enefti_redux('enefti_heading_h1_tablets', 'line-height').' !important;
	    				}
	    				body h2{
	    					font-size: '.enefti_redux('enefti_heading_h2_tablets', 'font-size').' !important;
	    					line-height: '.enefti_redux('enefti_heading_h2_tablets', 'line-height').' !important;
	    				}
	    				body h3{
	    					font-size: '.enefti_redux('enefti_heading_h3_tablets', 'font-size').' !important;
	    					line-height: '.enefti_redux('enefti_heading_h3_tablets', 'line-height').' !important;
	    				}
	    				body h4{
	    					font-size: '.enefti_redux('enefti_heading_h4_tablets', 'font-size').' !important;
	    					line-height: '.enefti_redux('enefti_heading_h4_tablets', 'line-height').' !important;
	    				}
	    				body h5{
	    					font-size: '.enefti_redux('enefti_heading_h5_tablets', 'font-size').' !important;
	    					line-height: '.enefti_redux('enefti_heading_h5_tablets', 'line-height').' !important;
	    				}
	    				body h6{
	    					font-size: '.enefti_redux('enefti_heading_h6_tablets', 'font-size').' !important;
	    					line-height: '.enefti_redux('enefti_heading_h6_tablets', 'line-height').' !important;
	    				}

	    			}';
		}


		if (class_exists('Dokan_Pro')) {
	    	$html .= 'body.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li:hover a, body.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.active a{color: #fff !important;}';
		}


	    // THEME OPTIONS STYLESHEET
	    $html .= '
				.post-password-form input[type="password"],
				.no-results input,
				.modeltheme-modal input.email,
				.post-password-form input[type="submit"],
				.wc_vendors_active form input[type="submit"],
				.modeltheme-modal input[type="email"], 
				.modeltheme-modal input[type="text"], 
				.modeltheme-modal input[type="password"],
				#yith-wcwl-form input[type="text"],
				.memorabilia-news .newsletter-footer.light .email,
				.woocommerce div.product form.cart .variations select,
				#signup-modal-content .woocommerce-form-register.register input[type="text"], 
				#signup-modal-content .woocommerce-form-register.register input[type="email"], 
				#signup-modal-content .woocommerce-form-register.register input[type="tel"], 
				#signup-modal-content .woocommerce-form-register.register input[type="password"], 
				#signup-modal-content .woocommerce-form-register.register textarea,
				.dokan-orders-content .dokan-order-filter-serach .select2-container--default .select2-selection--single,
				.dokan-orders-content #order-filter .dokan-form-control,
				.dokan-dashboard-content .dokan-alert-warning,
				.dokan-product-edit-form .select2-container--default .select2-selection--single,
				.dokan-product-edit-form .select2-container--default.select2-container--focus .select2-selection--multiple,
				.dokan-product-edit-form .select2-container--default .select2-selection--multiple,
				.dokan-orders-content .dokan-orders-area input.add_note,
				.comment-form input, .comment-form textarea,
				.woocommerce-cart table.cart td.actions .coupon .input-text,
				.wp-block-search .wp-block-search__input,
				.woocommerce .woocommerce-ordering select,
				.woocommerce form .form-row textarea, 
				.woocommerce form .form-row select, 
				.woocommerce form .form-row input.input-text, 
				.woocommerce-page form .form-row input.input-text,
				.wc_vendors_active #wcv_bank_account_name,
				.wc_vendors_active #wcv_bank_account_number,
				.wc_vendors_active #wcv_bank_name,
				.wc_vendors_active #wcv_bank_routing_number,
				.wc_vendors_active #wcv_bank_iban,
				.wc_vendors_active #wcv_bank_bic_swift,
				.wc_vendors_active #pv_shop_name,
				.wc_vendors_active #pv_paypal,
				.wc_vendors_active textarea,
				.wc_vendors_active input.date-pick,
				.menu-search,
				.newsletter-footer.light .email,
				.modeltheme-modal input[type="submit"], 
				.modeltheme-modal button[type="submit"], 
				form#login .register_button,
				.newsletter-footer .email,
				button.single_add_to_cart_button.button.alt[data-tooltip]:before,
				.woocommerce.single-product .wishlist-container a.button[data-tooltip]:before,
				.woocommerce.single-product div.product form.cart button.bid_button.button.alt[data-tooltip]:before,
				.woocommerce_simple_domain .button-bid a,
				.products span.winning,
				form#login .submit_button,
				.top-inquiry-button,
				.error-404 a.vc_button_404,
				.single-product a.wcfm_catalog_enquiry,
				.comment-form button#submit,
				.nft-tabs .vc_active .vc_tta-panel-title>a,
				.wp-block-archives select,
				.wp-block-categories-dropdown select
				{
				    border-radius: '.esc_html($fields_radius).'px;
				    -webkit-border-radius: '.esc_html($fields_radius).'px;
				}
				.dokan-settings-content .dokan-settings-area .dokan-form-control,
				.dokan-product-listing-area .dokan-form-control,
				.content-area .dokan-seller-search-form .dokan-w4 input[type=search],
				input#order_date_filter,
				.woocommerce form .form-row .select2-container span,
				.evc-button.evc-btn-normal,
				.header_mini_cart .button.wc-forward, .header_mini_cart .button.checkout,
				.dokan-form-control{
				    border-radius: '.esc_html($fields_radius).'px !important;
				}
				.yith-wcwl-wishlistaddedbrowse.show a,
				.overlay-components .component a,
				.overlay-components .component a,
				.vc_col-md-3 .overlay-components .component a,
				.modeltheme_products_carousel .button-bid a,
				.modeltheme_products_carousel .modeltheme-button-bid a,
				.category-button a,
				.mt_products_slider .button-bid a,
				.woocommerce.single-product div.product form.cart .button,
				.woocommerce.single-product .wishlist-container a.button,
				.masonry_banner .read-more,
				.testimonail01-content,
				.header-v2 .header_mini_cart_group,
				.header-v3 .menu-products .shop_cart,
				.header-v3 .header_mini_cart_group,
				.pagination .page-numbers,
				.nav-previous a, .nav-next a,
				a.add-wsawl.sa-watchlist-action, a.remove-wsawl.sa-watchlist-action,
				.form-submit input[type="submit"],
				.widget_search .search-field,
				.social-shareer a,
				.woocommerce ul.products li.product .onsale, 
				body .woocommerce ul.products li.product .onsale, 
				body .woocommerce ul.products li.product .onsale,
				.woocommerce_categories2 .yith-wcwl-add-to-wishlist.exists .yith-wcwl-wishlistaddedbrowse.hide a,
				.full-width-part .more-link,
				table.my_account_orders tbody tr td.order-actions a.button,
				.wpcf7-form .wpcf7-submit,
				.newsletter-footer input.submit,
				.woocommerce .woocommerce-pagination ul.page-numbers li,
				.woocommerce nav.woocommerce-pagination ul li a, 
				.woocommerce nav.woocommerce-pagination ul li span,
				a#register-modal,
				#signup-modal-content .woocommerce-form-register.register .button[type=\'submit\'],
				.wc-social-login a.ywsl-social::after,
				.back-to-top,
				.woocommerce.widget_product_search .search-field,
				.no-results input[type="submit"],
				.enefti_shortcode_cause .button-content a,
				.wp-block-search .wp-block-search__button,
				.product-badge,
				.sale_banner_right span.read-more,
				.custom-about .button-winona,
				.menu-search .btn.btn-primary,
				.featured_product_shortcode .featured_product_button,
				.cd-gallery .button-bid a,
				.wcv-dashboard-navigation li a{
				    border-radius: '.esc_html($fields_radius).'px;
				    -webkit-border-radius: '.esc_html($fields_radius).'px;
				}
				.modeltheme_products_shadow .woocommerce ul.products li.product .button[data-tooltip],
				.campaign_procentage.progress,
				.woocommerce .woocommerce-widget-layered-nav-dropdown__submit,
				.pagination-wrap ul.pagination > li > a,
				.dokan-pagination-container .dokan-pagination li a,
				#yith-wcwl-form input[type="submit"],
				.woocommerce #respond input#submit, 
				.woocommerce a.button, 
				.woocommerce button.button, 
				.woocommerce input.button,
				table.compare-list .add-to-cart td a,
				.woocommerce #respond input#submit.alt, 
				.woocommerce a.button.alt, 
				.woocommerce button.button.alt, 
				.woocommerce input.button.alt,
				input[type="submit"].dokan-btn, a.dokan-btn, .dokan-btn{
				    -webkit-border-radius: '.esc_html($fields_radius).'px !important;
				    border-radius: '.esc_html($fields_radius).'px !important;
				}
				.enefti-shop-sort-group .gridlist-toggle a#grid {
				    border-radius: '.esc_html($fields_radius).'px 0 0 '.esc_html($fields_radius).'px;
				}
				.enefti-shop-sort-group .gridlist-toggle a#list {
				    border-radius: 0 '.esc_html($fields_radius).'px '.esc_html($fields_radius).'px 0;
				}

	    		footer .menu .menu-item a{
			    	color: '.enefti_redux('footer_bottom_color_links').';
			    }
    			.footer-top .widget-title, p.copyright{
			    	color: '.enefti_redux('footer_bottom_color_text').';
			    }
    			.top-footer div.left{
			    	color: '.enefti_redux('footer_top_color_text').';
			    }
    			.header-v3 .navbar-default, 
    			.header-v3 nav#modeltheme-main-head{
			    	background-color: '.enefti_redux('mt_style_top_header3_color', 'background-color').';
			    }
			    .header-v2 .top-header{
			    	background-color: '.enefti_redux('mt_style_top_header2_color', 'background-color').';
			    }
			    .header-v2 .navbar-default{
			    	background-color: '.enefti_redux('mt_style_bottom_header2_color', 'background-color').';
			    }
			    .header-v8 .navbar-default, 
    			.header-v8 nav#modeltheme-main-head{
			    	background-color: '.enefti_redux('mt_style_top_header8_color', 'background-color').';
			    }
		        .breadcrumb a::after {
		            content: "'.enefti_redux('breadcrumbs-delimitator').'";
		            content:"/";
		        }
		        .navbar-header .logo img {
		            max-width: '.esc_html(enefti_redux("logo_max_width")).'px;
		        }
			    ::selection{
			        color: '.enefti_redux('enefti_text_selection_color').';
			        background: '.enefti_redux('enefti_text_selection_background_color').';
			    }
			    ::-moz-selection { /* Code for Firefox */
			        color: '.enefti_redux('enefti_text_selection_color').';
			        background: '.enefti_redux('enefti_text_selection_background_color').';
			    }
			    a,
			    a:visited{
			        color: '.enefti_redux('enefti_global_link_styling', 'regular').';
			    }
			    a:focus,
			    a:hover{
			        color: '.enefti_redux('enefti_global_link_styling', 'hover').';
			    }
			    /*------------------------------------------------------------------
			        COLOR
			    ------------------------------------------------------------------*/
				span.amount,
				.cd-gallery .woocommerce-title-metas .enefti-supported-cause a,
				table.compare-list .remove td a .remove,
				.woocommerce form .form-row .required,
				.woocommerce .woocommerce-info::before,
				.woocommerce .woocommerce-message::before,
				.woocommerce div.product p.price, 
				.woocommerce div.product span.price,
				.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
				.widget_popular_recent_tabs .nav-tabs li.active a,
				.widget_product_categories .cat-item:hover,
				.widget_product_categories .cat-item a:hover,
				.widget_archive li:hover,
				.widget_archive li a:hover,
				.widget_categories .cat-item:hover,
				.widget_categories li a:hover,
				.woocommerce .star-rating span::before,
				.pricing-table.recomended .button.solid-button, 
				.pricing-table .table-content:hover .button.solid-button,
				.pricing-table.Recommended .button.solid-button, 
				.pricing-table.recommended .button.solid-button, 
				.pricing-table.recomended .button.solid-button, 
				.pricing-table .table-content:hover .button.solid-button,
				.testimonial-author,
				.testimonials-container blockquote::before,
				.testimonials-container blockquote::after,
				h1 span,
				h2 span,
				label.error,
				.woocommerce input.button:hover,
				.author-name,
				.prev-next-post a:hover,
				.prev-text,
				.next-text,
				.social ul li a:hover i,
				.wpcf7-form span.wpcf7-not-valid-tip,
				.text-dark .statistics .stats-head *,
				.wpb_button.btn-filled,
				.widget_meta a:hover,
				.logo span,
				a.shop_cart::after,
				.woocommerce ul.products li.product .archive-product-title a:hover,
				.shop_cart:hover,
				.widget_pages a:hover,
				.categories_shortcode .category.active, .categories_shortcode .category:hover,
				.widget_recent_entries_with_thumbnail li:hover a,
				.widget_recent_entries li a:hover,
				.wpb_button.btn-filled:hover,
				li.seller-name::before,
				li.store-address::before,
				li.store-name::before,
				.full-width-part .post-name a:hover,
				.full-width-part .post-category-comment-date a:hover, .article-details .post-author a:hover,
				.woocommerce div.product form.buy-now.cart .button:hover span.amount,
				.wc_vendors_active form input[type="submit"]:hover,
				.wcv-dashboard-navigation li a:hover,
				.woocommerce ul.cart_list li:hover a, .woocommerce ul.product_list_widget li:hover a,
				a.add-wsawl.sa-watchlist-action:hover, a.remove-wsawl.sa-watchlist-action:hover,
				.top-footer .menu-search .btn.btn-primary:hover i.fa,
				.post-name i,
				.modal-content p i,
				#yith-wcwl-form input[type="submit"]:hover,
				.modeltheme-modal input[type="submit"]:hover,
				.modeltheme-modal button[type="submit"]:hover,
				form#login .submit_button:hover,
				blockquote::before,
				div#cat-drop-stack a:hover,
				.woocommerce-MyAccount-navigation-link > a:hover,
				.woocommerce-MyAccount-navigation-link.is-active > a,
				.sidebar-content .widget_nav_menu li a:hover,
				.woocommerce-account .woocommerce-MyAccount-content p a:hover,
				#signup-modal-content .woocommerce-form-register.register .button[type="submit"]:hover,
				.newsletter-footer button.rippler:after,
				.widget_enefti_social_icons a:hover,
				.component.wishlist .yith-wcwl-add-to-wishlist a:hover i,
				.page-links a:hover {
				    color: '.esc_html($enefti_style_main_texts_color).';
				}
				body .enefti_shortcode_blog .post-name a:hover,
				a#register-modal:hover,
				.mt-addons-title:hover,
				.mt-single-category a:hover,
				body .mt-addons-blog-posts-carousel-post-name a:hover,
				.recentcomments a:hover,
				.nft-tabs .vc_active .vc_tta-panel-title>a {
				    color: '.esc_html($enefti_style_main_texts_color).' !important;
				}
				.dokan-btn-theme a:hover, .dokan-btn-theme:hover, input[type="submit"].dokan-btn-danger:hover, input[type="submit"].dokan-btn-theme:hover,
				.woocommerce-MyAccount-navigation-link > a:hover,
				.woocommerce-MyAccount-navigation-link.is-active > a
				body .enefti_shortcode_blog .post-name a:hover,
				.masonry_banner .read-more:hover,
				.category-button a:hover,
				.dokan-single-store .profile-frame .profile-info-box .profile-info-summery-wrapper .profile-info-summery .profile-info i,
				.product_meta > span a:hover,
				.dokan-dashboard .dokan-dashboard-wrap .delete a,
				.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.active a,
				.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li a:hover,
				#dropdown-user-profile ul li a:hover,
				.header-v3 .menu-products .shop_cart,
				.simple-sitemap-container ul a:hover,
				.wishlist_table tr td.product-name a.button:hover,
				.dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li:hover, .dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu li.dokan-common-links a:hover,
				.mega_menu .cf-mega-menu li a:hover, .mega_menu .cf-mega-menu.sub-menu p a:hover,
				.woocommerce a.remove,
				.enefti_shortcode_cause .button-content a:hover,
				.enefti_shortcode_blog .image_top .blog-content p.author,
				.modeltheme_products_shadow .details-container > div.details-item .amount,
				.mt_products_slider .full .woocommerce-title-metas span.amount,
				.woocommerce_categories.listed_info .style_v2 span.before-text i,
				.woocommerce-cart table.cart td.product-name a:hover,
				.tagcloud > a:hover,
				.mt-addons-member-name a.mt-addons-member-url:hover,
				.widget_enefti_recent_entries_with_thumbnail li a:hover,
				.woocommerce .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item a:hover{
				    color: '.esc_html($enefti_style_main_texts_color).' !important;
				}
				.tagcloud > a:hover,
				 nav,
				.enefti-icon-search,
				.wpb_button::after,
				.rotate45,
				.latest-posts .post-date-day,
				.latest-posts h3, 
				.latest-tweets h3, 
				.latest-videos h3,
				.button.solid-button,
				button.vc_btn,
				.pricing-table.recomended .table-content, 
				.pricing-table .table-content:hover,
				.pricing-table.Recommended .table-content, 
				.pricing-table.recommended .table-content, 
				.pricing-table.recomended .table-content, 
				.pricing-table .table-content:hover,
				.block-triangle,
				.owl-theme .owl-controls .owl-page span,
				body .vc_btn.vc_btn-blue, 
				body a.vc_btn.vc_btn-blue, 
				body button.vc_btn.vc_btn-blue,
				.woocommerce input.button,
				table.compare-list .add-to-cart td a,
				.woocommerce #respond input#submit.alt, 
				.woocommerce input.button.alt,
				.woocommerce a.remove:hover,
				.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
				.woocommerce nav.woocommerce-pagination ul li a:focus, 
				.woocommerce nav.woocommerce-pagination ul li:hover, 
				.woocommerce nav.woocommerce-pagination ul li span.current, 
				.widget_social_icons li a:hover, 
				#subscribe > button[type="submit"],
				.social-sharer > li:hover,
				.prev-next-post a:hover .rotate45,
				.masonry_banner.default-skin,
				.member-footer .social::before, 
				.member-footer .social::after,
				.subscribe > button[type="submit"],
				.woocommerce #respond input#submit.alt.disabled, 
				.woocommerce #respond input#submit.alt.disabled:hover, 
				.woocommerce #respond input#submit.alt:disabled, 
				.woocommerce #respond input#submit.alt:disabled:hover, 
				.woocommerce #respond input#submit.alt[disabled]:disabled, 
				.woocommerce #respond input#submit.alt[disabled]:disabled:hover, 
				.woocommerce a.button.alt.disabled, 
				.woocommerce a.button.alt.disabled:hover, 
				.woocommerce a.button.alt:disabled, 
				.woocommerce a.button.alt:disabled:hover, 
				.woocommerce a.button.alt[disabled]:disabled, 
				.woocommerce a.button.alt[disabled]:disabled:hover, 
				.woocommerce button.button.alt.disabled, 
				.woocommerce button.button.alt.disabled:hover, 
				.woocommerce button.button.alt:disabled, 
				.woocommerce button.button.alt:disabled:hover, 
				.woocommerce button.button.alt[disabled]:disabled, 
				.woocommerce button.button.alt[disabled]:disabled:hover, 
				.woocommerce input.button.alt.disabled, 
				.woocommerce input.button.alt.disabled:hover, 
				.woocommerce input.button.alt:disabled, 
				.woocommerce input.button.alt:disabled:hover, 
				.woocommerce input.button.alt[disabled]:disabled, 
				.woocommerce input.button.alt[disabled]:disabled:hover,
				table.compare-list .add-to-cart td a,
				.shop_cart,
				h3#reply-title::after,
				.newspaper-info,
				.categories_shortcode .owl-controls .owl-buttons i:hover,
				.widget-title:after,
				h2.heading-bottom:after,
				.wpb_content_element .wpb_accordion_wrapper .wpb_accordion_header.ui-state-active,
				#primary .main-content ul li:not(.rotate45)::before,
				.menu-search .btn.btn-primary:hover,
				.btn-register, .modeltheme-modal input[type="submit"], .modeltheme-modal button[type="submit"], form#login .register_button, form#login .submit_button,
				.bottom-components .component a:hover, .bottom-components .component a:hover, .bottom-components .component a:hover, .woocommerce-page .overlay-components .component a:hover, .woocommerce-page .vc_col-md-3 .overlay-components .component a:hover,
				.woocommerce.single-product .wishlist-container .yith-wcwl-wishlistaddedbrowse.show a,
				.widget_address_social_icons .social-links a,
				.hover-components .component:hover,
				.navbar-default .navbar-toggle .icon-bar,
				#yith-wcwl-form input[type="submit"],
				.nav-previous a, .nav-next a,
				article.dokan-orders-area .dokan-panel-default > .dokan-panel-heading,
				#signup-modal-content .woocommerce-form-register.register .button[type="submit"],
				.dokan-dashboard .dokan-dashboard-content article.dashboard-content-area .dashboard-widget .widget-title,
				.woocommerce-MyAccount-navigation-link > a,
				.newsletter-footer input.submit:hover, .newsletter-footer input.submit:focus,
				 a.remove-wsawl.sa-watchlist-action,
				footer .footer-top .menu .menu-item a::before,
				.wcv-dashboard-navigation li a,
				.wc_vendors_active form input[type="submit"],
				.cd-gallery .button-bid a,
				.enefti-shop-filters-button:focus,
				.enefti-shop-filters-button,
				.mt_products_slider .button-bid a,
				.wishlist-title-with-form .show-title-form,
				body .dokan-pagination-container .dokan-pagination li.active a,
				.dokan-pagination-container .dokan-pagination li a:hover,
				.categories_shortcode .category,
				.enefti_shortcode_blog.boxed .post-button a.more-link,
				li#nav-menu-register:before,
				.mt-addons-blog-posts-carousel-content-inside,
				.gradient-header strong,
				.gradient-icon span,
				.enefti-floating-social-btn,
				.woocommerce nav.woocommerce-pagination ul li a:hover,
				.contact-icons span.vc-oi,
				.product-badge,
				.pagination .page-numbers:hover,
				#wp-calendar #today,
				.mobile_only_icon_group.account i  {
				    background: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).');
				}
				.top-inquiry-button,
				li#nav-menu-register:before,
				.woocommerce button.button.alt,
				.woocommerce a.button,
				.woocommerce a.button:hover,
				.woocommerce button.button,
				.page-template-template-blog .full-width-part .more-link, .full-width-part .more-link,
				.woocommerce button.button:hover,
				.woocommerce a.button.alt,
				.woocommerce a.button.alt:hover,
				.woocommerce #respond input#submit,
				.woocommerce #respond input#submit:hover,
				.form-submit input,
				.wpcf7-form .wpcf7-submit,
				.no-results input[type="submit"],
				.error-404 a.vc_button_404,
				body #wcfm_membership_container input.wcfm_submit_button, 
				body #wcfm_membership_container a.wcfm_submit_button,
				body #wcfm_membership_container input.wcfm_submit_button:hover, 
				body #wcfm_membership_container a.wcfm_submit_button:hover,
				.comment-form button#submit,
				button.wp-block-search__button,
				.wp-block-search .wp-block-search__button,
				.post-password-form input[type="submit"],
				.woocommerce a.added_to_cart {
				    background-image: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).','.esc_html($enefti_style_main_backgrounds_color_gr2).','.esc_html($enefti_style_main_backgrounds_color_gr1).');
				}
				.gradient-btn .mt-addons_button_holder .mt-addons_button {
				    background-image: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).','.esc_html($enefti_style_main_backgrounds_color_gr2).','.esc_html($enefti_style_main_backgrounds_color_gr1).') !important;
				}
				.bottom-components .component a:hover, .bottom-components .component a:hover, .bottom-components .component a:hover, .woocommerce-page .overlay-components .component a:hover,.woocommerce-page .vc_col-md-3 .overlay-components .component a:hover,
				.columns-4 .overlay-components .component a:hover, .vc_col-md-4 .overlay-components .component a:hover, .no-sidebar .vc_col-md-3 .overlay-components .component a:hover,
				.overlay-components .component.add-to-cart a, .bottom-components .component.add-to-cart a,
				.woocommerce_categories2 .products .component .yith-wcwl-wishlistexistsbrowse.show a,
				body .tp-bullets.preview1 .bullet,
				div#dokan-content .overlay-components .component a:hover,
				body #mega_main_menu li.default_dropdown .mega_dropdown > li > .item_link:hover, 
				body #mega_main_menu li.widgets_dropdown .mega_dropdown > li > .item_link:hover, 
				body #mega_main_menu li.multicolumn_dropdown .mega_dropdown > li > .item_link:hover, 
				body .dokan-settings-content .dokan-settings-area a.dokan-btn-info,
				.btn-sticky-left,
				.dokan-btn-info,
				body #mega_main_menu li.grid_dropdown .mega_dropdown > li > .item_link:hover,
				.custom_enefti button,
				.woocommerce_categories.grid th,
				.enefti_shortcode_cause .button-content a,
				.domain.woocommerce_categories .button-bid a,
				.domain-but button,
				.woocommerce_simple_domain .button-bid a,
				.mt-product-search .menu-search button.form-control,
				.mt-tabs .tabs-style-iconbox nav ul li.tab-current a,
				.sale_banner_right span.read-more,
				.freelancer-list-shortcode .project-bid .button.btn,
				.woocommerce.archive .ar-projs .modeltheme-button-bid,
				.header-v9 #navbar ul.menu > .menu-item > a::after,
				.about-icons img
				{
				    background: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).') !important;
				}
				.flip-clock-wrapper ul li a div div.inn,
				.featured_product_shortcode span.amount,
				.featured_product_shortcode .featured_product_button:hover,
				.custom_enefti button:hover,
				.enefti-countdown strong,
				.categories_shortcode .category.active span, .categories_shortcode .category:hover span,
				.woocommerce_categories.grid td.product-title a,
				.woocommerce_categories.grid td.add-cart a,
				.woocommerce_categories.list span.amount,
				.cd-tab-filter a:hover,
				.no-touch .cd-filter-block h4:hover,
				.cd-gallery .woocommerce-title-metas a:hover,
				.cd-tab-filter a.selected,
				.no-touch .cd-filter-trigger:hover,
				.woocommerce .woocommerce-widget-layered-nav-dropdown__submit:hover,
				.mt_products_slider .woocommerce-title-metas h3 a:hover,
				.enefti_shortcode_cause h3 a:hover,
				.mt_products_slider .button-bid a:hover,
				.header-v3 .menu-products .shop_cart:hover,
				.domain.woocommerce_categories .archive-product-title a:hover,
				.custom-btn button:hover,
				.modeltheme_products_carousel .modeltheme-title-metas a:hover,
				.modeltheme_products_carousel.owl-theme .owl-controls .owl-buttons div,
				.modeltheme_products_simple h3.modeltheme-archive-product-title a:hover,
				.freelancer-list-shortcode h3.archive-product-title a:hover,
				.mt-categories-content:hover span.mt-title:hover,
				.freelancer-list-shortcode .project-bid .button.btn:hover,
				.woocommerce.archive .ar-projs .modeltheme-button-bid:hover a,
				.user-information h3.user-profile-title a:hover,
				.user-information span.info-pos i,
				.work-dashboard h3.archive-product-title a:hover,
				.user-profile-info span.info-pos i,
				.woocommerce_categories.listed_info h3.archive-product-title a:hover,
				.header-v8 .menu-inquiry .button:hover,
				.header-v9 .menu-inquiry .button:hover,
				.nft-tabs .vc_tta-tab.vc_active>a,
				.nft-tabs .vc_tta-tab>a:hover{
					color: '.esc_html($enefti_style_main_backgrounds_color_gr1).' !important;
				}
				.dokan-btn-success.grant_access, input#dokan-add-tracking-number,
				.dokan-dashboard .dokan-dash-sidebar, .dokan-dashboard .dokan-dash-sidebar ul.dokan-dashboard-menu,
				input[type="submit"].dokan-btn-theme, a.dokan-btn-theme, .dokan-btn-theme,
				#cd-zoom-in, #cd-zoom-out,
				.woocommerce .woocommerce-widget-layered-nav-dropdown__submit,
				.custom-btn button,
				.modeltheme_products_carousel .button-bid a,
				.modeltheme_products_carousel .modeltheme-button-bid a,
				.modeltheme_products_simple .modeltheme-product-wrapper a.button,
				.hiw-btn .button-winona{
				    background-image: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).') !important;
				}
				.gridlist-toggle a:hover,
				.gridlist-toggle a.active,
				.dataTables_wrapper .pagination>.active>a, .dataTables_wrapper .pagination>.active>span, 
				.dataTables_wrapper .pagination>.active>a:hover, 
				.dataTables_wrapper .pagination>.active>span:hover, 
				.dataTables_wrapper .pagination>.active>a:focus, 
				.dataTables_wrapper .pagination>.active>span:focus {
					background-image: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).') !important;
				}
				.pagination > li > a.current, 
				.pagination > li > a:hover{
					background-color: '.esc_html($enefti_style_main_backgrounds_color_hover).';
					border: 1px solid '.esc_html($enefti_style_main_backgrounds_color_hover).';
				}
				.woocommerce ul.products li.product .onsale, 
				body .woocommerce ul.products li.product .onsale, 
				body .woocommerce ul.products li.product .onsale,
				.pagination .page-numbers.current,
				.pagination .page-numbers.current:hover,
				.category-button.boxed a,
				.masonry_banner .read-more.boxed {
					background: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).');
				}
				.author-bio,
				.widget_popular_recent_tabs .nav-tabs > li.active,
				body .left-border, 
				body .right-border,
				body .member-header,
				body .member-footer .social,
				body .button[type="submit"],
				.navbar ul li ul.sub-menu,
				.wpb_content_element .wpb_tabs_nav li.ui-tabs-active,
				.header_mini_cart,
				.header_mini_cart.visible_cart,
				#contact-us .form-control:focus,
				.header_mini_cart .woocommerce .widget_shopping_cart .total, 
				.header_mini_cart .woocommerce.widget_shopping_cart .total,
				.sale_banner_holder:hover,
				.testimonial-img,
				.wpcf7-form input:focus, 
				.wpcf7-form textarea:focus,
				.dokan-btn-success.grant_access, input#dokan-add-tracking-number,
				.navbar-default .navbar-toggle:hover, 
				.navbar-default .navbar-toggle {
				    border-color: '.esc_html($enefti_style_main_backgrounds_color_gr1).';
				}
				.woocommerce .woocommerce-info,
				.woocommerce .woocommerce-message,
				.nft-tabs .vc_active .vc_tta-panel-title>a{
					border-color: '.esc_html($enefti_style_main_backgrounds_color_gr2).';
				}
				.sidebar-content .widget-title::before, .dokan-widget-area .widget-title::before,
				.dokan-settings-content .dokan-settings-area a.dokan-btn-info, .dokan-btn-info,
				input[type="submit"].dokan-btn-theme, a.dokan-btn-theme, .dokan-btn-theme,
				.header-v3 .menu-products .shop_cart,
				.lvca-heading.lvca-alignleft h3.lvca-title::after,
				.header-v8 .menu-inquiry .button,
				.nft-tabs li.vc_tta-tab.vc_active,
				.mt-addons-info-wrapper,
				.nft-tabs li.vc_tta-tab:hover{
				    border-color: '.esc_html($enefti_style_main_backgrounds_color_gr1).' !important;
				}
				.mt-tabs .tabs-style-iconbox nav ul li.tab-current a::after{
				    border-top-color: '.esc_html($enefti_style_main_backgrounds_color_gr1).' !important;
				}
				.services2 .block-triangle:hover i,
				.cd-filter::before,
				.cd-filter .cd-close {
					background-image: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).');
				}
				#navbar .menu-item.black-friday-menu-link > a  {
					background-image: linear-gradient(to right, '.esc_html($enefti_style_main_backgrounds_color_gr1).','.esc_html($enefti_style_main_backgrounds_color_gr2).') !important;
				}
				.wc_vendors_active form input[type="submit"]:hover,
				.wcv-dashboard-navigation li a:hover,
				.woocommerce input.button:hover,
				table.compare-list .add-to-cart td a:hover,
				.woocommerce #respond input#submit.alt:hover, 
				.woocommerce input.button.alt:hover,
				.enefti-search.enefti-search-open .enefti-icon-search, 
				.no-js .enefti-search .enefti-icon-search,
				.enefti-icon-search:hover,
				.latest-posts .post-date-month,
				.button.solid-button:hover,
				body .vc_btn.vc_btn-blue:hover, 
				body a.vc_btn.vc_btn-blue:hover, 
				body button.vc_btn.vc_btn-blue:hover,
				.subscribe > button[type="submit"]:hover,
				table.compare-list .add-to-cart td a:hover,
				.shop_cart:hover,
				.widget_address_social_icons .social-links a:hover,
				form#login .submit_button:hover,
				.modeltheme-modal input[type="submit"]:hover, 
				.modeltheme-modal button[type="submit"]:hover, 
				.modeltheme-modal p.btn-register-p a:hover,
				#yith-wcwl-form input[type="submit"]:hover,
				#signup-modal-content .woocommerce-form-register.register .button[type="submit"]:hover,
				.woocommerce_categories2 .bottom-components .component a:hover,.woocommerce_categories2 .bottom-components .component a:hover,
				woocommerce_categories2 .bottom-components .component a:hover
				 {
				    background: '.esc_html($enefti_style_main_backgrounds_color_hover).'; /*Color: Main Dark */
				}
				.woocommerce_categories.grid td.add-cart a:hover,
				.woocommerce_categories.grid td.product-title a:hover,
				.domain.woocommerce_categories .archive-product-title a
				{
					color: '.esc_html($enefti_style_main_backgrounds_color_hover).' !important;
				}
				.no-touch #cd-zoom-in:hover, .no-touch #cd-zoom-out:hover,
				.woocommerce .woocommerce-widget-layered-nav-dropdown__submit:hover,
				.enefti_shortcode_cause .button-content a:hover,
				.cd-gallery .button-bid a:hover,
				.mt_products_slider .button-bid a:hover 
				{
				    background-color: '.esc_html($enefti_style_main_backgrounds_color_hover).' !important; /*Color: Main Dark */
				}

				.woocommerce ul.cart_list li a::before, .woocommerce ul.product_list_widget li a::before{
					opacity: '.esc_html($enefti_style_semi_opacity_backgrounds).';
				}';

	    wp_add_inline_style( 'enefti-custom-style', enefti_minify_css($html) );
	}
}