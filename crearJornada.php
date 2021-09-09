<?php

include 'funciones.php';

if (isset($_POST['submit'])) {

  $resultado = [
    'error' => false,
    'mensaje' => 'La Jornada ' . escapar($_POST['codigo']) . '-' . escapar($_POST['nombre']) . ' ha sido agregada con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    // Código que insertará un departamento
    $jornada = [
      "codigo" => $_POST['codigo'],
      "nombre" => $_POST['nombre'],
      "entrada" => $_POST['entrada'],
      "salida" => $_POST['salida'],      
    ];

    $consultaSQL = "INSERT INTO jornada (codigo, nombre, entrada, salida) values (:" . implode(", :", array_keys($jornada)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($jornada);
  } catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
?>

<?php include "templates/header.php"; ?>

<?php
if (isset($resultado)) {
?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Nueva Jornada</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="codigo">Codigo</label>
          <input type="text" name="codigo" id="codigo" class="form-control">
        </div>
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="nombre" class="form-control">
        </div>
	<div class="form-group">
          <label for="entrada">Entrada</label>
          <input type="time" name="entrada" id="entrada" class="form-control">
        </div>
	<div class="form-group">
          <label for="salida">Salida</label>
          <input type="time" name="salida" id="salida" class="form-control">
        </div>
        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-primary" value="Crear">
          <a class="btn btn-primary" href="jornada.php">Regresar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>