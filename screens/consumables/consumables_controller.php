<?php
session_start();
require_once '../../app/database.php';
require_once 'consumables_service.php';

// I create the service which connects to DB and loads guest info
$service     = new ConsumablesService();
$guestName   = $service->getName();
$guestTier   = $service->getTier();
$firstLetter = strtoupper(substr($guestName, 0, 1)) ?: 'G';
$consumables = $service->getAllConsumables();
$myOrders    = $service->getMyOrders();
$successMsg  = '';
$errorMsg    = '';

// I handle form submissions from ORDER NOW and CANCEL buttons
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Guest placed an order
    if ($action === 'order') {
        $consumableID = (int) ($_POST['consumableID'] ?? 0);
        $result = $service->placeOrder($consumableID);
        if ($result['success']) {
            $successMsg = $result['message'];
        } else {
            $errorMsg = $result['message'];
        }
        // I refresh the orders list after placing
        $myOrders = $service->getMyOrders();
    }

    // Guest cancelled an order
    if ($action === 'cancel') {
        $orderID = (int) ($_POST['orderID'] ?? 0);
        $ok = $service->cancelOrder($orderID);
        if ($ok) {
            $successMsg = 'Order cancelled successfully.';
        } else {
            $errorMsg = 'Could not cancel this order.';
        }
        // I refresh the orders list after cancelling
        $myOrders = $service->getMyOrders();
    }
}

// I check if the guest's tier can access a locked item
function canAccess(string $required, string $guestTier): bool
{
    $tiers = ['NONE' => 0, 'SILVER' => 1, 'GOLD' => 2, 'PLATINUM' => 3, 'DIAMOND' => 4];
    $req   = strtoupper($required);
    $guest = strtoupper($guestTier);
    return ($tiers[$guest] ?? 0) >= ($tiers[$req] ?? 0);
}

// I pick an emoji icon based on the item name
function getIcon(?string $name): string
{
    // I return default if name is empty or null
    if (!$name) return '🍽';

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

// I load the layout file to display the page
require_once 'consumables_layout.php';
?>
