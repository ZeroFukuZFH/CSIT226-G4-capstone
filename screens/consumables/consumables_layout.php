<?php require_once 'consumables_controller.php'; ?>
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

<div class="toast" id="toast"></div>

<div class="modal-overlay" id="orderModal">
  <div class="modal">
    <div class="modal-label">✦ Place Order</div>
    <div class="modal-title" id="modalItemName"></div>
    <div class="modal-price" id="modalItemPrice"></div>
    <div class="modal-actions">
      <button class="btn-primary" onclick="confirmOrder()">CONFIRM ORDER</button>
      <button class="btn-outline" onclick="closeModal()">CANCEL</button>
    </div>
  </div>
</div>

<div class="page-wrapper">

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
    <div class="sidebar-bottom">
      <div class="user-row">
        <div class="user-avatar"><?= $initial ?></div>
        <div class="user-info">
          <div class="user-name"><?= htmlspecialchars($guestName) ?></div>
          <div class="user-tier">✦ <?= htmlspecialchars($guestTier) ?> Tier</div>
        </div>
      </div>
      <button class="logout-btn" onclick="window.location.href='../auth/auth_layout.php'">SIGN OUT</button>
    </div>
  </div>

  <div class="main-content">
    <div class="page-header">
      <div class="ph-label">✦ In-Room & Dining</div>
      <h1>Consumables</h1>
    </div>

    <div class="consumables-grid">
      <?php foreach ($consumables as $item): ?>

        <?php
          $isLocked = !empty($item['accessRequired'])
                      && $item['accessRequired'] !== $guestTier
                      && $guestTier !== 'Platinum';
        ?>

        <div class="consumable-card <?= $isLocked ? 'card-locked' : '' ?>">

          <?php if ($isLocked): ?>
            <div class="lock-badge">🔒</div>
          <?php endif; ?>

          <?php if (!empty($item['imageURL'])): ?>
            <img src="<?= htmlspecialchars($item['imageURL']) ?>" class="card-icon" alt="">
          <?php else: ?>
            <div class="card-icon-emoji">🍾</div>
          <?php endif; ?>

          <div class="card-name"><?= htmlspecialchars($item['name']) ?></div>
          <div class="card-desc"><?= htmlspecialchars($item['description']) ?></div>
          <div class="card-price">
            <?= $isLocked ? 'P' . number_format($item['price'], 0) . '+' : 'P' . number_format($item['price'], 0) ?>
          </div>

          <?php if ($isLocked): ?>
            <div class="card-locked-label"><?= htmlspecialchars($item['accessRequired']) ?>+ REQUIRED</div>
          <?php elseif ($item['price'] == 0): ?>
            <button class="btn-card-order"
              onclick="openModal(<?= $item['consumableID'] ?>, '<?= addslashes($item['name']) ?>', 'Complimentary')">
              REQUEST
            </button>
          <?php else: ?>
            <button class="btn-card-order"
              onclick="openModal(<?= $item['consumableID'] ?>, '<?= addslashes($item['name']) ?>', 'P<?= number_format($item['price'], 0) ?>')">
              ORDER NOW
            </button>
          <?php endif; ?>

        </div>

      <?php endforeach; ?>
    </div>
  </div>

</div>

<script>
  let selectedID = null;

  function openModal(id, name, price) {
    selectedID = id;
    document.getElementById('modalItemName').textContent  = name;
    document.getElementById('modalItemPrice').textContent = price;
    document.getElementById('orderModal').classList.add('show');
  }

  function closeModal() {
    document.getElementById('orderModal').classList.remove('show');
    selectedID = null;
  }

  async function confirmOrder() {
    const data = new FormData();
    data.append('action',       'place_order');
    data.append('consumableID', selectedID);

    const res  = await fetch('consumables_controller.php', { method: 'POST', body: data });
    const json = await res.json();

    closeModal();
    showToast(json.message, !json.success);
  }

  async function cancelOrder(orderID) {
    if (!confirm('Cancel this order?')) return;

    const data = new FormData();
    data.append('action',  'cancel_order');
    data.append('orderID', orderID);

    const res  = await fetch('consumables_controller.php', { method: 'POST', body: data });
    const json = await res.json();
    showToast(json.message, !json.success);

    if (json.success) setTimeout(() => location.reload(), 1400);
  }

  function showToast(msg, isErr) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className   = 'toast' + (isErr ? ' err-toast' : '');
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
  }

  document.getElementById('orderModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });
</script>

</body>
</html>