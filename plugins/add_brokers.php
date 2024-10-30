<?php

// Vérifiez que WordPress est chargé avant d'exécuter ce script

if (!defined('ABSPATH')) {

    exit;

}


function add_broker_product($broker_name, $broker_description, $broker_price) {

    // Assurez-vous que WooCommerce est actif

    if (!class_exists('WooCommerce')) {

        return;

    }


    // Créer un nouveau produit WooCommerce

    $new_product = array(

        'post_title'    => $broker_name,

        'post_content'  => $broker_description,

        'post_status'   => 'publish',

        'post_type'     => 'product',

    );


    // Insérer le produit dans la base de données

    $product_id = wp_insert_post($new_product);


    if (!is_wp_error($product_id)) {

        // Mettre à jour le prix du produit

        update_post_meta($product_id, '_regular_price', $broker_price);

        update_post_meta($product_id, '_price', $broker_price);


        // Définir le produit comme simple produit

        wp_set_object_terms($product_id, 'simple', 'product_type');


        echo "Courtier ajouté avec succès : $broker_name\n";

    } else {

        echo "Erreur lors de l'ajout du courtier : " . $product_id->get_error_message() . "\n";

    }

}

