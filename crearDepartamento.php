<?php

include 'funciones.php';

if (isset($_POST['submit'])) {

  $resultado = [
    'error' => false,
    'mensaje' => 'El Departamento ' . escapar($_POST['codigo']) . '-' . escapar($_POST['nombre']) . ' ha sido agregado con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    // Código que insertará un departamento
    $departamento = [
      "codigo" => $_POST['codigo'],
      "nombre" => $_POST['nombre'],
    ];

    $consultaSQL = "INSERT INTO departamento (codigo, nombre) values (:" . implode(", :", array_keys($departamento)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($departamento);
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
      <h2 class="mt-4">Nuevo Departamento</h2>
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
          <input type="submit" name="submit" class="btn btn-primary" value="Crear">
          <a class="btn btn-primary" href="departamentos.php">Regresar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>