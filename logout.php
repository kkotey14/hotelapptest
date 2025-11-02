<?php
require 'db.php';
require 'auth.php';

// 1. Confirm session is started (redundant, but safe)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. DEBUG: Show session state
echo "<pre>DEBUG: SESSION DATA\n";
print_r($_SESSION);
echo "</pre>";

// 3. Check if user ID is set
if (!empty($_SESSION['user']['id'])) {
    $uid = (int)$_SESSION['user']['id'];

    try {
        // 4. DEBUG: Announce we're about to run the query
        echo "<p>DEBUG: Preparing to update logout time for user ID: $uid</p>";

        $stmt = $pdo->prepare("
            UPDATE user_sessions
            SET logout_at = CURRENT_TIMESTAMP
            WHERE id = (
                SELECT id FROM (
                    SELECT id FROM user_sessions
                    WHERE user_id = ?
                      AND logout_at IS NULL
                    ORDER BY login_at DESC
                    LIMIT 1
                ) AS sub
            )
        ");
        $stmt->execute([$uid]);

        echo "<p>DEBUG: Logout timestamp updated successfully.</p>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>SQL ERROR: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:orange;'>DEBUG: No user ID found in session. Skipping DB update.</p>";
}

// 5. Destroy session
$_SESSION = [];
session_destroy();

echo "<p>DEBUG: Session destroyed. Redirecting...</p>";

// 6. Pause before redirect
sleep(2);
header("Location: index.php");
exit;
