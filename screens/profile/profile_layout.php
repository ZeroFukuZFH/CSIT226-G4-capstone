<?php
require_once '../dashboard/dashboard_controller.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tranquility Base - Edit Profile</title>
    <link rel="stylesheet" href="profile_style.css">
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
                <li class="active"><a href="../profile/profile_layout.php" class="nav-btn">Edit Profile</a></li>
            </ul>
        </nav>
    </div>

    <div class="user-profile">
        <div class="profile-info">
            <div class="avatar"></div>
            <div class="user-meta">
                <div class="user-name"><?php echo $dashboard->getName(); ?></div>
                <div class="user-tier"><?php echo $dashboard->getTier() . ' TIER'; ?></div>
            </div>
        </div>
        <form method="POST" action="profile_controller.php">
            <button name="logout" type="submit" class="sign-out-btn">SIGN OUT</button>
        </form>
    </div>
</aside>

<main>
    <form method="POST" action="profile_controller.php">
        <div class="profile-form-container">
            <h2>Edit Profile</h2>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password">
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm new password">
            </div>
            
            <button type="submit" name="update_profile" class="btn-save">Save Changes</button>
        </div>
    </form>
</main>

</body>
</html>