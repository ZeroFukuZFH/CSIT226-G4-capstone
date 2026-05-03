<?php 
    require_once 'database.php';
    
    interface IDashboardService {
        public function getTier(): string;
        public function getName(): string;
    }

    class DashboardService extends Database implements IDashboardService {
        private string $accessLevel;
        private string $name;
        
        public function __construct()
        {
            parent::__construct();
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $sql = 'SELECT username, accessLevel FROM Guest WHERE email=?';
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['email']);
            $stmt->execute();
            
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $this->name = $row['username'];
                $this->accessLevel = $row['accessLevel'];
            } else {
                $this->name = "Guest";
                $this->accessLevel = "None";
            }
        }

        public function getTier(): string {
            return $this->accessLevel;
        }

        public function getName(): string {
            return $this->name;
        }
    }
?>