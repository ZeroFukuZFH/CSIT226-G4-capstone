<?php

require_once '../../app/profileservice.php';
class ProfileController {
  private IProfileService $model;
  public function __construct(IProfileService $model){
    $this->model = $model;
  }

  public function preventDefault(){
    if(session_status() === PHP_SESSION_NONE){
      session_start();
    }
  }

  public function updateGuest(string $username,string $password, string $email){
    if (!isset($_SESSION['id'])) {
      return "User not logged in.";
    }
    $this->model->updateGuest($username,$password,$email);
  }
}

$profile = new ProfileController(new ProfileService());
$profile->preventDefault();

if(isset($_POST['submit'])){
  $firstName = $_POST['first_name'] ?? '';
  $lastName  = $_POST['last_name'] ?? '';
  $email     = $_POST['email'] ?? '';

  $currentPass = $_POST['current_password'] ?? '';
  $newPass     = $_POST['new_password'] ?? '';
  $confirmPass = $_POST['confirm_password'] ?? '';

  $fullName = $firstName . ' ' . $lastName;
  if ($newPass !== $confirmPass) {
    echo 'New password does not match confirm password.';
  } else {
    $profile->updateGuest($fullName,$email,$newPass);
  }
}
?>
