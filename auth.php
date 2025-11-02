<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

/** Current user helpers */
function current_user() {
  return $_SESSION['user'] ?? null;
}
function is_logged_in() {
  return !empty($_SESSION['user']);
}

/**
 * Where to send the user *after* a successful login.
 * - Uses a saved target (set by require_login / require_role)
 * - Falls back to the Stay page (rooms_list.php)
 * - Only allows relative paths for safety
 */
function next_after_login(): string {
  $fallback = 'rooms_list.php';
  if (empty($_SESSION['redirect_after_login'])) return $fallback;

  $target = $_SESSION['redirect_after_login'];
  unset($_SESSION['redirect_after_login']);

  // Allow only same-site relative URLs (no scheme/host)
  if (preg_match('~^(https?:)?//~i', $target)) return $fallback;
  if (strpos($target, "\n") !== false || strpos($target, "\r") !== false) return $fallback;

  return ltrim($target, '/'); // keep it relative
}

/**
 * Force login.
 * Saves the page the user was trying to access so we can
 * return them there right after they sign in.
 */
function require_login() {
  if (!is_logged_in()) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'rooms_list.php';
    header('Location: login.php');
    exit;
  }
}

/** Require one of the given roles (e.g., ['admin','staff']) */
function require_role(array $roles) {
  require_login();
  $u = current_user();
  if (!in_array($u['role'] ?? '', $roles, true)) {
    header('Location: index.php');
    exit;
  }
}


function require_role_admin($roles = []) {
  if (!isset($_SESSION['user']) || empty($_SESSION['user']['role']) || !in_array($_SESSION['user']['role'], $roles)) {
      // Pretty Forbidden page
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