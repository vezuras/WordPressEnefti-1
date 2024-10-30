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

<header class="header-v8">

<?php do_action('enefti_after_mobile_navigation_burger'); ?>

  <div class="navbar navbar-default" id="enefti-main-head">
        <div class="row">
          <div class="navbar-header col-md-2 col-sm-12">

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
              
             
          <div class="col-md-6 navigation-navbar">
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
          </div>
          <?php if (class_exists('WooCommerce')) : ?>
            <div class="col-md-4 search-form-product">

             <?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
              if ($enefti_redux['is_search_enabled'] == true) { ?>
              <div class="enefti-header-searchform-v8">
                <form name="header-search-form" method="GET" class="woocommerce-product-search menu-search" action="<?php echo esc_url(home_url('/')); ?>">

                  <?php do_action('enefti_header8_search_form_before'); ?>
                  
                  <input type="text"  name="s" class="search-field" id="keyword" onkeyup="fetchs()" maxlength="128" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('Search products...', 'enefti'); ?>">
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  <input type="hidden" name="post_type" value="product" />
                </form>
                <div id="datafetch"></div> 
              </div>
              <div class="account-urls">
                <div class="top-header-right-wrapper">
                  <a class="top-inquiry-button" href="<?php echo esc_url($enefti_redux['inquiry_button_link_2']); ?>">
                  <?php echo esc_html($enefti_redux['inquiry_button_text_2']); ?></a>
                </div>
              </div>
              <?php }
            } ?>
            
            </div>
          <?php endif; ?>
        </div>
    </div>
 
</header>