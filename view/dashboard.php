<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
            height: 100vh;
            background-image: url('images/pexels-scottwebb-3255761.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .top-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .content h1 {
            font-size: 38px;
            color: #333;
            margin-right: 80px;
        }

        .search {
            border: 1px solid #ddd;
            padding: 5px 15px;
            border-radius: 20px;
            width: 400px;
            height: 35px;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .user-info i {
            font-size: 16px;
            color: #333;
            margin-right: 10px;
        }

        .user-info span {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">TRIPPED</div>
        <a href="#"><i class="fas fa-house-user"></i> Dashboard</a>
        <a href="#"><i class="fas fa-calendar-day"></i> Bookings</a>
        <a href="#"><i class="fas fa-bus"></i> Transports</a>
        <a href="#"><i class="fas fa-users"></i> Customers</a>
        <a href="#"><i class="fas fa-star-half-alt"></i> Reviews</a>
        <a href="dashboard-event.php"><i class="fas fa-route"></i> Events</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Settings</a>
        <a href="event-front.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="top-content">
            <h1>Dashboard</h1>
            <input type="text" class="search" placeholder="Search...">
            <div class="user-info">
                <i class="fas fa-user"></i>
                <span>My account</span>
            </div>
        </div>

        <div class="merged-card">
            <div class="card-item">
                <div class="card-title">People Going</div>
                <div class="card-value">274</div>
                <div class="card-description">24% more people than last trip with some itinerary</div>
            </div>
            <div class="card-item">
                <div class="card-title">Destinations Covered</div>
                <div class="card-value">12</div>
                <div class="card-description">12 places to visit in 7 days trip itinerary</div>
            </div>
            <div class="card-item">
                <div class="card-title">People Interested</div>
                <div class="card-value">540</div>
                <div class="card-description">540 showed interest in going for this trip</div>
            </div>
        </div>

        <div class="stats-info">
            <h2>Stats Info</h2>
            <div class="stat-cards">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-title">Active Users</div>
                    <div class="stat-value">1,200</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-title">New Bookings</div>
                    <div class="stat-value">45</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>