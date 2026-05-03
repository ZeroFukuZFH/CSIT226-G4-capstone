<?php
require_once '../../app/database.php';

class AutomotiveController {
    private $db;
    public string $lastError = '';

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
        if (!isset($_SESSION['tier']) || !isset($_SESSION['guestId'])) {
            $stmt = $this->db->prepare("SELECT guestId, accessLevel, username FROM Guest WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['email']);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                $_SESSION['tier']    = $row['accessLevel'];
                $_SESSION['name']    = $row['username'];
                $_SESSION['guestId'] = $row['guestId'];
            }
        }
    }

    public function getUserTierLevel(): int {
        $tier = $_SESSION['tier'] ?? 'SILVER';
        $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
        return $levels[$tier] ?? 1;
    }

    public function getUserTier(): string {
        return $_SESSION['tier'] ?? 'SILVER';
    }

    public function getUserName(): string {
        return $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest';
    }

    public function bookVehicle(string $vehicleName, string $vehiclePrice): bool {
        if (!isset($_SESSION['guestId'])) { $this->lastError = 'no_guest'; return false; }
        $guestId = $_SESSION['guestId'];

        $bookingType = 'AUTOMOTIVE';
        $stmt = $this->db->prepare("INSERT INTO Booking (guestId, bookingType) VALUES (?, ?)");
        $stmt->bind_param("is", $guestId, $bookingType);
        if (!$stmt->execute()) { $this->lastError = 'booking_fail: ' . $this->db->error; return false; }
        $bookingId = $this->db->insert_id;

        $itemType = 'AUTOMOTIVE';
        $isAvailable = 0;
        $price = (float) preg_replace('/[^\d.]/', '', $vehiclePrice);
        $stmt = $this->db->prepare("INSERT INTO BookingItem (bookingId, ItemType, Price, isAvailable) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdi", $bookingId, $itemType, $price, $isAvailable);
        if (!$stmt->execute()) { $this->lastError = 'bookingitem_fail: ' . $this->db->error; return false; }
        $bookingItemId = $this->db->insert_id;

        $stmt = $this->db->prepare("INSERT INTO Automotives (bookingItemId, VehicleType, Model) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $bookingItemId, $vehicleName, $vehiclePrice);
        if (!$stmt->execute()) { $this->lastError = 'automotives_fail: ' . $this->db->error; return false; }
        return true;
    }
}

$automotiveController = new AutomotiveController();
$automotiveController->preventUnauthorized();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $vehicleName  = $_POST['vehicle_name']  ?? '';
    $vehiclePrice = $_POST['vehicle_price'] ?? '';

    if ($automotiveController->bookVehicle($vehicleName, $vehiclePrice)) {
        $message = htmlspecialchars($vehicleName) . ' booked successfully!';
        $messageType = 'success';
    } else {
        $message = 'Booking failed: ' . $automotiveController->lastError;
        $messageType = 'error';
    }
}
?>
