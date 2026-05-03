<?php 

    require_once 'database.php';
    require_once '../../data/guest.php';
    interface IProfileService {
        public function updateGuest(string $username,string $password, string $email);

    }

    class ProfileService extends Database implements IProfileService {
        
        
        public function updateGuest(string $username, string $password, string $email) {
            

            $sql = "UPDATE Guest SET username=?, password=?, email=? WHERE guestId=?";
            $stmt = $this->conn->prepare($sql);

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bind_param("sssi", $username, $hashedPassword, $email, $_SESSION['id']);

            return $stmt->execute();
        }

    }
?>