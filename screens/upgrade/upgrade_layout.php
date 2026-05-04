<?php
include 'upgrade_controller.php';
require_once '../dashboard/dashboard_controller.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tranquility Base | Upgrade Tier</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="upgrade_style.css">
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
                    <li><a href="../consumables/consumables_layout.html" class="nav-btn">Consumables</a></li>
                    <li><a href="../automotive/automotive_layout.php" class="nav-btn">Automotives</a></li>
                    <li><a href="../amusement/amusement_layout.html" class="nav-btn">Amusement</a></li>
                </ul>
            </nav>
        </div>

        <div class="nav-section">
            <span class="nav-label">Membership</span>
            <nav>
                <ul>
                    <li class="active">Upgrade Tier</li>
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
                    <div class="user-tier"><?php echo $dashboard->getTier(); ?> TIER</div>
                </div>
            </div>
            <form method="POST" action="../dashboard/dashboard_controller.php">
                <button name="logout" type="submit" class="sign-out-btn">SIGN OUT</button>
            </form>
        </div>
    </aside>

    <main>
        <header class="header-top">
            <div>
                <span class="nav-label no-margin">Member Portal</span>
                <h1>Access Tiers</h1>
            </div>
        </header>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php
        $currentLevel = $upgradeController->getUserTierLevel();
        $currentTier = $upgradeController->getUserTier();

        $tiers = [
            ['name' => 'SILVER', 'level' => 1, 'price' => 'Free', 'icon' => '◇', 'perks' => ['Room Bookings', 'Consumables', 'Automotive (Basic)', 'Booking Tracking']],
            ['name' => 'GOLD', 'level' => 2, 'price' => '₱5,000/mo', 'icon' => '✦', 'perks' => ['All Silver Perks', 'Amusement Access', 'Rare Spirits Menu', 'Priority Booking']],
            ['name' => 'PLATINUM', 'level' => 3, 'price' => '₱15,000/mo', 'icon' => '❋', 'perks' => ['All Gold Perks', 'Supercar Rental', 'Private Chef', 'Spa Sanctuary']],
            ['name' => 'DIAMOND', 'level' => 4, 'price' => '₱50,000/mo', 'icon' => '◈', 'perks' => ['All Platinum Perks', 'Royal Penthouse', 'Helicopter Transfer', 'Private Island Day']],
        ];
        ?>

        <h3 class="section-title">✦ Choose Your Tier</h3>

        <div class="tier-grid">
            <?php foreach ($tiers as $tier): ?>
                <div class="card <?= $tier['name'] === $currentTier ? 'current-tier' : '' ?>">
                    <div class="card-icon"><?= $tier['icon'] ?></div>
                    <h4><?= $tier['name'] ?></h4>
                    <div class="tier-price"><?= $tier['price'] ?></div>
                    <div class="tier-perks">
                        <?php foreach ($tier['perks'] as $perk): ?>
                            <div class="tier-perk">✓ <?= $perk ?></div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($tier['name'] === $currentTier): ?>
                        <button class="btn-active" disabled>ACTIVE PLAN</button>
                    <?php elseif ($tier['level'] > $currentLevel): ?>
                        <form method="POST" action="upgrade_layout.php">
                            <input type="hidden" name="tier" value="<?= $tier['name'] ?>">
                            <label class="payment-confirm">
                                <input type="checkbox" name="payment_confirmed" required>
                                I authorize the charge of <?= $tier['price'] ?>
                            </label>
                            <button type="submit" name="upgrade" class="btn-upgrade">UPGRADE →</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="upgrade_layout.php">
                            <input type="hidden" name="tier" value="<?= $tier['name'] ?>">
                            <button type="submit" name="upgrade" class="btn-upgrade">DOWNGRADE →</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

</body>
</html>
