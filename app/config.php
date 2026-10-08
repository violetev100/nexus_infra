<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=escuela;charset=utf8mb4', 'root', '', [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function auth() { if (empty($_SESSION['u'])) { header('Location: login.php'); exit; } }
function csrf() { return $_SESSION['t'] ??= bin2hex(random_bytes(16)); }
function check() { if (!hash_equals($_SESSION['t'] ?? '', $_POST['t'] ?? '')) die('Token inválido'); }
