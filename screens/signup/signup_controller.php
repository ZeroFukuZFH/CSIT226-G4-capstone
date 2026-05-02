<?php 
    require_once '../../app/authservice.php';
    
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    class SignupController {
        private ISignupService $model;

        public function preventRevert(){
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if(isset($_SESSION['email'])){
                header('Location: ../auth/auth_layout.html');
                exit();
            }
        }

        public function __construct(ISignupService $model)
        {
            $this->model = $model;
        }

        public function signup(string $username, string $email, string $password){
            return $this->model->signup($username,$email,$password);
        }
    }

    $signupController = new SignupController(new AuthService());
    $signupController->preventRevert();

    // add back 
    
    if (isset($_POST['submit'])) {
        $fName = $_POST['fname'];
        $lName = $_POST['lname'];
        $email = $_POST['email'];
        $pass  = $_POST['password'];
        $conf  = $_POST['confirmpassword'];

        if ($pass !== $conf) {
            die("Passwords do not match!");
        }

        $username = $fName . ' ' . $lName;
        $success = $signupController->signup($username, $email, $pass);
        if($success){
            // reserved for dashboard
            exit();
        } 
        
    }


?>