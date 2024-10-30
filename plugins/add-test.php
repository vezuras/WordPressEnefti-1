<?php
// Informations de connexion WooCommerce API
$woocommerce_api_url = "https://nummu.ca/wp-json/wc/v3/products";
$consumer_key = 'ck_dbbc96cd1a086d92512baf6d407706dad8e2e449';
$consumer_secret = 'cs_83157af9ddf2be8d32e632fdfc701d9bced95fdf';

// Préparer les données minimales du produit
$product_data = [
    'name' => 'Produit Test Minimaliste',
    'type' => 'simple',
    'regular_price' => '9.99',
    'description' => 'Ceci est une description de test pour un produit minimaliste.',
];

// Initialiser la requête cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $woocommerce_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($product_data));
curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

// Exécuter la requête et obtenir la réponse
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Vérifier si la requête a réussi
if ($http_code === 201) {
    echo "Produit créé avec succès ! Réponse : " . $response;
} else {
    echo "Erreur HTTP $http_code lors de la création du produit. Réponse : " . $response;
}

// Fermer la session cURL
curl_close($ch);
?>
