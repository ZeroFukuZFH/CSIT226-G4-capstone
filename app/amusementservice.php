<?php 
    require_once 'database.php';
    interface IAmusementService {
        public function insert(string $name, string $type,float $price);
    }

    class AmusementService extends Database implements IAmusementService {
        public function insert(string $name, string $type,float $price) {

            $booking_sql = "INSERT INTO Booking (guestId, bookingType) VALUES (?, ?)";
            $stmt2 = $this->conn->prepare($booking_sql);
            $guestId = $_SESSION['id'];
            $bookingType = "Amusement";
            $stmt2->bind_param("is", $guestId, $bookingType);
            $stmt2->execute();
            $bookingId = $this->conn->insert_id;
            
            $bookingItem_sql = "INSERT INTO BookingItem (bookingId, ItemType, Price, isAvailable) VALUES (?, ?, ?, ?)";
            $stmt3 = $this->conn->prepare($bookingItem_sql);
            $itemType = "Amusement";
            $isAvailable = 1;
            $stmt3->bind_param("isdi", $bookingId, $itemType, $price, $isAvailable);
            $stmt3->execute();
            $bookingItemId = $this->conn->insert_id;
            
            $amusement_sql = "INSERT INTO Amusement (bookingItemId, Name, AmusementType) VALUES (?, ?, ?)";
            $stmt1 = $this->conn->prepare($amusement_sql);
            $stmt1->bind_param("iss", $bookingItemId, $name, $type);
            $stmt1->execute();
            
            return $bookingItemId;
        }
    }
?>