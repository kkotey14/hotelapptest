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
  $fallback = 'rooms_list.php';
  if (empty($_SESSION['redirect_after_login'])) return $fallback;
  $target = $_SESSION['redirect_after_login'];
  unset($_SESSION['redirect_after_login']);

  // Prevent open redirects
  if (preg_match('~^(https?:)?//~i', $target)) return $fallback;
  if (strpos($target, "\n") !== false || strpos($target, "\r") !== false) return $fallback;

  return ltrim($target, '/');
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
