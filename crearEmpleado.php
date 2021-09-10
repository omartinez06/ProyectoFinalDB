<?php

include 'funciones.php';
$config = include 'config.php';

if (isset($_POST['submit'])) {

  $resultado = [
    'error' => false,
    'mensaje' => 'El empleado ' . escapar($_POST['codigo']) . '-' . escapar($_POST['nombre']) . ' ha sido agregada con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    // Código que insertará un departamento
    $empleado = [
      "codigo" => $_POST['codigo'],
      "nombre" => $_POST['nombre'],
      "jornada" => $_POST['jornada'],
      "departamento" => $_POST['departamento'],
    ];

    $consultaSQL = "INSERT INTO empleado (codigo, nombre, codigo_jordana, codigo_departamento) values (:" . implode(", :", array_keys($empleado)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($empleado);
  } catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}
try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  $consultaJornadaSQL = "SELECT * FROM jornada";
  $sentenciaJornada = $conexion->prepare($consultaJornadaSQL);
  $sentenciaJornada->execute();
  $jornadas = $sentenciaJornada->fetchAll();


  $consultaDeptoSQL = "SELECT * FROM departamento";
  $sentenciaDepto = $conexion->prepare($consultaDeptoSQL);
  $sentenciaDepto->execute();
  $departamentos = $sentenciaDepto->fetchAll();
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
      <h2 class="mt-4">Nuevo Empleado</h2>
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
          <label for="jornada">Jornada</label>
          <select class="form-select" aria-label="Jornada" id="jornada" name="jornada">

            <?php
            if ($jornadas && $sentenciaJornada->rowCount() > 0) {
              foreach ($jornadas as $fila) {
            ?>
                <option value=<?php echo escapar($fila["CODIGO"]); ?>><?php echo escapar($fila["NOMBRE"]); ?></option>
            <?php
              }
            }
            ?>


          </select>
        </div>

        <div class="form-group">
          <label for="departamento">Departamento</label>
          <select class="form-select" aria-label="Departamento" id="departamento" name="departamento">

            <?php
            if ($departamentos && $sentenciaDepto->rowCount() > 0) {
              foreach ($departamentos as $fila) {
            ?>
                <option value=<?php echo escapar($fila["CODIGO"]); ?>><?php echo escapar($fila["NOMBRE"]); ?></option>
            <?php
              }
            }
            ?>


          </select>
        </div>

        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-primary" value="Crear">
          <a class="btn btn-primary" href="empleados.php">Regresar</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>