<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';
require 'auth.php';
require_role(['customer']);
require 'header.php';

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $room_id  = $_POST['room_id'] ?? '';
  $ci       = $_POST['ci'] ?? $_POST['check_in'] ?? '';
  $co       = $_POST['co'] ?? $_POST['check_out'] ?? '';
  $guests   = (int)($_POST['guests'] ?? 1);
  $user_id  = $_SESSION['user']['id'];

  // --- Validate inputs ---
  if (!$room_id || !$ci || !$co) {
    $errors[] = 'All fields are required.';
  } elseif (strtotime($co) <= strtotime($ci)) {
    $errors[] = 'Check-out date must be after check-in date.';
  }

  // Fetch room + check inventory
  if (empty($errors)) {
    $room = $pdo->prepare("SELECT * FROM rooms WHERE id=? AND is_active=1 LIMIT 1");
    $room->execute([$room_id]);
    $room = $room->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
      $errors[] = 'Room not found.';
    } elseif ($guests > (int)$room['max_guests']) {
      $errors[] = 'Too many guests for selected room.';
    } else {
      $inventory = max(1, (int)$room['inventory']);

      // Check overlapping bookings
      $conflict = $pdo->prepare("
        SELECT COUNT(*) FROM bookings
        WHERE room_id = ? AND status IN ('pending','confirmed')
        AND NOT (check_out <= ? OR check_in >= ?)
      ");
      $conflict->execute([$room_id, $ci, $co]);
      $used = (int)$conflict->fetchColumn();

      if ($used >= $inventory) {
        $errors[] = 'Room is fully booked for the selected dates.';
      }
    }
  }

  // --- Insert booking ---
  if (empty($errors)) {
    $insert = $pdo->prepare("
      INSERT INTO bookings (user_id, room_id, check_in, check_out, status)
      VALUES (?, ?, ?, ?, 'pending')
    ");
    if ($insert->execute([$user_id, $room_id, $ci, $co])) {
      $success = true;
    } else {
      $errors[] = 'Database error, please try again.';
    }
  }
}
?>

<section class="container">
  <h1 class="h2">Booking Confirmation</h1>

  <?php if ($success): ?>
    <p class="success">✅ Your booking was successful! View it under <a href="my_bookings.php">My Bookings</a>.</p>
  <?php else: ?>
    <div class="error">
      <?php foreach ($errors as $e): ?>
        <p>❌ <?= h($e) ?></p>
      <?php endforeach; ?>
    </div>
    <a class="btn" href="javascript:history.back()">⬅ Go Back</a>
  <?php endif; ?>
</section>

<?php require 'footer.php'; ?>
