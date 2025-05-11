<?php
session_start();
// Récupération des informations de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'] ?? '';
$nom = $_SESSION['nom'] ?? '';
$prenom = $_SESSION['prenom'] ?? '';
$email = $_SESSION['email'] ?? '';

// Inclusion des fonctions API
require_once '../../controller/recommendationsController.php';

// Définir les régions disponibles
$available_regions = [
'europe' => 'Europe',
'asie' => 'Asie',
'amerique' => 'Amérique',
'afrique' => 'Afrique',
'oceanie' => 'Océanie'
];

// Récupérer la région sélectionnée (par défaut 'europe')
$selected_region = $_GET['region'] ?? 'europe';
if (!array_key_exists($selected_region, $available_regions)) {
$selected_region = 'europe';
}

// Get personalized travel recommendations
$travelPrompt = "Génère 3 recommandations de voyages personnalisées pour $prenom $nom qui aime voyager en " . $available_regions[$selected_region] . ".
Formatte la réponse comme suit pour chaque recommandation:
Destination: [ville]
Dates: [date de début] - [date de fin]
Description: [description courte en français]
Statut: [Confirmé/En attente]";

$travelRecommendations = getGeminiRecommendations($travelPrompt);

// Parse travel recommendations
$trips = [];
if ($travelRecommendations) {
$trips = parseTravelRecommendations($travelRecommendations);
}

