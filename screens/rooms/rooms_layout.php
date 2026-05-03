<?php include 'rooms_controller.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tranquility Base | Rooms</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="rooms_style.css">
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
                    <li class="active">Rooms</li>
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
                    <li><a href="../amusement/amusement_layout.html" class="nav-btn">Amusement</a></li>
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
                <div class="avatar"><?= strtoupper(substr($roomsController->getUserName(), 0, 1)) ?></div>
                <div class="user-meta">
                    <div class="user-name"><?= $roomsController->getUserName() ?></div>
                    <div class="user-tier"><?= $roomsController->getUserTier() ?> TIER</div>
                </div>
            </div>
            <div class="sign-out-btn">SIGN OUT</div>
        </div>
    </aside>

    <main>
        <div class="page-header">
            <span class="nav-label no-margin">✦ ACCOMMODATIONS</span>
            <h1>Luxury Rooms</h1>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php
        $tierLevel = $roomsController->getUserTierLevel();

        $rooms = [
            ['name' => 'Deluxe Suite', 'meta' => '42 SQM · OCEAN VIEW · FLOOR 12', 'price' => '₱8,500', 'icon' => '🛏️', 'required' => 1],
            ['name' => 'Premier Room', 'meta' => '26 SQM · GARDEN VIEW · FLOOR 6', 'price' => '₱5,200', 'icon' => '🏨', 'required' => 1],
            ['name' => 'Royal Penthouse', 'meta' => '120 SQM · PANORAMIC · FLOOR 30', 'price' => '₱45,000', 'icon' => '👑', 'required' => 4],
            ['name' => 'Executive Room', 'meta' => '35 SQM · CITY VIEW · FLOOR 15', 'price' => '₱6,800', 'icon' => '🌙', 'required' => 1],
        ];
        ?>

        <div class="room-grid">
            <?php foreach ($rooms as $room): ?>
                <?php $locked = $tierLevel < $room['required']; ?>
                <div class="room-card">
                    <div class="room-image">
                        <span class="room-icon"><?= $room['icon'] ?></span>
                        <div class="room-badge <?= $locked ? 'badge-locked' : 'badge-available' ?>">
                            <?= $locked ? 'DIAMOND ONLY' : 'AVAILABLE' ?>
                        </div>
                    </div>
                    <div class="room-body">
                        <div class="room-name"><?= $room['name'] ?></div>
                        <div class="room-meta"><?= $room['meta'] ?></div>
                        <div class="room-price"><?= $room['price'] ?> <span>/ night</span></div>

                        <?php if ($locked): ?>
                            <div class="room-actions">
                                <div class="requirement-tag">💎 DIAMOND REQUIRED</div>
                                <button class="btn-locked" disabled>LOCKED</button>
                            </div>
                        <?php else: ?>
                            <button class="btn-reserve" onclick="openModal('<?= $room['name'] ?>', '<?= $room['price'] ?>')">RESERVE NOW</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <!-- BOOKING MODAL -->
    <div class="modal-overlay" id="bookModal">
        <div class="modal">
            <button class="modal-close" onclick="closeModal()">✕</button>
            <div class="modal-sub">✦ Room Reservation</div>
            <div class="modal-title" id="modalTitle">Reserve Room</div>

            <form method="POST" action="rooms_layout.php">
                <input type="hidden" id="room_name" name="room_name" value="">
                <input type="hidden" id="room_price" name="room_price" value="">

                <div class="form-field">
                    <label>Room Type</label>
                    <input type="text" id="modalRoomLabel" readonly>
                </div>

                <div class="form-field">
                    <label for="floor">Preferred Floor (1–30)</label>
                    <input type="number" id="floor" name="floor" min="1" max="30" placeholder="e.g. 12" required>
                </div>

                <button type="submit" name="book" class="btn-confirm">CONFIRM RESERVATION →</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">CANCEL</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(roomName, roomPrice) {
            document.getElementById('room_name').value = roomName;
            document.getElementById('room_price').value = roomPrice;
            document.getElementById('modalRoomLabel').value = roomName;
            document.getElementById('modalTitle').textContent = 'Reserve ' + roomName;
            document.getElementById('bookModal').classList.add('active');
        }
        function closeModal() {
            document.getElementById('bookModal').classList.remove('active');
        }
        document.getElementById('bookModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
    </script>

</body>
</html>
