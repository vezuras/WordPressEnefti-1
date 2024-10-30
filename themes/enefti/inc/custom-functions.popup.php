<?php
/*
 File name:          Custom Popup
*/

defined( 'ABSPATH' ) || exit;

if ( !function_exists( 'enefti_popup_modal' ) ) {
    function enefti_popup_modal() { 
        // REDUX VARIABLE
        global $enefti_redux;
        $user_url = get_permalink( get_option('woocommerce_myaccount_page_id') );
        echo'<div class="popup modeltheme-modal" id="modal-log-in" data-expire="'.esc_attr($enefti_redux['enefti-enable-popup-expire-date']).'" show="'.esc_attr($enefti_redux['enefti-enable-popup-show-time']).'">
            
            <div class="mt-popup-wrapper col-md-12" id="popup-modal-wrapper">
                <div class="dismiss">
                <a id="exit-popup"></a>
            </div>
                <div class="mt-popup-image col-md-4">
                    <img src="'.esc_url($enefti_redux['enefti-enable-popup-img']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />
                </div>
                <div class="mt-popup-content col-md-8 text-center">
                    <img src="'.esc_url($enefti_redux['enefti-enable-popup-company']['url']).'" alt="'.esc_attr(get_bloginfo()).'" />';
                    if($enefti_redux['enefti-enable-popup-desc']) {
                        echo '<p class="mt-popup-desc">'.$enefti_redux['enefti-enable-popup-desc'].'</p>';
                    }
                    echo '<p class="mt-popup-desc">'.do_shortcode(''.$enefti_redux["enefti-enable-popup-form"].'').'</p>';
                    if($enefti_redux['enefti-enable-popup-additional'] == false) {
                        echo '<p class="mt-additional">'.esc_html__('Already a member?','enefti').' <a href="'.esc_url($user_url).'">'.esc_html__('Log In.','enefti').'</a></p>';
                    }
                echo '</div>          
            </div>
        </div>';
    }
}