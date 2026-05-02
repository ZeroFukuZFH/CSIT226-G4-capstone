<?php include 'automotive_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tranquility Base | Automotive Services</title>
    <link rel="stylesheet" href="automotive_style.css">
</head>
<body>

<div class="app-layout">

    <aside class="sidebar">
        <div class="sidebar-top">
            <div class="hotel-label">✦ HOTEL AND CASINO</div>
            <div class="hotel-name">TRANQUILITY<br>BASE</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section-label">NAVIGATION</div>
            <a href="../dashboard/dashboard_layout.html" class="nav-item">
                <span class="nav-icon">⊞</span> DASHBOARD
            </a>
            <a href="../rooms/rooms_layout.html" class="nav-item">
                <span class="nav-icon">—</span> ROOMS
            </a>
            <a href="../bookings/bookings_layout.html" class="nav-item">
                <span class="nav-icon">≡</span> BOOKING STATUS
            </a>

            <div class="nav-section-label">SERVICES</div>
            <a href="../consumables/consumables_layout.html" class="nav-item">
                <span class="nav-icon">🍴</span> CONSUMABLES
            </a>
            <a href="automotive_layout.php" class="nav-item active">
                <span class="nav-icon">🚗</span> AUTOMOTIVES
            </a>
            <a href="../amusement/amusement_layout.html" class="nav-item">
                <span class="nav-icon">🎡</span> AMUSEMENT
            </a>

            <div class="nav-section-label">MEMBERSHIP</div>
            <a href="../upgrade/upgrade_layout.html" class="nav-item">
                <span class="nav-icon">◇</span> UPGRADE TIER
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['name'] ?? $_SESSION['email'] ?? 'G', 0, 1)) ?></div>
            <div class="user-info">
                <div class="user-name"><?= $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest' ?></div>
                <div class="user-tier">✦ <?= $_SESSION['tier'] ?? 'SILVER' ?> TIER</div>
            </div>
            <form method="POST" action="../login/login_controller.php">
                <button type="submit" name="signout" class="btn-signout">SIGN OUT</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <p class="label">✦ CONCIERGE</p>
            <h1>Automotive Services</h1>
        </div>

        <div id="message-area">
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="vehicle-grid">

            <div class="vehicle-card" data-required="1">
                <div class="vehicle-icon">🚗</div>
                <div class="vehicle-name">Airport Transfer</div>
                <div class="vehicle-desc">Luxury sedan with professional chauffeur, meets you at arrival gate</div>
                <div class="vehicle-price">₱2,500</div>
                <form method="POST" action="automotive_layout.php">
                    <input type="hidden" name="vehicle_name" value="Airport Transfer">
                    <input type="hidden" name="vehicle_price" value="₱2,500">
                    <button type="submit" name="book" class="btn-book">BOOK NOW</button>
                </form>
            </div>

            <div class="vehicle-card" data-required="1">
                <div class="vehicle-icon">🚌</div>
                <div class="vehicle-name">City Tour Van</div>
                <div class="vehicle-desc">Full-day guided tour with premium van, up to 8 guests</div>
                <div class="vehicle-price">₱8,000 / day</div>
                <form method="POST" action="automotive_layout.php">
                    <input type="hidden" name="vehicle_name" value="City Tour Van">
                    <input type="hidden" name="vehicle_price" value="₱8,000 / day">
                    <button type="submit" name="book" class="btn-book">BOOK NOW</button>
                </form>
            </div>

            <div class="vehicle-card" data-required="3">
                <span class="lock-icon">🔒</span>
                <div class="vehicle-icon">🏎</div>
                <div class="vehicle-name">Supercar Rental</div>
                <div class="vehicle-desc">Ferrari, Lamborghini, or Porsche available for the discerning guest</div>
                <div class="vehicle-price">₱35,000 / day</div>
                <div class="tier-badge">PLATINUM+ REQUIRED</div>
            </div>

            <div class="vehicle-card" data-required="2">
                <div class="vehicle-icon">⛵</div>
                <div class="vehicle-name">Yacht Transfer</div>
                <div class="vehicle-desc">Private boat from marina to hotel pier</div>
                <div class="vehicle-price">₱12,000</div>
                <div class="tier-badge">GOLD+ REQUIRED</div>
            </div>

            <div class="vehicle-card" data-required="4">
                <span class="lock-icon">🔒</span>
                <div class="vehicle-icon">🚁</div>
                <div class="vehicle-name">Helicopter Transfer</div>
                <div class="vehicle-desc">Private helicopter with panoramic views, any destination</div>
                <div class="vehicle-price">₱85,000</div>
                <div class="tier-badge">✦ DIAMOND REQUIRED</div>
            </div>

            <div class="vehicle-card" data-required="1">
                <div class="vehicle-icon">🚐</div>
                <div class="vehicle-name">Group Shuttle</div>
                <div class="vehicle-desc">Scheduled hotel shuttle to major destinations, every 2hrs</div>
                <div class="vehicle-price">₱500 / person</div>
                <form method="POST" action="automotive_layout.php">
                    <input type="hidden" name="vehicle_name" value="Group Shuttle">
                    <input type="hidden" name="vehicle_price" value="₱500 / person">
                    <button type="submit" name="book" class="btn-book">BOOK NOW</button>
                </form>
            </div>

        </div>
    </main>

</div>

<nav class="bottom-nav">
    <div class="nav-links">
        <a href="../auth/auth_layout.html">AUTH</a>
        <a href="../login/login_layout.html">LOGIN</a>
        <a href="../signup/signup_layout.html">SIGN UP</a>
        <a href="../dashboard/dashboard_layout.html">DASHBOARD</a>
        <a href="../rooms/rooms_layout.html">ROOMS</a>
        <a href="automotive_layout.php" class="active">AUTOMOTIVE</a>
        <a href="../consumables/consumables_layout.html">CONSUMABLES</a>
        <a href="../amusement/amusement_layout.html">AMUSEMENT</a>
        <a href="../upgrade/upgrade_layout.html">UPGRADE TIER</a>
        <a href="../bookings/bookings_layout.html">BOOKINGS</a>
    </div>
</nav>

</body>
</html>
