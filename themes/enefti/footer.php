<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package enefti
 */
?>
<?php
global $enefti_redux;
?>

    <?php 
        do_action('enefti_before_back_to_top_button');
    ?>

    <?php if ( !class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
        <!-- BACK TO TOP BUTTON -->
        <a class="back-to-top modeltheme-is-visible modeltheme-fade-out" href="<?php echo esc_url('#0'); ?>">
            <span></span>
        </a>
    <?php }else{ ?>
        <?php if (enefti_redux('enefti_backtotop_status') == true) { ?>
            <!-- BACK TO TOP BUTTON -->
           <a class="back-to-top modeltheme-is-visible modeltheme-fade-out" href="<?php echo esc_url('#0'); ?>">
                <span></span>
            </a>
        <?php } ?>
    <?php } ?>

    <?php
        $footer_widgets = 'no-footer-widgets';
        if ( is_active_sidebar( 'footer_column_1' ) || is_active_sidebar( 'footer_column_2' ) || is_active_sidebar( 'footer_column_3' ) || is_active_sidebar( 'footer_column_4' ) || is_active_sidebar( 'footer_column_5' ) ) {
            $footer_widgets = 'has-footer-widgets';
        }
    ?>
    <!-- MAIN FOOTER -->
    <footer class="<?php echo esc_attr($footer_widgets); ?> enefti-main-footer">
        
        <?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
            <?php if (enefti_redux('enefti-enable-footer-top') == true) { ?>
                <?php 
                    $mt_footer_row_top_status = get_post_meta( get_the_ID(), 'mt_footer_row_top_status', true );
                ?>
                <?php if (isset($mt_footer_row_top_status) && $mt_footer_row_top_status != 'on') { ?>
                    <div class="top-footer row">
                        <div class="container">
                            <?php if(is_active_sidebar( 'footer-top' )){ ?>
                                <?php dynamic_sidebar( 'footer-top' ); ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php } ?>

        <?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                <div class="container footer-top">
                    <?php if ( $enefti_redux['enefti-enable-footer-widgets'] ) { ?>
                        <div class="row footer-row-1">
                            <?php
                            $columns    = 12/intval($enefti_redux['enefti_number_of_footer_columns']);
                            $nr         = array("1", "2", "3", "4", "6");

                            if (in_array($enefti_redux['enefti_number_of_footer_columns'], $nr)) {
                                $class = 'col-md-'.esc_html($columns);
                                for ( $i=1; $i <= intval( $enefti_redux['enefti_number_of_footer_columns'] ) ; $i++ ) { 

                                    echo '<div class="'.esc_attr($class).' widget widget_text">';
                                        dynamic_sidebar( 'footer_column_'.esc_html($i) );
                                    echo '</div>';

                                }
                            }elseif($enefti_redux['enefti_number_of_footer_columns'] == 5){
                                #First
                                if ( is_active_sidebar( 'footer_column_1' ) ) {
                                    echo '<div class="col-md-3 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_1' );
                                    echo '</div>';
                                }
                                #Second
                                if ( is_active_sidebar( 'footer_column_2' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_2' );
                                    echo '</div>';
                                }
                                #Third
                                if ( is_active_sidebar( 'footer_column_3' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_3' );
                                    echo '</div>';
                                }
                                #Fourth
                                if ( is_active_sidebar( 'footer_column_4' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_4' );
                                    echo '</div>';
                                }
                                #Fifth
                                if ( is_active_sidebar( 'footer_column_5' ) ) {
                                    echo '<div class="col-md-3 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_5' );
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>

                    <?php if ($enefti_redux['enefti-enable-footer-widgets-row2']) { ?>
                        <div class="row footer-row-2">
                            <?php
                            $columns    = 12/intval($enefti_redux['enefti_number_of_footer_columns_row2']);
                            $nr         = array("1", "2", "3", "4", "6");

                            if (in_array($enefti_redux['enefti_number_of_footer_columns_row2'], $nr)) {
                                $class = 'col-md-'.esc_html($columns);
                                for ( $i=1; $i <= intval( $enefti_redux['enefti_number_of_footer_columns_row2'] ) ; $i++ ) { 

                                    echo '<div class="'.esc_attr($class).' widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row2'.esc_html($i) );
                                    echo '</div>';

                                }
                            }elseif($enefti_redux['enefti_number_of_footer_columns_row2'] == 5){
                                #First
                                if ( is_active_sidebar( 'footer_column_row21' ) ) {
                                    echo '<div class="col-md-3 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row21' );
                                    echo '</div>';
                                }
                                #Second
                                if ( is_active_sidebar( 'footer_column_row22' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row22' );
                                    echo '</div>';
                                }
                                #Third
                                if ( is_active_sidebar( 'footer_column_row23' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row23' );
                                    echo '</div>';
                                }
                                #Fourth
                                if ( is_active_sidebar( 'footer_column_row24' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row24' );
                                    echo '</div>';
                                }
                                #Fifth
                                if ( is_active_sidebar( 'footer_column_row25' ) ) {
                                    echo '<div class="col-md-3 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row25' );
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>

                    <?php if ($enefti_redux['enefti-enable-footer-widgets-row3']) { ?>
                        <div class="row footer-row-3">
                            <?php
                            $columns    = 12/intval($enefti_redux['enefti_number_of_footer_columns_row3']);
                            $nr         = array("1", "2", "3", "4", "6");

                            if (in_array($enefti_redux['enefti_number_of_footer_columns_row3'], $nr)) {
                                $class = 'col-md-'.esc_html($columns);
                                for ( $i=1; $i <= intval( $enefti_redux['enefti_number_of_footer_columns_row3'] ) ; $i++ ) { 

                                    echo '<div class="'.esc_attr($class).' widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row3'.esc_html($i) );
                                    echo '</div>';

                                }
                            }elseif($enefti_redux['enefti_number_of_footer_columns_row3'] == 5){
                                #First
                                if ( is_active_sidebar( 'footer_column_row31' ) ) {
                                    echo '<div class="col-md-3 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row31' );
                                    echo '</div>';
                                }
                                #Second
                                if ( is_active_sidebar( 'footer_column_row32' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row32' );
                                    echo '</div>';
                                }
                                #Third
                                if ( is_active_sidebar( 'footer_column_row33' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row33' );
                                    echo '</div>';
                                }
                                #Fourth
                                if ( is_active_sidebar( 'footer_column_row34' ) ) {
                                    echo '<div class="col-md-2 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row34' );
                                    echo '</div>';
                                }
                                #Fifth
                                if ( is_active_sidebar( 'footer_column_row35' ) ) {
                                    echo '<div class="col-md-3 widget widget_text">';
                                        dynamic_sidebar( 'footer_column_row35' );
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
        <?php } ?>

        <?php do_action('enefti_before_footer_mobile_navigation'); ?>

        <div class="footer footer-copyright">
            <div class="container">
                <div class="row">
                    <?php if ( class_exists( 'ReduxFrameworkPlugin' ) ) { ?>
                        <div class="col-md-6">
                            <p class="copyright"><?php echo wp_kses($enefti_redux['enefti_footer_text_left'], 'link'); ?></p>
                        </div>
                        <div class="col-md-6 payment-methods">
                            <p class="copyright"><?php echo wp_kses($enefti_redux['enefti_footer_text_right'], 'link'); ?></p>
                        </div>
                    <?php }else { ?>
                        <div class="col-md-6">
                            <p class="copyright"><?php esc_html_e( 'Copyright by ModelTheme. All Rights Reserved.', 'enefti' ); ?></p>
                        </div>
                        <div class="col-md-6 payment-methods">
                            <p class="copyright"><?php esc_html_e( 'Elite Author on ThemeForest', 'enefti' ); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </footer>
</div>

<?php wp_footer(); ?>
</body>
</html>