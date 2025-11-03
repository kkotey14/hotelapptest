<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';
require 'auth.php';

require_login();
require_role(['admin', 'staff']); // ✅ allows either role

// Fetch summary counts
$roomsCount = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$bookingsCount = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$upcomingCount = $pdo->query("SELECT COUNT(*) FROM bookings WHERE check_in >= CURDATE()")->fetchColumn();
$customersCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();

// Fetch recent bookings
$recent = $pdo->query("
  SELECT b.*, u.name, u.email, r.type, r.number
  FROM bookings b
  JOIN users u ON b.user_id = u.id
  JOIN rooms r ON b.room_id = r.id
  ORDER BY b.created_at DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require 'header.php'; ?>

<section class="container">
  <h2>Admin Dashboard</h2>

  <div class="grid" style="margin-top:10px;margin-bottom:20px">
    <div class="card center"><div class="h1"><?= (int)$roomsCount ?></div><div class="muted">Rooms</div></div>
    <div class="card center"><div class="h1"><?= (int)$bookingsCount ?></div><div class="muted">Bookings</div></div>
    <div class="card center"><div class="h1"><?= (int)$upcomingCount ?></div><div class="muted">Upcoming</div></div>
    <div class="card center"><div class="h1"><?= (int)$customersCount ?></div><div class="muted">Customers</div></div>
  </div>

  <div class="card" style="margin-bottom:20px">
    <h3>Quick Actions</h3>
    <div class="flex" style="gap:10px">
      <a class="btn" href="manage_rooms.php">Manage Rooms</a>
      <a class="btn" href="admin_users.php">Manage Users</a>
    </div>
  </div>

  <div class="card">
    <h3>Recent Bookings</h3>
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Guest</th>
          <th>Room</th>
          <th>Check-in</th>
          <th>Check-out</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recent as $r): ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td>
              <?= htmlspecialchars($r['name']) ?><br>
              <span class="muted small"><?= htmlspecialchars($r['email']) ?></span>
            </td>
            <td><?= htmlspecialchars($r['type']) ?> • <?= htmlspecialchars($r['number']) ?></td>
            <td><?= htmlspecialchars($r['check_in']) ?></td>
            <td><?= htmlspecialchars($r['check_out']) ?></td>
            <td><?= htmlspecialchars($r['status']) ?></td>
            <td>
              <?php if ($r['status'] !== 'cancelled'): ?>
                <form method="post" action="booking_cancel.php" onsubmit="return confirm('Are you sure you want to cancel this booking?')" style="margin:0">
                  <input type="hidden" name="booking_id" value="<?= (int)$r['id'] ?>">
                  <button class="btn red small" type="submit">Cancel</button>
                </form>
              <?php else: ?>
                <span class="muted small">Cancelled</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>

<?php require 'footer.php'; ?>
