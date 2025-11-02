<?php
// Database connection settings
$host = 'localhost';
$user = 'root';           // Replace with your DB user
$pass = '';               // Replace with your DB password
$dbName = 'hotelapp';
$dumpFile = __DIR__ . '/schema_with_rooms.sql';



try {
    // Step 1: Connect to MySQL server (no DB selected yet)
    $pdo = new PDO("mysql:host=$host", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Step 2: Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo "<p>✅ Database '$dbName' created or already exists.</p>";

    // Step 3: Connect to the new database
    $pdo->exec("USE `$dbName`");

    // Step 4: Load and execute the SQL dump file
    if (!file_exists($dumpFile)) {
        throw new Exception("SQL dump file not found at: $dumpFile");
    }

    $sql = file_get_contents($dumpFile);

    // Split the SQL script into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $stmt) {
        if (!empty($stmt)) {
            $pdo->exec($stmt);
        }
    }

    echo "<p>✅ Database tables and data imported successfully.</p>";
    echo "<p>🎉 Setup complete. You can now <a href='index.php'>start using the app</a>.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ DB Error: " . $e->getMessage() . "</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
