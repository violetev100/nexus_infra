<?php require 'config.php'; auth();
$n = fn($t) => $pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();
$prom = $pdo->query('SELECT a.nombre, ROUND(AVG(i.calificacion),1) p FROM alumnos a
  JOIN inscripciones i ON i.alumno_id=a.id WHERE i.calificacion IS NOT NULL GROUP BY a.id ORDER BY p DESC')->fetchAll();
$titulo = 'Panel'; include 'header.php'; ?>
<div class="cards">
 <div class="card"><b><?= $n('alumnos') ?></b>Alumnos</div>
 <div class="card"><b><?= $n('materias') ?></b>Materias</div>
 <div class="card"><b><?= $n('inscripciones') ?></b>Inscripciones</div>
</div>
<h3>Promedio por alumno</h3>
<table><tr><th>Alumno</th><th>Promedio</th></tr>
<?php foreach ($prom as $r): ?><tr><td><?= e($r['nombre']) ?></td><td><?= e($r['p']) ?></td></tr><?php endforeach ?>
</table>
<?php include 'footer.php';
