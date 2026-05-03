<?php

require_once 'dashboard_controller.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tranquility Base | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="dashboard_layout.css">
</head>
<body>
    

    <aside>
        <div class="logo">
            HOTEL AND CASINO<br>
            <strong>TRANQUILITY<br>BASE</strong>
        </div>

        <div class="nav-section">
            <span class="nav-label">Navigation</span>
            <nav>
                <ul>
                    <li class="active">Dashboard</li>
                    <li><a href="../rooms/rooms_layout.php" class="nav-btn">Rooms</a></li>
                    <li><a href="../bookings/bookings_layout.html" class="nav-btn">Booking Status</a></li>
                </ul>
            </nav>
        </div>

        <div class="nav-section">
            <span class="nav-label">Services</span>
            <nav>
                <ul>
                    <li><a href="../consumables/consumables_layout.html" class="nav-btn">Consumables</a></li>
                    <li><a href="../automotive/automotive_layout.php" class="nav-btn">Automotives</a></li>
                    <li><a href="../amusement/amusement_layout.php" class="nav-btn">Amusement</a></li>
                </ul>
            </nav>
        </div>

        <div class="nav-section">
            <span class="nav-label">Membership</span>
            <nav>
                <ul>
                    <li><a href="../upgrade/upgrade_layout.php" class="nav-btn">Upgrade Tier</a></li>
                </ul>
            </nav>
        </div>

        <div class="nav-section">
            <span class="nav-label">My Account</span>
            <nav>
                <ul>
                    <li><a href="../profile/profile_layout.php" class="nav-btn">Edit Profile</a></li>
                </ul>
            </nav>
        </div>

        <div class="user-profile">
            <div class="profile-info">
                <div class="avatar"></div>
                <div class="user-meta">
                    <div class="user-name"><?php echo $dashboard->getName(); ?></div>
                    <div class="user-tier"><?php echo $dashboard->getTier() . ' ';?>TIER</div>
                </div>
            </div>
            <form method="POST" action="dashboard_controller.php">
                <button name="logout" type="submit" class="sign-out-btn">SIGN OUT</button>
            </form>
        </div>
    </aside>

    <main>
        <header class="header-top">
            <div>
                <span class="nav-label no-margin">Member Portal</span>
                <h1>Dashboard</h1>
            </div>
        </header>

        <section class="welcome-banner">
            <div class="banner-text">
                <h2>Welcome back, <?php echo $dashboard->getName(); ?></h2>
                <p>✦ Your Tranquility Base experience awaits</p>
            </div>
            <div class="tier-badge">
                <h3><?php echo $dashboard->getTier(); ?></h3>
                <p class="sub-label">CURRENT TIER</p>
            </div>
        </section>

        <h3 class="section-title">✦ Services</h3>
        
        <div class="services-grid">
            <a href="../rooms/rooms_layout.php" class="nav-btn">
                <div class="card">
                    <div class="card-icon">🛏️</div>
                    <h4>Rooms</h4>
                    <p>Browse and reserve luxury accommodations</p>
                </div>
            </a>
            
            <a href="../automotive/automotive_layout.php" class="nav-btn">
                <div class="card" >
                    <div class="card-icon">🚗</div>
                    <h4>Automotives</h4>
                    <p>Premium vehicle concierge services</p>
                </div>
            </a>

            
            <a href="../consumables/consumables_layout.html" class="nav-btn">
                <div class="card">
                    <div class="card-icon">🍾</div>
                    <h4>Consumables</h4>
                    <p>Fine dining & curated in-room selections</p>
                </div>
            </a>
            
            <a href="../amusement/amusement_layout.php" class="nav-btn">
                <div class="card">
                    <div class="card-icon">🏛️</div>
                    <h4>Amusement</h4>
                    <p>Exclusive entertainment experiences</p>
                </div>
            </a>
            
            <a href="../upgrade/upgrade_layout.php" class="nav-btn">
                <div class="card">
                    <div class="card-icon">⬆️</div>
                    <h4>Upgrade Tier</h4>
                    <p>Unlock premium access and benefits</p>
                </div>
            </a>
            
            <a href="../bookings/bookings_layout.html" class="nav-btn">
                <div class="card">
                    <div class="card-icon">📋</div>
                    <h4>Bookings</h4>
                    <p>View and manage your reservations</p>
                </div>
            </a>
            
        </div>
    </main>

</body>
</html>