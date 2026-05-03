<?php
require_once '../../app/database.php';

class UpgradeController {
    private $db;
    public string $lastError = '';

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function preventUnauthorized() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['email'])) {
            header('Location: ../login/login_layout.html');
            exit();
        }
        if (!isset($_SESSION['tier'])) {
            $stmt = $this->db->prepare("SELECT accessLevel, username FROM Guest WHERE email = ?");
            $stmt->bind_param("s", $_SESSION['email']);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row) {
                $_SESSION['tier'] = $row['accessLevel'];
                $_SESSION['name'] = $row['username'];
            }
        }
    }

    public function getUserTier(): string {
        return $_SESSION['tier'] ?? 'SILVER';
    }

    public function getUserTierLevel(): int {
        $levels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
        return $levels[$this->getUserTier()] ?? 1;
    }

    public function getUserName(): string {
        return $_SESSION['name'] ?? $_SESSION['email'] ?? 'Guest';
    }

    public function upgradeTier(string $tier): bool {
        $tierLevels = ['SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];

        if (!array_key_exists($tier, $tierLevels)) {
            $this->lastError = 'invalid';
            return false;
        }
        if ($tierLevels[$tier] === $this->getUserTierLevel()) {
            $this->lastError = 'same';
            return false;
        }

        $email = $_SESSION['email'];
        $stmt  = $this->db->prepare("UPDATE Guest SET accessLevel = ? WHERE email = ?");
        $stmt->bind_param("ss", $tier, $email);
        if ($stmt->execute()) {
            $_SESSION['tier'] = $tier;
            return true;
        }
        return false;
    }
}

$upgradeController = new UpgradeController();
$upgradeController->preventUnauthorized();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upgrade'])) {
    $tier = $_POST['tier'] ?? '';

    if (!empty($tier)) {
        if ($upgradeController->upgradeTier($tier)) {
            $message = 'Successfully upgraded to ' . htmlspecialchars($tier) . ' tier!';
            $messageType = 'success';
        } else {
            $errors = [
                'same'    => 'You are already on this tier.',
                'invalid' => 'Invalid tier selected.',
            ];
            $message = $errors[$upgradeController->lastError] ?? 'Upgrade failed. Please try again.';
            $messageType = 'error';
        }
    }
}
