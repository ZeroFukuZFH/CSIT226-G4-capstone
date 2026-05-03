<?php
    require_once '../../app/database.php';

    class RoomsController {
        private $db;

        public function __construct() {
            $database = new Database();
            $this->db = $database->conn;
        }

        public function preventUnauthorized() {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['email'])) {
                header('Location: ../login/login_layout.html');
                exit();
            }
        }

        public function getUserTier() {
            return $_SESSION['tier'] ?? 'SILVER';
        }

        public function getUserTierLevel() {
            $tier = $_SESSION['tier'] ?? 'SILVER';
            $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
            return $levels[$tier] ?? 1;
        }

        public function getUserName() {
            return $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest';
        }

        public function bookRoom($roomName, $roomPrice, $floor) {
            $ref = '#TRQ-' . rand(1000, 9999);
            $email = $_SESSION['email'];
            $detail = $roomName . ' - Floor ' . $floor;
            $date = date('M j, Y');
            $status = 'CONFIRMED';

            $stmt = $this->db->prepare("INSERT INTO bookings (email, ref, service, details, booking_date, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $email, $ref, $roomName, $detail, $date, $status);
            return $stmt->execute();
        }
    }

    $roomsController = new RoomsController();
    $roomsController->preventUnauthorized();

    $message = '';
    $messageType = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
        $roomName = $_POST['room_name'] ?? '';
        $roomPrice = $_POST['room_price'] ?? '';
        $floor = (int)($_POST['floor'] ?? 0);

        if ($floor < 1 || $floor > 30) {
            $message = 'Please enter a valid floor number (1–30).';
            $messageType = 'error';
        } elseif ($roomsController->bookRoom($roomName, $roomPrice, $floor)) {
            $message = $roomName . ' booked successfully!';
            $messageType = 'success';
        } else {
            $message = 'Booking failed. Please try again.';
            $messageType = 'error';
        }
    }
?>
