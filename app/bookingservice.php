<?php 
    session_start();  // ADD THIS - REQUIRED FOR $_SESSION
    require_once 'database.php';

    class Bookings {
        public int $bookingId;
        public string $itemType;
        public float $price;
        
        public function __construct(int $bookingId, string $itemType, float $price) {
            $this->bookingId = $bookingId;
            $this->itemType = $itemType;
            $this->price = $price;
        }
    }

    interface IBookingService {
        public function getUserBookingItems(): array;
    }
    
    class BookingService extends Database implements IBookingService {
        public function getUserBookingItems(): array {
            $bookingItems = [];
            
            // CHECK IF SESSION EXISTS
            if (!isset($_SESSION['id'])) {
                return $bookingItems;  // Return empty if no session
            }
            
            $sql = "SELECT b.*, bi.ItemType, bi.Price 
                    FROM Booking b 
                    JOIN BookingItem bi ON b.bookingId = bi.bookingId 
                    WHERE b.guestId = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $_SESSION['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $bookingItems[] = new Bookings(
                    $row['bookingId'],
                    $row['ItemType'],
                    (float)$row['Price']  // REMOVED THE TRAILING COMMA
                );
            }
            
            $stmt->close();
            return $bookingItems;
        }
    }
?>