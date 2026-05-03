<?php

require_once '../../app/profileservice.php';

class ProfileController {
    private IProfileService $model;
    
    public function __construct(IProfileService $model){
        $this->model = $model;
    }

    public function editProfile(string $username, string $email, string $password){
        return $this->model->updateGuest($username, $password, $email);
    }

    public function logout() {
            $_SESSION = [];
            header("Location: ../auth/auth_layout.html");
            session_destroy();
            exit();
    }
}

session_start();

$profile = new ProfileController(new ProfileService());

if(!isset($_SESSION['email'])){
    header('Location: ../login/login_layout.html');
    session_destroy();
    exit();
}

if(isset($_POST['update_profile'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if (!empty($password) && $password !== $confirm_password) {
        $_SESSION['error_message'] = "Passwords do not match!";
        header('Location: ../profile/profile_layout.php');
        exit();
    }
    
    // Update profile
    if($profile->editProfile($username, $email, $password)) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
    } 
    
    header('Location: ../profile/profile_layout.php');
    exit();
}

if(isset($_POST['logout'])){
        $profile->logout();
    }

?>