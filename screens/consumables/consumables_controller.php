<?php
session_start();

require_once '../../app/database.php';
require_once 'consumables_service.php';

$service    = new ConsumablesService();
$guestName  = $service->getName();
$guestTier  = $service->getTier();
$firstLetter = strtoupper(substr($guestName, 0, 1));
$consumables = $service->getAllConsumables();
$myOrders   = $service->getMyOrders();
$successMsg = '';
$errorMsg   = '';

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'order') {
        $consumableID = intval($_POST['consumableID'] ?? 0);
        $result = $service->placeOrder($consumableID);
        if ($result['success']) {
            $successMsg = $result['message'];
        } else {
            $errorMsg = $result['message'];
        }
        // Refresh orders after placing
        $myOrders = $service->getMyOrders();
    }

    if ($action === 'cancel') {
        $orderID = intval($_POST['orderID'] ?? 0);
        $ok = $service->cancelOrder($orderID);
        if ($ok) {
            $successMsg = 'Order cancelled successfully.';
        } else {
            $errorMsg = 'Could not cancel order.';
        }
        $myOrders = $service->getMyOrders();
    }
}

// Helper: check if guest tier can access item
function canAccess(string $required, string $guestTier): bool {
    $tiers = ['NONE' => 0, 'SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
    $req   = strtoupper($required);
    $guest = strtoupper($guestTier);
    return ($tiers[$guest] ?? 0) >= ($tiers[$req] ?? 0);
}

// Helper: get icon based on item name
function getIcon(string $name): string {
    $name = strtolower($name);
    if (str_contains($name, 'champagne') || str_contains($name, 'sparkling')) return '🍾';
    if (str_contains($name, 'wine'))      return '🍷';
    if (str_contains($name, 'whiskey') || str_contains($name, 'spirit') || str_contains($name, 'scotch')) return '🥃';
    if (str_contains($name, 'coffee') || str_contains($name, 'morning') || str_contains($name, 'tea'))    return '☕';
    if (str_contains($name, 'dining') || str_contains($name, 'sandwich') || str_contains($name, 'meal'))  return '🥩';
    if (str_contains($name, 'chef'))      return '👨‍🍳';
    if (str_contains($name, 'cake') || str_contains($name, 'pastry') || str_contains($name, 'amenity'))   return '🧁';
    if (str_contains($name, 'water'))     return '💧';
    return '🍽';
}

// Load the layout
require_once 'consumables_layout.html';
?>