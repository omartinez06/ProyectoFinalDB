<?php
include 'funciones.php';


$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['nombre'])) {
    $consultaSQL = "SELECT E.CODIGO, E.NOMBRE, J.NOMBRE CODIGO_JORNADA, D.NOMBRE CODIGO_DEPARTAMENTO FROM EMPLEADO E INNER JOIN JORNADA J ON E.CODIGO_JORDANA = J.CODIGO INNER JOIN DEPARTAMENTO D ON D.CODIGO = E.CODIGO_DEPARTAMENTO WHERE E.NOMBRE LIKE '%" . $_POST['nombre'] . "%'";
  } else {
    $consultaSQL = "SELECT E.CODIGO, E.NOMBRE, J.NOMBRE CODIGO_JORNADA, D.NOMBRE CODIGO_DEPARTAMENTO FROM EMPLEADO E INNER JOIN JORNADA J ON E.CODIGO_JORDANA = J.CODIGO INNER JOIN DEPARTAMENTO D ON D.CODIGO = E.CODIGO_DEPARTAMENTO";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $empleados = $sentencia->fetchAll();
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
            <a href="crearEmpleado.php" class="btn btn-primary mt-4">Crear Empleado</a>
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
            <h2 class="mt-3">Lista de Empleados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Jornada</th>
                        <th>Departamento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
          if ($empleados && $sentencia->rowCount() > 0) {
            foreach ($empleados as $fila) {
          ?>
                    <tr>
                        <td><?php echo escapar($fila["CODIGO"]); ?></td>
                        <td><?php echo escapar($fila["NOMBRE"]); ?></td>
                        <td><?php echo escapar($fila["CODIGO_JORNADA"]); ?></td>
                        <td><?php echo escapar($fila["CODIGO_DEPARTAMENTO"]); ?></td>
                        <td>
                            <a href="<?= 'borrarEmpleado.php?codigo=' . escapar($fila["CODIGO"]) ?>">🗑️Borrar</a>
                            <a href="<?= 'editarEmpleado.php?codigo=' . escapar($fila["CODIGO"]) ?>">✏️Editar</a>
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