<?php
require 'db.php';
require 'auth.php';
require_login();

// Only admin/staff allowed
if (!in_array($_SESSION['user']['role'], ['admin','staff'])) {
    header("Location: index.php");
    exit;
}

$room_id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id=?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_inventory = (int)$_POST['inventory'];
    if ($new_inventory >= 0) {
        $upd = $pdo->prepare("UPDATE rooms SET inventory=? WHERE id=?");
        $upd->execute([$new_inventory, $room_id]);
        $msg = "Inventory updated successfully!";
        $room['inventory'] = $new_inventory; // refresh value
    } else {
        $msg = "Please enter a valid number greater than or equal to 0.";
    }
}
?>

<?php require 'header.php'; ?>
<div class="container card" style="padding:20px; max-width:600px; margin:20px auto;">
  <h2>Edit Inventory</h2>
  <p><strong>Room:</strong> <?= htmlspecialchars($room['type']) ?> — <?= htmlspecialchars($room['number']) ?></p>
  <?php if ($msg): ?><div class="flash"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  
  <form method="post">
    <label>Inventory:
      <input type="number" name="inventory" value="<?= htmlspecialchars($room['inventory']) ?>" min="0" class="input" required>
    </label>
    <button type="submit" class="btn primary" style="margin-top:10px;">Save</button>
  </form>
  
  <div style="margin-top:10px;">
    <a class="btn" href="manage_rooms.php">Back to Rooms</a>
  </div>
</div>
<?php require 'footer.php'; ?>
