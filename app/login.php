<?php require 'config.php';
// Primer uso: crea el usuario admin / admin123
if (!$pdo->query('SELECT COUNT(*) FROM usuarios')->fetchColumn())
  $pdo->prepare('INSERT INTO usuarios(usuario,clave) VALUES(?,?)')->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $s = $pdo->prepare('SELECT * FROM usuarios WHERE usuario=?'); $s->execute([$_POST['usuario']]);
  $u = $s->fetch();
  if ($u && password_verify($_POST['clave'], $u['clave'])) {
    session_regenerate_id(true); $_SESSION['u'] = $u['usuario']; header('Location: index.php'); exit;
  }
  $error = 'Usuario o contraseña incorrectos';
}
?><!DOCTYPE html><html lang="es"><head><meta charset="utf-8"><title>Login</title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<form class="login" method="post"><h2>Iniciar sesión</h2>
<?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif ?>
<input name="usuario" placeholder="Usuario" required><input name="clave" type="password" placeholder="Contraseña" required>
<button>Entrar</button><small>Demo: admin / admin123</small></form></body></html>
