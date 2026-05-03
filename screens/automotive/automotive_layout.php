<?php include 'automotive_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tranquility Base | Automotive Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="automotive_style.css">
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
                    <li><a href="../bookings/bookings_layout.php" class="nav-btn">Booking Status</a></li>
                </ul>
            </nav>
        </div>

        <div class="nav-section">
            <span class="nav-label">Services</span>
            <nav>
                <ul>
                    <li><a href="../consumables/consumables_layout.php" class="nav-btn">Consumables</a></li>
                    <li class="active">Automotives</li>
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

        <div class="user-profile">
            <div class="profile-info">
                <div class="avatar"><?= htmlspecialchars(strtoupper(substr($automotiveController->getUserName(), 0, 1))) ?></div>
                <div class="user-meta">
                    <div class="user-name"><?= htmlspecialchars($automotiveController->getUserName()) ?></div>
                    <div class="user-tier"><?= htmlspecialchars($automotiveController->getUserTier()) ?> TIER</div>
                </div>
            </div>
            <form method="POST" action="../signout.php">
                <button type="submit" class="sign-out-btn">SIGN OUT</button>
            </form>
        </div>
    </aside>

    <main>
        <div class="page-header">
            <span class="label no-margin">✦ CONCIERGE</span>
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

</body>
</html>
