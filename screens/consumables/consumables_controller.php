<?php
require_once '../database/Database.php';
require_once 'ConsumablesService.php';

$service = new ConsumablesService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $action = $_POST['action'] ?? '';

    if ($action === 'place_order') {
        $consumableID = (int) $_POST['consumableID'];
        echo json_encode($service->placeOrder($consumableID));
        exit;
    }

    if ($action === 'cancel_order') {
        $orderID = (int) $_POST['orderID'];
        $ok      = $service->cancelOrder($orderID);
        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Order cancelled.' : 'Could not cancel order.',
        ]);
        exit;
    }
}

$consumables = $service->getAllConsumables();
$myOrders    = $service->getMyOrders();
$guestName   = $service->getName();
$guestTier   = $service->getTier();
$initial     = strtoupper(substr($guestName, 0, 1)) ?: 'G';
?>