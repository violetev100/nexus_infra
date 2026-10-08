<?php require 'config.php'; auth();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { check();
  if (isset($_POST['del'])) $pdo->prepare('DELETE FROM inscripciones WHERE id=?')->execute([$_POST['del']]);
  elseif (isset($_POST['nota'])) {
    $c = $_POST['nota'] === '' ? null : max(0, min(10, (float)$_POST['nota']));
    $pdo->prepare('UPDATE inscripciones SET calificacion=? WHERE id=?')->execute([$c, $_POST['id']]);
  } else $pdo->prepare('INSERT IGNORE INTO inscripciones(alumno_id,materia_id) VALUES(?,?)')->execute([$_POST['alumno_id'], $_POST['materia_id']]);
  header('Location: inscripciones.php'); exit;
}
$al = $pdo->query('SELECT id,nombre FROM alumnos ORDER BY nombre')->fetchAll();
$ma = $pdo->query('SELECT id,nombre FROM materias ORDER BY nombre')->fetchAll();
$rows = $pdo->query('SELECT i.id,i.calificacion,a.nombre alumno,m.nombre materia FROM inscripciones i
  JOIN alumnos a ON a.id=i.alumno_id JOIN materias m ON m.id=i.materia_id ORDER BY a.nombre,m.nombre')->fetchAll();
$titulo = 'Inscripciones y calificaciones'; include 'header.php'; ?>
<form class="f" method="post"><input type="hidden" name="t" value="<?= csrf() ?>">
 <select name="alumno_id" required><?php foreach ($al as $a): ?><option value="<?= $a['id'] ?>"><?= e($a['nombre']) ?></option><?php endforeach ?></select>
 <select name="materia_id" required><?php foreach ($ma as $m): ?><option value="<?= $m['id'] ?>"><?= e($m['nombre']) ?></option><?php endforeach ?></select>
 <button>Inscribir</button></form>
<table><tr><th>Alumno</th><th>Materia</th><th>Calificación (0-10)</th><th></th></tr>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['alumno']) ?></td><td><?= e($r['materia']) ?></td>
<td><form class="i" method="post"><input type="hidden" name="t" value="<?= csrf() ?>"><input type="hidden" name="id" value="<?= $r['id'] ?>">
<input name="nota" type="number" step="0.1" min="0" max="10" size="4" style="width:70px" value="<?= e($r['calificacion']) ?>"> <button>Guardar</button></form></td>
<td><form class="i" method="post" onsubmit="return confirm('¿Eliminar?')"><input type="hidden" name="t" value="<?= csrf() ?>">
<button class="del" name="del" value="<?= $r['id'] ?>">Borrar</button></form></td></tr><?php endforeach ?></table>
<?php include 'footer.php';
