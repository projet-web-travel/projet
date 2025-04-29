<?php
session_start();
// Récupération des informations de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];
$nom = $_SESSION['nom'] ?? '';
$prenom = $_SESSION['prenom'] ?? '';
$email = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRIPPED - Tableau de bord client</title>
    <style>
        :root {
            --primary-color: #00c3ff;
            --secondary-color: #333;
            --light-gray: #f5f5f5;
            --medium-gray: #e0e0e0;
            --dark-gray: #666;
            --white: #fff;
            --success: #4CAF50;
            --warning: #FF9800;
            --danger: #f44336;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f0f2f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        header {
            background-color: var(--white);
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: var(--secondary-color);
        }
        .logo span {
            color: var(--primary-color);
        }
        .user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: bold;
        }
        .user-info {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: bold;
        }
        .user-status {
            font-size: 12px;
            color: var(--dark-gray);
        }
        main {
            padding: 30px 0;
        }
        .welcome-banner {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .welcome-message h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: var(--secondary-color);
        }
        .welcome-message p {
            color: var(--dark-gray);
        }
        .loyalty-points {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 15px 25px;
            border-radius: 8px;
            text-align: center;
        }
        .points-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        .card {
            background-color: var(--white);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--secondary-color);
        }
        .see-all {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 14px;
        }
        .trip-card {
            display: flex;
            align-items: center;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .trip-icon {
            width: 50px;
            height: 50px;
            background-color: var(--light-gray);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .trip-details {
            flex-grow: 1;
        }
        .trip-destination {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .trip-date {
            font-size: 14px;
            color: var(--dark-gray);
        }
        .trip-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-confirmed {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success);
        }
        .status-pending {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--warning);
        }
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .action-btn {
            background-color: var(--light-gray);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            background-color: var(--medium-gray);
        }
        .notification {
            display: flex;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid var(--medium-gray);
        }
        .notification:last-child {
            border-bottom: none;
        }
        .notification-icon {
            width: 30px;
            height: 30px;
            background-color: rgba(0, 195, 255, 0.1);
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .notification-content {
            flex-grow: 1;
        }
        .notification-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .notification-desc {
            color: var(--dark-gray);
            font-size: 14px;
        }
        .notification-time {
            font-size: 12px;
            color: var(--dark-gray);
            margin-top: 5px;
        }
        .offers-gallery {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 15px;
        }
        .offer-card {
            min-width: 200px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .offer-image {
            height: 100px;
            background-color: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .offer-details {
            padding: 15px;
        }
        .offer-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .offer-price {
            color: var(--primary-color);
            font-weight: bold;
        }
        .offer-date {
            font-size: 12px;
            color: var(--dark-gray);
            margin-top: 5px;
        }
        .profile-summary {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 20px 0;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            background-color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .profile-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .profile-email {
            color: var(--dark-gray);
            margin-bottom: 15px;
        }
        .edit-profile-btn {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .edit-profile-btn:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }
        .footer {
            background-color: var(--white);
            padding: 25px 0;
            margin-top: 30px;
            text-align: center;
            color: var(--dark-gray);
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            .welcome-banner {
                flex-direction: column;
                text-align: center;
            }
            .loyalty-points {
                margin-top: 20px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="navbar">
                <div class="logo">
                    <span>TRIPPED</span> Client <div><?php echo($nom); ?></div>
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
                <p>Bienvenue dans votre espace voyageur. Prêt pour votre prochaine aventure?</p>
            </div>
            <div class="loyalty-points">
                <div class="points-value">2,450</div>
                <div>Points Fidélité</div>
            </div>
        </div>
        <div class="dashboard-grid">
            <div class="main-content">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Mes Voyages</div>
                        <a href="#" class="see-all">Tout voir</a>
                    </div>
                    <div class="trip-card">
                        <div class="trip-icon">✈️</div>
                        <div class="trip-details">
                            <div class="trip-destination">Paris → Londres</div>
                            <div class="trip-date">15 mai - 20 mai 2025</div>
                        </div>
                        <div class="trip-status status-confirmed">Confirmé</div>
                    </div>
                    <div class="trip-card">
                        <div class="trip-icon">🏨</div>
                        <div class="trip-details">
                            <div class="trip-destination">Hôtel Royal Plaza, Londres</div>
                            <div class="trip-date">15 mai - 20 mai 2025</div>
                        </div>
                        <div class="trip-status status-confirmed">Confirmé</div>
                    </div>
                    <div class="trip-card">
                        <div class="trip-icon">✈️</div>
                        <div class="trip-details">
                            <div class="trip-destination">Paris → Barcelone</div>
                            <div class="trip-date">12 juin - 18 juin 2025</div>
                        </div>
                        <div class="trip-status status-pending">En attente</div>
                    </div>
                    <div class="quick-actions">
                        <button class="action-btn">📅 Modifier mes réservations</button>
                        <button class="action-btn">🧳 Check-in en ligne</button>
                        <button class="action-btn">🔍 Rechercher un voyage</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Offres Personnalisées</div>
                        <a href="#" class="see-all">Toutes les offres</a>
                    </div>
                    <div class="offers-gallery">
                        <div class="offer-card">
                            <div class="offer-image">🏝️</div>
                            <div class="offer-details">
                                <div class="offer-title">Week-end à Rome</div>
                                <div class="offer-price">À partir de 299€</div>
                                <div class="offer-date">Disponible jusqu'au 15 juin</div>
                            </div>
                        </div>
                        <div class="offer-card">
                            <div class="offer-image">🗼</div>
                            <div class="offer-details">
                                <div class="offer-title">Séjour à Paris</div>
                                <div class="offer-price">À partir de 349€</div>
                                <div class="offer-date">Disponible jusqu'au 30 mai</div>
                            </div>
                        </div>
                        <div class="offer-card">
                            <div class="offer-image">🏖️</div>
                            <div class="offer-details">
                                <div class="offer-title">Plages de Barcelone</div>
                                <div class="offer-price">À partir de 399€</div>
                                <div class="offer-date">Disponible jusqu'au 20 juin</div>
                            </div>
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
                        <button class="edit-profile-btn">Modifier le profil</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Notifications</div>
                        <a href="#" class="see-all">Tout voir</a>
                    </div>
                    <div class="notification">
                        <div class="notification-icon">✓</div>
                        <div class="notification-content">
                            <div class="notification-title">Réservation confirmée</div>
                            <div class="notification-desc">Votre réservation pour Londres a été confirmée.</div>
                            <div class="notification-time">Il y a 2 heures</div>
                        </div>
                    </div>
                    <div class="notification">
                        <div class="notification-icon">💰</div>
                        <div class="notification-content">
                            <div class="notification-title">Promotion disponible</div>
                            <div class="notification-desc">-15% sur les vols vers l'Italie ce mois-ci!</div>
                            <div class="notification-time">Il y a 1 jour</div>
                        </div>
                    </div>
                    <div class="notification">
                        <div class="notification-icon">📝</div>
                        <div class="notification-content">
                            <div class="notification-title">Check-in disponible</div>
                            <div class="notification-desc">Le check-in pour votre vol vers Londres est maintenant ouvert.</div>
                            <div class="notification-time">Il y a 2 jours</div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Assistance</div>
                    </div>
                    <div style="text-align: center; padding: 15px 0;">
                        <div style="margin-bottom: 15px;">Besoin d'aide pour votre voyage?</div>
                        <button class="action-btn" style="width: 100%;">💬 Contacter le service client</button>
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
</body>
</html>
<?php
$pdo = null; // Fermer la connexion proprement
?>