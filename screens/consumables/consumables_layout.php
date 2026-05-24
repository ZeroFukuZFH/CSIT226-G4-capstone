<?php
session_start();

if (empty($_SESSION['email'])) {
    header('Location: /CSIT226-G4-capstone/screens/auth/auth_layout.html');
    exit;
}

require_once __DIR__ . '/../../app/database.php';
require_once __DIR__ . '/consumables_service.php';

$service     = new ConsumablesService();
$guestName   = $service->getName();
$guestTier   = $service->getTier();
$firstLetter = strtoupper(substr($guestName, 0, 1)) ?: 'G';
$consumables = $service->getAllConsumables();
$myOrders    = $service->getMyOrders();
$successMsg  = '';
$errorMsg    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'order') {
        $consumableID = (int) ($_POST['consumableID'] ?? 0);
        $result = $service->placeOrder($consumableID);
        if ($result['success']) {
            $successMsg = $result['message'];
        } else {
            $errorMsg = $result['message'];
        }
        $myOrders = $service->getMyOrders();
    }

    if ($action === 'cancel') {
        $orderID = (int) ($_POST['orderID'] ?? 0);
        $ok = $service->cancelOrder($orderID);
        if ($ok) {
            $successMsg = 'Order cancelled successfully.';
        } else {
            $errorMsg   = 'Could not cancel this order.';
        }
        $myOrders = $service->getMyOrders();
    }
}

function canAccess(string $required, string $guestTier): bool
{
    $tiers = ['NONE' => 0, 'SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
    return ($tiers[strtoupper($guestTier)] ?? 0) >= ($tiers[strtoupper($required)] ?? 0);
}

function getIcon(?string $name): string
{
    if (!$name) return '🍽';
    $name = strtolower($name);
    if (str_contains($name, 'champagne') || str_contains($name, 'sparkling')) return '🍾';
    if (str_contains($name, 'wine'))      return '🍷';
    if (str_contains($name, 'whiskey') || str_contains($name, 'spirit') || str_contains($name, 'scotch')) return '🥃';
    if (str_contains($name, 'coffee') || str_contains($name, 'morning') || str_contains($name, 'tea'))    return '☕';
    if (str_contains($name, 'dining') || str_contains($name, 'sandwich') || str_contains($name, 'meal'))  return '🥩';
    if (str_contains($name, 'chef'))      return '👨‍🍳';
    if (str_contains($name, 'cake') || str_contains($name, 'pastry') || str_contains($name, 'amenity'))   return '🧁';
    if (str_contains($name, 'water'))     return '💧';
    return '🍽';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consumables — Tranquility Base</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="consumables_style.css">
</head>
<body>

<div class="toast" id="toast"></div>

<div class="page-wrapper">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="s-crest">HOTEL AND CASINO</div>
            <div class="s-name">TRANQUILITY<br><strong>BASE</strong></div>
        </div>

        <nav class="sidebar-section">
            <div class="sidebar-section-label">Navigation</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/dashboard/dashboard_layout.php'">Dashboard</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/rooms/rooms_layout.php'">Rooms</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/bookings/bookings_layout.php'">Booking Status</div>
        </nav>

        <nav class="sidebar-section">
            <div class="sidebar-section-label">Services</div>
            <div class="sidebar-item active-item">Consumables</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/automotive/automotive_layout.php'">Automotives</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/amusement/amusement_layout.php'">Amusement</div>
        </nav>

        <nav class="sidebar-section">
            <div class="sidebar-section-label">Membership</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/upgrade/upgrade_layout.php'">Upgrade Tier</div>
        </nav>

        <nav class="sidebar-section">
            <div class="sidebar-section-label">My Account</div>
            <div class="sidebar-item" onclick="window.location.href='/CSIT226-G4-capstone/screens/profile/profile_layout.php'">Edit Profile</div>
        </nav>

        <div class="sidebar-bottom">
            <div class="user-row">
                <div class="user-avatar"><?= $firstLetter ?></div>
                <div class="user-info">
                    <div class="user-name"><?= htmlspecialchars($guestName) ?></div>
                    <div class="user-tier"><?= htmlspecialchars($guestTier) ?> TIER</div>
                </div>
            </div>
            <button class="logout-btn" onclick="window.location.href='/CSIT226-G4-capstone/screens/auth/auth_layout.html'">
                SIGN OUT
            </button>
        </div>
    </aside>

    <main class="main-content">

        <div class="page-header">
            <div class="ph-label">+ IN-ROOM &amp; DINING</div>
            <h1>Consumables</h1>
        </div>

        <div class="product-grid">
            <?php if (!empty($consumables)): ?>
                <?php foreach ($consumables as $item): ?>
                <?php
                    $isLocked = !empty($item['accessRequired']) && !canAccess($item['accessRequired'], $guestTier);
                ?>
                <div class="product-card <?= $isLocked ? 'locked-card' : '' ?>">

                    <?php if ($isLocked): ?>
                    <div class="product-lock">🔒</div>
                    <?php endif; ?>

                    <div class="product-icon"><?= getIcon($item['name'] ?? '') ?></div>
                    <div class="product-name"><?= htmlspecialchars($item['name'] ?? '') ?></div>
                    <div class="product-desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>

                    <?php if ($item['price'] == 0): ?>
                    <div class="product-price complimentary-text">Complimentary</div>
                    <?php else: ?>
                    <div class="product-price">₱<?= number_format($item['price'], 0) ?></div>
                    <?php endif; ?>

                    <?php if ($isLocked): ?>
                    <div class="sc-tier-req tier-<?= strtolower($item['accessRequired']) ?>">
                        <?= htmlspecialchars($item['accessRequired']) ?>+ Required
                    </div>
                    <?php else: ?>
                    <form method="POST" action="/CSIT226-G4-capstone/screens/consumables/consumables_layout.php">
                        <input type="hidden" name="action" value="order">
                        <input type="hidden" name="consumableID" value="<?= $item['consumableID'] ?>">
                        <button type="submit" class="btn-order">
                            <?= $item['price'] == 0 ? 'REQUEST' : 'ORDER NOW' ?>
                        </button>
                    </form>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <div class="empty-state">No consumables available at this time.</div>
            <?php endif; ?>
        </div>

        <?php if (!empty($myOrders)): ?>
        <section class="orders-section">
            <div class="orders-label">✦ My Orders</div>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($myOrders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['itemName']) ?></td>
                    <td>₱<?= number_format($order['totalPrice'], 0) ?></td>
                    <td>
                        <span class="status-badge status-<?= strtolower($order['status']) ?>">
                            <?= htmlspecialchars($order['status']) ?>
                        </span>
                    </td>
                    <td><?= date('M d, Y', strtotime($order['createdAt'])) ?></td>
                    <td>
                        <?php if ($order['status'] === 'Pending'): ?>
                        <form method="POST" action="/CSIT226-G4-capstone/screens/consumables/consumables_layout.php">
                            <input type="hidden" name="action" value="cancel">
                            <input type="hidden" name="orderID" value="<?= $order['orderID'] ?>">
                            <button type="submit" class="btn-cancel">Cancel</button>
                        </form>
                        <?php else: ?>
                        <span class="no-action">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>
        <?php endif; ?>

    </main>

</div>

<script>
    function showToast(msg) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 2800);
    }

    <?php if (!empty($successMsg)): ?>
    window.onload = () => showToast('✦ <?= addslashes($successMsg) ?>');
    <?php elseif (!empty($errorMsg)): ?>
    window.onload = () => showToast('<?= addslashes($errorMsg) ?>');
    <?php endif; ?>
</script>

</body>
</html>