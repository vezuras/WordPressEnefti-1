<?php
function enefti_child_scripts() {
    wp_enqueue_style( 'enefti-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'enefti_child_scripts' );

// Afficher un commentaire HTML en utilisant le hook wp_footer pour vérifier l'activation de functions.php
function enefti_child_test_comment() {
    echo "<!-- Test de functions.php du thème enfant actif -->";
}
add_action('wp_footer', 'enefti_child_test_comment');
?>
