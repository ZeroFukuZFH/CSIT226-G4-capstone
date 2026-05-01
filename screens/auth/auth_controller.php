<?php 
    class AuthController {
        public function navigateToLogin(){
            header('Location: ../login/login_layout.html');
        }
        public function navigateToSignUp() {
            header('Location: ../signup/signup_layout.html');
        }        
    }

    $auth = new AuthController();

    if(isset($_POST['login'])) {
        $auth->navigateToLogin();
    }
    
    if (isset($_POST['signup'])) {
        $auth->navigateToSignUp();
    }
    
?>