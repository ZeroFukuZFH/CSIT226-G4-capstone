<?php 
    session_start();
    require_once '../dashboard/dashboard_controller.php';
    require_once '../../app/bookingservice.php';
    echo "<!-- Debug: Session ID = " . (isset($_SESSION['id']) ? $_SESSION['id'] : 'NOT SET') . " -->";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tranquility Base - Bookings</title>
    <link rel="stylesheet" href="bookings_style.css">
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
                <li class="active"><a href="../bookings/bookings_layout.php" class="nav-btn">Booking Status</a></li>
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
                <div class="user-tier"><?php echo $dashboard->getTier() . ' '; ?>TIER</div>
            </div>
        </div>
        <form method="POST" action="../dashboard/dashboard_controller.php">
            <button name="logout" type="submit" class="sign-out-btn">SIGN OUT</button>
        </form>
    </div>
</aside>

<main>
    <div class="bookings-container">
        <h1>Booking Status</h1>
        
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>REF #</th>
                    <th>TYPE</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    
                    $bookings = new BookingService();
                    $list = $bookings->getUserBookingItems();

                    foreach ($list as $item) {
                        echo '<tr>';
                        echo '<td>' . $item->bookingId . '</td>';
                        echo '<td>' . $item->itemType . '</td>';  // Changed to lowercase 'itemType'
                        echo '<td>$' . number_format($item->price, 2) . '</td>';
                        echo '</tr>';
                    }
                    
                    if (empty($list)) {
                        echo '<tr><td colspan="3" style="text-align: center;">No bookings found</td></tr>';
                    }

                ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>