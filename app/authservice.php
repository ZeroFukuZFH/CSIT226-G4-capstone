<?php 
    require_once 'database.php';
    interface ILoginService {
        public function login(string $username, string $password): bool;
    }

    interface ISignupService {
        public function signup(string $username, string $email, string $password);
    }

    class AuthService extends Database implements ILoginService,ISignupService {
        public function login(string $email, string $password): bool {
            $sql = "SELECT * FROM Guest WHERE email=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    return true;
                }
            }
            return false;
        }
        public function signup(string $username, string $email, string $password){
            $defaultAccessLevel = "SILVER";
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO Guest (accessLevel,password,email,username) VALUES (?,?,?,?);";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss",$defaultAccessLevel,$hashedPassword,$email,$username);
            return $stmt->execute();
        }

    }
?>