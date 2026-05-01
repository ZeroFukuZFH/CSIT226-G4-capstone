<?php 
    require_once 'login_model.php';
    class LoginController {
        private LoginModel $model;
        public function __construct(LoginModel $model){
            $this->model = $model;
        }

        public function authenticate(string $email,string $password){
            return $this->model->authenticate($email, $password);
        }
    }


    $email = $_POST["email"];
    $password = $_POST["password"];
    $submit = $_POST["submit"];
    $back = $_POST["back"];
    $remember = $_POST["remember"];

    $loginModel = new LoginModel();
    $loginController = new LoginController($loginModel);
    if(isset($submit)) {
        if($email == "" && $password == ""){
            echo "fields must not be empty";
            return;
        }

        if ($loginController->authenticate($email,$password)){
            echo "login successful";
        } else {
            echo "Invalid email or password";
        }
    }
        
?>