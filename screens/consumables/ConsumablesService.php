<?php
require_once '../database/Database.php';

class ConsumablesService extends Database {

    private string $name;
    private string $accessLevel;
    private int    $guestID;

    public function __construct()
    {
        parent::__construct();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $sql  = 'SELECT guestID, username, accessLevel FROM Guest WHERE email = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $_SESSION['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->guestID     = $row['guestID'];
            $this->name        = $row['username'];
            $this->accessLevel = $row['accessLevel'];
        } else {
            $this->guestID     = 0;
            $this->name        = 'Guest';
            $this->accessLevel = 'None';
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTier(): string
    {
        return $this->accessLevel;
    }

    public function getAllConsumables(): array
    {
        $result = $this->conn->query(
            'SELECT consumableID, name, description, price, accessRequired, imageURL
             FROM Consumable
             ORDER BY name'
        );

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function placeOrder(int $consumableID): array
    {
        $sql  = 'SELECT name, price FROM Consumable WHERE consumableID = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $consumableID);
        $stmt->execute();
        $result = $stmt->get_result();
        $item   = $result->fetch_assoc();

        if (!$item) {
            return ['success' => false, 'message' => 'Item not found.'];
        }

        $sql2  = 'INSERT INTO ConsumableOrder (guestID, consumableID, totalPrice, status) VALUES (?, ?, ?, "Pending")';
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param('iid', $this->guestID, $consumableID, $item['price']);
        $ok = $stmt2->execute();

        if ($ok) {
            return ['success' => true, 'message' => 'Order placed for ' . $item['name'] . '!'];
        } else {
            return ['success' => false, 'message' => 'Failed to place order. Please try again.'];
        }
    }

    public function getMyOrders(): array
    {
        $sql = 'SELECT co.orderID, co.totalPrice, co.status, co.createdAt,
                       c.name AS itemName
                FROM ConsumableOrder co
                JOIN Consumable c ON co.consumableID = c.consumableID
                WHERE co.guestID = ?
                ORDER BY co.createdAt DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->guestID);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function cancelOrder(int $orderID): bool
    {
        $sql  = 'UPDATE ConsumableOrder SET status = "Cancelled"
                 WHERE orderID = ? AND guestID = ? AND status = "Pending"';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $orderID, $this->guestID);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }
}
?>