<?php
require 'db.php';
require 'auth.php';
require_login();

// Only admin can delete users
if ($_SESSION['user']['role'] !== 'admin') {
  header('Location: index.php');
  exit;
}



$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($user_id > 0) {
  // Prevent deleting yourself
  if ($user_id === (int)$_SESSION['user']['id']) {
    echo "You cannot delete your own account.";
    exit;
  }

  $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
  $stmt->execute([$user_id]);
}

header("Location: admin_users.php");
exit;
