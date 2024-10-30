<?php
  #Redux global variable
  global $enefti_redux;
  #WooCommerce global variable
  global $woocommerce;
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
?>
<?php  
if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
  if ( enefti_redux('enefti_top_header_info_switcher') == true) {
      echo enefti_my_banner_header();
  }
} ?>
<header class="header-v5">

  <?php do_action('enefti_after_mobile_navigation_burger'); ?>
  
  <div class="navbar navbar-default" id="enefti-main-head">
      <div class="container">
        <div class="row">
            <div class="navbar-header col-md-3 col-sm-12">

                <?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                  <?php if ($enefti_redux['enefti_mobile_burger_select'] == 'dropdown' || $enefti_redux['enefti_mobile_burger_select'] == '') { ?>
                      <?php do_action('enefti_burger_dropdown_button'); ?>
                  <?php } ?> 
                <?php } else {?>
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                <?php } ?>

                <?php do_action('enefti_before_mobile_navigation_burger'); ?>

                <?php echo enefti_logo(); ?>
            </div>
              
             
          <div class="first-part col-md-9 col-sm-12">
          <?php if (class_exists('WooCommerce')) : ?>
            <div class="col-md-8 search-form-product"> 

              <?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
              if ($enefti_redux['is_search_enabled'] == false) { ?>
              <div class="enefti-header-searchform">
                <form name="header-search-form" method="GET" class="woocommerce-product-search menu-search" action="<?php echo esc_url(home_url('/')); ?>">
                
                  <?php do_action('enefti_header5_search_form_before'); ?>
                  
                  <input type="text"  name="s" class="search-field" id="keyword" onkeyup="fetchs()" maxlength="128" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Search products...', 'enefti'); ?>">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  <input type="hidden" name="post_type" value="product" />
                </form>
                <div id="datafetch"></div> 
              </div>
              <?php }
            } ?>
            
            </div>
          <?php endif; ?>
           <?php if (class_exists('WooCommerce')) { ?>
              <div class="col-md-4 menu-products">
            <?php } else { ?>
              <div class="col-md-12 menu-products">
              <?php } ?>
                <?php if ( class_exists( 'WooCommerce' ) ) { ?>
                  <a  class="shop_cart" href="<?php echo esc_url($cart_url); ?>">
                    <?php esc_html_e('My Cart', 'enefti'); ?>
                  </a>
                  <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'enefti'); ?>">
                    <?php echo sprintf ( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'enefti' ), WC()->cart->get_cart_contents_count() ); ?> , <?php echo WC()->cart->get_cart_total(); ?>
                  </a>
                  <!-- Shop Minicart -->
                  <div class="header_mini_cart">
                        <?php the_widget( 'WC_Widget_Cart' ); ?>
                  </div>
                <?php } ?>
            </div>
        </div>
      </div>
  </div>

  <!-- BOTTOM BAR -->
    <nav class="navbar bottom-navbar-default" id="modeltheme-main-head">
      <div class="container">
        <div class="row row-0">
          <!-- NAV MENU -->
          <div id="navbar" class="navbar-collapse collapse col-md-10">
            <div class="bot_nav_wrap">
              <ul class="menu nav navbar-nav pull-left nav-effect nav-menu">
              <?php
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
              ?>
            </ul>
           </div>
          </div>
          <div class="col-md-2 my-account-navbar">
            <ul>
            <?php if ( class_exists('woocommerce')) { ?>
              <?php if (is_user_logged_in()) { ?>  
                <div id="dropdown-user-profile" class="ddmenu">
                  <li id="nav-menu-register" class="nav-menu-account"><?php echo esc_html__('My Account','enefti'); ?></li>
                  <ul>

                    <?php if(class_exists('Mtsub')) {
                       do_action( 'mt_after_my_account' ); ?>
                    <?php } ?>
                    
                    <li><a href="<?php echo esc_url(get_permalink( get_option('woocommerce_myaccount_page_id') )); ?>"><i class="icon-layers icons"></i> <?php echo esc_html__('My Dashboard','enefti'); ?></a></li>
                    
                    <?php if(class_exists('Mt_Freelancer_Mode')) {
                      do_action( 'mt_before_dashboard' ); ?>
                    <?php } ?>
                      
                    <?php if (class_exists('Dokan_Vendor') && dokan_is_user_seller( dokan_get_current_user_id() )) {  ?>            
                      <li><a href="<?php echo esc_url( home_url().'/dashboard' ); ?>"><i class="icon-trophy icons"></i> <?php echo esc_html__('Vendor Dashboard','enefti'); ?></a></li>
                    <?php } ?>
                    
                    <?php if (class_exists('WCFM') && wcfm_is_vendor()) { ?>
                      <li><a href="<?php echo apply_filters( 'wcfm_dashboard_home', get_wcfm_page() ); ?>"><i class="icon-trophy icons"></i> <?php echo esc_html__('Vendor Dashboard','enefti'); ?></a></li>
                    <?php } ?>
                    
                    <?php if (class_exists('WC_Vendors')) { ?>
                      <?php if (get_option('wcvendors_vendor_dashboard_page_id') != '') { ?>
                        <li><a href="<?php echo esc_url( get_permalink(get_option('wcvendors_vendor_dashboard_page_id')) ); ?>"><i class="icon-trophy icons"></i> <?php echo esc_html__('Vendor Dashboard','enefti'); ?></a></li>
                      <?php } ?>
                    <?php } ?>
                    
                    <?php do_action('enefti_after_vendor_dashboard_link'); ?>
                    
                    <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')).'orders'); ?>"><i class="icon-bag icons"></i> <?php echo esc_html__('My Orders','enefti'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id')).'edit-account'); ?>"><i class="icon-user icons"></i> <?php echo esc_html__('Account Details','enefti'); ?></a></li>
                    <div class="dropdown-divider"></div>
                    <li><a href="<?php echo esc_url(wp_logout_url( home_url() )); ?>"><i class="icon-logout icons"></i> <?php echo esc_html__('Log Out','enefti'); ?></a></li>
                  </ul>
                </div>
              <?php } else { ?> <!-- logged out -->
                <li id="nav-menu-login" class="enefti-logoin">
                  <?php do_action('enefti_login_link_a'); ?>
                </li>
              <?php } ?>
            <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </nav>
  </div>
</header>