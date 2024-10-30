<?php 
defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {


	/**
	 * IBID_WC_List_Grid class
	 **/
	if ( ! class_exists( 'IBID_WC_List_Grid' ) ) {

		class IBID_WC_List_Grid {

			public function __construct() {
				// Hooks
  				add_action( 'wp' , array( $this, 'enefti_setup_gridlist' ) , 20);
			}

			/*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			// Setup
			function enefti_setup_gridlist() {
				if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'enefti_setup_scripts_script' ), 20);
					add_action( 'woocommerce_before_shop_loop', array( $this, 'enefti_gridlist_toggle_button' ), 30);
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'enefti_gridlist_buttonwrap_open' ), 9);
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'enefti_gridlist_buttonwrap_close' ), 11);
					add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_excerpt', 5);
					add_action( 'woocommerce_after_subcategory', array( $this, 'enefti_gridlist_cat_desc' ) );
				}
			}

			function enefti_setup_scripts_script() {
				add_action( 'wp_footer', array( $this, 'enefti_gridlist_set_default_view' ) );
			}

			// Toggle button
			function enefti_gridlist_toggle_button() {

				$grid_view = __( 'Grid view', 'enefti' );
				$list_view = __( 'List view', 'enefti' );

				$output = sprintf( '<nav class="gridlist-toggle"><a href="#" id="grid" title="%1$s"><span class="dashicons dashicons-grid-view"></span> <em>%1$s</em></a><a href="#" id="list" title="%2$s"><span class="dashicons dashicons-exerpt-view"></span> <em>%2$s</em></a></nav>', $grid_view, $list_view );

				echo apply_filters( 'enefti_gridlist_toggle_button_output', $output, $grid_view, $list_view );
			}

			// Button wrap
			function enefti_gridlist_buttonwrap_open() {
				echo apply_filters( 'gridlist_button_wrap_start', '<div class="gridlist-buttonwrap">' );
			}
			function enefti_gridlist_buttonwrap_close() {
				echo apply_filters( 'gridlist_button_wrap_end', '</div>' );
			}

			function enefti_gridlist_set_default_view() {
				global $enefti_redux;
				$default = 'grid';
				if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
					if ($enefti_redux['enefti_shop_grid_list_switcher'] && !empty($enefti_redux['enefti_shop_grid_list_switcher'])) {
						$default = $enefti_redux['enefti_shop_grid_list_switcher'];
					}
				}
				?>
					<script>
					if ( 'function' == typeof(jQuery) ) {
						jQuery(document).ready(function($) {
							if ($.cookie( 'gridcookie' ) == null) {
								$( 'ul.products' ).addClass( '<?php echo esc_html($default); ?>' );
								$( '.gridlist-toggle #<?php echo esc_html($default); ?>' ).addClass( 'active' );
							}
						});
					}
					</script>
				<?php
			}

			function enefti_gridlist_cat_desc( $category ) {
				global $woocommerce;
				echo apply_filters( 'enefti_gridlist_cat_desc_wrap_start', '<div itemprop="description">' );
					echo wp_kses_post($category->description);
				echo apply_filters( 'enefti_gridlist_cat_desc_wrap_end', '</div>' );

			}
		}

		$IBID_WC_List_Grid = new IBID_WC_List_Grid();
	}
}

if (!function_exists('enefti_mobile_shop_filters')) {
	add_action( 'woocommerce_before_shop_loop', 'enefti_mobile_shop_filters', 30);
	function enefti_mobile_shop_filters(){
		echo '<a href="#" class="enefti-shop-filters-button btn btn-success hide-on-desktops"><i class="fa fa-filter"></i> '.esc_html__('Filters', 'enefti').'</a>';
	}
}

if (!function_exists('enefti_custom_search_form')) {
	add_action('enefti_products_search_form','enefti_custom_search_form');
	function enefti_custom_search_form(){ ?>
		<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label>
		        <input type="hidden" name="post_type" value="product" />
				<input type="search" class="search-field" placeholder="<?php echo esc_attr__( 'Search...', 'enefti' ); ?>" value="" name="s">
				<input type="submit" class="search-submit">
			</label>
		</form>
	<?php }
}

//Nextend Social Links
if (class_exists('NextendSocialLogin') && !class_exists('NextendSocialLoginPRO')) {
	if (!function_exists('enefti_get_social_btns_form')) {

		function enefti_get_social_btns_form() {
			echo do_shortcode('[nextend_social_login]');
		}

		add_action('woocommerce_after_customer_login_form','enefti_get_social_btns_form');
	}
}




//Shortcode : Selling value
if (!function_exists('enefti_product_selling_value')) {
	function enefti_product_selling_value( $id='' ) {
	       
	    // GET CURRENT USER ORDERS
	    $all_orders = wc_get_orders(
	        array(
	            'limit'    => -1,
	            'status'   => array( 'completed', 'processing'),
	        )
	    );
	    
	    $count = 0;
	    if($id) {
		    if($all_orders) {
			    foreach ( $all_orders as $single_order ) {
			        $order = wc_get_order( $single_order->get_id() );
			        $items = $order->get_items();
			        foreach ( $items as $item ) {
			            $product_id = $item->get_product_id();
			            if ( $product_id == $id ) {
			                $count = $count + absint( $item->get_total() ); 
			            }
			        }
			    }
		    }
		}
	    // RETURN HTML
	    return $count;
	}
}


//Single Product : Vendor Section - dokan
if (!function_exists('enefti_vendor_section')) {
	function enefti_vendor_section() {

	global $product, $dokan;
        $seller = $product->post->post_author;
        $author = get_user_by( 'id', $seller );
        $store_info = dokan_get_store_info( $author->ID );
        $vendor = dokan()->vendor->get( $seller );

        echo '<div class="enefti-vendor-section">';
        	echo '<div class="vendor-section-wrapper">';
        		echo '<div class="vendor-header">';
        			echo wp_kses_post( dokan_get_readable_seller_rating( $author->ID ) );
        		echo'</div>';

        		if($vendor->get_shop_name()) {
	        		echo '<div class="single-item">';
	        			echo '<span>'.esc_html__('Store Name: ','enefti').'</span>';
	        			echo '<span class="right">'.esc_attr($vendor->get_shop_name()).'</span>';
	        		echo '</div>';
        		}

        		echo '<div class="single-item">';
        			echo '<span>'.esc_html__('Vendor: ','enefti').'</span>';
        			echo '<a href="'.esc_url( dokan_get_store_url( $author->ID ) ).'"><span class="right">'.esc_attr($author->display_name).'</span></a>';
        		echo '</div>';

        		if(dokan_get_seller_address( $author->ID )) {
	        		echo '<div class="single-item">';
	        			echo '<span>'.esc_html__('Location: ','enefti').'</span>';
	        			echo '<span class="right">'.esc_attr(dokan_get_seller_address( $author->ID )).'</span>';
	        		echo '</div>';
	        	}

        	echo '</div>';
        echo '</div>';

	}
	if (class_exists('Dokan_Template_Products')) {
		add_action('woocommerce_enefti_vendor_section','enefti_vendor_section');
	}
}

//Single Product : Meta after Title
if (!function_exists('enefti_meta_after_title')) {
	function enefti_meta_after_title(){
		global $product;
		echo '<div class="enefti-title-meta-section">';
			echo '<div class="meta-section-item">';
				echo '<span>'.esc_html__('Views: ','enefti').'</span>';
				echo '<span>'.enefti_count_views().'</span>';
			echo '</div>';

			if($product->get_condition()) {
				echo '<div class="meta-section-item">';
					echo '<span>'.esc_html__('Condition: ','enefti').'</span>';
					echo '<span><strong>'.esc_attr($product->get_condition()).'</strong></span>';
				echo '</div>';
			}

			if($product->get_sku()) {
				echo '<div class="meta-section-item">';
					echo '<span>'.esc_html__('SKU: ','enefti').'</span>';
					echo '<span><strong>'.esc_attr($product->get_sku()).'</strong></span>';
				echo '</div>';
			}
		echo '</div>';
	}
	add_action('woocommerce_enefti_meta_after_title','enefti_meta_after_title');
}

//Single Product : collection Name
if (!function_exists('enefti_collection_after_title')) {
	function enefti_collection_after_title(){
		global $post;
		if(class_exists('Mtnft_Vite')) {
			$collectionData = get_post_meta(get_the_id($post), '_mtnft_collection', true);
			 if($collectionData !== '') {
	        	$name = esc_html($collectionData['name']);
				echo '<div class="single-collection-title">';
					echo '<span>'.esc_attr($name).' '.esc_html__('Collection','enefti').'</span>';
				echo '</div>';
			}
		}
	}
	add_action('woocommerce_single_product_summary','enefti_collection_after_title', 6);
}

if (!function_exists('enefti_search_form_categories_dropdown')) {
	function enefti_search_form_categories_dropdown(){
		 
		if(isset($_REQUEST['product_cat']) && !empty($_REQUEST['product_cat'])) {
			$optsetlect=$_REQUEST['product_cat'];
		} else {
			$optsetlect=0;  
		}

		$args = array(
			'show_option_none' => esc_html__( 'Category', 'enefti' ),
			'option_none_value'  => '',
			'hierarchical' => true,
			'class' => 'cat',
			'echo' => 1,
			'value_field' => 'slug',
			'orderby' => 'name',
			'show_count' => true,
			'hide_empty' => true,
			'selected' => $optsetlect
		);

		$args['taxonomy'] = 'product_cat';
		$args['name'] = 'product_cat';              
		$args['class'] = 'form-control1';

		wp_dropdown_categories($args);
			
	}
	add_action('enefti_header1_search_form_before', 'enefti_search_form_categories_dropdown');
	add_action('enefti_header2_search_form_before', 'enefti_search_form_categories_dropdown');
	add_action('enefti_header4_search_form_before', 'enefti_search_form_categories_dropdown');
	add_action('enefti_header5_search_form_before', 'enefti_search_form_categories_dropdown');
}