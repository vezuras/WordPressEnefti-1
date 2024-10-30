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

<header class="header-v3">

  <?php do_action('enefti_after_mobile_navigation_burger'); ?>

  <div class="navbar navbar-default" id="enefti-main-head">
      <div class="container">
        <div class="row">

          <!-- LOGO -->
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
             
          <div class="first-part col-md-10 col-sm-12">
            <?php if (class_exists('WooCommerce')) : ?>
              
            <?php endif; ?>

            <div class="col-md-9 menu-holder">
              <nav class="navbar bottom-navbar-default" id="modeltheme-main-head">
                <div id="navbar" class="navbar-collapse collapse">
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
                          echo esc_html__('Header 3 navigation menu is missing.', 'enefti');
                        echo '</p>';
                      }
                    ?>
                  </ul>
                 </div>
                </div>
              </nav>
            </div>

            <div class="col-md-3 account-urls">
              <div class="top-header-right-wrapper">
                <a class="top-inquiry-button" href="<?php echo esc_url($enefti_redux['inquiry_button_link_2']); ?>">
                <?php echo esc_html($enefti_redux['inquiry_button_text_2']); ?></a>
              </div>
            </div>

        </div>
      </div>
  </div>
  </div>
</header>