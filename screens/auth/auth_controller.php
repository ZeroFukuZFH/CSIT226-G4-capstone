<?php 
    class AuthController {
        public function navigateToLogin(){
            print "You are on the login page";
        }
        public function navigateToSignUp() {
            print "You are on the sign up page";
        }        
    }

    $auth = new AuthController();
    if(isset($_POST['login'])) {
        $auth->navigateToLogin();
    }
    else if (isset($_POST['signup'])) {
        $auth->navigateToSignUp();
    }
    
?>