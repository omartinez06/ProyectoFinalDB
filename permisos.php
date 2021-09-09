<?php
include 'funciones.php';


$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['nombre'])) {
    $consultaSQL = "SELECT * FROM permiso WHERE codigo_empleado in (SELECT codigo FROM empleado WHERE nombre LIKE '%" . $_POST['nombre'] . "%')";
  } else {
    $consultaSQL = "SELECT * FROM permiso";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $permisos = $sentencia->fetchAll();
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
      <a href="crearPermiso.php" class="btn btn-primary mt-4">Crear Permiso</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="nombre" name="nombre" placeholder="Buscar por nombre de empleado" class="form-control">
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3">Lista de Permisos</h2>
      <table class="table">
        <thead>
          <tr>
			<th>ID</th>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Motivo</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($permisos && $sentencia->rowCount() > 0) {
            foreach ($permisos as $fila) {
          ?>
              <tr>
				<td><?php echo escapar($fila["ID"]); ?></td>
                <td><?php echo escapar($fila["CODIGO_EMPLEADO"]); ?></td>
                <td><?php echo escapar($fila["FECHA"]); ?></td>
                <td><?php echo escapar($fila["MOTIVO"]); ?></td>
                <td>
                  <a href="<?= 'borrarPermiso.php?codigo=' . escapar($fila["ID"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editarPermiso.php?codigo=' . escapar($fila["ID"]) ?>">✏️Editar</a>
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