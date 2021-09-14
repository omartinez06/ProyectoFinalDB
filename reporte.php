<?php
include 'funciones.php';


$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['nombre'])) {
    $consultaSQL = "";
  } else {
    $consultaSQL = "";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $reporte = $sentencia->fetchAll();
} catch (PDOException $error) {
  $error = $error->getMessage();
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
if ($error) {
?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
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
      <a href="index.php" class="btn btn-primary mt-4">Regresar a Inicio</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group">
          <label for="nombre">Empleado</label>
          <select class="form-select" aria-label="Empleado" id="nombre" name="nombre">

            <?php
            if ($empleados && $sentenciaEmpleado->rowCount() > 0) {
              foreach ($empleados as $fila) {
            ?>
                <option value=<?php echo escapar($fila["NOMBRE"]); ?>><?php echo escapar($fila["NOMBRE"]); ?></option>
            <?php
              }
            }
            ?>


          </select>
        </div>
        <div class="form-group">
          <label for="fechaInicio">Fecha Inicio</label>
          <input type="date" name="fechaInicio" id="fechaInicio" class="form-control">
        </div>
        <div class="form-group">
          <label for="fechaFinal">Fecha Final</label>
          <input type="date" name="fechaFinal" id="fechaFinal" class="form-control">
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3">Reporte</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>Entrada tarde minutos</th>
            <th>Salida temprano minutos</th>
            <th>Horas trabajadas</th>
            <th>Observaciones/Permisos</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($reporte && $sentencia->rowCount() > 0) {
            foreach ($reporte as $fila) {
          ?>
              <tr>
                <td><?php echo escapar($fila["FECHA"]); ?></td>
                <td><?php echo escapar($fila["ENTRADA"]); ?></td>
                <td><?php echo escapar($fila["SALIDA"]); ?></td>
                <td><?php echo escapar($fila["E_TARDE"]); ?></td>
                <td><?php echo escapar($fila["S_TEMPRANO"]); ?></td>
                <td><?php echo escapar($fila["HORAS"]); ?></td>
                <td><?php echo escapar($fila["OBSERVACIONES"]); ?></td>
              </tr>
          <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>