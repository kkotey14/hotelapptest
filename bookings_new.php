<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';
require 'auth.php';

$can_process_booking = true;
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
} else {
    $user_role = $_SESSION['user']['role'] ?? '';
    if ($user_role !== 'customer') {
        $errors[] = 'Due to hotel policy, admin and staff are required to create a customer account to make reservations.';
        $can_process_booking = false;
    }
}

require 'header.php';

function h($s) {
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $can_process_booking) {
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
      $booking_id = $pdo->lastInsertId();

      // Save selected services
      if (!empty($_POST['services']) && is_array($_POST['services'])) {
          $stmt = $pdo->prepare("INSERT INTO Services_in_Booking (booking_id, service_id) VALUES (?, ?)");
          foreach ($_POST['services'] as $s) {
              // $s is currently in format "Service Name|Price"
              // First insert into services table if not exists
              list($name, $price) = explode('|', $s);
              $price = floatval(str_replace(['U$', '$'], '', $price));
              // Check if service already exists
              $sv = $pdo->prepare("SELECT id FROM room_services WHERE name=? AND price=? LIMIT 1");
              $sv->execute([$name, $price]);
              $sid = $sv->fetchColumn();
              if (!$sid) {
                  $ins = $pdo->prepare("INSERT INTO room_services (name, price) VALUES (?, ?)");
                  $ins->execute([$name, $price]);
                  $sid = $pdo->lastInsertId();
              }
              $stmt->execute([$booking_id, $sid]);
          }
      }
    } else {
      $errors[] = 'Database error, please try again.';
    }
  }
}



?>

<section class="container">
  <h1 class="h2">Booking Confirmation</h1>

  <?php if ($success): ?>
    <div class="card" style="padding:20px; max-width: 700px; margin: 0 auto;">
        <h3 style="margin-top:0;">✅ Your booking request has been received!</h3>
        <p>Your request is now pending approval from our staff. You can view its status under <a href="my_bookings.php">My Bookings</a>.</p>
        <hr>
        <p><strong>Confirmation ID:</strong> #<?= htmlspecialchars($booking_id) ?></p>
        <p><strong>Check-in Time:</strong> 2:00 PM</p>
        <p><strong>Check-out Time:</strong> 12:00 PM</p>
        <hr>
        <p class="muted">Please be ready to show your confirmation ID to the receptionist when you arrive at the hotel.</p>
    </div>
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
