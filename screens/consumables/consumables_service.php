<?php

class ConsumablesService extends Database {

    private string $name;
    private string $accessLevel;
    private int    $guestID;

    public function __construct()
    {
        // I call Database constructor so $this->conn is ready
        parent::__construct();

        // I start the session if it hasn't started yet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // I get the guest's info from DB using their session email
        $sql  = 'SELECT guestId, username, accessLevel FROM guest WHERE email = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $_SESSION['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // I save the guest info into the class properties
            $this->guestID     = $row['guestId'];
            $this->name        = $row['username'];
            $this->accessLevel = $row['accessLevel'];
        } else {
            // I set defaults if guest is not found
            $this->guestID     = 0;
            $this->name        = 'Guest';
            $this->accessLevel = 'NONE';
        }
    }

    // I return the guest's name
    public function getName(): string
    {
        return $this->name;
    }

    // I return the guest's tier
    public function getTier(): string
    {
        return $this->accessLevel;
    }

    // I get all consumable items from the consumables table
    public function getAllConsumables(): array
    {
        $result = $this->conn->query(
            'SELECT consumableID, name, description,
                    price, accessRequired, imageURL
             FROM consumables
             ORDER BY name'
        );

        // I return empty array if query fails instead of crashing
        if (!$result) return [];

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // I place an order by inserting into consumableorder table
    public function placeOrder(int $consumableID): array
    {
        // I get the item name and price first
        $sql  = 'SELECT name, price FROM consumables WHERE consumableID = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $consumableID);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        // I return error if item not found
        if (!$item) {
            return ['success' => false, 'message' => 'Item not found.'];
        }

        // I insert the new order into consumableorder
        $sql2  = 'INSERT INTO consumableorder (guestID, consumableID, totalPrice, status)
                  VALUES (?, ?, ?, "Pending")';
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param('iid', $this->guestID, $consumableID, $item['price']);
        $ok = $stmt2->execute();

        if ($ok) {
            return ['success' => true, 'message' => 'Order placed for ' . $item['name'] . '!'];
        } else {
            return ['success' => false, 'message' => 'Failed to place order. Please try again.'];
        }
    }

    // I get all orders made by this guest
    public function getMyOrders(): array
    {
        $sql = 'SELECT co.orderID, co.totalPrice, co.status, co.createdAt,
                       c.name AS itemName
                FROM consumableorder co
                JOIN consumables c ON co.consumableID = c.consumableID
                WHERE co.guestID = ?
                ORDER BY co.createdAt DESC';

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $this->guestID);
        $stmt->execute();

        $result = $stmt->get_result();

        // I return empty array if query fails instead of crashing
        if (!$result) return [];

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // I cancel a pending order by updating its status
    public function cancelOrder(int $orderID): bool
    {
        $sql  = 'UPDATE consumableorder
                 SET status = "Cancelled"
                 WHERE orderID = ? AND guestID = ? AND status = "Pending"';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $orderID, $this->guestID);
        $stmt->execute();

        return $stmt->affected_rows === 1;
    }
}
?>
