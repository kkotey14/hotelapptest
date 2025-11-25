<?php
require 'db.php';
require 'auth.php';

require_login();
require_role(['admin', 'staff']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_dashboard.php');
    exit;
}

verify_csrf_token();

$booking_id = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

$allowed_actions = ['approve', 'deny', 'delete'];

if ($booking_id > 0 && in_array($action, $allowed_actions)) {
    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$booking_id]);
    } else {
        // Check if the booking is actually pending
        $stmt = $pdo->prepare("SELECT status FROM bookings WHERE id = ?");
        $stmt->execute([$booking_id]);
        $current_status = $stmt->fetchColumn();

        if ($current_status === 'pending') {
            $new_status = ($action === 'approve') ? 'confirmed' : 'cancelled'; // Deny sets status to cancelled
            
            $update = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $update->execute([$new_status, $booking_id]);
        }
    }
}

$redirect_to = $_SERVER['HTTP_REFERER'] ?? 'staff_dashboard.php';
header("Location: $redirect_to");
exit;
