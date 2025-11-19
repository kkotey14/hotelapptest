<?php
// auth.php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/session_handler.php';

$handler = new DbSessionHandler($pdo);
session_set_save_handler($handler, true);

if (session_status() === PHP_SESSION_NONE) session_start();

/** Helpers */
function current_user() {
  return $_SESSION['user'] ?? null;
}

function is_logged_in() {
  return !empty($_SESSION['user']);
}

function next_after_login(): string {
  // 1. If a specific page was requested before login, redirect there.
  if (!empty($_SESSION['redirect_after_login'])) {
    $target = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']);

    // Prevent open redirects
    if (preg_match('~^(https?:)?//~i', $target) || strpos($target, "\n") !== false || strpos($target, "\r") !== false) {
        // Fallback to role-based default if target is invalid
    } else {
        return ltrim($target, '/');
    }
  }

  // 2. Otherwise, fall back to a role-based default dashboard.
  $role = $_SESSION['user']['role'] ?? 'customer';
  if ($role === 'admin') {
    return 'admin_dashboard.php';
  } elseif ($role === 'staff') {
    return 'staff_dashboard.php';
  } else {
    return 'index.php'; // Redirect to homepage after login
  }
}

function require_login() {
  if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'rooms_list.php';
    header('Location: login.php');
    exit;
  }
}

function require_role(array $roles) {
  require_login();
  $u = current_user();
  if (!in_array($u['role'] ?? '', $roles, true)) {
    header('Location: index.php');
    exit;
  }
}

// ✅ This is the one that was missing!
function require_role_admin($roles = []) {
  require_login();
  $u = current_user();
  if (!in_array($u['role'], $roles)) {
    http_response_code(403);
    require 'header.php';
    echo "<section class='container' style='padding:40px;text-align:center'>
            <h1>403 – Forbidden</h1>
            <p class='muted'>You do not have permission to access this page.</p>
            <a href='index.php' class='btn' style='margin-top:20px'>Back to Home</a>
          </section>";
    require 'footer.php';
    exit;
  }
}

// --- CSRF Protection ---

/**
 * Get the current CSRF token, generating one if it doesn't exist.
 * @return string
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Echo a hidden input field with the CSRF token for use in forms.
 */
function csrf_input(): void {
    echo '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verify the CSRF token from a POST request.
 * If it fails, the script will die.
 */
function verify_csrf_token(): void {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        http_response_code(403);
        die('CSRF token validation failed.');
    }
}
