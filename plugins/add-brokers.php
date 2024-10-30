<?php
// Inclure les dépendances MongoDB et WordPress
require __DIR__ . '/../vendor/autoload.php'; // Charger MongoDB
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'); // Charger WordPress

// Informations de connexion MongoDB
$MONGO_USERNAME = getenv('NUMMU_USERNAME') ?: 'nummu';
$MONGO_PASSWORD = getenv('NUMMU_PASSWORD') ?: 'PTxyFp06N4kLlZ8G';
$MONGO_URI = "mongodb+srv://$MONGO_USERNAME:$MONGO_PASSWORD@NUMMU.nblabru.mongodb.net/?retryWrites=true&w=majority&appName=NUMMU";
$MONGO_DATABASE = 'nummu';

// Informations WooCommerce API
$woocommerce_api_url = "https://nummu.ca/wp-json/wc/v3/products";
$consumer_key = 'ck_dbbc96cd1a086d92512baf6d407706dad8e2e449';
$consumer_secret = 'cs_83157af9ddf2be8d32e632fdfc701d9bced95fdf';

// Fonction pour télécharger et vérifier si une image existe déjà
function download_and_insert_image_to_wp_media_library($image_url, $broker_id) {
    if (empty($image_url)) {
        return null; // Pas d'image à traiter
    }

    $upload_dir = wp_upload_dir();
    $file_name = 'broker_image_' . $broker_id . '.jpg';
    $file_path = $upload_dir['path'] . '/' . $file_name;

    $attachment_id = attachment_url_to_postid($upload_dir['url'] . '/' . $file_name);
    if ($attachment_id) {
        return $attachment_id;
    }

    $image_data = file_get_contents($image_url);
    if ($image_data === false) {
        return null;
    }

    file_put_contents($file_path, $image_data);
    
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

// Essai de connexion à MongoDB
try {
    $client = new MongoDB\Client($MONGO_URI);
    $database = $client->selectDatabase($MONGO_DATABASE);
    $collection = $database->selectCollection('centris_brokers_full');

    $brokers = $collection->find([], ['limit' => 25])->toArray();

    if (!empty($brokers)) {
        foreach ($brokers as $broker) {
            $image_id = !empty($broker['broker_image_url']) ? download_and_insert_image_to_wp_media_library($broker['broker_image_url'], (string)$broker['_id']) : null;

            $formatted_job = strtoupper(str_replace(['Residential', 'Commercial'], ['Résidentiel', 'Commercial'], $broker['broker_job']));
            $language_options = ['french' => 'FR', 'english' => 'EN', 'spanish' => 'ES', 'creole' => 'CR', 'german' => 'DE', 'italian' => 'IT', 'portuguese' => 'PT', 'dutch' => 'NL', 'russian' => 'RU', 'chinese' => 'ZH', 'japanese' => 'JA', 'korean' => 'KO'];
            $languages = implode(', ', array_map(function($lang) use ($language_options) {
                return $language_options[$lang] ?? strtoupper($lang);
            }, explode(', ', strtolower($broker['broker_language']))));

            $broker_data = [
                'name' => $broker['broker_name'] ?? 'Courtier sans nom',
                'type' => 'external',
                'regular_price' => '',
                'sale_price' => '',
                'description' => !empty($broker['broker_area']) ? "<strong>Zone(s) servie(s):</strong> {$broker['broker_area']}<br>" : '',
                'short_description' => "
                    <p><strong>POSTE:</strong> $formatted_job<br>
                    <strong>AGENCE:</strong> " . strtoupper($broker['broker_agency']) . "<br>
                    {$broker['broker_address']}<br>
                    <strong>LANGUE(S):</strong> $languages</p>",
                'external_url' => $broker['broker_url'] ?? '',
                'button_text' => 'Fiche Centris',
                'meta_data' => [
                    ['key' => 'broker_id', 'value' => (string)$broker['_id']],
                    ['key' => 'broker_license', 'value' => $broker['broker_license'] ?? ''],
                    ['key' => 'broker_agency', 'value' => $broker['broker_agency'] ?? ''],
                    ['key' => 'broker_phone', 'value' => json_encode($broker['broker_phone'] ?? [])],
                    ['key' => 'broker_city', 'value' => $broker['broker_city'] ?? ''],
                    ['key' => 'broker_province', 'value' => $broker['broker_province'] ?? ''],
                    ['key' => 'broker_postal_code', 'value' => $broker['broker_postal_code'] ?? ''],
                    ['key' => 'broker_geolocations', 'value' => json_encode($broker['broker_geolocations'] ?? [])],
                    ['key' => 'broker_language', 'value' => $languages],
                ],
                'categories' => [['id' => 259]],
                'images' => !is_null($image_id) ? [['id' => $image_id]] : []
            ];

            echo "Données envoyées à WooCommerce : " . json_encode($broker_data) . "\n";

            if (isset($broker['broker_agency']) && !empty($broker['broker_agency'])) {
                echo "Confirmation : L'agence '{$broker['broker_agency']}' est bien incluse pour le courtier {$broker['broker_name']}.\n";
            } else {
                echo "Avertissement : Aucune agence trouvée pour le courtier {$broker['broker_name']}.\n";
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $woocommerce_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($broker_data));
            curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch) . "\n";
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response_data = json_decode($response, true);
                
                if ($http_code !== 201) {
                    echo "Erreur HTTP $http_code lors de l'ajout du courtier. Réponse complète de l'API :\n" . $response . "\n";
                } elseif (isset($response_data['name'])) {
                    echo "Courtier ajouté : " . $response_data['name'] . "\n";
                } else {
                    echo "Erreur lors de l'ajout du courtier : " . json_encode($response_data) . "\n";
                }
            }
            
            curl_close($ch);
        }
    } else {
        echo "Aucun courtier trouvé dans la collection 'centris_brokers_full'.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors de la connexion à MongoDB ou de l'accès à la collection: " . $e->getMessage() . "\n";
}
?><?php
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

