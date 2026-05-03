<?php
session_start();

// Handle order request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $item   = $_POST['item'] ?? '';

    if ($action === 'order') {
        // TODO: Save order to database
        echo json_encode(['status' => 'success', 'message' => 'Order placed for: ' . $item]);
        exit;
    }
}
?>

<script>
  // Toast notification
  function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
  }
</script>