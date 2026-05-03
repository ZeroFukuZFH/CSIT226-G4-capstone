<?php
include 'rooms_controller.php';
require_once '../dashboard/dashboard_controller.php';
?>
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
                    <li><a href="../bookings/bookings_layout.php" class="nav-btn">Booking Status</a></li>
                </ul>
            </nav>
        </div>

        <div class="nav-section">
            <span class="nav-label">Services</span>
            <nav>
                <ul>
                    <li><a href="../consumables/consumables_layout.php" class="nav-btn">Consumables</a></li>
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
                    <div class="user-tier"><?php echo $dashboard->getTier(); ?> TIER</div>
                </div>
            </div>
            <form method="POST" action="../dashboard/dashboard_controller.php">
                <button name="logout" type="submit" class="sign-out-btn">SIGN OUT</button>
            </form>
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
        $catalog   = $roomsController->getCatalog();

        $rooms = [
            ['name' => 'Deluxe Suite',    'meta' => '42 SQM · OCEAN VIEW',  'icon' => '🛏️'],
            ['name' => 'Premier Room',    'meta' => '26 SQM · GARDEN VIEW', 'icon' => '🏨'],
            ['name' => 'Royal Penthouse', 'meta' => '120 SQM · PANORAMIC',  'icon' => '👑'],
            ['name' => 'Executive Room',  'meta' => '35 SQM · CITY VIEW',   'icon' => '🌙'],
        ];
        ?>

        <div class="room-grid">
            <?php foreach ($rooms as $room):
                $data      = $catalog[$room['name']];
                $locked    = $tierLevel < $data['required'];
                $available = !$locked && $roomsController->isRoomAvailable($room['name']);
                $price     = '₱' . number_format($data['price']);
                $floor     = $data['floor'];
                $tierName  = $data['tier_name'];
            ?>
                <div class="room-card">
                    <div class="room-image">
                        <span class="room-icon"><?= $room['icon'] ?></span>
                        <div class="room-badge <?= $locked ? 'badge-locked' : ($available ? 'badge-available' : 'badge-booked') ?>">
                            <?php if ($locked): ?>
                                <?= htmlspecialchars($tierName) ?> ONLY
                            <?php elseif ($available): ?>
                                AVAILABLE
                            <?php else: ?>
                                BOOKED
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="room-body">
                        <div class="room-name"><?= htmlspecialchars($room['name']) ?></div>
                        <div class="room-meta"><?= htmlspecialchars($room['meta']) ?> · FLOOR <?= $floor ?></div>
                        <div class="room-price"><?= $price ?> <span>/ night</span></div>

                        <?php if ($locked): ?>
                            <div class="room-actions">
                                <div class="requirement-tag">Requires <?= htmlspecialchars($tierName) ?> Tier</div>
                                <button class="btn-locked" disabled>LOCKED</button>
                            </div>
                        <?php elseif (!$available): ?>
                            <button class="btn-locked" disabled>UNAVAILABLE</button>
                        <?php else: ?>
                            <button class="btn-reserve"
                                onclick="openModal('<?= htmlspecialchars($room['name'], ENT_QUOTES) ?>', '<?= $price ?>', <?= $floor ?>)">
                                RESERVE NOW
                            </button>
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

                <div class="form-field">
                    <label>Room Type</label>
                    <input type="text" id="modalRoomLabel" readonly>
                </div>

                <div class="form-field">
                    <label>Floor</label>
                    <input type="text" id="modalFloorLabel" readonly>
                </div>

                <button type="submit" name="book" class="btn-confirm">CONFIRM RESERVATION →</button>
                <button type="button" class="btn-cancel" onclick="closeModal()">CANCEL</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(roomName, roomPrice, floor) {
            document.getElementById('room_name').value = roomName;
            document.getElementById('modalRoomLabel').value = roomName;
            document.getElementById('modalFloorLabel').value = 'Floor ' + floor;
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
