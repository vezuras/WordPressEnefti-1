// <?php
// // Inclure les dépendances MongoDB et WordPress
// require 'vendor/autoload.php'; // Charger MongoDB
// require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php'); // Charger WordPress

// // Informations de connexion MongoDB
// $MONGO_USERNAME = getenv('NUMMU_USERNAME') ?: 'nummu';
// $MONGO_PASSWORD = getenv('NUMMU_PASSWORD') ?: 'PTxyFp06N4kLlZ8G';
// $MONGO_URI = "mongodb+srv://$MONGO_USERNAME:$MONGO_PASSWORD@NUMMU.nblabru.mongodb.net/?retryWrites=true&w=majority&appName=NUMMU";
// $MONGO_DATABASE = 'nummu';

// // Informations WooCommerce API
// $woocommerce_api_url = "https://nummu.ca/wp-json/wc/v3/products";
// $consumer_key = 'ck_c5b5a2fcc17c9dca0ea3d954ee338c62b5df3d53';
// $consumer_secret = 'cs_f73e1dcb0b4150507596535c309307fba27f36a0';

// // Fonction pour télécharger l'image d'une annonce et l'ajouter à la Media Library
// function download_and_insert_image_to_wp_media_library($image_url, $annonce_id) {
//     if (empty($image_url)) {
//         return null; // Pas d'image à traiter
//     }

//     // Télécharger l'image
//     $image_data = file_get_contents($image_url);
//     if ($image_data === false) {
//         return null; // Impossible de télécharger l'image
//     }

//     // Obtenir le chemin des uploads WordPress et générer un nom de fichier unique avec l'ID de l'annonce
//     $upload_dir = wp_upload_dir();
//     $file_name = 'annonce_image_' . $annonce_id . '.jpg';
//     $file_path = $upload_dir['path'] . '/' . $file_name;

//     // Enregistrer l'image dans le répertoire WordPress
//     $result = file_put_contents($file_path, $image_data);
//     if ($result === false) {
//         return null; // Erreur lors de l'enregistrement de l'image
//     }

//     // Ajouter l'image à la Media Library
//     $attachment = array(
//         'guid' => $upload_dir['url'] . '/' . $file_name,
//         'post_mime_type' => 'image/jpeg',
//         'post_title' => sanitize_file_name($file_name),
//         'post_content' => '',
//         'post_status' => 'inherit'
//     );

//     // Insérer l'image dans la base de données WordPress
//     $attach_id = wp_insert_attachment($attachment, $file_path);
//     require_once(ABSPATH . 'wp-admin/includes/image.php');
//     $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
//     wp_update_attachment_metadata($attach_id, $attach_data);

//     return $attach_id; // Retourner l'ID de l'image dans la Media Library
// }

// // Fonction pour extraire la ville à partir de l'adresse
// function extract_city_from_address($street_address) {
//     // Repérer la première parenthèse pour ignorer les informations de quartier
//     $parenthesis_pos = strpos($street_address, '(');
//     if ($parenthesis_pos !== false) {
//         // Extraire la partie avant la parenthèse
//         $address_without_quarter = substr($street_address, 0, $parenthesis_pos);
//     } else {
//         $address_without_quarter = $street_address;
//     }

//     // Séparer l'adresse en parties avec les virgules
//     $address_parts = explode(',', $address_without_quarter);

//     // La ville est souvent le dernier élément utile de l'adresse
//     $city = trim(end($address_parts));

//     return $city;
// }

// // Essai de connexion à MongoDB
// try {
//     $client = new MongoDB\Client($MONGO_URI);
//     $database = $client->selectDatabase($MONGO_DATABASE);
    
//     // Vérification de la collection 'centris_full'
//     $collection = $database->selectCollection('centris_full');
    
//     // Récupérer les 10 premières annonces actives
//     $annonces = $collection->find(['active' => true], ['limit' => 10])->toArray();
    
