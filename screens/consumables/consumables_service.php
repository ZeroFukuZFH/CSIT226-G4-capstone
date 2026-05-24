<?php

// this is the service class for consumables — all the database logic lives here
// gi-extend nato ang Database para ma-access ang $this->conn directly
class ConsumablesService extends Database {

    private string $name;
    private string $accessLevel;
    private int    $guestID;

    public function __construct()
    {
        // call the parent Database constructor so $this->conn is ready to use
        parent::__construct();

        // start the session if wala pa siya nagsugod
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // kuhaon nato ang guest info from the database using their session email
        $sql  = 'SELECT guestId, username, accessLevel FROM guest WHERE email = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $_SESSION['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // i-save ang guest info sa class properties para magamit sa ubang methods
            $this->guestID     = $row['guestId'];
            $this->name        = $row['username'];
            $this->accessLevel = $row['accessLevel'];
        } else {
            // fallback defaults kung wala ma-find ang guest — just in case
            $this->guestID     = 0;
            $this->name        = 'Guest';
            $this->accessLevel = 'NONE';
        }
    }

    // ibalik ang pangalan sa guest — ginagamit sa sidebar display
    public function getName(): string
    {
        return $this->name;
    }

    // ibalik ang tier sa guest — ginagamit para check kung may access ba siya
    public function getTier(): string
    {
        return $this->accessLevel;
    }

    // kuhaon tanan consumable items from the database, sorted by name
    public function getAllConsumables(): array
    {
        $result = $this->conn->query(
            'SELECT consumableID, name, description,
                    price, accessRequired, imageURL
             FROM consumables
             ORDER BY name'
        );

        // ibalik empty array nalang kung mag-fail ang query para dili mag-crash ang page
        if (!$result) return [];

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // mag-place ug order — mag-insert sa consumableorder table with Pending status
    public function placeOrder(int $consumableID): array
    {
        // una kuhaon ang item name and price para ma-validate siya
        $sql  = 'SELECT name, price FROM consumables WHERE consumableID = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $consumableID);
        $stmt->execute();
        $item = $stmt->get_result()->fetch_assoc();

        // kung wala ma-find ang item, ibalik dayon ug error — ayaw na proceed
        if (!$item) {
            return ['success' => false, 'message' => 'Item not found.'];
        }

        // insert the new order into the consumableorder table
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

    // kuhaon tanan orders sa current guest, pinaka-bag-o ang una
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

        // ibalik empty array nalang kung mag-fail — safer ni kaysa mag-crash
        if (!$result) return [];

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // i-cancel ang order by updating its status to Cancelled
    // only works if the order is still Pending and belongs to this guest
    public function cancelOrder(int $orderID): bool
    {
        $sql  = 'UPDATE consumableorder
                 SET status = "Cancelled"
                 WHERE orderID = ? AND guestID = ? AND status = "Pending"';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $orderID, $this->guestID);
        $stmt->execute();

        // kung affected_rows is 1, meaning na-cancel gyud siya — ibalik true
        return $stmt->affected_rows === 1;
    }
}
?>