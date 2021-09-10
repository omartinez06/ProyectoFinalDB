<?php

include 'funciones.php';
$config = include 'config.php';

if (isset($_POST['submit'])) {

  $resultado = [
    'error' => false,
    'mensaje' => 'El permiso para ' . escapar($_POST['codigo_empleado']) . ' ha sido agregada con éxito'
  ];

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    // Código que insertará un departamento
    $permiso = [
      "id" => $_POST['id'],
      "codigo_empleado" => $_POST['codigo_empleado'],
      "fecha" => $_POST['fecha'],
      "motivo" => $_POST['motivo'],
    ];

    $consultaSQL = "INSERT INTO permiso (id, codigo_empleado, fecha, motivo) values (:" . implode(", :", array_keys($permiso)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($permiso);
  } catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  $consultaEmpleadoSQL = "SELECT * FROM empleado";
  $sentenciaEmpleado = $conexion->prepare($consultaEmpleadoSQL);
  $sentenciaEmpleado->execute();
  $empleados = $sentenciaEmpleado->fetchAll();
} catch (PDOException $error) {
  $error = $error->getMessage();
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
      <h2 class="mt-4">Nuevo Permiso</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="id">Id</label>
          <input type="number" name="id" id="id" class="form-control">
        </div>

        <div class="form-group">
          <label for="codigo_empleado">Empleado</label>
          <select class="form-select" aria-label="Empleado" id="codigo_empleado" name="codigo_empleado">

            <?php
            if ($empleados && $sentenciaEmpleado->rowCount() > 0) {
              foreach ($empleados as $fila) {
            ?>
                <option value=<?php echo escapar($fila["CODIGO"]); ?>><?php echo escapar($fila["NOMBRE"]); ?></option>
            <?php
              }
            }
            ?>


          </select>
        </div>

        <div class="form-group">
          <label for="fecha">Fecha</label>
          <input type="date" name="fecha" id="fecha" class="form-control">
        </div>
        <div class="form-group">
          <label for="motivo">Motivo</label>
          <input type="text" name="motivo" id="motivo" class="form-control">
        </div>

        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-primary" value="Crear">
          <a class="btn btn-primary" href="permisos.php">Regresar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>