// Get personalized offers
$offersPrompt = "Génère 3 offres de voyage personnalisées pour $prenom $nom qui voyage souvent en " . $available_regions[$selected_region] . ".
Formatte la réponse comme suit pour chaque offre:
Titre: [titre de l'offre]
Prix: [prix en euros]
Dates: [dates de validité]
Description: [description courte en français]";

$offersRecommendations = getGeminiRecommendations($offersPrompt);

// Parse offers recommendations
$offers = [];
if ($offersRecommendations) {
$offers = parseOfferRecommendations($offersRecommendations);
}

// Fallback data if API fails
if (empty($trips)) {
$trips = getFallbackTrips();
}

if (empty($offers)) {
$offers = getFallbackOffers();
}

// Add the custom offer
$customOffer = [
'title' => 'Escapade citadine romantique à Rome',
'price' => '850€ (pour deux personnes)',
'dates' => 'Disponible jusqu\'au 30 juin 2025',
'description' => 'Vol direct + hôtel 4* en centre historique pour un weekend romantique'
];
$offers[] = $customOffer;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TRIPPED - Tableau de bord client</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/dashboardstyle.css">
<style>
.region-selector {
margin-bottom: 20px;
padding: 15px;
background-color: #f9f9f9;
border-radius: 8px;
box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.region-selector form {
display: flex;
align-items: center;
gap: 15px;
}
.region-selector label {
font-weight: 600;
color: #333;
margin-right: 10px;
}
.region-selector select {
padding: 8px 15px;
border-radius: 6px;
border: 1px solid #ddd;
background-color: white;
font-size: 14px;
min-width: 200px;
}
.region-selector button {
padding: 8px 15px;
border-radius: 6px;
border: none;
background-color: #4a89dc;
color: white;
font-weight: 600;
cursor: pointer;
transition: background-color 0.2s;
}
.region-selector button:hover {
background-color: #3a70c0;
}
.loading-indicator {
display: none;
margin-left: 10px;
}
.loading-indicator i {
animation: spin 1s linear infinite;
}
@keyframes spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}
</style>
</head>
<body>
<header>
<div class="container">
<div class="navbar">
<div class="logo">
<i class="fas fa-plane logo-icon"></i>
<span>TRIPPED</span> Client
</div>
<div class="user-nav">
<div class="user-avatar"><?= htmlspecialchars(substr($nom, 0, 1) . substr($prenom, 0, 1)) ?></div>
<div class="user-info">
<div class="user-name"><?= htmlspecialchars($nom . ' ' . $prenom) ?></div>
<div class="user-status">Client Premium</div>
</div>
</div>
</div>
</div>
</header>
<main class="container">
<div class="welcome-banner">
<div class="welcome-message">
<h1>Bonjour, <?= htmlspecialchars($prenom) ?>!</h1>
<p>Bienvenue dans votre espace voyageur. Prêt pour votre prochaine aventure? Consultez vos voyages à venir et découvrez nos offres personnalisées.</p>
</div>
<div class="loyalty-points">
<div class="points-value">2,450</div>
<div>Points Fidélité</div>
</div>
</div>
<div class="dashboard-grid">
<div class="main-content">
<div class="region-selector">
<form id="regionForm" method="GET" action="">
<label for="region">Choisissez votre région de voyage :</label>
<select name="region" id="region">
<?php foreach ($available_regions as $key => $region): ?>
<option value="<?= $key ?>" <?= $key === $selected_region ? 'selected' : '' ?>><?= $region ?></option>
<?php endforeach; ?>
</select>
<button type="submit">Mettre à jour les recommandations</button>
<div class="loading-indicator" id="loadingIndicator">
<i class="fas fa-spinner"></i> Chargement...
</div>
</form>
</div>
<div class="card">
<div class="card-header">
<div class="card-title">
<i class="fas fa-suitcase"></i>
Mes Voyages en <?= $available_regions[$selected_region] ?>
</div>
<a href="#" class="see-all">
Tout voir
<i class="fas fa-chevron-right"></i>
</a>
</div>
<?php foreach ($trips as $trip): ?>
<div class="trip-card">
<div class="trip-icon">
<?= strpos($trip['destination'] ?? '', 'Hôtel') !== false ? '🏨' : '✈️' ?>
</div>
<div class="trip-details">
<div class="trip-destination"><?= htmlspecialchars($trip['destination'] ?? '') ?></div>
<div class="trip-date"><?= htmlspecialchars($trip['dates'] ?? '') ?></div>
<div class="trip-description"><?= htmlspecialchars($trip['description'] ?? '') ?></div>
<div class="trip-status-container">
<div class="trip-status <?= ($trip['status'] ?? '') === 'Confirmé' ? 'status-confirmed' : 'status-pending' ?>">
<?= htmlspecialchars($trip['status'] ?? 'En attente') ?>
</div>
</div>
</div>
</div>
<?php endforeach; ?>
<div class="quick-actions">
<button class="action-btn">
<i class="fas fa-calendar-alt"></i>
Modifier mes réservations
</button>
<button class="action-btn">
<i class="fas fa-ticket-alt"></i>
Check-in en ligne
</button>
<button class="action-btn primary">
<i class="fas fa-search"></i>
Rechercher un voyage
</button>
</div>
</div>
<div class="card">
<div class="card-header">
<div class="card-title">
<i class="fas fa-percent"></i>
Offres Personnalisées pour <?= $available_regions[$selected_region] ?>
</div>
<a href="#" class="see-all">
Toutes les offres
<i class="fas fa-chevron-right"></i>
</a>
</div>
<div class="offers-container">
<div class="offers-header" id="offersToggle">
<div class="card-title">
<i class="fas fa-gift"></i>
Vos offres spéciales (<?= count($offers) ?>)
</div>
<div class="offers-toggle">
<span>Voir moins</span>
<i class="fas fa-chevron-up"></i>
</div>
</div>
<div class="offers-gallery" id="offersGallery">
<?php foreach ($offers as $offer): ?>
<div class="offer-card">
<div class="offer-image">
<?php
$emoji = '✈️';
$title = $offer['title'] ?? '';
if (strpos($title, 'Rome') !== false) $emoji = '🏛️';
elseif (strpos($title, 'Paris') !== false) $emoji = '🗼';
elseif (strpos($title, 'Barcelone') !== false) $emoji = '🏖️';
elseif (strpos($title, 'romantique') !== false) $emoji = '💖';
elseif (strpos($title, 'Tokyo') !== false) $emoji = '🗾';
elseif (strpos($title, 'New York') !== false) $emoji = '🗽';
elseif (strpos($title, 'Safari') !== false) $emoji = '🦁';
elseif (strpos($title, 'Sydney') !== false) $emoji = '🦘';
echo $emoji;
?>
</div>
<div class="offer-details">
<div class="offer-title"><?= htmlspecialchars($offer['title'] ?? '') ?></div>
<div class="offer-price"><?= htmlspecialchars($offer['price'] ?? '') ?></div>
<div class="offer-date"><?= htmlspecialchars($offer['dates'] ?? '') ?></div>
<div class="offer-description"><?= htmlspecialchars($offer['description'] ?? '') ?></div>
</div>
</div>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
<div class="sidebar">
<div class="card">
<div class="profile-summary">
<div class="profile-avatar"><?= htmlspecialchars(substr($nom, 0, 1) . substr($prenom, 0, 1)) ?></div>
<div class="profile-name"><?= htmlspecialchars($nom . ' ' . $prenom) ?></div>
<div class="profile-email"><?= htmlspecialchars($email) ?></div>
<button class="edit-profile-btn">
<i class="fas fa-user-edit"></i>
Modifier le profil
</button>
</div>
</div>
<div class="card">
<div class="card-header">
<div class="card-title">
<i class="fas fa-bell"></i>
Notifications
</div>
<a href="#" class="see-all">
Tout voir
<i class="fas fa-chevron-right"></i>
</a>
</div>
<div class="notification">
<div class="notification-icon">
<i class="fas fa-check"></i>
</div>
<div class="notification-content">
<div class="notification-title">Réservation confirmée</div>
<div class="notification-desc">Votre réservation pour Londres a été confirmée.</div>
<div class="notification-time">Il y a 2 heures</div>
</div>
</div>
<div class="notification">
<div class="notification-icon">
<i class="fas fa-tag"></i>
</div>
<div class="notification-content">
<div class="notification-title">Promotion disponible</div>
<div class="notification-desc">-15% sur les vols vers l'Italie ce mois-ci!</div>
<div class="notification-time">Il y a 1 jour</div>
</div>
</div>
<div class="notification">
<div class="notification-icon">
<i class="fas fa-check-in"></i>
</div>
<div class="notification-content">
<div class="notification-title">Check-in disponible</div>
<div class="notification-desc">Le check-in pour votre vol vers Londres est maintenant ouvert.</div>
<div class="notification-time">Il y a 2 jours</div>
</div>
</div>
</div>
<div class="card">
<div class="assistance-card">
<div class="assistance-icon">
<i class="fas fa-headset"></i>
</div>
<div class="assistance-text">
Besoin d'aide pour votre voyage? Notre équipe est disponible 24/7.
</div>
<button class="action-btn primary" style="width: 100%;">
<i class="fas fa-comment-dots"></i>
Contacter le service client
</button>
</div>
</div>
</div>
</div>
</main>
<footer class="footer">
<div class="container">
© 2025 TRIPPED Travel Agency. Tous droits réservés.
</div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
// Toggle offers
const offersToggle = document.getElementById('offersToggle');
const offersGallery = document.getElementById('offersGallery');
const toggleIcon = offersToggle.querySelector('.fa-chevron-up');
const toggleText = offersToggle.querySelector('span');
offersToggle.addEventListener('click', function() {
if (offersGallery.style.display === 'none') {
offersGallery.style.display = 'grid';
toggleIcon.classList.remove('fa-chevron-down');
toggleIcon.classList.add('fa-chevron-up');
toggleText.textContent = 'Voir moins';
} else {
offersGallery.style.display = 'none';
toggleIcon.classList.remove('fa-chevron-up');
toggleIcon.classList.add('fa-chevron-down');
toggleText.textContent = 'Voir plus';
}
});

// Loading indicator
const regionForm = document.getElementById('regionForm');
const loadingIndicator = document.getElementById('loadingIndicator');

regionForm.addEventListener('submit', function() {
loadingIndicator.style.display = 'inline-block';
});
});
</script>
</body>
</html>