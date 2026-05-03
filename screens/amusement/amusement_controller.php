<?php 

require_once '../../app/amusementservice.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class AmusementController {
    private IAmusementService $model;
    public function __construct(IAmusementService $model){
        $this->model = $model;
    }

    public function insert(string $name, string $type, float $price) {
        return $this->model->insert($name, $type, $price);
    }



    public function logout() {
        $_SESSION = [];
        header("Location: ../auth/auth_layout.html");
        session_destroy();
        exit();
    }
}

session_start();

$amusement = new AmusementController(new AmusementService());
if(!isset($_SESSION['email'])){
    header('Location: ../login/login_layout.html');
    session_destroy();
    exit();
}

if (isset($_POST['private_casino'])) {
    $amusement->insert('Private Casino', 'casino', 1000.00);
    header("Location: amusement_layout.php");
    exit();
}

if (isset($_POST['live_performance'])) {
    $amusement->insert('Live Performance', 'performance', 500.00);
    header("Location: amusement_layout.php");
    exit();
}

if (isset($_POST['infinity_pool_club'])) {
    $amusement->insert('Infinity Pool Club', 'pool', 750.00);
    header("Location: amusement_layout.php");
    exit();
}

if (isset($_POST['spa_sanctuary'])) {
    $amusement->insert('Spa Sanctuary', 'spa', 800.00);
    header("Location: amusement_layout.php");
    exit();
}

if (isset($_POST['shooting_range'])) {
    $amusement->insert('Shooting Range', 'range', 600.00);
    header("Location: amusement_layout.php");
    exit();
}

if (isset($_POST['private_island_day'])) {
    $amusement->insert('Private Island Day', 'island', 5000.00);
    header("Location: amusement_layout.php");
    exit();
}

if(isset($_POST['logout'])){
    $amusement->logout();
}

?>