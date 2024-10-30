<?php
function enefti_child_scripts() {
    wp_enqueue_style( 'enefti-parent-style', get_template_directory_uri(). '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enefti_child_scripts' );
