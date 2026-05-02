<?php 
    require_once '../../app/dashboardservice.php';
    class DashboardController {
        private IDashboardService $model;
        public function __construct(IDashboardService $model){
            $this->model = $model;
        }
        
        public function protectRoute(){
            if (session_status() === PHP_SESSION_NONE) session_start();

            if(!isset($_SESSION['email'])){
                header('Location: ../login/login_layout.html');
                session_destroy();
                exit();
            }
        }
        public function getTier(): string{
            return $this->model->getTier();
        }

        public function getName(): string{
            return $this->model->getName();
        }
    }

    $dashboard = new DashboardController(new DashboardService());
    $dashboard->protectRoute();

    if (isset($_POST['btn_bookings'])) {
        // route here!!!
        exit();
    }

    if (isset($_POST['btn_rooms'])) {
        // route here!!!
        exit();
    }

    if (isset($_POST['btn_consumables'])) {
        // route here!!!
        exit();
    }

    if (isset($_POST['btn_automotives'])) {
        // route here!!!
        exit();
    }

    if (isset($_POST['btn_amusement'])) {
        // route here!!!
        exit();
    }

?>