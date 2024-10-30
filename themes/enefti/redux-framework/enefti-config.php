<?php
/**
  ReduxFramework enefti Theme Config File
  For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
 * */



if (!class_exists("Redux_Framework_enefti_config")) {

    class Redux_Framework_enefti_config {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }
            
            // This is needed. Bah WordPress bugs.  ;)
            if ( get_template_directory() && strpos( Redux_Functions_Ex::wp_normalize_path( __FILE__ ), Redux_Functions_Ex::wp_normalize_path( get_template_directory() ) ) !== false) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);    
            }
        }

        public function initSettings() {

            if ( !class_exists("ReduxFramework" ) ) {
                return;
            }       
            
            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

       

        

        public function setSections() {

            include_once(get_template_directory() . '/redux-framework/modeltheme-config.arrays.php');

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $enefti_patterns_path = ReduxFramework::$_dir . '../polygon/patterns/';
            $enefti_patterns_url = ReduxFramework::$_url . '../polygon/patterns/';
            $enefti_patterns = array();

            if (is_dir($enefti_patterns_path)) :

                if ($enefti_patterns_dir = opendir($enefti_patterns_path)) :
                    $enefti_patterns = array();

                    while (( $enefti_patterns_file = readdir($enefti_patterns_dir) ) !== false) {

                        if (stristr($enefti_patterns_file, '.png') !== false || stristr($enefti_patterns_file, '.jpg') !== false) {
                            $name = explode(".", $enefti_patterns_file);
                            $name = str_replace('.' . end($name), '', $enefti_patterns_file);
                            $enefti_patterns[] = array('alt' => $name, 'img' => $enefti_patterns_url . $enefti_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct = wp_get_theme();
            $this->theme = $ct;
            $item_name = $this->theme->get('Name');
            $tags = $this->theme->Tags;
            $screenshot = $this->theme->get_screenshot();
            $class = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'enefti'), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                    <a href="<?php echo esc_url(wp_customize_url()); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                        <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','enefti'); ?>" />
                    </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview','enefti'); ?>" />
            <?php endif; ?>

                <h4>
            <?php echo esc_html($this->theme->display('Name')); ?>
                </h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'enefti'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'enefti'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'enefti') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo esc_html($this->theme->display('Description')); ?></p>
                <?php
                if ($this->theme->parent()) {
                    printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'enefti') . '</p>', __('http://codex.WordPress.org/Child_Themes', 'enefti'), $this->theme->parent()->display('Name'));
                }
                ?>

                </div>

            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();


            /*
             *
             * ---> START SECTIONS
             *
             */
            include_once(get_template_directory(). '/redux-framework/modeltheme-config.responsive.php');


            # General Settings
            $this->sections[] = array(
                'icon' => 'el-icon-wrench',
                'title' => __('General Settings', 'enefti'),
            );
            # General
            $this->sections[] = array(
                'icon' => 'el el-chevron-right',
                'subsection' => true,
                'title' => __('Breadcrumbs', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_general_breadcrumbs',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Breadcrumbs</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti-enable-breadcrumbs',
                        'type'     => 'switch', 
                        'title'    => __('Breadcrumbs', 'enefti'),
                        'subtitle' => __('Enable or disable breadcrumbs', 'enefti'),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'breadcrumbs-delimitator',
                        'type'     => 'text',
                        'title'    => __('Breadcrumbs delimitator', 'enefti'),
                        'subtitle' => __('This is a little space under the Field Title in the Options table, additional info is good in here.', 'enefti'),
                        'desc'     => __('This is the description field, again good for additional info.', 'enefti'),
                        'default'  => '/',
                        'required' => array( 'enefti-enable-breadcrumbs', '=', true ),
                    ),
                    array(
                        'id'    => 'enefti_breadcrumbs_navxt',
                        'type'  => 'info',
                        'style' => 'success',
                        'title' => __('Note', 'enefti'),
                        'icon'  => 'el-icon-info-sign',
                        'desc'  => __( 'Enefti is also compatible with <a target="_blank" href="https://wordpress.org/plugins/breadcrumb-navxt/"><strong>Breadcrumb NavXT</strong></a> plugin, for an enhanced Breadcrumbs / SEO Ready Breadcrumbs feature. Install it and it will automatically replace the default breadcrumbs feature.', 'enefti'),
                        'required' => array( 'enefti-enable-breadcrumbs', '=', true ),
                    ),
                )
            );
            /**
            ||-> SECTION: Page Preloader
            */
            $this->sections[] = array(
                'title' => esc_html__( 'Page Preloader Settings', 'enefti' ),
                'icon' => 'el el-dashboard',
                'subsection' => true,
                'fields' => array(
                    array(
                        'id'   => 'mt_preloader_status',
                        'type' => 'info',
                        'class' => 'mt_divider',
                        'desc' => esc_html__( 'Preloader Status', 'enefti' )
                    ),
                    array(
                        'id'       => 'mt_preloader_status',
                        'type'     => 'switch', 
                        'title'    => esc_html__('Enable Page Preloader', 'enefti'),
                        'subtitle' => esc_html__('Enable or disable page preloader', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'   => 'mt_preloader_styling',
                        'type' => 'info',
                        'class' => 'mt_divider',
                        'desc' => esc_html__( 'Preloader Styling', 'enefti' ),
                        'required' => array( 'mt_preloader_status', '=', true ),
                    ),
                    array(         
                        'id'       => 'mt_preloader_bg_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Page Preloader Backgrond', 'enefti'), 
                        'subtitle' => esc_html__('Default: #4aafe1', 'enefti'),
                        'default'  => array(
                            'background-color' => '#4aafe1',
                        ),
                        'output' => array(
                            '.mt_preloader_holder,
                            .mt_preloader_holder .inner-ring'
                        ),
                        'required' => array( 'mt_preloader_status', '=', true ),
                    ),
                ),
            );
            # General -> Sidebars
            $this->sections[] = array(
                'icon' => 'el-icon-website',
                'title' => __('Sidebars (Widgetized Ares)', 'enefti'),
                'subsection' => true,
                'fields' => array(
                    array(
                        'id'   => 'enefti_sidebars_generator',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Generate Unlimited Sidebars</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'dynamic_sidebars',
                        'type'     => 'multi_text',
                        'title'    => __( 'Sidebars', 'enefti' ),
                        'subtitle' => __( 'Use the "Add More" button to create unlimited sidebars.', 'enefti' ),
                        'desc'     => __( 'All the widgetized areas will be automatically generated and listed under Appearance - Widgets.', 'enefti' ),
                        'add_text' => __( 'Add one more Sidebar', 'enefti' )
                    )
                )
            );



            # Section #2: Styling Settings
            $this->sections[] = array(
                'icon' => 'el-icon-magic',
                'title' => __('Styling Settings', 'enefti'),
            );
            // Colors
            $this->sections[] = array(
                'icon' => 'el-icon-magic',
                'subsection' => true,
                'title' => __('Colors', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_divider_links',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Links Colors(Regular, Hover, Active/Visited)</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_global_link_styling',
                        'type'     => 'link_color',
                        'title'    => esc_html__('Links Color Option', 'enefti'),
                        'subtitle' => esc_html__('Only color validation can be done on this field type(Default Regular: #d01498; Default Hover: #d01498; Default Active: #484848;)', 'enefti'),
                        'default'  => array(
                            'regular'  => '#d01498', // blue
                            'hover'    => '#d01498', // blue-x3
                            'active'   => '#484848',  // blue-x3
                            'visited'  => '#484848',  // blue-x3
                        )
                    ),
                    array(
                        'id'   => 'enefti_divider_main_colors',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Main Colors & Backgrounds</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_style_main_texts_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Main texts color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #D01498', 'enefti'),
                        'default'  => '#D01498',
                        'validate' => 'color',
                    ),
                    array(
                        'id'       => 'enefti_style_main_backgrounds_color',
                        'type'     => 'color_gradient',
                        'title'    => esc_html__('Main background color', 'enefti'), 
                        'validate' => 'color',
                        'default'  => array(
                            'from' => '#647ECB',
                            'to'   => '#D01498', 
                        ),
                    ),
                    array(
                        'id'       => 'enefti_style_main_backgrounds_color_hover',
                        'type'     => 'color',
                        'title'    => esc_html__('Main background color (hover)', 'enefti'), 
                        'subtitle' => esc_html__('Default: #ffffff', 'enefti'),
                        'default'  => '#ffffff',
                        'validate' => 'color',
                    ),
                    array(
                        'id'       => 'enefti_style_semi_opacity_backgrounds',
                        'type'     => 'color_rgba',
                        'title'    => esc_html__( 'Semitransparent blocks background', 'enefti' ),
                        'default'  => array(
                            'color' => '#f02222',
                            'alpha' => '.95'
                        ),
                        'output' => array(
                            'background-color' => '.fixed-sidebar-menu',
                        ),
                        'mode'     => 'background'
                    ),
                    array(
                        'id'   => 'enefti_divider_text_selection',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Text Selection Color & Background</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_text_selection_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Text selection color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #ffffff', 'enefti'),
                        'default'  => '#ffffff',
                        'validate' => 'color',
                    ),
                    array(
                        'id'       => 'enefti_text_selection_background_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Text selection background color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #D01498', 'enefti'),
                        'default'  => '#D01498',
                        'validate' => 'color',
                    ),


                    array(
                        'id'   => 'enefti_divider_nav_menu',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Menus Styling</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_nav_menu_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Nav Menu Text Color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #ffffff', 'enefti'),
                        'default'  => '#ffffff',
                        'validate' => 'color',
                        'output' => array(
                            'color' => '#navbar .menu-item > a,
                                        .navbar-nav .search_products a,
                                        .navbar-default .navbar-nav > li > a,
                                        li.nav-menu-account,
                                        .my-account-navbar a,
                                        .top-header .contact-header p',
                        )
                    ),
                    array(
                        'id'       => 'enefti_nav_menu_color_hover',
                        'type'     => 'color',
                        'title'    => esc_html__('Nav Menu Text Color on hover', 'enefti'), 
                        'subtitle' => esc_html__('Default: #fff', 'enefti'),
                        'default'  => '#fff',
                        'validate' => 'color',
                        'output' => array(
                            'color' => '#navbar .menu-item > a:hover, 
                                        #navbar .menu-item > a:focus,
                                        .navbar-nav .search_products a:hover, 
                                        .navbar-nav .search_products a:focus,
                                        .navbar-default .navbar-nav > li > a:hover, 
                                        .navbar-default .navbar-nav > li > a:focus',
                        )
                    ),
                    array(
                        'id'   => 'enefti_divider_nav_submenu',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Submenus Styling</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_nav_submenu_background',
                        'type'     => 'color',
                        'title'    => esc_html__('Nav Submenu Background Color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #FFF', 'enefti'),
                        'default'  => '#FFF',
                        'validate' => 'color',
                        'output' => array(
                            'background-color' => '#navbar .sub-menu, .navbar ul li ul.sub-menu',
                        )
                    ),
                    array(
                        'id'       => 'enefti_nav_submenu_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Nav Submenu Text Color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #484848', 'enefti'),
                        'default'  => '#484848',
                        'validate' => 'color',
                        'output' => array(
                            'color' => '#navbar ul.sub-menu li a,.bot_nav_cat_wrap li a:hover,  .mega_menu .cf-mega-menu.sub-menu p a',
                        )
                    ),
                    array(
                        'id'       => 'enefti_nav_submenu_hover_background_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Nav Submenu Hover Background Color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #FFF', 'enefti'),
                        'default'  => '#FFF',
                        'validate' => 'color',
                        'output' => array(
                            'background-color' => '#navbar ul.sub-menu li a:hover',
                        )
                    ),
                    array(
                        'id'       => 'enefti_nav_submenu_hover_text_color',
                        'type'     => 'color',
                        'title'    => esc_html__('Nav Submenu Hover Background Color', 'enefti'), 
                        'subtitle' => esc_html__('Default: #D01498', 'enefti'),
                        'default'  => '#D01498',
                        'validate' => 'color',
                        'output' => array(
                            'color' => '#navbar ul.sub-menu li a:hover',
                        )
                    ),
                )
            );
            // Fonts
            $this->sections[] = array(
                'icon' => 'el-icon-fontsize',
                'subsection' => true,
                'title' => __('Typography', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_styling_gfonts',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Import Google Fonts</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_google_fonts_select',
                        'type'     => 'select',
                        'multi'    => true,
                        'title'    => esc_attr__('Import Google Font Globally', 'enefti'), 
                        'subtitle' => esc_attr__('Select one or multiple fonts', 'enefti'),
                        'desc'     => esc_attr__('Importing fonts made easy', 'enefti'),
                        'options'  => $google_fonts_list,
                        'default'  => array(
                            'Montserrat:regular,500,600,700,800,900,latin',
                            'Poppins:300,regular,500,600,700,latin-ext,latin,devanagari',
                            'Raleway:300,regular,500,600,700,latin-ext,latin,devanagari'
                        ),
                    ),
                    array(
                        'id'   => 'enefti_styling_fonts',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Set the main site font</h3>', 'enefti' )
                    ),
                    array(
                        'id'          => 'enefti-body-typography',
                        'type'        => 'typography', 
                        'title'       => __('Body Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => false,
                        'text-align'  => false,
                        'letter-spacing'  => false,
                        'line-height'  => false,
                        'font-weight'  => false,
                        'font-size'   => false,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('body'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Raleway', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'   => 'enefti_divider_5',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Headings</h3>', 'enefti' )
                    ),
                    array(
                        'id'          => 'enefti_heading_h1',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Heading H1 Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => true,
                        'line-height'  => true,
                        'font-weight'  => false,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('h1', 'h1 span'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Poppins', 
                            'font-size' => '36px', 
                            'color' => '#242424', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'          => 'enefti_heading_h2',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Heading H2 Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => true,
                        'line-height'  => true,
                        'font-weight'  => false,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('h2'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Poppins', 
                            'font-size' => '30px', 
                            'color' => '#242424', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'          => 'enefti_heading_h3',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Heading H3 Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => true,
                        'line-height'  => true,
                        'font-weight'  => false,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('h3', '.post-name'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Poppins', 
                            'font-size' => '24px', 
                            'color' => '#242424', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'          => 'enefti_heading_h4',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Heading H4 Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => true,
                        'line-height'  => true,
                        'font-weight'  => false,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('h4'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Poppins', 
                            'font-size' => '18px', 
                            'color' => '#242424', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'          => 'enefti_heading_h5',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Heading H5 Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => true,
                        'line-height'  => true,
                        'font-weight'  => false,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('h5'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Poppins', 
                            'font-size' => '14px', 
                            'color' => '#242424', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'          => 'enefti_heading_h6',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Heading H6 Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => true,
                        'line-height'  => true,
                        'font-weight'  => false,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('h6'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Poppins', 
                            'font-size' => '12px', 
                            'color' => '#242424', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'   => 'enefti_divider_6',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Inputs & Textareas Font family</h3>', 'enefti' )
                    ),
                    array(
                        'id'                => 'enefti_inputs_typography',
                        'type'              => 'typography', 
                        'title'             => esc_html__('Inputs Font family', 'enefti'),
                        'google'            => true, 
                        'font-backup'       => true,
                        'color'             => false,
                        'text-align'        => false,
                        'letter-spacing'    => false,
                        'line-height'       => false,
                        'font-weight'       => false,
                        'font-size'         => false,
                        'font-style'        => false,
                        'subsets'           => false,
                        'output'            => array('input', 'textarea'),
                        'units'             =>'px',
                        'subtitle'          => esc_html__('Font family for inputs and textareas', 'enefti'),
                        'default'           => array(
                            'font-family'       => 'Raleway', 
                            'google'            => true
                        ),
                    ),
                    array(
                        'id'   => 'enefti_divider_7',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Buttons Font family</h3>', 'enefti' )
                    ),
                    array(
                        'id'                => 'enefti_buttons_typography',
                        'type'              => 'typography', 
                        'title'             => esc_html__('Buttons Font family', 'enefti'),
                        'google'            => true, 
                        'font-backup'       => true,
                        'color'             => false,
                        'text-align'        => false,
                        'letter-spacing'    => false,
                        'line-height'       => false,
                        'font-weight'       => false,
                        'font-size'         => false,
                        'font-style'        => false,
                        'subsets'           => false,
                        'output'            => array(
                            'input[type="submit"]'
                        ),
                        'units'             =>'px',
                        'subtitle'          => esc_html__('Font family for buttons', 'enefti'),
                        'default'           => array(
                            'font-family'       => 'Raleway', 
                            'google'            => true
                        ),
                    ),
                )
            );
            // Fonts (mobile)
            $this->sections[] = $responsive_headings;
            // Custom CSS
            $this->sections[] = array(
                'icon' => 'el-icon-css',
                'subsection' => true,
                'title' => __('Custom CSS', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_styling_custom_css',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Custom CSS</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_css_editor',
                        'type'     => 'ace_editor',
                        'title'    => __('CSS Code', 'enefti'),
                        'subtitle' => __('Paste your CSS code here.', 'enefti'),
                        'mode'     => 'css',
                        'theme'    => 'monokai',
                        'desc'     => 'Add your own custom styling (CSS rules only)',
                        'default'     => '#header{margin: 0 auto;}',
                    )
                )
            );
            // Form Fields
            $this->sections[] = array(
                'icon' => 'el el-list-alt',
                'subsection' => true,
                'title' => __('Form Fields', 'enefti'),
                'fields' => array(
                    array(
                        'id'        => 'enefti_fields_styling_radius',
                        'type'      => 'radio',
                        'title'     => __('Fields Shape:', 'enefti'),
                        'subtitle'  => __('Form fields & buttons radius', 'enefti'),
                        'options'   => array(
                            '30'   => 'Rounded',
                            '0'   => 'Square',
                            '5'   => 'Round',
                        ),
                        'default'   => '5',
                        'desc' => esc_html__( 'Rounded: 30px radius | Square: 0px radius | Round: 5px radius', 'enefti' ),
                        //30 -> border radius
                    ),
                )
            );


            # Section #2: Header Settings

            $this->sections[] = array(
                'icon' => 'el-icon-arrow-up',
                'title' => __('Header Settings', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_header_variant',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Header Variant</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'header_layout',
                        'type'     => 'image_select',
                        'title'    => __( 'Select Header layout', 'enefti' ),
                        'options'  => array(
                            'second_header' => array(
                                'alt' => 'Header',
                                'img' => get_template_directory_uri().'/redux-framework/assets/headers/header_2.jpg'
                            ),
                            'third_header' => array(
                                'alt' => 'Header #2',
                                'img' => get_template_directory_uri().'/redux-framework/assets/headers/header_3.jpg'
                            ),
                            'eighth_header' => array(
                                'alt' => 'Header #3',
                                'img' => get_template_directory_uri().'/redux-framework/assets/headers/header_8.jpg'
                            ), 
                        ),
                        'default'  => 'second_header'
                    ),
                    array(
                        'id'   => 'mt_divider_first_header',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => '<h3>'.esc_html__( 'Header 1 Custom Background (Menu bar)', 'enefti' ).'</h3>',
                        'required' => array( 'header_layout', '=', 'first_header' ),
                    ),
                    array(         
                        'id'       => 'nav_main_background',
                        'type'     => 'background',
                        'title'    => __('Navigation background', 'enefti'),
                        'subtitle' => __('Override the Navigation background with color.', 'enefti'),
                        'required' => array( 'header_layout', '=', 'first_header' ),
                        'output'      => array('.header-v1 .navbar.bottom-navbar-default')
                    ),
                    array(
                        'id'   => 'mt_divider_second_header',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => '<h3>'.esc_html__( 'Header Top & Bottom Header Background', 'enefti' ).'</h3>',
                        'required' => array( 'header_layout', '=', 'second_header' ),
                    ),
                    array(
                        'id'       => 'mt_style_top_header2_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Top Header - background color', 'enefti'), 
                        'default'  => '#000000',
                        'required' => array( 'header_layout', '=', 'second_header' ),
                        'default'  => array(
                            'background-color' => '#000000',
                        ),
                    ),
                    array(
                        'id'       => 'mt_style_bottom_header2_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Main Header - background color', 'enefti'), 
                        'default'  => '#000000',
                        'required' => array( 'header_layout', '=', 'second_header' ),
                        'default'  => array(
                            'background-color' => '#000000',
                        ),
                    ),

                    array(
                        'id'   => 'mt_divider_third_header',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => '<h3>'.esc_html__( 'Header Top & Bottom Header Background', 'enefti' ).'</h3>',
                        'required' => array( 'header_layout', '=', 'third_header' ),
                    ),
                    array(
                        'id'       => 'mt_style_top_header3_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Main Header - background color', 'enefti'), 
                        'default'  => '#1C1F26',
                        'required' => array( 'header_layout', '=', 'third_header' ),
                        'default'  => array(
                            'background-color' => '#1C1F26',
                        ),
                    ),
                    array(
                        'id'   => 'mt_divider_seventh_header',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => '<h3>'.esc_html__( 'Header 7 Custom Settings', 'enefti' ).'</h3>',
                        'required' => array( 'header_layout', '=', 'seventh_header' ),
                    ),
                    array(         
                        'id'       => 'inquiry_button_background',
                        'type'     => 'background',
                        'title'    => __('Inquiry Button background', 'enefti'),
                        'subtitle' => __('Set Inquiry Button background', 'enefti'),
                        'required' => array( 'header_layout', '=', 'seventh_header' ),
                        'default'  =>  '#2695FF',
                        'output'      => array('.header-v7 .menu-inquiry .button')
                    ),
                    array(
                        'id' => 'inquiry_button_text',
                        'required' => array( 'header_layout', '=', 'seventh_header' ),
                        'type' => 'text',
                        'title' => __('Inquiry Button Text', 'enefti'),
                        'subtitle' => __('Set Inquiry Button Text', 'enefti'),
                        'default' => 'Post Project'
                    ),
                    array(
                        'id' => 'inquiry_button_link',
                        'required' => array( 'header_layout', '=', 'seventh_header' ),
                        'type' => 'text',
                        'title' => __('Inquiry Button Link', 'enefti'),
                        'subtitle' => __('Set Inquiry Button Link', 'enefti'),
                        'default' => '#'
                    ),
                     array(
                        'id'   => 'mt_divider_eighth_header',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => '<h3>'.esc_html__( 'Header Top & Bottom Header Background', 'enefti' ).'</h3>',
                        'required' => array( 'header_layout', '=', 'eighth_header' ),
                    ),
                    array(
                        'id'       => 'mt_style_top_header8_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Main Header - background color', 'enefti'), 
                        'default'  => '#1C1F26',
                        'required' => array( 'header_layout', '=', 'eighth_header' ),
                        'default'  => array(
                            'background-color' => '#1C1F26',
                        ),
                    ),
                    array(
                        'id'   => 'mt_divider_ninth_header',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => '<h3>'.esc_html__( 'Header 9 Custom Settings', 'enefti' ).'</h3>',
                        'required' => array( 'header_layout', '=', 'ninth_header' ),
                    ),
                    array(         
                        'id'       => 'inquiry_button_background_9',
                        'type'     => 'background',
                        'title'    => __('Inquiry Button background', 'enefti'),
                        'subtitle' => __('Set Inquiry Button background', 'enefti'),
                        'required' => array( 'header_layout', '=', 'ninth_header' ),
                        'default'  =>  '#2695FF',
                        'output'      => array('.header-v9 .menu-inquiry .button')
                    ),
                    array(
                        'id' => 'inquiry_button_text_9',
                        'required' => array( 'header_layout', '=', 'ninth_header' ),
                        'type' => 'text',
                        'title' => __('Inquiry Button Text', 'enefti'),
                        'subtitle' => __('Set Inquiry Button Text', 'enefti'),
                        'default' => 'Inquiry'
                    ),
                    array(
                        'id' => 'inquiry_button_link_9',
                        'required' => array( 'header_layout', '=', 'ninth_header' ),
                        'type' => 'text',
                        'title' => __('Inquiry Button Link', 'enefti'),
                        'subtitle' => __('Set Inquiry Button Link', 'enefti'),
                        'default' => '#'
                    ),
                    array(
                        'id' => 'enefti_top_bar_text',
                        'type' => 'editor',
                        'title' => __('Top Bar Text Left', 'enefti'),
                        'required' => array( 'header_layout', '=', 'second_header' ),
                        'default' => 'Discover, find and sell extraordinary NFT with us.',
                    ),
                    array(
                        'id' => 'inquiry_button_text_2',
                        'type' => 'text',
                        'title' => __('Inquiry Button Text', 'enefti'),
                        'subtitle' => __('Set Inquiry Button Text', 'enefti'),
                        'default' => 'Become Vendor'
                    ),
                    array(
                        'id' => 'inquiry_button_link_2',
                        'type' => 'text',
                        'title' => __('Inquiry Button Link', 'enefti'),
                        'subtitle' => __('Set Inquiry Button Link', 'enefti'),
                        'default' => '#'
                    ),
                    array(
                        'id'       => 'is_nav_sticky',
                        'type'     => 'switch', 
                        'title'    => __('Fixed Navigation menu', 'enefti'),
                        'subtitle' => __('Enable or disable "fixed positioned navigation menu".', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'is_popup_enabled',
                        'type'     => 'switch', 
                        'title'    => __('Login/Register Display', 'enefti'),
                        'subtitle' => __('Choose form to open in Pop-up or redirect to "My Account" page.', 'enefti'),
                        'on' => __('Login/Register Popup', 'enefti'),
                        'off' => __('My Account link', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'is_search_enabled',
                        'type'     => 'switch', 
                        'title'    => __('Search Bar', 'enefti'),
                        'subtitle' => __('Enable or disable Search Bar on header.', 'enefti'),
                        'default'  => true,
                    ),                
                    array(
                        'id'   => 'enefti_header_search_settings',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Search Settings</h3>', 'enefti' )
                    ),
                    array(
                        'id'        => 'search_for',
                        'type'      => 'select',
                        'title'     => __('Search form for:', 'enefti'),
                        'subtitle'  => __('Select the scope of the header search form(Search for PRODUCTS or POSTS).', 'enefti'),
                        'options'   => array(
                                'products'   => 'Products',
                                'posts'   => 'Posts'
                            ),
                        'default'   => 'products',
                    ),
                    array(
                        'id'   => 'enefti_header_logo_settings',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Logo & Favicon Settings</h3>', 'enefti' )
                    ),
                    array(
                        'id' => 'enefti_logo',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Logo as image', 'enefti'),
                        'compiler' => 'true',
                        'default' => array('url' => get_template_directory_uri().'/images/svg/logo.svg'),
                    ),
                    array(
                        'id' => 'enefti_logo_sticky',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Logo as image (only for sticky header/Fixed Navigation menu option)', 'enefti'),
                        'compiler' => 'true',
                        'default' => array('url' => get_template_directory_uri().'/images/svg/logo-dark.svg'),
                    ),
                    array(
                        'id'        => 'logo_max_width',
                        'type'      => 'slider',
                        'title'     => __('Logo Max Width', 'enefti'),
                        'subtitle'  => __('Use the slider to increase/decrease max size of the logo.', 'enefti'),
                        'desc'      => __('Min: 1px, max: 500px, step: 1px, default value: 140px', 'enefti'),
                        "default"   => 150,
                        "min"       => 1,
                        "step"      => 1,
                        "max"       => 500,
                        'display_value' => 'label'
                    ),
                    array(
                        'id' => 'enefti_favicon',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Favicon url', 'enefti'),
                        'compiler' => 'true',
                        'subtitle' => __('Use the upload button to import media.', 'enefti'),
                        'default' => array('url' => get_template_directory_uri().'/images/favicon-enefti.png'),
                    ),
                    array(
                        'id'   => 'enefti_header_styling_settings',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Header Styling Settings</h3>', 'enefti' )
                    ),
                    array(
                        'id'   => 'enefti_header_styling_settings',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Top Header Information Settings</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_top_header_info_switcher',
                        'type'     => 'switch', 
                        'title'    => __('Header Discount Block', 'enefti'),
                        'subtitle' => __('Enable or disable the Header Discount Block.', 'enefti'),
                        'default'  => false,
                    ),
                    array(         
                        'id'       => 'discout_header_background',
                        'type'     => 'background',
                        'title'    => __('Header Discount Background', 'enefti'),
                        'subtitle' => __('Header background with image or color.', 'enefti'),
                        'output'      => array('.enefti-top-banner'),
                        'required' => array( 'enefti_top_header_info_switcher', '=', true ),
                        'default'  => array(
                            'background-color' => '#f5f5f5',
                        )
                    ),
                    array(
                        'id' => 'discout_header_text',
                        'type' => 'text',
                        'required' => array( 'enefti_top_header_info_switcher', '=', true ),
                        'title' => __('Header Discount Text', 'enefti'),
                        'default' => 'New Student Deal..'
                    ),
                    array(
                        'id' => 'discout_header_date',
                        'type' => 'date',
                        'required' => array( 'enefti_top_header_info_switcher', '=', true ),
                        'title' => __('Header Discount Expiration Date', 'enefti'),
                        'default' => '22/02/2022'
                    ),
                    array(
                        'id' => 'discout_header_btn_text',
                        'type' => 'text',
                        'required' => array( 'enefti_top_header_info_switcher', '=', true ),
                        'title' => __('Button Text', 'enefti'),
                        'default' => 'Join Now'
                    ),
                    array(
                        'id' => 'discout_header_btn_link',
                        'type' => 'text',
                        'required' => array( 'enefti_top_header_info_switcher', '=', true ),
                        'title' => __('Button Link', 'enefti'),
                        'default' => '#'
                    ),
                    array(
                        'id'       => 'discout_header_btn_color',
                        'type'     => 'color',
                        'required' => array( 'enefti_top_header_info_switcher', '=', true ),
                        'title'    => esc_html__('Button Background', 'enefti'), 
                        'default'  => '#ef6f31',
                        'validate' => 'background',
                        'output' => array(
                            'background-color' => '.enefti-top-banner .button',
                        )
                    )
                )
            );
            $this->sections[] = array(
                'icon' => 'el-icon-circle-arrow-up',
                'subsection' => true,
                'title' => __('Mobile Settings', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_header_mobile_settings',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Mobile Header Settings</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_mobile_burger_select',
                        'type'     => 'select', 
                        'title'    => __('Mobile Burger version', 'enefti'),
                        'subtitle' => __('Choose variant for mobile menu display.', 'enefti'),
                        'options'   => array(
                            'dropdown'   => __( 'Dropdown Menu', 'enefti' ),
                            'sidebar'   => __( 'Sidebar Menu', 'enefti' ),
                        ),
                        'default'   => 'dropdown',
                    ),
                    array(
                        'id'       => 'enefti_header_category_menu_mobile',
                        'type'     => 'switch', 
                        'title'    => __('Category menu on mobile enabled?', 'enefti'),
                        'subtitle' => __('Enable or disable "category navigation menu on mobile".', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_header_mobile_switcher_top',
                        'type'     => 'switch', 
                        'title'    => __('Icon Groups on Top Header (Mobile only)', 'enefti'),
                        'subtitle' => __('Enable or disable the Icon Group on Top Header.', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_header_mobile_switcher_top_search',
                        'type'     => 'switch', 
                        'title'    => __('Search Icon Groups on Top Header (Mobile only)', 'enefti'),
                        'subtitle' => __('Enable or disable the Search Icon Group on Top Header.', 'enefti'),
                        'required' => array( 'enefti_header_mobile_switcher_top', '=', true ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'enefti_header_mobile_switcher_top_account',
                        'type'     => 'switch', 
                        'title'    => __('Account Icon Groups on Top Header (Mobile only)', 'enefti'),
                        'subtitle' => __('Enable or disable the My Account Icon Group on Top Header.', 'enefti'),
                        'required' => array( 'enefti_header_mobile_switcher_top', '=', true ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'enefti_header_mobile_switcher_footer',
                        'type'     => 'switch', 
                        'title'    => __('Icon Groups on Sticky Footer (Mobile only)', 'enefti'),
                        'subtitle' => __('Enable or disable the Icon Group on Sticky Footer.', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_header_mobile_switcher_footer_search',
                        'type'     => 'switch', 
                        'title'    => __('Search Icon Groups on Sticky Footer (Mobile only)', 'enefti'),
                        'subtitle' => __('Enable or disable the Search Icon Group on Sticky Footer.', 'enefti'),
                        'required' => array( 'enefti_header_mobile_switcher_footer', '=', true ),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'enefti_header_mobile_switcher_footer_account',
                        'type'     => 'switch', 
                        'title'    => __('Account Icon Groups on Sticky Footer (Mobile only)', 'enefti'),
                        'subtitle' => __('Enable or disable the Account Icon Group on Sticky Footer.', 'enefti'),
                        'required' => array( 'enefti_header_mobile_switcher_footer', '=', true ),
                        'default'  => true,
                    ),
                ),
            );
            # General Settings
            $this->sections[] = array(
                'icon' => 'el-icon-arrow-down',
                'title' => __('Footer Settings', 'enefti'),
            );
            $this->sections[] = array(
                'icon' => 'el-icon-circle-arrow-up',
                'subsection' => true,
                'title' => __('Footer Top', 'enefti'),
                'fields' => array(
                    array(
                        'id'       => 'enefti-enable-footer-top',
                        'type'     => 'switch', 
                        'title'    => __('Footer Top', 'enefti'),
                        'subtitle' => __('Enable or disable footer top', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'   => 'enefti_footer_row_top',
                        'type' => 'info',
                        'desc' => 'Click <a href="'.admin_url( 'widgets.php#footer-top').'">here</a> to add/remove the clients logo positioned above the footer widgets. Search for the "Footer Top" widgetized area.',
                        'required' => array( 'enefti-enable-footer-top', '=', 'true' ),
                    ),
                    array(         
                        'id'       => 'footer_top_background',
                        'type'     => 'background',
                        'title'    => __('Footer (top) - background', 'enefti'),
                        'subtitle' => __('Footer background with image or color.', 'enefti'),
                        'output'      => array('footer'),
                        'default'  => array(
                            'background-color' => '#000000',
                        ),
                        'required' => array( 'enefti-enable-footer-top', '=', 'true' ),
                    ),
                    array(         
                        'id'       => 'footer_top_color_text',
                        'type'     => 'color',
                        'title'    => __('Footer (top) - color text', 'enefti'),
                        'subtitle' => __('Footer text color.', 'enefti'),
                        'default'  =>  '#fff',
                        'validate' => 'color',
                        'required' => array( 'enefti-enable-footer-top', '=', 'true' ),

                    ),
                    array(
                        'id'   => 'enefti_footer_row1',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Footer Widgets (Row #1)</h3>', 'enefti' ),
                        
                    ),
                    array(
                        'id'       => 'enefti-enable-footer-widgets',
                        'type'     => 'switch', 
                        'title'    => __('Status', 'enefti'),
                        'default'  => true,
                    ),
                    array(
                        'id'       => 'enefti_number_of_footer_columns',
                        'type'     => 'select',
                        'title'    => __('Footer Widgets Row #1 - Number of columns', 'enefti'), 
                        'options'  => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6'
                        ),
                        'default'  => '4',
                        'required' => array('enefti-enable-footer-widgets','equals',true),
                    ),
                    array(
                        'id'             => 'footer_row_1_spacing',
                        'type'           => 'spacing',
                        'output'         => array('.container.footer-top, .prefooter .container'),
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => esc_html__('Footer Widgets Row #1 - Padding', 'enefti'),
                        'default'            => array(
                            'padding-top'     => '0px', 
                            'padding-bottom'  => '0px', 
                            'units'          => 'px', 
                        ),
                        'required' => array('enefti-enable-footer-widgets','equals',true),
                    ),
                    array(
                        'id'   => 'enefti_footer_row2',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Footer Widgets (Row #2)</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti-enable-footer-widgets-row2',
                        'type'     => 'switch', 
                        'title'    => __('Status', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_number_of_footer_columns_row2',
                        'type'     => 'select',
                        'title'    => __('Footer Widgets Row #2 - Number of columns', 'enefti'), 
                        'options'  => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6'
                        ),
                        'default'  => '4',
                        'required' => array('enefti-enable-footer-widgets-row2','equals',true),
                    ),
                    array(
                        'id'             => 'footer_row_2_spacing',
                        'type'           => 'spacing',
                        'output'         => array('.footer-top .footer-row-2'),
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => esc_html__('Footer Widgets Row #2 - Padding', 'enefti'),
                        'default'            => array(
                            'padding-top'     => '0px', 
                            'padding-bottom'  => '0px', 
                            'units'          => 'px', 
                        ),
                        'required' => array('enefti-enable-footer-widgets-row2','equals',true),
                    ),

                    array(
                        'id'   => 'enefti_footer_row3',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Footer Widgets (Row #3)</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti-enable-footer-widgets-row3',
                        'type'     => 'switch', 
                        'title'    => __('Status', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_number_of_footer_columns_row3',
                        'type'     => 'select',
                        'title'    => __('Footer Widgets Row #3 - Number of columns', 'enefti'), 
                        'options'  => array(
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6'
                        ),
                        'default'  => '4',
                        'required' => array('enefti-enable-footer-widgets-row3','equals',true),
                    ),
                    array(
                        'id'             => 'footer_row_3_spacing',
                        'type'           => 'spacing',
                        'output'         => array('.footer-top .footer-row-3'),
                        'mode'           => 'padding',
                        'units'          => array('px'),
                        'units_extended' => 'false',
                        'title'          => esc_html__('Footer Widgets Row #3 - Padding', 'enefti'),
                        'default'            => array(
                            'padding-top'     => '0px', 
                            'padding-bottom'  => '0px', 
                            'units'          => 'px', 
                        ),
                        'required' => array('enefti-enable-footer-widgets-row3','equals',true),
                    ),
                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-circle-arrow-down',
                'subsection' => true,
                'title' => __('Footer Bottom (Copyright)', 'enefti'),
                'fields' => array(
                    array(
                        'id' => 'enefti_footer_text_left',
                        'type' => 'editor',
                        'title' => __('Footer Text Left', 'enefti'),
                        'default' => 'Copyright by ModelTheme. All Rights Reserved.',
                    ),
                    array(
                        'id' => 'enefti_footer_text_right',
                        'type' => 'editor',
                        'title' => __('Footer Text Right', 'enefti'),
                        'default' => 'Elite Author on ThemeForest.',
                    ),
                    array(
                        'id' => 'enefti_card_icons1',
                        'type' => 'background',
                        'title' => __('Footer card icons', 'enefti'),
                        'compiler' => 'true',
                        'background-color' => 'false',
                        'background-repeat' => 'false',
                        'background-size' => 'false',
                        'background-attachment' => 'false',
                        'background-position' => 'false',
                        'output'      => array('.card-icons1'),
                        'default' => '',
                    ),
                    array(         
                        'id'       => 'footer_bottom_background',
                        'type'     => 'background',
                        'title'    => __('Footer (bottom) - background', 'enefti'),
                        'subtitle' => __('Footer background with image or color.', 'enefti'),
                        'output'      => array('footer .footer'),
                        'default'  => array(
                            'background-color' => '#000000',
                        )
                    ),
                    array(         
                        'id'       => 'footer_bottom_color_text',
                        'type'     => 'color',
                        'title'    => __('Footer (bottom) - texts color', 'enefti'),
                        'subtitle' => __('Footer text color.', 'enefti'),
                        'default'  =>  '#FFFFFF',
                        'validate' => 'color'
                    ),
                    array(         
                        'id'       => 'footer_bottom_color_links',
                        'type'     => 'color',
                        'title'    => __('Footer (bottom) - links color', 'enefti'),
                        'subtitle' => __('Footer links color.', 'enefti'),
                        'default'  =>  '#fff',
                        'validate' => 'color'
                    ),

                )
            );

            $this->sections[] = array(
                'icon' => 'el-icon-caret-up',
                'subsection' => true,
                'title' => __('Back to Top', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_back_to_top',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Back to Top Settings</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_backtotop_status',
                        'type'     => 'switch', 
                        'title'    => esc_html__('Back to Top Button Status', 'enefti'),
                        'subtitle' => esc_html__('Enable or disable "Back to Top Button"', 'enefti'),
                        'default'  => true,
                    ),
                    array(         
                        'id'       => 'enefti_backtotop_bg_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Back to Top Button Status Backgrond', 'enefti'), 
                        'subtitle' => esc_html__('Default: #d01498', 'enefti'),
                        'default'  => array(
                            'background-color' => '#d01498',
                            'background-repeat' => 'no-repeat',
                            'background-position' => 'center center',
                            'background-image' => get_template_directory_uri().'/images/mt-to-top-arrow.svg',
                        ),
                        'required' => array( 'enefti_backtotop_status', '=', 'true' ),
                    ),

                )
            );


            # Section #4: Contact Settings

            $this->sections[] = array(
                'icon' => 'el-icon-map-marker-alt',
                'title' => __('Contact Settings', 'enefti'),
                'fields' => array(
                    array(
                        'id' => 'enefti_contact_phone',
                        'type' => 'text',
                        'title' => __('Phone Number', 'enefti'),
                        'subtitle' => __('Contact phone number displayed on the contact us page.', 'enefti'),
                        'validate_callback' => 'redux_validate_callback_function',
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_contact_email',
                        'type' => 'text',
                        'title' => __('Email', 'enefti'),
                        'subtitle' => __('Contact email displayed on the contact us page., additional info is good in here.', 'enefti'),
                        'validate' => 'email',
                        'msg' => 'custom error message',
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_work_program',
                        'type' => 'text',
                        'title' => __('Program', 'enefti'),
                        'subtitle' => __('Enter your work program', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_contact_address',
                        'type' => 'text',
                        'title' => __('Address', 'enefti'),
                        'subtitle' => __('Enter your contact address', 'enefti'),
                        'default' => ''
                    ),
                )
            );

            # Section #6: Blog Settings

            $icons = array(
            'fa fa-angellist'      => 'fa fa-angellist',
            'fa fa-area-chart'     => 'fa fa-area-chart',
            'fa fa-at'             => 'fa fa-at',
            'fa fa-bell-slash'     => 'fa fa-bell-slash',
            'fa fa-bell-slash-o'   => 'fa fa-bell-slash-o',
            'fa fa-bicycle'        => 'fa fa-bicycle',
            'fa fa-binoculars'     => 'fa fa-binoculars',
            'fa fa-birthday-cake'  => 'fa fa-birthday-cake',
            'fa fa-bus'            => 'fa fa-bus',
            'fa fa-calculator'     => 'fa fa-calculator',
            'fa fa-cc'             => 'fa fa-cc',
            'fa fa-cc-amex'        => 'fa fa-cc-amex',
            'fa fa-cc-discover'    => 'fa fa-cc-discover',
            'fa fa-cc-mastercard'  => 'fa fa-cc-mastercard',
            'fa fa-cc-paypal'      => 'fa fa-cc-paypal',
            'fa fa-cc-stripe'      => 'fa fa-cc-stripe',
            'fa fa-cc-visa'        => 'fa fa-cc-visa',
            'fa fa-copyright'      => 'fa fa-copyright',
            'fa fa-eyedropper'     => 'fa fa-eyedropper',
            'fa fa-futbol-o'       => 'fa fa-futbol-o',
            'fa fa-google-wallet'  => 'fa fa-google-wallet',
            'fa fa-ils'            => 'fa fa-ils',
            'fa fa-ioxhost'        => 'fa fa-ioxhost',
            'fa fa-lastfm'         => 'fa fa-lastfm',
            'fa fa-lastfm-square' => 'fa fa-lastfm-square',
            'fa fa-line-chart' => 'fa fa-line-chart',
            'fa fa-meanpath' => 'fa fa-meanpath',
            'fa fa-newspaper-o' => 'fa fa-newspaper-o',
            'fa fa-paint-brush' => 'fa fa-paint-brush',
            'fa fa-paypal' => 'fa fa-paypal',
            'fa fa-pie-chart' => 'fa fa-pie-chart',
            'fa fa-plug' => 'fa fa-plug',
            'fa fa-shekel' => 'fa fa-shekel',
            'fa fa-sheqel' => 'fa fa-sheqel',
            'fa fa-slideshare' => 'fa fa-slideshare',
            'fa fa-soccer-ball-o' => 'fa fa-soccer-ball-o',
            'fa fa-toggle-off' => 'fa fa-toggle-off',
            'fa fa-toggle-on' => 'fa fa-toggle-on',
            'fa fa-trash' => 'fa fa-trash',
            'fa fa-tty' => 'fa fa-tty',
            'fa fa-twitch' => 'fa fa-twitch',
            'fa fa-wifi' => 'fa fa-wifi',
            'fa fa-yelp' => 'fa fa-yelp',
            'fa fa-adjust' => 'fa fa-adjust',
            'fa fa-anchor' => 'fa fa-anchor',
            'fa fa-archive' => 'fa fa-archive',
            'fa fa-arrows' => 'fa fa-arrows',
            'fa fa-arrows-h' => 'fa fa-arrows-h',
            'fa fa-arrows-v' => 'fa fa-arrows-v',
            'fa fa-asterisk' => 'fa fa-asterisk',
            'fa fa-automobile' => 'fa fa-automobile',
            'fa fa-ban' => 'fa fa-ban',
            'fa fa-bank' => 'fa fa-bank',
            'fa fa-bar-chart' => 'fa fa-bar-chart',
            'fa fa-bar-chart-o' => 'fa fa-bar-chart-o',
            'fa fa-barcode' => 'fa fa-barcode',
            'fa fa-bars' => 'fa fa-bars',
            'fa fa-beer' => 'fa fa-beer',
            'fa fa-bell' => 'fa fa-bell',
            'fa fa-bell-o' => 'fa fa-bell-o',
            'fa fa-bolt' => 'fa fa-bolt',
            'fa fa-bomb' => 'fa fa-bomb',
            'fa fa-book' => 'fa fa-book',
            'fa fa-bookmark' => 'fa fa-bookmark',
            'fa fa-bookmark-o' => 'fa fa-bookmark-o',
            'fa fa-briefcase' => 'fa fa-briefcase',
            'fa fa-bug' => 'fa fa-bug',
            'fa fa-building' => 'fa fa-building',
            'fa fa-building-o' => 'fa fa-building-o',
            'fa fa-bullhorn' => 'fa fa-bullhorn',
            'fa fa-bullseye' => 'fa fa-bullseye',
            'fa fa-cab' => 'fa fa-cab',
            'fa fa-calendar' => 'fa fa-calendar',
            'fa fa-calendar-o' => 'fa fa-calendar-o',
            'fa fa-camera' => 'fa fa-camera',
            'fa fa-camera-retro' => 'fa fa-camera-retro',
            'fa fa-car' => 'fa fa-car',
            'fa fa-caret-square-o-down' => 'fa fa-caret-square-o-down',
            'fa fa-caret-square-o-left' => 'fa fa-caret-square-o-left',
            'fa fa-caret-square-o-right' => 'fa fa-caret-square-o-right',
            'fa fa-caret-square-o-up' => 'fa fa-caret-square-o-up',
            'fa fa-certificate' => 'fa fa-certificate',
            'fa fa-check' => 'fa fa-check',
            'fa fa-check-circle' => 'fa fa-check-circle',
            'fa fa-check-circle-o' => 'fa fa-check-circle-o',
            'fa fa-check-square' => 'fa fa-check-square',
            'fa fa-check-square-o' => 'fa fa-check-square-o',
            'fa fa-child' => 'fa fa-child',
            'fa fa-circle' => 'fa fa-circle',
            'fa fa-circle-o' => 'fa fa-circle-o',
            'fa fa-circle-o-notch' => 'fa fa-circle-o-notch',
            'fa fa-circle-thin' => 'fa fa-circle-thin',
            'fa fa-clock-o' => 'fa fa-clock-o',
            'fa fa-close' => 'fa fa-close',
            'fa fa-cloud' => 'fa fa-cloud',
            'fa fa-cloud-download' => 'fa fa-cloud-download',
            'fa fa-cloud-upload' => 'fa fa-cloud-upload',
            'fa fa-code' => 'fa fa-code',
            'fa fa-code-fork' => 'fa fa-code-fork',
            'fa fa-coffee' => 'fa fa-coffee',
            'fa fa-cog' => 'fa fa-cog',
            'fa fa-cogs' => 'fa fa-cogs',
            'fa fa-comment' => 'fa fa-comment',
            'fa fa-comment-o' => 'fa fa-comment-o',
            'fa fa-comments' => 'fa fa-comments',
            'fa fa-comments-o' => 'fa fa-comments-o',
            'fa fa-compass' => 'fa fa-compass',
            'fa fa-credit-card' => 'fa fa-credit-card',
            'fa fa-crop' => 'fa fa-crop',
            'fa fa-crosshairs' => 'fa fa-crosshairs',
            'fa fa-cube' => 'fa fa-cube',
            'fa fa-cubes' => 'fa fa-cubes',
            'fa fa-cutlery' => 'fa fa-cutlery',
            'fa fa-dashboard' => 'fa fa-dashboard',
            'fa fa-database' => 'fa fa-database',
            'fa fa-desktop' => 'fa fa-desktop',
            'fa fa-dot-circle-o' => 'fa fa-dot-circle-o',
            'fa fa-download' => 'fa fa-download',
            'fa fa-edit' => 'fa fa-edit',
            'fa fa-ellipsis-h' => 'fa fa-ellipsis-h',
            'fa fa-ellipsis-v' => 'fa fa-ellipsis-v',
            'fa fa-envelope' => 'fa fa-envelope',
            'fa fa-envelope-o' => 'fa fa-envelope-o',
            'fa fa-envelope-square' => 'fa fa-envelope-square',
            'fa fa-eraser' => 'fa fa-eraser',
            'fa fa-exchange' => 'fa fa-exchange',
            'fa fa-exclamation' => 'fa fa-exclamation',
            'fa fa-exclamation-circle' => 'fa fa-exclamation-circle',
            'fa fa-exclamation-triangle' => 'fa fa-exclamation-triangle',
            'fa fa-external-link' => 'fa fa-external-link',
            'fa fa-external-link-square' => 'fa fa-external-link-square',
            'fa fa-eye' => 'fa fa-eye',
            'fa fa-eye-slash' => 'fa fa-eye-slash',
            'fa fa-fax' => 'fa fa-fax',
            'fa fa-female' => 'fa fa-female',
            'fa fa-fighter-jet' => 'fa fa-fighter-jet',
            'fa fa-file-archive-o' => 'fa fa-file-archive-o',
            'fa fa-file-audio-o' => 'fa fa-file-audio-o',
            'fa fa-file-code-o' => 'fa fa-file-code-o',
            'fa fa-file-excel-o' => 'fa fa-file-excel-o',
            'fa fa-file-image-o' => 'fa fa-file-image-o',
            'fa fa-file-movie-o' => 'fa fa-file-movie-o',
            'fa fa-file-pdf-o' => 'fa fa-file-pdf-o',
            'fa fa-file-photo-o' => 'fa fa-file-photo-o',
            'fa fa-file-picture-o' => 'fa fa-file-picture-o',
            'fa fa-file-powerpoint-o' => 'fa fa-file-powerpoint-o',
            'fa fa-file-sound-o' => 'fa fa-file-sound-o',
            'fa fa-file-video-o' => 'fa fa-file-video-o',
            'fa fa-file-word-o' => 'fa fa-file-word-o',
            'fa fa-file-zip-o' => 'fa fa-file-zip-o',
            'fa fa-film' => 'fa fa-film',
            'fa fa-filter' => 'fa fa-filter',
            'fa fa-fire' => 'fa fa-fire',
            'fa fa-fire-extinguisher' => 'fa fa-fire-extinguisher',
            'fa fa-flag' => 'fa fa-flag',
            'fa fa-flag-checkered' => 'fa fa-flag-checkered',
            'fa fa-flag-o' => 'fa fa-flag-o',
            'fa fa-flash' => 'fa fa-flash',
            'fa fa-flask' => 'fa fa-flask',
            'fa fa-folder' => 'fa fa-folder',
            'fa fa-folder-o' => 'fa fa-folder-o',
            'fa fa-folder-open' => 'fa fa-folder-open',
            'fa fa-folder-open-o' => 'fa fa-folder-open-o',
            'fa fa-frown-o' => 'fa fa-frown-o',
            'fa fa-gamepad' => 'fa fa-gamepad',
            'fa fa-gavel' => 'fa fa-gavel',
            'fa fa-gear' => 'fa fa-gear',
            'fa fa-gears' => 'fa fa-gears',
            'fa fa-gift' => 'fa fa-gift',
            'fa fa-glass' => 'fa fa-glass',
            'fa fa-globe' => 'fa fa-globe',
            'fa fa-graduation-cap' => 'fa fa-graduation-cap',
            'fa fa-group' => 'fa fa-group',
            'fa fa-hdd-o' => 'fa fa-hdd-o',
            'fa fa-headphones' => 'fa fa-headphones',
            'fa fa-heart' => 'fa fa-heart',
            'fa fa-heart-o' => 'fa fa-heart-o',
            'fa fa-history' => 'fa fa-history',
            'fa fa-home' => 'fa fa-home',
            'fa fa-image' => 'fa fa-image',
            'fa fa-inbox' => 'fa fa-inbox',
            'fa fa-info' => 'fa fa-info',
            'fa fa-info-circle' => 'fa fa-info-circle',
            'fa fa-institution' => 'fa fa-institution',
            'fa fa-key' => 'fa fa-key',
            'fa fa-keyboard-o' => 'fa fa-keyboard-o',
            'fa fa-language' => 'fa fa-language',
            'fa fa-laptop' => 'fa fa-laptop',
            'fa fa-leaf' => 'fa fa-leaf',
            'fa fa-legal' => 'fa fa-legal',
            'fa fa-lemon-o' => 'fa fa-lemon-o',
            'fa fa-level-down' => 'fa fa-level-down',
            'fa fa-level-up' => 'fa fa-level-up',
            'fa fa-life-bouy' => 'fa fa-life-bouy',
            'fa fa-life-buoy' => 'fa fa-life-buoy',
            'fa fa-life-ring' => 'fa fa-life-ring',
            'fa fa-life-saver' => 'fa fa-life-saver',
            'fa fa-lightbulb-o' => 'fa fa-lightbulb-o',
            'fa fa-location-arrow' => 'fa fa-location-arrow',
            'fa fa-lock' => 'fa fa-lock',
            'fa fa-magic' => 'fa fa-magic',
            'fa fa-magnet' => 'fa fa-magnet',
            'fa fa-mail-forward' => 'fa fa-mail-forward',
            'fa fa-mail-reply' => 'fa fa-mail-reply',
            'fa fa-mail-reply-all' => 'fa fa-mail-reply-all',
            'fa fa-male' => 'fa fa-male',
            'fa fa-map-marker' => 'fa fa-map-marker',
            'fa fa-meh-o' => 'fa fa-meh-o',
            'fa fa-microphone' => 'fa fa-microphone',
            'fa fa-microphone-slash' => 'fa fa-microphone-slash',
            'fa fa-minus' => 'fa fa-minus',
            'fa fa-minus-circle' => 'fa fa-minus-circle',
            'fa fa-minus-square' => 'fa fa-minus-square',
            'fa fa-minus-square-o' => 'fa fa-minus-square-o',
            'fa fa-mobile' => 'fa fa-mobile',
            'fa fa-mobile-phone' => 'fa fa-mobile-phone',
            'fa fa-money' => 'fa fa-money',
            'fa fa-moon-o' => 'fa fa-moon-o',
            'fa fa-mortar-board' => 'fa fa-mortar-board',
            'fa fa-music' => 'fa fa-music',
            'fa fa-navicon' => 'fa fa-navicon',
            'fa fa-paper-plane' => 'fa fa-paper-plane',
            'fa fa-paper-plane-o' => 'fa fa-paper-plane-o',
            'fa fa-paw' => 'fa fa-paw',
            'fa fa-pencil' => 'fa fa-pencil',
            'fa fa-pencil-square' => 'fa fa-pencil-square',
            'fa fa-pencil-square-o' => 'fa fa-pencil-square-o',
            'fa fa-phone' => 'fa fa-phone',
            'fa fa-phone-square' => 'fa fa-phone-square',
            'fa fa-photo' => 'fa fa-photo',
            'fa fa-picture-o' => 'fa fa-picture-o',
            'fa fa-plane' => 'fa fa-plane',
            'fa fa-plus' => 'fa fa-plus',
            'fa fa-plus-circle' => 'fa fa-plus-circle',
            'fa fa-plus-square' => 'fa fa-plus-square',
            'fa fa-plus-square-o' => 'fa fa-plus-square-o',
            'fa fa-power-off' => 'fa fa-power-off',
            'fa fa-print' => 'fa fa-print',
            'fa fa-puzzle-piece' => 'fa fa-puzzle-piece',
            'fa fa-qrcode' => 'fa fa-qrcode',
            'fa fa-question' => 'fa fa-question',
            'fa fa-question-circle' => 'fa fa-question-circle',
            'fa fa-quote-left' => 'fa fa-quote-left',
            'fa fa-quote-right' => 'fa fa-quote-right',
            'fa fa-random' => 'fa fa-random',
            'fa fa-recycle' => 'fa fa-recycle',
            'fa fa-refresh' => 'fa fa-refresh',
            'fa fa-remove' => 'fa fa-remove',
            'fa fa-reorder' => 'fa fa-reorder',
            'fa fa-reply' => 'fa fa-reply',
            'fa fa-reply-all' => 'fa fa-reply-all',
            'fa fa-retweet' => 'fa fa-retweet',
            'fa fa-road' => 'fa fa-road',
            'fa fa-rocket' => 'fa fa-rocket',
            'fa fa-rss' => 'fa fa-rss',
            'fa fa-rss-square' => 'fa fa-rss-square',
            'fa fa-search' => 'fa fa-search',
            'fa fa-search-minus' => 'fa fa-search-minus',
            'fa fa-search-plus' => 'fa fa-search-plus',
            'fa fa-send' => 'fa fa-send',
            'fa fa-send-o' => 'fa fa-send-o',
            'fa fa-share' => 'fa fa-share',
            'fa fa-share-alt' => 'fa fa-share-alt',
            'fa fa-share-alt-square' => 'fa fa-share-alt-square',
            'fa fa-share-square' => 'fa fa-share-square',
            'fa fa-share-square-o' => 'fa fa-share-square-o',
            'fa fa-shield' => 'fa fa-shield',
            'fa fa-shopping-cart' => 'fa fa-shopping-cart',
            'fa fa-sign-in' => 'fa fa-sign-in',
            'fa fa-sign-out' => 'fa fa-sign-out',
            'fa fa-signal' => 'fa fa-signal',
            'fa fa-sitemap' => 'fa fa-sitemap',
            'fa fa-sliders' => 'fa fa-sliders',
            'fa fa-smile-o' => 'fa fa-smile-o',
            'fa fa-sort' => 'fa fa-sort',
            'fa fa-sort-alpha-asc' => 'fa fa-sort-alpha-asc',
            'fa fa-sort-alpha-desc' => 'fa fa-sort-alpha-desc',
            'fa fa-sort-amount-asc' => 'fa fa-sort-amount-asc',
            'fa fa-sort-amount-desc' => 'fa fa-sort-amount-desc',
            'fa fa-sort-asc' => 'fa fa-sort-asc',
            'fa fa-sort-desc' => 'fa fa-sort-desc',
            'fa fa-sort-down' => 'fa fa-sort-down',
            'fa fa-sort-numeric-asc' => 'fa fa-sort-numeric-asc',
            'fa fa-sort-numeric-desc' => 'fa fa-sort-numeric-desc',
            'fa fa-sort-up' => 'fa fa-sort-up',
            'fa fa-space-shuttle' => 'fa fa-space-shuttle',
            'fa fa-spinner' => 'fa fa-spinner',
            'fa fa-spoon' => 'fa fa-spoon',
            'fa fa-square' => 'fa fa-square',
            'fa fa-square-o' => 'fa fa-square-o',
            'fa fa-star' => 'fa fa-star',
            'fa fa-star-half' => 'fa fa-star-half',
            'fa fa-star-half-empty' => 'fa fa-star-half-empty',
            'fa fa-star-half-full' => 'fa fa-star-half-full',
            'fa fa-star-half-o' => 'fa fa-star-half-o',
            'fa fa-star-o' => 'fa fa-star-o',
            'fa fa-suitcase' => 'fa fa-suitcase',
            'fa fa-sun-o' => 'fa fa-sun-o',
            'fa fa-support' => 'fa fa-support',
            'fa fa-tablet' => 'fa fa-tablet',
            'fa fa-tachometer' => 'fa fa-tachometer',
            'fa fa-tag' => 'fa fa-tag',
            'fa fa-tags' => 'fa fa-tags',
            'fa fa-tasks' => 'fa fa-tasks',
            'fa fa-taxi' => 'fa fa-taxi',
            'fa fa-terminal' => 'fa fa-terminal',
            'fa fa-thumb-tack' => 'fa fa-thumb-tack',
            'fa fa-thumbs-down' => 'fa fa-thumbs-down',
            'fa fa-thumbs-o-down' => 'fa fa-thumbs-o-down',
            'fa fa-thumbs-o-up' => 'fa fa-thumbs-o-up',
            'fa fa-thumbs-up' => 'fa fa-thumbs-up',
            'fa fa-ticket' => 'fa fa-ticket',
            'fa fa-times' => 'fa fa-times',
            'fa fa-times-circle' => 'fa fa-times-circle',
            'fa fa-times-circle-o' => 'fa fa-times-circle-o',
            'fa fa-tint' => 'fa fa-tint',
            'fa fa-toggle-down' => 'fa fa-toggle-down',
            'fa fa-toggle-left' => 'fa fa-toggle-left',
            'fa fa-toggle-right' => 'fa fa-toggle-right',
            'fa fa-toggle-up' => 'fa fa-toggle-up',
            'fa fa-trash-o' => 'fa fa-trash-o',
            'fa fa-tree' => 'fa fa-tree',
            'fa fa-trophy' => 'fa fa-trophy',
            'fa fa-truck' => 'fa fa-truck',
            'fa fa-umbrella' => 'fa fa-umbrella',
            'fa fa-university' => 'fa fa-university',
            'fa fa-unlock' => 'fa fa-unlock',
            'fa fa-unlock-alt' => 'fa fa-unlock-alt',
            'fa fa-unsorted' => 'fa fa-unsorted',
            'fa fa-upload' => 'fa fa-upload',
            'fa fa-user' => 'fa fa-user',
            'fa fa-users' => 'fa fa-users',
            'fa fa-video-camera' => 'fa fa-video-camera',
            'fa fa-volume-down' => 'fa fa-volume-down',
            'fa fa-volume-off' => 'fa fa-volume-off',
            'fa fa-volume-up' => 'fa fa-volume-up',
            'fa fa-warning' => 'fa fa-warning',
            'fa fa-wheelchair' => 'fa fa-wheelchair',
            'fa fa-wrench' => 'fa fa-wrench',
            'fa fa-file' => 'fa fa-file',
            'fa fa-file-o' => 'fa fa-file-o',
            'fa fa-file-text' => 'fa fa-file-text',
            'fa fa-file-text-o' => 'fa fa-file-text-o',
            'fa fa-bitcoin' => 'fa fa-bitcoin',
            'fa fa-btc' => 'fa fa-btc',
            'fa fa-cny' => 'fa fa-cny',
            'fa fa-dollar' => 'fa fa-dollar',
            'fa fa-eur' => 'fa fa-eur',
            'fa fa-euro' => 'fa fa-euro',
            'fa fa-gbp' => 'fa fa-gbp',
            'fa fa-inr' => 'fa fa-inr',
            'fa fa-jpy' => 'fa fa-jpy',
            'fa fa-krw' => 'fa fa-krw',
            'fa fa-rmb' => 'fa fa-rmb',
            'fa fa-rouble' => 'fa fa-rouble',
            'fa fa-rub' => 'fa fa-rub',
            'fa fa-ruble' => 'fa fa-ruble',
            'fa fa-rupee' => 'fa fa-rupee',
            'fa fa-try' => 'fa fa-try',
            'fa fa-turkish-lira' => 'fa fa-turkish-lira',
            'fa fa-usd' => 'fa fa-usd',
            'fa fa-won' => 'fa fa-won',
            'fa fa-yen' => 'fa fa-yen',
            'fa fa-align-center' => ' fa fa-align-center',
            'fa fa-align-justify' => 'fa fa-align-justify',
            'fa fa-align-left' => 'fa fa-align-left',
            'fa fa-align-right' => 'fa fa-align-right',
            'fa fa-bold' => 'fa fa-bold',
            'fa fa-chain' => 'fa fa-chain',
            'fa fa-chain-broken' => 'fa fa-chain-broken',
            'fa fa-clipboard' => 'fa fa-clipboard',
            'fa fa-columns' => 'fa fa-columns',
            'fa fa-copy' => 'fa fa-copy',
            'fa fa-cut' => 'fa fa-cut',
            'fa fa-dedent' => 'fa fa-dedent',
            'fa fa-files-o' => 'fa fa-files-o',
            'fa fa-floppy-o' => 'fa fa-floppy-o',
            'fa fa-font' => 'fa fa-font',
            'fa fa-header' => 'fa fa-header',
            'fa fa-indent' => 'fa fa-indent',
            'fa fa-italic' => 'fa fa-italic',
            'fa fa-link' => 'fa fa-link',
            'fa fa-list' => 'fa fa-list',
            'fa fa-list-alt' => 'fa fa-list-alt',
            'fa fa-list-ol' => 'fa fa-list-ol',
            'fa fa-list-ul' => 'fa fa-list-ul',
            'fa fa-outdent' => 'fa fa-outdent',
            'fa fa-paperclip' => 'fa fa-paperclip',
            'fa fa-paragraph' => 'fa fa-paragraph',
            'fa fa-paste' => 'fa fa-paste',
            'fa fa-repeat' => 'fa fa-repeat',
            'fa fa-rotate-left' => 'fa fa-rotate-left',
            'fa fa-rotate-right' => 'fa fa-rotate-right',
            'fa fa-save' => 'fa fa-save',
            'fa fa-scissors' => 'fa fa-scissors',
            'fa fa-strikethrough' => 'fa fa-strikethrough',
            'fa fa-subscript' => 'fa fa-subscript',
            'fa fa-superscript' => 'fa fa-superscript',
            'fa fa-table' => 'fa fa-table',
            'fa fa-text-height' => 'fa fa-text-height',
            'fa fa-text-width' => 'fa fa-text-width',
            'fa fa-th' => 'fa fa-th',
            'fa fa-th-large' => 'fa fa-th-large',
            'fa fa-th-list' => 'fa fa-th-list',
            'fa fa-underline' => 'fa fa-underline',
            'fa fa-undo' => 'fa fa-undo',
            'fa fa-unlink' => 'fa fa-unlink',
            'fa fa-angle-double-down' => ' fa fa-angle-double-down',
            'fa fa-angle-double-left' => 'fa fa-angle-double-left',
            'fa fa-angle-double-right' => 'fa fa-angle-double-right',
            'fa fa-angle-double-up' => 'fa fa-angle-double-up',
            'fa fa-angle-down' => 'fa fa-angle-down',
            'fa fa-angle-left' => 'fa fa-angle-left',
            'fa fa-angle-right' => 'fa fa-angle-right',
            'fa fa-angle-up' => 'fa fa-angle-up',
            'fa fa-arrow-circle-down' => 'fa fa-arrow-circle-down',
            'fa fa-arrow-circle-left' => 'fa fa-arrow-circle-left',
            'fa fa-arrow-circle-o-down' => 'fa fa-arrow-circle-o-down',
            'fa fa-arrow-circle-o-left' => 'fa fa-arrow-circle-o-left',
            'fa fa-arrow-circle-o-right' => 'fa fa-arrow-circle-o-right',
            'fa fa-arrow-circle-o-up' => 'fa fa-arrow-circle-o-up',
            'fa fa-arrow-circle-right' => 'fa fa-arrow-circle-right',
            'fa fa-arrow-circle-up' => 'fa fa-arrow-circle-up',
            'fa fa-arrow-down' => 'fa fa-arrow-down',
            'fa fa-arrow-left' => 'fa fa-arrow-left',
            'fa fa-arrow-right' => 'fa fa-arrow-right',
            'fa fa-arrow-up' => 'fa fa-arrow-up',
            'fa fa-arrows-alt' => 'fa fa-arrows-alt',
            'fa fa-caret-down' => 'fa fa-caret-down',
            'fa fa-caret-left' => 'fa fa-caret-left',
            'fa fa-caret-right' => 'fa fa-caret-right',
            'fa fa-caret-up' => 'fa fa-caret-up',
            'fa fa-chevron-circle-down' => 'fa fa-chevron-circle-down',
            'fa fa-chevron-circle-left' => 'fa fa-chevron-circle-left',
            'fa fa-chevron-circle-right' => 'fa fa-chevron-circle-right',
            'fa fa-chevron-circle-up' => 'fa fa-chevron-circle-up',
            'fa fa-chevron-down' => 'fa fa-chevron-down',
            'fa fa-chevron-left' => 'fa fa-chevron-left',
            'fa fa-chevron-right' => 'fa fa-chevron-right',
            'fa fa-chevron-up' => 'fa fa-chevron-up',
            'fa fa-hand-o-down' => 'fa fa-hand-o-down',
            'fa fa-hand-o-left' => 'fa fa-hand-o-left',
            'fa fa-hand-o-right' => 'fa fa-hand-o-right',
            'fa fa-hand-o-up' => 'fa fa-hand-o-up',
            'fa fa-long-arrow-down' => 'fa fa-long-arrow-down',
            'fa fa-long-arrow-left' => 'fa fa-long-arrow-left',
            'fa fa-long-arrow-right' => 'fa fa-long-arrow-right',
            'fa fa-long-arrow-up' => 'fa fa-long-arrow-up',
            'fa fa-backward' => 'fa fa-backward',
            'fa fa-compress' => 'fa fa-compress',
            'fa fa-eject' => 'fa fa-eject',
            'fa fa-expand' => 'fa fa-expand',
            'fa fa-fast-backward' => 'fa fa-fast-backward',
            'fa fa-fast-forward' => 'fa fa-fast-forward',
            'fa fa-forward' => 'fa fa-forward',
            'fa fa-pause' => 'fa fa-pause',
            'fa fa-play' => 'fa fa-play',
            'fa fa-play-circle' => 'fa fa-play-circle',
            'fa fa-play-circle-o' => 'fa fa-play-circle-o',
            'fa fa-step-backward' => 'fa fa-step-backward',
            'fa fa-step-forward' => 'fa fa-step-forward',
            'fa fa-stop' => 'fa fa-stop',
            'fa fa-youtube-play' => 'fa fa-youtube-play'
            );

            $this->sections[] = array(
                'icon' => 'el-icon-comment',
                'title' => __('Blog Settings', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_divider_blog_archive_layout',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Blog Archive Layout</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_blog_layout',
                        'type'     => 'image_select',
                        'title'    => __( 'Blog List Layout', 'enefti' ),
                        'subtitle' => __( 'Select Blog List layout.', 'enefti' ),
                        'options'  => array(
                            'enefti_blog_left_sidebar' => array(
                                'alt' => '2 Columns - Left sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-left.jpg'
                            ),
                            'enefti_blog_fullwidth' => array(
                                'alt' => '1 Column - Full width',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-no.jpg'
                            ),
                            'enefti_blog_right_sidebar' => array(
                                'alt' => '2 Columns - Right sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-right.jpg'
                            )
                        ),
                        'default'  => 'enefti_blog_right_sidebar'
                    ),
                    array(
                        'id'       => 'enefti_blog_layout_sidebar',
                        'type'     => 'select',
                        'data'     => 'sidebars',
                        'title'    => __( 'Blog List Sidebar', 'enefti' ),
                        'subtitle' => __( 'Select Blog List Sidebar.', 'enefti' ),
                        'default'   => 'sidebar-1',
                        'required' => array('enefti_blog_layout', '!=', 'enefti_blog_fullwidth'),
                    ),
                    array(
                        'id'   => 'enefti_divider_blog_single_layout',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Blog Single Article Layout</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_single_blog_layout',
                        'type'     => 'image_select',
                        'title'    => __( 'Single Blog Layout', 'enefti' ),
                        'subtitle' => __( 'Select Single Blog Layout.', 'enefti' ),
                        'options'  => array(
                            'enefti_blog_left_sidebar' => array(
                                'alt' => '2 Columns - Left sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-left.jpg'
                            ),
                            'enefti_blog_fullwidth' => array(
                                'alt' => '1 Column - Full width',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-no.jpg'
                            ),
                            'enefti_blog_right_sidebar' => array(
                                'alt' => '2 Columns - Right sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-right.jpg'
                            )
                        ),
                        'default'  => 'enefti_blog_right_sidebar',
                        ),
                    array(
                        'id'       => 'enefti_single_blog_sidebar',
                        'type'     => 'select',
                        'data'     => 'sidebars',
                        'title'    => __( 'Single Blog Sidebar', 'enefti' ),
                        'subtitle' => __( 'Select Single Blog Sidebar.', 'enefti' ),
                        'default'   => 'sidebar-1',
                        'required' => array('enefti_single_blog_layout', '!=', 'enefti_blog_fullwidth'),
                    ),

                    array(
                        'id'   => 'enefti_divider_blog_single_tyography',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Blog Single Article Typography</h3>', 'enefti' )
                    ),
                    array(
                        'id'          => 'enefti-blog-post-typography',
                        'type'        => 'typography', 
                        'title'       => esc_html__('Blog Post Font family', 'enefti'),
                        'google'      => true, 
                        'font-backup' => true,
                        'color'       => true,
                        'text-align'  => false,
                        'letter-spacing'  => false,
                        'line-height'  => true,
                        'font-weight'  => true,
                        'font-size'   => true,
                        'font-style'  => false,
                        'subsets'     => false,
                        'output'      => array('p'),
                        'units'       =>'px',
                        'default'     => array(
                            'font-family' => 'Raleway', 
                            'font-size' => '17px', 
                            'line-height' => '25px', 
                            'font-weight' => '400', 
                            'color' => '#565656', 
                            'google'      => true
                        ),
                    ),
                    array(
                        'id'       => 'post_featured_image',
                        'type'     => 'switch', 
                        'title'    => __('Enable/disable featured image for single post.', 'enefti'),
                        'subtitle' => __('Show or Hide the featured image from blog post page.".', 'enefti'),
                        'default'  => true,
                    ),
                )
            );


            # Tab: Shop Settings
            $this->sections[] = array(
                'icon' => 'el-icon-shopping-cart-sign',
                'title' => __('Shop Settings', 'enefti'),
            );
            // Subtab: Shop Archives
            $this->sections[] = array(
                'subsection' => true,
                'icon' => 'el-icon-th',
                'title' => __('Shop Archives', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_shop_archive',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Shop Archives</h3>', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_shop_layout',
                        'type'     => 'image_select',
                        'title'    => __( 'Shop List Products Layout', 'enefti' ),
                        'subtitle' => __( 'Select Shop List Products layout.', 'enefti' ),
                        'options'  => array(
                            'enefti_shop_left_sidebar' => array(
                                'alt' => '2 Columns - Left sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-left.jpg'
                            ),
                            'enefti_shop_fullwidth' => array(
                                'alt' => '1 Column - Full width',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-no.jpg'
                            ),
                            'enefti_shop_right_sidebar' => array(
                                'alt' => '2 Columns - Right sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-right.jpg'
                            )
                        ),
                        'default'  => 'enefti_shop_left_sidebar'
                    ),

                    array(
                        'id'       => 'enefti_shop_grid_list_switcher',
                        'type'     => 'select', 
                        'title'    => __('Grid / List default', 'enefti'),
                        'subtitle' => __('Choose which format products should display in by default.', 'enefti'),
                        'options'   => array(
                            'grid'   => __( 'Grid', 'enefti' ),
                            'list'   => __( 'List', 'enefti' ),
                        ),
                        'default'   => 'grid',
                    ),

                    array(
                        'id'       => 'enefti_shop_layout_sidebar',
                        'type'     => 'select',
                        'data'     => 'sidebars',
                        'title'    => __( 'Shop List Sidebar', 'enefti' ),
                        'subtitle' => __( 'Select Shop List Sidebar.', 'enefti' ),
                        'default'   => 'woocommerce',
                        'required' => array('enefti_shop_layout', '!=', 'enefti_shop_fullwidth'),
                    ),
                    array(
                        'id'        => 'enefti-shop-columns',
                        'type'      => 'select',
                        'title'     => __('Number of shop columns', 'enefti'),
                        'subtitle'  => __('Number of products per column to show on shop list template.', 'enefti'),
                        'options'   => array(
                            '2'   => '2 columns',
                            '3'   => '3 columns',
                            '4'   => '4 columns'
                        ),
                        'default'   => '3',
                    ),
                    array(
                        'id'       => 'enefti-archive-secondary-image-on-hover',
                        'type'     => 'switch', 
                        'title'    => __('Secondary Image on Hover', 'enefti'),
                        'subtitle' => __('Enable or disable the Secondary Image on Hover(The 2nd image is actually the first image from the media gallery of the product)', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_archive_background',
                        'type'     => 'background',
                        'title'    => esc_html__('Archive Page Background', 'enefti'), 
                        'subtitle' => esc_html__('Default: #ffffff', 'enefti'),
                        'default'  => '#ffffff',
                        'validate' => 'background',
                        'output' => array(
                            'color' => 'body.archive.woocommerce',
                        )
                    ),
                )
            );

            // Subtab: Product Single
            $this->sections[] = array(
                'subsection' => true,
                'icon' => 'el-icon-shopping-cart-sign',
                'title' => __('Product Single', 'enefti'),
                'fields' => array(
                    array(
                        'id'   => 'enefti_shop_single_product',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Product Page</h3>', 'enefti' )
                    ),
                    array(
                        'id'        => 'enefti_layout_version',
                        'type'      => 'select',
                        'title'     => __('Select Single Product layout', 'enefti'),
                        'subtitle'  => __('Unique layout to show on single product template.', 'enefti'),
                        'options'   => array(
                            ''   => 'Override default',
                            'main'      => 'Style 1'
                        ),
                        'default'   => 'main'
                    ),
                    array(
                        'id'       => 'enefti-enable-general-info',
                        'type'     => 'switch', 
                        'title'    => __('General Information', 'enefti'),
                        'subtitle' => __('Enable or disable General Information on single product', 'enefti'),
                        'required' => array('enefti_layout_version', '=', 'third'),
                        'default'  => false,
                    ),
                    array(
                        'id' => 'enefti-enable-general-img1',
                        'type' => 'media',
                        'url' => true,
                        'title' => esc_html__('First Icon', 'enefti') ,
                        'compiler' => 'true',
                        'required' => array('enefti-enable-general-info', '=', true),
                    ),
                    array(
                        'id' => 'enefti-enable-general-desc1',
                        'type' => 'editor',
                        'title' => esc_html__('First Block', 'enefti') ,
                        'default' => '<span>'.esc_html__('Lorem ipsum dolor sit amet, consectetur adipisc elit. Duis sollicitudin diam in diamui varius, sed anim.', 'enefti').'</span>',
                        'required' => array('enefti-enable-general-info', '=', true),
                    ),
                    array(
                        'id' => 'enefti-enable-general-img2',
                        'type' => 'media',
                        'url' => true,
                        'title' => esc_html__('Second Icon', 'enefti') ,
                        'compiler' => 'true',
                        'required' => array('enefti-enable-general-info', '=', true),
                    ),
                    array(
                        'id' => 'enefti-enable-general-desc2',
                        'type' => 'editor',
                        'title' => esc_html__('Second Block', 'enefti') ,
                        'default' => '<span>'.esc_html__('Lorem ipsum dolor sit amet, consectetur adipisc elit. Duis sollicitudin diam in diamui varius, sed anim.', 'enefti').'</span>',
                        'required' => array('enefti-enable-general-info', '=', true),
                    ),
                    array(
                        'id'       => 'enefti_project_color',
                        'type'     => 'background',
                        'title'    => esc_html__('Project Breadcrumbs background', 'enefti'), 
                        'subtitle' => esc_html__('Available only for Single Project Page', 'enefti'),
                        'default'  => '#171E2C',
                        'validate' => 'background',
                        'required' => array( 'enefti_layout_version', '=', 'project' ),
                        'output' => array(
                            'color' => '.single-project .enefti-breadcrumbs,
                                        .single-project .enefti-breadcrumbs-b',
                        )
                    ),
                    array(
                        'id'       => 'enefti_project_color_text',
                        'type'     => 'color',
                        'title'    => esc_html__('Project Breadcrumbs color text', 'enefti'), 
                        'subtitle' => esc_html__('Available only for Single Project Page', 'enefti'),
                        'default'  => '#fff',
                        'validate' => 'color',
                        'required' => array( 'enefti_layout_version', '=', 'project' ),
                        'output' => array(
                            'color' => '.single-project .enefti-breadcrumbs h1, 
                                        .single-project .enefti-breadcrumbs .mt-view-count,
                                        .single-project .description p,
                                        .single-project .project-tabs li a',
                        )
                    ),
                    array(
                        'id'       => 'enefti_project_image',
                        'type'     => 'switch', 
                        'title'    => __('Enable Featured Image?', 'enefti'),
                        'subtitle' => __('Enable or disable Featured Image on single product', 'enefti'),
                        'required' => array( 'enefti_layout_version', '=', 'project' ),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_view_counter',
                        'type'     => 'switch', 
                        'title'    => __('Enable Views Counter?', 'enefti'),
                        'subtitle' => __('Enable or disable Views Counter on single product', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'       => 'enefti_single_product_layout',
                        'type'     => 'image_select',
                        'title'    => __( 'Single Product Layout', 'enefti' ),
                        'subtitle' => __( 'Select Single Product Layout.', 'enefti' ),
                        'options'  => array(
                            'enefti_shop_left_sidebar' => array(
                                'alt' => '2 Columns - Left sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-left.jpg'
                            ),
                            'enefti_shop_fullwidth' => array(
                                'alt' => '1 Column - Full width',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-no.jpg'
                            ),
                            'enefti_shop_right_sidebar' => array(
                                'alt' => '2 Columns - Right sidebar',
                                'img' => get_template_directory_uri().'/redux-framework/assets/sidebar-right.jpg'
                            )
                        ),
                        'default'  => 'enefti_shop_fullwidth'
                    ),
                    array(
                        'id'       => 'enefti_single_shop_sidebar',
                        'type'     => 'select',
                        'data'     => 'sidebars',
                        'title'    => __( 'Shop Single Product Sidebar', 'enefti' ),
                        'subtitle' => __( 'Select Shop List Sidebar.', 'enefti' ),
                        'default'   => 'sidebar-1',
                        'required' => array('enefti_single_product_layout', '!=', 'enefti_shop_fullwidth'),
                    ),
                    array(
                        'id'       => 'enefti-enable-related-products',
                        'type'     => 'switch', 
                        'title'    => __('Related Products', 'enefti'),
                        'subtitle' => __('Enable or disable related products on single product', 'enefti'),
                        'default'  => true,
                    ),
                    array(
                        'id'        => 'enefti-related-products-number',
                        'type'      => 'select',
                        'title'     => __('Number of related products', 'enefti'),
                        'subtitle'  => __('Number of related products to show on single product template.', 'enefti'),
                        'options'   => array(
                            '4'   => '4',
                            '8'   => '8',
                            '12'   => '12'
                        ),
                        'default'   => '4',
                        'required' => array('enefti-enable-related-products', '=', true),
                    ),
                )
            );

            # Section: 404 Page Settings
            $this->sections[] = array(
                'icon' => 'el-icon-error',
                'title' => __('404 Page Settings', 'enefti'),
                'fields' => array(
                    array(
                        'id' => 'img_404',
                        'type' => 'media',
                        'url' => true,
                        'title' => __('Image for 404 Not found page', 'enefti'),
                        'compiler' => 'true',
                        'default' => array('url' => get_template_directory_uri().'/images/404.png'),
                    )
                )
            );

            # Section: Popup Settings
            $this->sections[] = array(
                'icon' => 'fa fa-angle-double-up',
                'title' => __('Popup Settings', 'enefti'),
                'fields' => array(
                    array(
                        'id'       => 'enefti-enable-popup',
                        'type'     => 'switch', 
                        'title'    => __('Popup', 'enefti'),
                        'subtitle' => __('Enable or disable popup', 'enefti'),
                        'default'  => false,
                    ),
                    array(
                        'id'   => 'enefti_popup_design',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Popup Design</h3>', 'enefti' ),
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id' => 'enefti-enable-popup-img',
                        'type' => 'media',
                        'url' => true,
                        'title'    => __('Popup Image', 'enefti'),
                        'subtitle' => __('Set your popup image', 'enefti'),
                        'compiler' => 'true',
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id' => 'enefti-enable-popup-company',
                        'type' => 'media',
                        'url' => true,
                        'title'    => __('Your Company Logo', 'enefti'),
                        'subtitle' => __('Set your company logo', 'enefti'),
                        'compiler' => 'true',
                        'default' => array('url' => get_template_directory_uri().'/images/logo-enefti.png'),
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id' => 'enefti-enable-popup-desc',
                        'type' => 'text',
                        'title' => __('Subtitle Description', 'enefti'),
                        'subtitle' => __('Write a few words as description', 'enefti'),
                        'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sit amet sagittis sem, at sollicitudin lectus.',
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id' => 'enefti-enable-popup-form',
                        'type' => 'editor',
                        'title' => __('Custom Form Shortcode', 'enefti'),
                        'subtitle' => __('Write a few words as description', 'enefti'),
                         'args'   => array(
                            'teeny'            => true,
                            'textarea_rows'    => 10
                        ),
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id'       => 'enefti-enable-popup-additional',
                        'type'     => 'switch', 
                        'title'    => __('Disable Login message?', 'enefti'),
                        'subtitle' => __('Enable or disable Login message.', 'enefti'),
                        'default'  => false,
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id'   => 'enefti_popup_settings',
                        'type' => 'info',
                        'class' => 'enefti_divider',
                        'desc' => __( '<h3>Popup Settings</h3>', 'enefti' ),
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id'        => 'enefti-enable-popup-expire-date',
                        'type'      => 'select',
                        'title'     => __('Expiring Cookie', 'enefti'),
                        'subtitle'  => __('Select the days for when the cookies to expire.', 'enefti'),
                        'options'   => array(
                                '1'    => 'One day',
                                '3'    => 'Three days',
                                '7'    => 'Seven days',
                                '30'   => 'One Month',
                                '3000' => 'Be Remembered'
                            ),
                        'default'   => '1',
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                    array(
                        'id'        => 'enefti-enable-popup-show-time',
                        'type'      => 'select',
                        'title'     => __('Show Popup', 'enefti'),
                        'subtitle'  => __('Select a specific time to show the popup.', 'enefti'),
                        'options'   => array(
                                '5000'     => '5 seconds',
                                '10000'    => '10 seconds',
                                '20000'    => '20 seconds'
                            ),
                        'default'   => '5000',
                        'required' => array( 'enefti-enable-popup', '=', true ),
                    ),
                )
            );


            # Tab: Shop Settings
            $this->sections[] = array(
                'icon' => 'el-icon-myspace',
                'title' => __('Social Media', 'enefti'),
            );
            # Section: Social Profiles
            $this->sections[] = array(
                'subsection' => true,
                'icon' => 'el-icon-share-alt',
                'title' => __('Social Profiles', 'enefti'),
                'fields' => array(
                    array(
                        'id' => 'enefti_social_fb',
                        'type' => 'text',
                        'title' => __('Facebook URL', 'enefti'),
                        'subtitle' => __('Type your Facebook url.', 'enefti'),
                        'default' => 'http://facebook.com'
                    ),
                    array(
                        'id' => 'enefti_social_discord',
                        'type' => 'text',
                        'title' => esc_html__('Discord URL', 'enefti'),
                        'subtitle' => esc_html__('Type your Discord url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_tw',
                        'type' => 'text',
                        'title' => __('Twitter username', 'enefti'),
                        'subtitle' => __('Type your Twitter username.', 'enefti'),
                        'default' => 'google'
                    ),
                    array(
                        'id' => 'enefti_social_telegram',
                        'type' => 'text',
                        'title' => __('Telegram link', 'enefti'),
                        'subtitle' => __('Type your Telegram link.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_pinterest',
                        'type' => 'text',
                        'title' => __('Pinterest URL', 'enefti'),
                        'subtitle' => __('Type your Pinterest url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_skype',
                        'type' => 'text',
                        'title' => __('Skype Name', 'enefti'),
                        'subtitle' => __('Type your Skype username.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_instagram',
                        'type' => 'text',
                        'title' => __('Instagram URL', 'enefti'),
                        'subtitle' => __('Type your Instagram url.', 'enefti'),
                        'default' => 'http://instagram.com'
                    ),
                    array(
                        'id' => 'enefti_social_youtube',
                        'type' => 'text',
                        'title' => __('YouTube URL', 'enefti'),
                        'subtitle' => __('Type your YouTube url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_dribbble',
                        'type' => 'text',
                        'title' => __('Dribbble URL', 'enefti'),
                        'subtitle' => __('Type your Dribbble url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_linkedin',
                        'type' => 'text',
                        'title' => __('LinkedIn URL', 'enefti'),
                        'subtitle' => __('Type your LinkedIn url.', 'enefti'),
                        'default' => 'http://linkedin.com'
                    ),
                    array(
                        'id' => 'enefti_social_deviantart',
                        'type' => 'text',
                        'title' => __('Deviant Art URL', 'enefti'),
                        'subtitle' => __('Type your Deviant Art url.', 'enefti'),
                        'default' => 'http://deviantart.com'
                    ),
                    array(
                        'id' => 'enefti_social_digg',
                        'type' => 'text',
                        'title' => __('Digg URL', 'enefti'),
                        'subtitle' => __('Type your Digg url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_flickr',
                        'type' => 'text',
                        'title' => __('Flickr URL', 'enefti'),
                        'subtitle' => __('Type your Flickr url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_stumbleupon',
                        'type' => 'text',
                        'title' => __('Stumbleupon URL', 'enefti'),
                        'subtitle' => __('Type your Stumbleupon url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_tumblr',
                        'type' => 'text',
                        'title' => __('Tumblr URL', 'enefti'),
                        'subtitle' => __('Type your Tumblr url.', 'enefti'),
                        'default' => ''
                    ),
                    array(
                        'id' => 'enefti_social_vimeo',
                        'type' => 'text',
                        'title' => __('Vimeo URL', 'enefti'),
                        'subtitle' => __('Type your Vimeo url.', 'enefti'),
                        'default' => ''
                    ),
                )
            );
            $this->sections[] = array(
                'title'      => esc_html__( 'Floating Social Button', 'enefti' ),
                'id'         => 'mt_social_media_settings_fixed_social_btn',
                'icon' => 'el-icon-compass-alt',
                'subsection' => true,
                'fields'     => array(
                    array(
                        'id'       => 'enefti_fixed_social_btn_status',
                        'type'     => 'switch', 
                        'title'    => esc_html__('Enable Floating Social Button', 'enefti'),
                        'subtitle' => esc_html__('Enable or disable Floating Social Button', 'enefti'),
                        'default'  => false,
                    ),

                    array(
                        'id'   => 'enefti_fixed_social_btn_info',
                        'type' => 'info',
                        'style' => 'success',
                        'desc' => __( 'This floating button will be shown above the Back to Top button (fixed on the right side of the bottom page). The Skin color is changeable from theme panel -> Styling', 'enefti' )
                    ),
                    array(
                        'id'       => 'enefti_fixed_social_btn_social_select',
                        'type'     => 'radio',
                        'title'    => esc_html__( 'Select Social Media url to show', 'enefti' ),
                        'subtitle' => esc_html__( 'Url/Icon can be set from Social Media tab - on Theme Panel', 'enefti' ),
                        'options'  => array(
                            'telegram'      => esc_html__( 'Telegram Link/Icon', 'enefti' ),
                            'facebook'      => esc_html__( 'Facebook Link/Icon', 'enefti' ),
                            'twitter'      => esc_html__( 'Twitter Link/Icon', 'enefti' ),
                            'pinterest'      => esc_html__( 'Pinterest Link/Icon', 'enefti' ),
                            'skype'      => esc_html__( 'Skype Link/Icon', 'enefti' ),
                            'instagram'      => esc_html__( 'Instagram Link/Icon', 'enefti' ),
                            'youtube'      => esc_html__( 'YouTube Link/Icon', 'enefti' ),
                            'dribbble'      => esc_html__( 'Dribbble Link/Icon', 'enefti' ),
                            'linkedin'      => esc_html__( 'LinkedIn Link/Icon', 'enefti' ),
                            'deviantart'      => esc_html__( 'LinkedIn Link/Icon', 'enefti' ),
                            'digg'      => esc_html__( 'Digg Link/Icon', 'enefti' ),
                            'flickr'      => esc_html__( 'Flickr Link/Icon', 'enefti' ),
                            'stumbleupon'      => esc_html__( 'Stumbleupon Link/Icon', 'enefti' ),
                            'tumblr'      => esc_html__( 'Tumblr Link/Icon', 'enefti' ),
                            'vimeo'      => esc_html__( 'Vimeo Link/Icon', 'enefti' ),
                        ),
                        'default'  => 'telegram',
                        'required' => array( 'enefti_fixed_social_btn_status', '=', '1' ),
                    ),
                    array(
                        'id'       => 'enefti_fixed_social_btn_text_custom_text',
                        'type'     => 'checkbox',
                        'title'    => esc_html__( 'Custom Tooltip text?', 'enefti' ),
                        'subtitle' => esc_html__( 'Override the default Connect With [social_account] text', 'enefti' ),
                        'default'  => '0',
                        'required' => array( 'enefti_fixed_social_btn_status', '=', '1' ),
                    ),
                    array(
                        'id' => 'enefti_fixed_social_btn_text',
                        'type' => 'text',
                        'title' => esc_html__('Set custom tooltip tooltip', 'enefti'),
                        'desc' => esc_html__('Connect With Telegram', 'enefti'),
                        'default' => '',
                        'required' => array( 
                            array('enefti_fixed_social_btn_status', '=', '1'),
                            array('enefti_fixed_social_btn_text_custom_text', '=', '1'),
                        ),
                    ),
                ),
            );
            # Section: Social Shares
            $this->sections[] = array(
                'subsection' => true,
                'icon' => 'el-icon-share',
                'title' => __('Social Shares', 'enefti'),
                'fields' => array(
                    array(
                        'id'       => 'enefti_social_share_links',
                        'type'     => 'checkbox', 
                        'title'    => __('Social Shares', 'enefti'),
                        'subtitle' => __('Choose what social share links to be listed (product pages & posts)', 'enefti'),
                        'options'   => array(
                            'twitter'   => __( 'Twitter', 'enefti' ),
                            'facebook'   => __( 'Facebook', 'enefti' ),
                            'whatsapp'   => __( 'Whatsapp', 'enefti' ),
                            'pinterest'   => __( 'Pinterest', 'enefti' ),
                            'linkedin'   => __( 'LinkedIn', 'enefti' ),
                            'telegram'   => __( 'Telegram', 'enefti' ),
                            'email'   => __( 'Email', 'enefti' ),
                        ),
                        'default' => array(
                            'twitter' => '1', 
                            'facebook' => '1', 
                            'whatsapp' => '1',
                            'pinterest' => '0',
                            'linkedin' => '0',
                            'telegram' => '0',
                            'email' => '0',
                        )
                    ),
                    array(
                        'id'       => 'enefti_social_share_locations',
                        'type'     => 'checkbox', 
                        'title'    => __('Social Shares Locations', 'enefti'),
                        'subtitle' => __('Enable or disable social share links on product pages or posts', 'enefti'),
                        'options'   => array(
                            'product'   => __( 'Product Single', 'enefti' ),
                            'post'   => __( 'Post Single', 'enefti' ),
                        ),
                        'default' => array(
                            'product' => '0', 
                            'post' => '0',
                        )
                    ),
                )
            );


            $theme_info = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'enefti') . '<a href="' . esc_url($this->theme->get('ThemeURI')) . '" target="_blank">' . esc_html($this->theme->get('ThemeURI')) . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'enefti') . esc_html($this->theme->get('Author')) . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'enefti') . esc_html($this->theme->get('Version')) . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . esc_html($this->theme->get('Description')) . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'enefti') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-1',
                'title' => __('', 'enefti'),
                'content' => __('', 'enefti')
            );

            $this->args['help_tabs'][] = array(
                'id' => 'redux-opts-2',
                'title' => __('', 'enefti'),
                'content' => __('', 'enefti')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('', 'enefti');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name' => 'redux_demo', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get('Name'), // Name that appears at the top of your panel
                'display_version' => $theme->get('Version'), // Version that appears at the top of your panel
                'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __('Theme Panel', 'enefti'),
                'page' => __('Theme Panel', 'enefti'),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'menu_icon' => get_template_directory_uri().'/images/svg/theme-panel-menu-icon.svg', // Specify a custom URL to an icon
                'google_api_key' => '', // Must be defined to add google fonts to the typography module
                'admin_bar' => true, // Show the panel pages on the admin bar
                'global_variable' => 'enefti_redux', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'customizer' => true, // Enable basic customizer support
                // OPTIONAL -> Give you extra features
                'page_priority'        => 2,
                'page_parent' => 'themes.php', // For a full list of options
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => '_options', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '', // What to print by the field's title if the value shown is default. Suggested: *
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'domain'              => 'enefti', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'show_import_export' => true, // REMOVE
                'system_info' => false, // REMOVE
                'help_tabs' => array(),
                'help_sidebar' => '',      
                'show_options_object' => false,   
            );

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace("-", "_", $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('', 'enefti'), $v);
            } else {
                $this->args['intro_text'] = __('', 'enefti');
            }

            // Add content after the form.
            $this->args['footer_text'] = __('', 'enefti');
        }

    }

    new Redux_Framework_enefti_config();
}