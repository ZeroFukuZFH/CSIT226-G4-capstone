<?php
    require_once '../dashboard/dashboard_controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tranquility Base - Amusement</title>
    <link rel="stylesheet" href="amusement_style.css">
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
                    <li><a href="../dashboard/dashboard_layout.php" class="nav-btn">Dashboard</a></li>
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
                    <li class="active">Amusement</li>
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
    <form method="POST" action="amusement_controller.php">
        
        <div class="services-grid">
            <button type="submit" name="private_casino" class="card-btn">
                <div class="card">
                    <div class="card-icon">🎰</div>
                    <h4>Private Casino</h4>
                    <p>Exclusive gaming lounge with personal dealer</p>
                </div>
            </button>

            <button type="submit" name="live_performance" class="card-btn">
                <div class="card">
                    <div class="card-icon">🎭</div>
                    <h4>Live Performance</h4>
                    <p>Private concerts and theatrical experiences</p>
                </div>
            </button>

            <button type="submit" name="infinity_pool_club" class="card-btn">
                <div class="card">
                    <div class="card-icon">🏊</div>
                    <h4>Infinity Pool Club</h4>
                    <p>VIP pool access with cabana service</p>
                </div>
            </button>

            <button type="submit" name="spa_sanctuary" class="card-btn">
                <div class="card">
                    <div class="card-icon">💆</div>
                    <h4>Spa Sanctuary</h4>
                    <p>World-class spa treatments and wellness programs</p>
                </div>
            </button>

            <button type="submit" name="shooting_range" class="card-btn">
                <div class="card">
                    <div class="card-icon">🎯</div>
                    <h4>Shooting Range</h4>
                    <p>Professional range with range officer and equipment</p>
                </div>
            </button>

            <button type="submit" name="private_island_day" class="card-btn">
                <div class="card">
                    <div class="card-icon">🏝️</div>
                    <h4>Private Island Day</h4>
                    <p>Exclusive island experience for you and guests</p>
                </div>
            </button>
        </div>
    </form>
</main>
    
</body>
</html>