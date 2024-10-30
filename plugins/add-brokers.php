<?php
// Inclure les dépendances MongoDB et WordPress
require 'vendor/autoload.php'; // Charger MongoDB
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'); // Charger WordPress

// Informations de connexion MongoDB
$MONGO_USERNAME = getenv('NUMMU_USERNAME') ?: 'nummu';
$MONGO_PASSWORD = getenv('NUMMU_PASSWORD') ?: 'PTxyFp06N4kLlZ8G';
$MONGO_URI = "mongodb+srv://$MONGO_USERNAME:$MONGO_PASSWORD@NUMMU.nblabru.mongodb.net/?retryWrites=true&w=majority&appName=NUMMU";
$MONGO_DATABASE = 'nummu';

// Informations WooCommerce API
$woocommerce_api_url = "https://nummu.ca/wp-json/wc/v3/products";
$consumer_key = 'ck_c5b5a2fcc17c9dca0ea3d954ee338c62b5df3d53';
$consumer_secret = 'cs_f73e1dcb0b4150507596535c309307fba27f36a0';

// Fonction pour télécharger et ajouter une image à la bibliothèque des médias WordPress
function download_and_insert_image_to_wp_media_library($image_url, $broker_id) {
    if (empty($image_url)) return null;

    $upload_dir = wp_upload_dir();
    $file_name = 'broker_image_' . $broker_id . '.jpg';
    $file_path = $upload_dir['path'] . '/' . $file_name;

    // Vérifier si l'image existe déjà
    $attachment_id = attachment_url_to_postid($upload_dir['url'] . '/' . $file_name);
    if ($attachment_id) return $attachment_id;

    // Télécharger et enregistrer l'image
    $image_data = file_get_contents($image_url);
    if ($image_data === false) return null;
    file_put_contents($file_path, $image_data);

    // Ajouter l'image dans la Media Library
    $attachment = [
        'guid' => $upload_dir['url'] . '/' . $file_name,
        'post_mime_type' => 'image/jpeg',
        'post_title' => sanitize_file_name($file_name),
        'post_content' => '',
        'post_status' => 'inherit'
    ];

    $attach_id = wp_insert_attachment($attachment, $file_path);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

// Connexion à MongoDB et traitement des données des courtiers
try {
    $client = new MongoDB\Client($MONGO_URI);
    $database = $client->selectDatabase($MONGO_DATABASE);
    $collection = $database->selectCollection('centris_brokers_full');
    $brokers = $collection->find([], ['limit' => 10])->toArray(); // Récupérer les 10 premiers courtiers

    if (!empty($brokers)) {
        foreach ($brokers as $broker) {
            $image_id = !empty($broker['broker_image_url']) ? download_and_insert_image_to_wp_media_library($broker['broker_image_url'], (string)$broker['_id']) : null;

            // Format des données
            $languages = implode(', ', array_map('ucwords', explode(', ', strtolower($broker['broker_language'] ?? ''))));
            $broker_phone = !empty($broker['broker_phone']) ? implode(', ', $broker['broker_phone']) : 'Non disponible';

            // Préparation des données du produit pour WooCommerce
            $broker_data = [
                'name' => $broker['broker_name'] ?? 'Courtier sans nom',
                'type' => 'external',
                'regular_price' => '',
                'description' => "<p><strong>Zone(s) servie(s) :</strong> {$broker['broker_area']}</p>",
                'short_description' => "
                    <p><strong>Poste :</strong> {$broker['broker_job']}<br>
                    <strong>Agence :</strong> {$broker['broker_agency']}<br>
                    <strong>Licence :</strong> {$broker['broker_license']}<br>
                    <strong>Adresse :</strong> {$broker['broker_address']}<br>
                    <strong>Langue(s) :</strong> {$languages}<br>
                    <strong>Téléphone :</strong> {$broker_phone}</p>",
                'external_url' => $broker['broker_url'] ?? '',
                'button_text' => 'Voir la fiche du courtier',
                'meta_data' => [
                    ['key' => 'broker_id', 'value' => (string)$broker['_id']],
                    ['key' => 'broker_agency', 'value' => $broker['broker_agency']],
                    ['key' => 'broker_license', 'value' => $broker['broker_license']],
                    ['key' => 'broker_phone', 'value' => json_encode($broker['broker_phone'])],
                    ['key' => 'broker_language', 'value' => $languages],
                    ['key' => 'broker_area', 'value' => $broker['broker_area']],
                    ['key' => 'broker_city', 'value' => $broker['broker_city']],
                    ['key' => 'broker_province', 'value' => $broker['broker_province']],
                    ['key' => 'broker_postal_code', 'value' => $broker['broker_postal_code']],
                    ['key' => 'broker_address', 'value' => $broker['broker_address']],
                    ['key' => 'broker_geolocations', 'value' => json_encode($broker['broker_geolocations'] ?? [])],
                ],
                'categories' => [['id' => 176]], // Catégorie 'Courtier Immobilier'
                'images' => !is_null($image_id) ? [['id' => $image_id]] : []
            ];

            // Envoi des données à WooCommerce
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $woocommerce_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($broker_data));
            curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch) . "\n";
            } elseif ($http_code !== 201) {
                echo "Erreur HTTP $http_code lors de l'ajout du courtier. Réponse complète :\n" . $response . "\n";
            } else {
                echo "Courtier ajouté : {$broker['broker_name']}\n";
            }

            curl_close($ch);
        }
    } else {
        echo "Aucun courtier trouvé dans la collection 'centris_brokers_full'.\n";
    }
} catch (Exception $e) {
    echo "Erreur lors de la connexion à MongoDB ou de l'accès à la collection : " . $e->getMessage() . "\n";
}
?>





<!-- Meta_data : Contient toutes les données spécifiques aux courtiers, comme broker_id, broker_agency, broker_license, broker_phone, broker_language, broker_area, broker_city, broker_province, broker_postal_code, broker_address, et broker_geolocations.
Short_description : Présente une description concise incluant le poste, l'agence, l’adresse, les langues parlées, et le téléphone.
Categories : Définit la catégorie du produit comme Courtier Immobilier avec l’ID 259. -->
