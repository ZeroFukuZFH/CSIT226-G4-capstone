<?php 
    require_once '../../app/dashboardservice.php';
    class DashboardController {
        private IDashboardService $model;
        public function __construct(IDashboardService $model){
            $this->model = $model;
        }
        
        public function getTier(): string{
            return $this->model->getTier();
        }

        public function getName(): string{
            return $this->model->getName();
        }

        public function logout() {
            $_SESSION = [];
            header("Location: ../auth/auth_layout.html");
            session_destroy();
            exit();
        }
    }
    session_start();

    $dashboard = new DashboardController(new DashboardService());
    if(!isset($_SESSION['email'])){
        header('Location: ../login/login_layout.html');
        session_destroy();
        exit();
    }

    if(isset($_POST['logout'])){
        $dashboard->logout();
    }

?>