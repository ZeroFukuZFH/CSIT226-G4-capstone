<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consumables — Tranquiliy Base</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Josefin+Sans:wght@100;300;400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="consumables_style.css">
</head>
<body>

<!-- Toast popup message -->
<div class="toast" id="toast"></div>

<div class="page-wrapper">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="s-crest">✦ HOTEL AND CASINO</div>
      <div class="s-name">TRANQUILIY BASE</div>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Navigation</div>
      <div class="sidebar-item" onclick="window.location.href='../dashboard/dashboard_layout.php'">⊞ Dashboard</div>
      <div class="sidebar-item" onclick="window.location.href='../rooms/rooms_layout.php'">🛏 Rooms</div>
      <div class="sidebar-item" onclick="window.location.href='../booking/booking_layout.php'">📋 Booking Status</div>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Services</div>
      <div class="sidebar-item active-item">🍾 Consumables</div>
      <div class="sidebar-item" onclick="window.location.href='../automotive/automotive_layout.php'">🚗 Automotives</div>
      <div class="sidebar-item locked">🎰 Amusement <span class="lock-icon">🔒</span></div>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Membership</div>
      <div class="sidebar-item" onclick="window.location.href='../upgrade/upgrade_layout.php'">⬆ Upgrade Tier</div>
    </div>

    <div class="sidebar-section">
      <div class="sidebar-section-label">Account</div>
      <div class="sidebar-item" onclick="window.location.href='../profile/profile_layout.php'">👤 My Profile</div>
    </div>

    <!-- Guest info loaded from database -->
    <div class="sidebar-bottom">
      <div class="user-row">
        <div class="user-avatar"><?= $firstLetter ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($guestName) ?></div>
          <div class="user-tier">✦ <?= htmlspecialchars($guestTier) ?> Tier</div>
        </div>
      </div>
      <button class="logout-btn" onclick="window.location.href='../auth/auth_layout.html'">SIGN OUT</button>
    </div>
  </div>
  <!-- END SIDEBAR -->

  <!-- MAIN CONTENT -->
  <div class="main-content">

    <div class="page-header">
      <div class="ph-label">✦ In-Room &amp; Dining</div>
      <h1>Consumables</h1>
    </div>

    <!-- PRODUCT GRID — loaded from consumables table in DB -->
    <div class="product-grid">
      <?php if (!empty($consumables)): ?>
        <?php foreach ($consumables as $item): ?>
          <?php
            // I check if this item is locked for the guest's tier
            $isLocked = !empty($item['accessRequired'])
                        && !canAccess($item['accessRequired'], $guestTier);
          ?>

          <div class="product-card <?= $isLocked ? 'locked-card' : '' ?>">

            <!-- Lock badge for locked items -->
            <?php if ($isLocked): ?>
              <div class="product-lock">🔒</div>
            <?php endif; ?>

            <!-- Emoji icon based on item name -->
            <div class="product-icon"><?= getIcon($item['name'] ?? '') ?></div>

            <!-- Item name and description from database -->
            <div class="product-name"><?= htmlspecialchars($item['name'] ?? '') ?></div>
            <div class="product-desc"><?= htmlspecialchars($item['description'] ?? '') ?></div>

            <!-- Price from database -->
            <?php if ($item['price'] == 0): ?>
              <div class="product-price complimentary-text">Complimentary</div>
            <?php else: ?>
              <div class="product-price">₱<?= number_format($item['price'], 0) ?></div>
            <?php endif; ?>

            <!-- Button or locked label -->
            <?php if ($isLocked): ?>
              <div class="sc-tier-req tier-<?= strtolower($item['accessRequired']) ?>">
                <?= htmlspecialchars($item['accessRequired']) ?>+ Required
              </div>
            <?php else: ?>
              <form method="POST" action="consumables_controller.php">
                <input type="hidden" name="action" value="order">
                <input type="hidden" name="consumableID" value="<?= $item['consumableID'] ?>">
                <button type="submit" class="btn-sm">
                  <?= $item['price'] == 0 ? 'REQUEST' : 'ORDER NOW' ?>
                </button>
              </form>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="color: var(--muted); font-size: 12px; letter-spacing: 2px;">
          No consumables available.
        </div>
      <?php endif; ?>
    </div>
    <!-- END PRODUCT GRID -->

    <!-- MY ORDERS TABLE — shows only if guest has orders -->
    <?php if (!empty($myOrders)): ?>
      <div class="orders-section">
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
                    <form method="POST" action="consumables_controller.php">
                      <input type="hidden" name="action" value="cancel">
                      <input type="hidden" name="orderID" value="<?= $order['orderID'] ?>">
                      <button type="submit" class="btn-cancel">Cancel</button>
                    </form>
                  <?php else: ?>
                    <span style="color: var(--muted); font-size: 9px;">—</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>
  <!-- END MAIN CONTENT -->

</div>

<script>
  // I show a toast popup message at the bottom of the screen
  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
  }

  // I show success or error message on page load
  <?php if (!empty($successMsg)): ?>
    window.onload = () => showToast('✦ <?= addslashes($successMsg) ?>');
  <?php elseif (!empty($errorMsg)): ?>
    window.onload = () => showToast('<?= addslashes($errorMsg) ?>');
  <?php endif; ?>
</script>

</body>
</html>
