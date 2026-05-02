<?php
    class AutomotiveController {
        private $db;

        public function __construct() {
            $this->db = new mysqli('localhost', 'root', '', 'tranquilitybase_schema');
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

        public function getUserTierLevel() {
            $tier = $_SESSION['tier'] ?? 'SILVER';
            $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
            return $levels[$tier] ?? 1;
        }

        public function getUserTier() {
            return $_SESSION['tier'] ?? 'SILVER';
        }

        public function getUserName() {
            return $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest';
        }

        public function bookVehicle($vehicleName, $vehiclePrice) {
            $ref = '#TRQ-' . rand(1000, 9999);
            $email = $_SESSION['email'];
            $date = date('M j, Y');
            $status = 'CONFIRMED';

            $stmt = $this->db->prepare(
                "INSERT INTO bookings (email, ref, service, details, booking_date, status) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssssss", $email, $ref, $vehicleName, $vehiclePrice, $date, $status);
            return $stmt->execute();
        }
    }

    $automotiveController = new AutomotiveController();
    $automotiveController->preventUnauthorized();

    $message = '';
    $messageType = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
        $vehicleName = $_POST['vehicle_name'] ?? '';
        $vehiclePrice = $_POST['vehicle_price'] ?? '';

        if ($automotiveController->bookVehicle($vehicleName, $vehiclePrice)) {
            $message = $vehicleName . ' booked successfully!';
            $messageType = 'success';
        } else {
            $message = 'Booking failed. Please try again.';
            $messageType = 'error';
        }
    }
?>
