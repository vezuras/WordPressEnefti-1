<?php
// Inclure les dépendances MongoDB et WordPress
require __DIR__ . '/../vendor/autoload.php';
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

// Fonction pour télécharger l'image d'une annonce et l'ajouter à la Media Library
function download_and_insert_image_to_wp_media_library($image_url, $annonce_id) {
    if (empty($image_url)) {
        return null; // Pas d'image à traiter
    }

    $image_data = file_get_contents($image_url);
    if ($image_data === false) {
        return null; // Impossible de télécharger l'image
    }

    $upload_dir = wp_upload_dir();
    $file_name = 'annonce_image_' . $annonce_id . '.jpg';
    $file_path = $upload_dir['path'] . '/' . $file_name;

    $result = file_put_contents($file_path, $image_data);
    if ($result === false) {
        return null;
    }

    $attachment = array(
        'guid' => $upload_dir['url'] . '/' . $file_name,
        'post_mime_type' => 'image/jpeg',
        'post_title' => sanitize_file_name($file_name),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file_path);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}

function extract_city_from_duproprio($annonce) {
    if (!empty($annonce['city'])) {
        return $annonce['city'];
    }

    if (!empty($annonce['category'])) {
        $category_parts = explode(',', $annonce['category']);
        if (count($category_parts) > 1) {
            $last_part = trim(end($category_parts));
            $city = explode(' à vendre', $last_part)[0];
            return trim($city);
        }
    }

    return 'Ville non disponible';
}

function clean_address($address) {
    $address = str_replace(' à vendre', '', $address);
    $address = trim($address);
    return $address;
}

try {
    $client = new MongoDB\Client($MONGO_URI);
    $database = $client->selectDatabase($MONGO_DATABASE);
    
    $collection = $database->selectCollection('duproprio_full');
    $annonces = $collection->find(['active' => true], ['limit' => 10])->toArray();
    
    if (!empty($annonces)) {
        foreach ($annonces as $annonce) {
            $image_id = null;
            if (!empty($annonce['image_url'])) {
                $image_id = download_and_insert_image_to_wp_media_library($annonce['image_url'], (string)$annonce['_id']);
            }

            $city = extract_city_from_duproprio($annonce);
            $formatted_address = isset($annonce['address']) ? clean_address($annonce['address']) : 'Adresse non disponible';
            $product_title = $city;

            $annonce_data = [
                'name' => $product_title,
                'type' => 'external',
                'regular_price' => (string)$annonce['price'],
                'description' => "<p><strong>Description complète :</strong> {$annonce['description']}</p>",
                'short_description' => "<p><strong>Adresse :</strong> {$formatted_address}</p>",
                'external_url' => !empty($annonce['url']) ? $annonce['url'] : '',
                'button_text' => 'Voir sur DuProprio',
                'meta_data' => [
                    ['key' => 'annonce_id', 'value' => (string)$annonce['_id']],
                    ['key' => 'sku', 'value' => $annonce['sku'] ?? ''],
                    ['key' => 'latitude', 'value' => $annonce['latitude'] ?? ''],
                    ['key' => 'longitude', 'value' => $annonce['longitude'] ?? ''],
                ],
                'categories' => [
                    ['id' => 183],
                    ['id' => 178],
                ],
                'images' => !is_null($image_id) ? [['id' => $image_id]] : []
            ];

            echo "Données envoyées à WooCommerce : " . json_encode($annonce_data) . "\n";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $woocommerce_api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($annonce_data));
            curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Erreur cURL : ' . curl_error($ch) . "\n";
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response_data = json_decode($response, true);

                if ($http_code === 201 && isset($response_data['id'])) {
                    echo "Produit créé avec succès ! Nom : " . $response_data['name'] . " (ID : " . $response_data['id'] . ")\n";
                } elseif ($http_code === 200 && is_null($response_data)) {
                    echo "Réponse HTTP 200 : La requête a été traitée mais sans création. Voici la réponse : NULL\n";
                } else {
                    echo "Erreur HTTP $http_code lors de l'ajout de l'annonce. Réponse complète de l'API :\n" . $response . "\n";
                }
            }

            curl_close($ch);
        }
    } else {
        echo "Aucune annonce trouvée dans la collection 'duproprio_full'.\n";
    }
    
} catch (Exception $e) {
    echo "Erreur lors de la connexion à MongoDB ou de l'accès à la collection: " . $e->getMessage() . "\n";
}
?>
