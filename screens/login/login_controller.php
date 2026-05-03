<?php 
    require_once '../../app/authservice.php';

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    class LoginController {
        private ILoginService $model;
        public function __construct(ILoginService $model){
            $this->model = $model;
        }
        public function preventRevert(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if(isset($_SESSION['email'])){
                header('Location: ../dashboard/dashboard_layout.php');
                exit();
            }
        }

        public function authenticate(string $email,string $password){
            return $this->model->login($email,$password);
        }

    }
    
    $loginController = new LoginController(new AuthService());
    $loginController->preventRevert();

    // add back option later
       

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        if (empty($email) || empty($password)) {
            echo "Fields must not be empty";
            return;
        } 
        
        if ($loginController->authenticate($email, $password)) {
            $_SESSION['email'] = $email;
            header('Location: ../dashboard/dashboard_layout.php');
            exit();
        } 
    }
        
?>