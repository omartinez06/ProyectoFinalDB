<?php
include 'funciones.php';


$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['nombre'])) {
    $consultaSQL = "SELECT * FROM jornada WHERE nombre LIKE '%" . $_POST['nombre'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM jornada";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $jornadas = $sentencia->fetchAll();
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
      <a href="crearJornada.php" class="btn btn-primary mt-4">Crear Jornada</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="nombre" name="nombre" placeholder="Buscar por nombre" class="form-control">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3">Lista de Jornadas</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Entrada</th>
            <th>Salida</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($jornadas && $sentencia->rowCount() > 0) {
            foreach ($jornadas as $fila) {
          ?>
              <tr>
                <td><?php echo escapar($fila["CODIGO"]); ?></td>
                <td><?php echo escapar($fila["NOMBRE"]); ?></td>
                <td><?php echo escapar($fila["ENTRADA"]); ?></td>
                <td><?php echo escapar($fila["SALIDA"]); ?></td>
                <td>
                  <a href="<?= 'borrarJornada.php?codigo=' . escapar($fila["CODIGO"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editarJornada.php?codigo=' . escapar($fila["CODIGO"]) ?>">✏️Editar</a>
                </td>
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