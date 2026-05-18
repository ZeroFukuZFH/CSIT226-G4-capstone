<?php
require_once '../../app/database.php';

class RoomsController {
    private $db;
    public string $lastError = '';

    private static $CATALOG = [ 
        'Deluxe Suite'    => ['price' => 8500.00,  'floor' => 12, 'required' => 1, 'tier_name' => 'SILVER'],
        'Premier Room'    => ['price' => 5200.00,  'floor' => 6,  'required' => 1, 'tier_name' => 'SILVER'],
        'Royal Penthouse' => ['price' => 45000.00, 'floor' => 30, 'required' => 4, 'tier_name' => 'DIAMOND'],
        'Executive Room'  => ['price' => 6800.00,  'floor' => 15, 'required' => 1, 'tier_name' => 'SILVER'],
    ];

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function preventUnauthorized() { //this block ensures na users who loggedin ra maka see ani nga page
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['email'])) { //checks the email to confirm you logging in
            header('Location: ../login/login_layout.html');
            exit();
        }
        if (!isset($_SESSION['tier'])) { //checks users memb level sa database
            $stmt = $this->db->prepare("SELECT accessLevel, username FROM Guest WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['email']); //param for security para di ma trick ang database
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                $_SESSION['tier'] = $row['accessLevel'];
                $_SESSION['name'] = $row['username'];
            }
        }
    }

    public function getUserTier(): string { //mem status
        return $_SESSION['tier'] ?? 'SILVER'; //if unknown jud ang rank kay mo default rank ang silver 
    }

    public function getUserTierLevel(): int {
        $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
        return $levels[$this->getUserTier()] ?? 1;
    }

    public function getUserName(): string {
        return $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest';
    }

    public function getCatalog(): array {
        return self::$CATALOG; //gi call ra ang katong prices nga gi make sa babaw
    }

    public function isRoomAvailable(string $roomName): bool { //diri ma see if available ang rooms or not
        $stmt = $this->db->prepare( //select count kay para ma kita ang total numbers of rooms occupied
            "SELECT COUNT(*) AS cnt
             FROM Room r
             JOIN BookingItem bi ON r.bookingItemId = bi.bookingItemId
             JOIN Booking b ON bi.bookingId = b.bookingId
             WHERE r.RoomType = ?  
               AND bi.ItemType = 'ROOM'
               AND bi.isAvailable = 0
               AND b.bookingType = 'ROOM'"
        ); 
        $stmt->bind_param("s", $roomName); //again security between website and database
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)$row['cnt'] === 0;
    }

    public function bookRoom(string $roomName): bool { //the action when usersn confirm their bookings but goes thru 
                                                      //security check first. naa pa sa catalog ang room, rank sa guest
                                                      //and if available ba jud ang rooms
        $data = self::$CATALOG[$roomName] ?? null;
        if (!$data) {
            $this->lastError = 'invalid';
            return false;
        }
        if ($this->getUserTierLevel() < $data['required']) {
            $this->lastError = 'tier';
            return false;
        }
        if (!$this->isRoomAvailable($roomName)) {
            $this->lastError = 'unavailable';
            return false;
        }

        $email = $_SESSION['email'];
        $price = $data['price'];
        $floor = $data['floor'];

        $stmt = $this->db->prepare("SELECT guestId FROM Guest WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $guest = $stmt->get_result()->fetch_assoc();
        if (!$guest) return false;
        $guestId = $guest['guestId'];

        $bookingType = 'ROOM';
        $stmt = $this->db->prepare("INSERT INTO Booking (guestId, bookingType) VALUES (?, ?)");
        $stmt->bind_param("is", $guestId, $bookingType);
        $stmt->execute();
        $bookingId = $this->db->insert_id;

        $itemType = 'ROOM';
        $isAvailable = 0;
        $stmt = $this->db->prepare("INSERT INTO BookingItem (bookingId, ItemType, Price, isAvailable) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdi", $bookingId, $itemType, $price, $isAvailable);
        $stmt->execute();
        $bookingItemId = $this->db->insert_id; //crucial part. after ma confirm and goes to security officially ma record 
                                                      //na ang booking sa booking status

        $stmt = $this->db->prepare("INSERT INTO Room (bookingItemId, RoomType, Floor) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $bookingItemId, $roomName, $floor);
        return $stmt->execute();
    }
}

$roomsController = new RoomsController(); //para if mo load ang page
$roomsController->preventUnauthorized();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $roomName = $_POST['room_name'] ?? '';

    if ($roomsController->bookRoom($roomName)) {
        $message = htmlspecialchars($roomName) . ' booked successfully!';
        $messageType = 'success';
    } else {
        $errors = [
            'unavailable' => htmlspecialchars($roomName) . ' is currently unavailable.',
            'tier'        => 'Your current tier does not allow this booking.',
            'invalid'     => 'Invalid room selection.',
        ];
        $message = $errors[$roomsController->lastError] ?? 'Booking failed. Please try again.';
        $messageType = 'error';
    }
}
