<?php
/**
 * Fonctions pour l'API Gemini et le traitement des données
 */

/**
 * Récupère des recommandations générées par l'API Gemini
 * 
 * @param string $prompt Le prompt à envoyer à l'API
 * @return string|null La réponse de l'API ou null en cas d'erreur
 */
function getGeminiRecommendations($prompt) {
    $apiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
    $apiKey = 'AIzaSyDu55pNQg9Msb031WerH_m_pmaiZWEXlsY';
    
    $data = [
        'contents' => [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ];
    
    $ch = curl_init($apiEndpoint . '?key=' . $apiKey);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $decodedResponse = json_decode($response, true);
    
    if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
        return $decodedResponse['candidates'][0]['content']['parts'][0]['text'];
    }
    
    return null;
}

/**
 * Analyse les recommandations de voyage pour les transformer en tableau
 * 
 * @param string $travelRecommendations La chaîne de caractères contenant les recommandations
 * @return array Tableau contenant les données structurées
 */
function parseTravelRecommendations($travelRecommendations) {
    $trips = [];
    if ($travelRecommendations) {
        $tripEntries = explode("\n\n", trim($travelRecommendations));
        foreach ($tripEntries as $entry) {
            $lines = explode("\n", $entry);
            $trip = [];
            foreach ($lines as $line) {
                if (strpos($line, 'Destination:') === 0) {
                    $trip['destination'] = trim(str_replace('Destination:', '', $line));
                } elseif (strpos($line, 'Dates:') === 0) {
                    $trip['dates'] = trim(str_replace('Dates:', '', $line));
                } elseif (strpos($line, 'Description:') === 0) {
                    $trip['description'] = trim(str_replace('Description:', '', $line));
                } elseif (strpos($line, 'Statut:') === 0) {
                    $trip['status'] = trim(str_replace('Statut:', '', $line));
                }
            }
            if (!empty($trip)) {
                $trips[] = $trip;
            }
        }
    }
    return $trips;
}

/**
 * Analyse les recommandations d'offres pour les transformer en tableau
 * 
 * @param string $offersRecommendations La chaîne de caractères contenant les offres
 * @return array Tableau contenant les données structurées
 */
function parseOfferRecommendations($offersRecommendations) {
    $offers = [];
    if ($offersRecommendations) {
        $offerEntries = explode("\n\n", trim($offersRecommendations));
        foreach ($offerEntries as $entry) {
            $lines = explode("\n", $entry);
            $offer = [];
            foreach ($lines as $line) {
                if (strpos($line, 'Titre:') === 0) {
                    $offer['title'] = trim(str_replace('Titre:', '', $line));
                } elseif (strpos($line, 'Prix:') === 0) {
                    $offer['price'] = trim(str_replace('Prix:', '', $line));
                } elseif (strpos($line, 'Dates:') === 0) {
                    $offer['dates'] = trim(str_replace('Dates:', '', $line));
                } elseif (strpos($line, 'Description:') === 0) {
                    $offer['description'] = trim(str_replace('Description:', '', $line));
                }
            }
            if (!empty($offer)) {
                $offers[] = $offer;
            }
        }
    }
    return $offers;
}

/**
 * Fournit des données de voyage par défaut en cas d'échec de l'API
 * 
 * @return array Données de voyage par défaut
 */
function getFallbackTrips() {
    return [
        [
            'destination' => 'Paris → Londres',
            'dates' => '15 mai - 20 mai 2025',
            'description' => 'Vol direct avec Air France',
            'status' => 'Confirmé'
        ],
        [
            'destination' => 'Hôtel Royal Plaza, Londres',
            'dates' => '15 mai - 20 mai 2025',
            'description' => 'Chambre Deluxe avec petit-déjeuner',
            'status' => 'Confirmé'
        ],
        [
            'destination' => 'Paris → Barcelone',
            'dates' => '12 juin - 18 juin 2025',
            'description' => 'Vol avec correspondance',
            'status' => 'En attente'
        ]
    ];
}

/**
 * Fournit des offres par défaut en cas d'échec de l'API
 * 
 * @return array Offres par défaut
 */
function getFallbackOffers() {
    return [
        [
            'title' => 'Week-end à Rome',
            'price' => 'À partir de 299€',
            'dates' => 'Disponible jusqu\'au 15 juin',
            'description' => 'Vol + hôtel 3* en centre-ville'
        ],
        [
            'title' => 'Séjour à Paris',
            'price' => 'À partir de 349€',
            'dates' => 'Disponible jusqu\'au 30 mai',
            'description' => '2 nuits dans un hôtel 4* près de la Tour Eiffel'
        ],
        [
            'title' => 'Plages de Barcelone',
            'price' => 'À partir de 399€',
            'dates' => 'Disponible jusqu\'au 20 juin',
            'description' => '5 jours/4 nuits avec vue sur mer'
        ]
    ];
}           