// Fonction pour télécharger et vérifier si une image existe déjà
function download_and_insert_image_to_wp_media_library($image_url, $broker_id) {
    if (empty($image_url)) {
        return null; // Pas d'image à traiter
    }

    $upload_dir = wp_upload_dir();
    $file_name = 'broker_image_' . $broker_id . '.jpg';
    $file_path = $upload_dir['path'] . '/' . $file_name;

    $attachment_id = attachment_url_to_postid($upload_dir['url'] . '/' . $file_name);
    if ($attachment_id) {
        return $attachment_id;
    }

    $image_data = file_get_contents($image_url);
    if ($image_data === false) {
        return null;
    }

    file_put_contents($file_path, $image_data);
    
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

// Essai de connexion à MongoDB
try {
    $client = new MongoDB\Client($MONGO_URI);
    $database = $client->selectDatabase($MONGO_DATABASE);
    $collection = $database->selectCollection('centris_brokers_full');

    $brokers = $collection->find([], ['limit' => 25])->toArray();

    if (!empty($brokers)) {
        foreach ($brokers as $broker) {
            $image_id = !empty($broker['broker_image_url']) ? download_and_insert_image_to_wp_media_library($broker['broker_image_url'], (string)$broker['_id']) : null;

            $formatted_job = strtoupper(str_replace(['Residential', 'Commercial'], ['Résidentiel', 'Commercial'], $broker['broker_job']));
            $language_options = ['french' => 'FR', 'english' => 'EN', 'spanish' => 'ES', 'creole' => 'CR', 'german' => 'DE', 'italian' => 'IT', 'portuguese' => 'PT', 'dutch' => 'NL', 'russian' => 'RU', 'chinese' => 'ZH', 'japanese' => 'JA', 'korean' => 'KO'];
            $languages = implode(', ', array_map(function($lang) use ($language_options) {
                return $language_options[$lang] ?? strtoupper($lang);
            }, explode(', ', strtolower($broker['broker_language']))));

            $broker_data = [
                'name' => $broker['broker_name'] ?? 'Courtier sans nom',
                'type' => 'external',
                'regular_price' => '',
                'sale_price' => '',
                'description' => !empty($broker['broker_area']) ? "<strong>Zone(s) servie(s):</strong> {$broker['broker_area']}<br>" : '',
                'short_description' => "
                    <p><strong>POSTE:</strong> $formatted_job<br>
                    <strong>AGENCE:</strong> " . strtoupper($broker['broker_agency']) . "<br>
                    {$broker['broker_address']}<br>
                    <strong>LANGUE(S):</strong> $languages</p>",
                'external_url' => $broker['broker_url'] ?? '',
                'button_text' => 'Fiche Centris',
                'meta_data' => [
                    ['key' => 'broker_id', 'value' => (string)$broker['_id']],
                    ['key' => 'broker_license', 'value' => $broker['broker_license'] ?? ''],
                    ['key' => 'broker_agency', 'value' => $broker['broker_agency'] ?? ''],
                    ['key' => 'broker_phone', 'value' => json_encode($broker['broker_phone'] ?? [])],
                    ['key' => 'broker_city', 'value' => $broker['broker_city'] ?? ''],
                    ['key' => 'broker_province', 'value' => $broker['broker_province'] ?? ''],
                    ['key' => 'broker_postal_code', 'value' => $broker['broker_postal_code'] ?? ''],
                    ['key' => 'broker_geolocations', 'value' => json_encode($broker['broker_geolocations'] ?? [])],
                    ['key' => 'broker_language', 'value' => $languages],
                ],
                'categories' => [['id' => 176]], // Catégorie "Courtier Immobilier"
                'images' => !is_null($image_id) ? [['id' => $image_id]] : []
            ];

            echo "Données envoyées à WooCommerce : " . json_encode($broker_data) . "\n";

            if (isset($broker['broker_agency']) && !empty($broker['broker_agency'])) {
                echo "Confirmation : L'agence '{$broker['broker_agency']}' est bien incluse pour le courtier {$broker['broker_name']}.\n";
            } else {
                echo "Avertissement : Aucune agence trouvée pour le courtier {$broker['broker_name']}.\n";
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $woocommerce_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($broker_data));
            curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            
            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch) . "\n";
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response_data = json_decode($response, true);
                
                if ($http_code !== 201) {
                    echo "Erreur HTTP $http_code lors de l'ajout du courtier. Réponse complète de l'API :\n" . $response . "\n";
                } elseif (isset($response_data['name'])) {
                    echo "Courtier ajouté : " . $response_data['name'] . "\n";
                } else {
                    echo "Erreur lors de l'ajout du courtier : " . json_encode($response_data) . "\n";
                }
            }
            
            curl_close($ch);
        }
    } else {
        echo "Aucun courtier trouvé dans la collection 'centris_brokers_full'.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors de la connexion à MongoDB ou de l'accès à la collection: " . $e->getMessage() . "\n";
}
?>
