<?php require 'config.php'; auth();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { check();
  if (isset($_POST['del'])) $pdo->prepare('DELETE FROM materias WHERE id=?')->execute([$_POST['del']]);
  else {
    $d = [trim($_POST['nombre']), trim($_POST['docente']), (int)$_POST['creditos']];
    if ($_POST['id']) { $d[] = $_POST['id']; $pdo->prepare('UPDATE materias SET nombre=?,docente=?,creditos=? WHERE id=?')->execute($d); }
    else $pdo->prepare('INSERT INTO materias(nombre,docente,creditos) VALUES(?,?,?)')->execute($d);
  }
  header('Location: materias.php'); exit;
}
$ed = ['id'=>'','nombre'=>'','docente'=>'','creditos'=>5];
if (isset($_GET['edit'])) { $s = $pdo->prepare('SELECT * FROM materias WHERE id=?'); $s->execute([$_GET['edit']]); $ed = $s->fetch() ?: $ed; }
$rows = $pdo->query('SELECT * FROM materias ORDER BY nombre')->fetchAll();
$titulo = 'Materias'; include 'header.php'; ?>
<form class="f" method="post"><input type="hidden" name="t" value="<?= csrf() ?>"><input type="hidden" name="id" value="<?= e($ed['id']) ?>">
 <input name="nombre" placeholder="Materia" value="<?= e($ed['nombre']) ?>" required>
 <input name="docente" placeholder="Docente" value="<?= e($ed['docente']) ?>" required>
 <input name="creditos" type="number" min="1" max="20" value="<?= e($ed['creditos']) ?>" required>
 <button><?= $ed['id'] ? 'Actualizar' : 'Agregar' ?></button></form>
<table><tr><th>Materia</th><th>Docente</th><th>Créditos</th><th></th></tr>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['nombre']) ?></td><td><?= e($r['docente']) ?></td><td><?= e($r['creditos']) ?></td>
<td><a class="btn" href="?edit=<?= $r['id'] ?>">Editar</a>
<form class="i" method="post" onsubmit="return confirm('¿Eliminar?')"><input type="hidden" name="t" value="<?= csrf() ?>">
<button class="del" name="del" value="<?= $r['id'] ?>">Borrar</button></form></td></tr><?php endforeach ?></table>
<?php include 'footer.php';
