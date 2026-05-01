<?php 
    require_once 'login_model.php';
    class LoginController {
        private LoginModel $model;
        public function __construct(LoginModel $model){
            $this->model = $model;
        }
        public function preventRevert(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if(isset($_SESSION['email'])){
                header('Location: ../auth/auth_layout.html');
                exit();
            }
        }

        public function authenticate(string $email,string $password){
            return $this->model->authenticate($email, $password);
        }

    }

    $loginModel = new LoginModel();
    $loginController = new LoginController($loginModel);
    $loginController->preventRevert();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {

    $email = $_POST["email"] ?? "";
    $password = $_POST["password"] ?? "";

    if (empty($email) || empty($password)) {
        echo "Fields must not be empty";
    } elseif ($loginController->authenticate($email, $password)) {
        $_SESSION['email'] = $email;
        echo "Login successful";
    } else {
        echo "Invalid email or password";
    }
}
        
?>