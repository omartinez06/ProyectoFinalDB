<?php
include 'funciones.php';

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['codigo'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El permiso no existe';
}

if (isset($_POST['submit'])) {
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $permiso = [
            "id"        => $_GET['id'],
            "codigo_empleado"    => $_POST['codigo_empleado'],
            "fecha"    => $_POST['fecha'],
            "motivo"    => $_POST['motivo']
        ];

        $consultaSQL = "UPDATE permiso SET
        codigo_empleado = :codigo_empleado, fecha = :fecha, motivo = :motivo
        WHERE id = :id";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($permiso);
    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $codigo = $_GET['codigo'];
    $consultaSQL = "SELECT * FROM permiso WHERE id =" . $codigo;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $permiso = $sentencia->fetch(PDO::FETCH_ASSOC);
	
	$consultaEmpleadoSQL = "SELECT * FROM empleado";
	$sentenciaEmpleado = $conexion->prepare($consultaEmpleadoSQL);
	$sentenciaEmpleado->execute();
	$empleados = $sentenciaEmpleado->fetchAll();

    if (!$permiso) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado el permiso';
    }
} catch (PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php
if ($resultado['error']) {
?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?= $resultado['mensaje'] ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
?>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    El empleado ha sido actualizado correctamente
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php
if (isset($permiso) && $permiso) {
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Editando el Permiso <?= escapar($permiso['CODIGO_EMPLEADO'])  ?></h2>
                <hr>
                <form method="post">
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
                        <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                        <a class="btn btn-primary" href="permisos.php">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>

<?php require "templates/footer.php"; ?>