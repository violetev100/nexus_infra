<?php require 'config.php'; auth();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { check();
  if (isset($_POST['del'])) $pdo->prepare('DELETE FROM alumnos WHERE id=?')->execute([$_POST['del']]);
  else {
    $d = [trim($_POST['nombre']), trim($_POST['email']), trim($_POST['grupo'])];
    if ($_POST['id']) { $d[] = $_POST['id']; $pdo->prepare('UPDATE alumnos SET nombre=?,email=?,grupo=? WHERE id=?')->execute($d); }
    else $pdo->prepare('INSERT INTO alumnos(nombre,email,grupo) VALUES(?,?,?)')->execute($d);
  }
  header('Location: alumnos.php'); exit;
}
$ed = ['id'=>'','nombre'=>'','email'=>'','grupo'=>''];
if (isset($_GET['edit'])) { $s = $pdo->prepare('SELECT * FROM alumnos WHERE id=?'); $s->execute([$_GET['edit']]); $ed = $s->fetch() ?: $ed; }
$rows = $pdo->query('SELECT * FROM alumnos ORDER BY nombre')->fetchAll();
$titulo = 'Alumnos'; include 'header.php'; ?>
<form class="f" method="post"><input type="hidden" name="t" value="<?= csrf() ?>"><input type="hidden" name="id" value="<?= e($ed['id']) ?>">
 <input name="nombre" placeholder="Nombre" value="<?= e($ed['nombre']) ?>" required>
 <input name="email" type="email" placeholder="Email" value="<?= e($ed['email']) ?>" required>
 <input name="grupo" placeholder="Grupo" size="6" value="<?= e($ed['grupo']) ?>" required>
 <button><?= $ed['id'] ? 'Actualizar' : 'Agregar' ?></button></form>
<table><tr><th>Nombre</th><th>Email</th><th>Grupo</th><th></th></tr>
<?php foreach ($rows as $r): ?><tr><td><?= e($r['nombre']) ?></td><td><?= e($r['email']) ?></td><td><?= e($r['grupo']) ?></td>
<td><a class="btn" href="?edit=<?= $r['id'] ?>">Editar</a>
<form class="i" method="post" onsubmit="return confirm('¿Eliminar?')"><input type="hidden" name="t" value="<?= csrf() ?>">
<button class="del" name="del" value="<?= $r['id'] ?>">Borrar</button></form></td></tr><?php endforeach ?></table>
<?php include 'footer.php';