//     if (!empty($annonces)) {
//         // Parcourir chaque annonce et l'envoyer vers WooCommerce
//         foreach ($annonces as $annonce) {
//             // Télécharger et ajouter l'image à la Media Library
//             $image_id = null;
//             if (!empty($annonce['image_url'])) {
//                 $image_id = download_and_insert_image_to_wp_media_library($annonce['image_url'], (string)$annonce['_id']);
//             }

//             // Extraire la ville à partir de l'adresse
//             $city = isset($annonce['address']['street_address']) ? extract_city_from_address($annonce['address']['street_address']) : 'Ville non disponible';

//             // Formater l'adresse de l'annonce
//             $formatted_address = $annonce['address']['street_address'] ?? 'Adresse non disponible';

//             // Utiliser la ville comme titre du produit
//             $product_title = $city;

//             // Préparer les données du produit pour WooCommerce
//             $annonce_data = [
//                 'name' => $product_title, // Utilisation de la ville comme titre
//                 'type' => 'external',
//                 'regular_price' => (string)$annonce['price'], // Utilisation du prix de l'annonce
//                 'description' => "<p><strong>Description complète :</strong> {$annonce['description']}</p>", // Description longue
//                 'short_description' => "<p><strong>Adresse :</strong> {$formatted_address}</p>", // Adresse complète
//                 'external_url' => !empty($annonce['url']) ? $annonce['url'] : '',
//                 'button_text' => 'Voir sur Centris', // Modification du texte du bouton
//                 'meta_data' => [
//                     ['key' => 'annonce_id', 'value' => (string)$annonce['_id']],
//                     ['key' => 'sku', 'value' => $annonce['mls']],
//                     ['key' => 'latitude', 'value' => $annonce['latitude']],
//                     ['key' => 'longitude', 'value' => $annonce['longitude']],
//                 ],
//                 'categories' => [
//                     ['id' => 400], // Catégorie 'Annonce Immobilière'
//                     ['id' => 251], // Catégorie 'Centris'
//                 ],
//                 'images' => !is_null($image_id) ? [['id' => $image_id]] : []
//             ];

//             // Loguer les données envoyées à WooCommerce pour inspection
//             echo "Données envoyées à WooCommerce : " . json_encode($annonce_data) . "\n";

//             // Préparer la requête vers l'API WooCommerce
//             $ch = curl_init();
//             curl_setopt($ch, CURLOPT_URL, $woocommerce_api_url);
//             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//             curl_setopt($ch, CURLOPT_POST, 1);
//             curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($annonce_data));
//             curl_setopt($ch, CURLOPT_USERPWD, $consumer_key . ":" . $consumer_secret);
//             curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
//             // Exécuter la requête
//             $response = curl_exec($ch);
            
//             // Vérifier si la requête a réussi
//             if (curl_errno($ch)) {
//                 echo 'Erreur cURL : ' . curl_error($ch) . "\n";
//             } else {
//                 $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//                 $response_data = json_decode($response, true);
                
//                 // Afficher la réponse complète de l'API même si le code HTTP est 200
//                 if ($http_code === 200 && is_null($response_data)) {
//                     echo "Réponse HTTP 200 : La requête a été traitée mais sans création. Voici la réponse : NULL\n";
//                 } elseif ($http_code !== 201) {
//                     echo "Erreur HTTP $http_code lors de l'ajout de l'annonce. Réponse complète de l'API :\n" . $response . "\n";
//                 } elseif (isset($response_data['name'])) {
//                     echo 'Annonce ajoutée : ' . $response_data['name'] . "\n";
//                 } else {
//                     echo 'Erreur lors de l\'ajout de l\'annonce : ' . json_encode($response_data) . "\n";
//                 }
//             }
            
//             // Fermer la session cURL
//             curl_close($ch);
//         }
//     } else {
//         echo "Aucune annonce trouvée dans la collection 'centris_full'.\n";
//     }
    
// } catch (Exception $e) {
//     echo "Erreur lors de la connexion à MongoDB ou de l'accès à la collection: " . $e->getMessage() . "\n";
// }
// ?>





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
                'categories' => [['id' => 259]], // Catégorie 'Courtier Immobilier'
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
