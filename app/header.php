<!DOCTYPE html>
<html lang="es"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($titulo ?? 'Sistema Escolar') ?></title>
<link rel="stylesheet" href="assets/style.css"></head><body>
<nav><b>🎓 Escuela</b><a href="index.php">Inicio</a><a href="alumnos.php">Alumnos</a>
<a href="materias.php">Materias</a><a href="inscripciones.php">Inscripciones</a>
<a class="out" href="logout.php">Salir (<?= e($_SESSION['u']) ?>)</a></nav><main>
<h1><?= e($titulo ?? '') ?></h1>
