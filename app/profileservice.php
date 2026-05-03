<?php 
require_once 'database.php';

interface IProfileService {
    public function updateGuest(string $username, string $password, string $email);
}

class ProfileService extends Database implements IProfileService {
    
    public function updateGuest(string $username, string $password, string $email) {
        $guestId = $_SESSION['id'];
        
        // If password is empty, keep the current password
        if (empty($password)) {
            $sql = "UPDATE Guest SET username=?, email=? WHERE guestId=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $username, $email, $guestId);
        } else {
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE Guest SET username=?, password=?, email=? WHERE guestId=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $username, $hashedPassword, $email, $guestId);
        }
        
        return $stmt->execute();
    }
}
?>