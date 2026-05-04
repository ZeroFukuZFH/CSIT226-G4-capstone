<?php
require_once '../../app/database.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class ConsumablesController {
    private $db;
    public string $lastError = '';

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function logout() {
        $_SESSION = [];
        header("Location: ../auth/auth_layout.html");
        session_destroy();
        exit();
    }

    public function getUserName(): string {
        return $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest';
    }

    public function getUserTier(): string {
        return $_SESSION['tier'] ?? 'SILVER';
    }

    public function getUserTierLevel(): int {
        $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
        return $levels[$this->getUserTier()] ?? 1;
    }

    public function getFirstLetter(): string {
        return strtoupper(substr($this->getUserName(), 0, 1));
    }

    public function loadSession(): void {
        if (!isset($_SESSION['tier'])) {
            $stmt = $this->db->prepare("SELECT accessLevel, username FROM Guest WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['email']);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                $_SESSION['tier'] = $row['accessLevel'];
                $_SESSION['name'] = $row['username'];
            }
        }
    }

    public function placeOrder(string $itemName, float $price): bool {
        $stmt = $this->db->prepare("SELECT guestId FROM Guest WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        $stmt->execute();
        $guest = $stmt->get_result()->fetch_assoc();
        if (!$guest) return false;

        $guestID = $guest['guestId'];

        $stmt2 = $this->db->prepare("SELECT consumableID FROM consumables WHERE name = ?");
        $stmt2->bind_param("s", $itemName);
        $stmt2->execute();
        $item = $stmt2->get_result()->fetch_assoc();
        if (!$item) return false;

        $consumableID = $item['consumableID'];

        $stmt3 = $this->db->prepare(
            "INSERT INTO consumableorder (guestID, consumableID, totalPrice, status) VALUES (?, ?, ?, 'Pending')"
        );
        $stmt3->bind_param("iid", $guestID, $consumableID, $price);
        return $stmt3->execute();
    }

    public function getMyOrders(): array {
        $stmt = $this->db->prepare(
            "SELECT co.orderID, co.totalPrice, co.status, co.createdAt,
                    c.name AS itemName
             FROM consumableorder co
             JOIN consumables c ON co.consumableID = c.consumableID
             JOIN Guest g ON co.guestID = g.guestId
             WHERE g.email = ?
             ORDER BY co.createdAt DESC"
        );
        $stmt->bind_param("s", $_SESSION['email']);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function cancelOrder(int $orderID): bool {
        $stmt = $this->db->prepare("SELECT guestId FROM Guest WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        $stmt->execute();
        $guest = $stmt->get_result()->fetch_assoc();
        if (!$guest) return false;

        $guestID = $guest['guestId'];
        $stmt2 = $this->db->prepare(
            "UPDATE consumableorder SET status = 'Cancelled'
             WHERE orderID = ? AND guestID = ? AND status = 'Pending'"
        );
        $stmt2->bind_param("ii", $orderID, $guestID);
        $stmt2->execute();
        return $stmt2->affected_rows === 1;
    }

    public function getAllConsumables(): array {
        $result = $this->db->query(
            "SELECT consumableID, name, description, price, accessRequired
             FROM consumables ORDER BY name"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

session_start();

$consumables = new ConsumablesController();

if (!isset($_SESSION['email'])) {
    header('Location: ../login/login_layout.html');
    session_destroy();
    exit();
}

$consumables->loadSession();
$message     = '';
$messageType = '';

if (isset($_POST['order_item'])) {
    $itemName = $_POST['item_name'] ?? '';
    $price    = floatval($_POST['item_price'] ?? 0);
    $ok = $consumables->placeOrder($itemName, $price);
    $message     = $ok ? htmlspecialchars($itemName) . ' ordered successfully!' : 'Failed to place order.';
    $messageType = $ok ? 'success' : 'error';
}

if (isset($_POST['cancel_order'])) {
    $orderID = intval($_POST['order_id'] ?? 0);
    $ok = $consumables->cancelOrder($orderID);
    $message     = $ok ? 'Order cancelled successfully.' : 'Could not cancel order.';
    $messageType = $ok ? 'success' : 'error';
}

if (isset($_POST['logout'])) {
    $consumables->logout();
}

function getIcon(string $name): string {
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

function getRequiredLevel(string $required): int {
    $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
    return $levels[strtoupper($required)] ?? 0;
}
?>
