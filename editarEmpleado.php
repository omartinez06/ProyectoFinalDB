<?php
include 'funciones.php';

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['codigo'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El empleado no existe';
}

if (isset($_POST['submit'])) {
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $empleado = [
            "codigo"        => $_GET['codigo'],
            "nombre"    => $_POST['nombre'],
            "jornada"    => $_POST['jornada'],
            "departamento"    => $_POST['departamento']
        ];

        $consultaSQL = "UPDATE empleado SET
        nombre = :nombre, codigo_jordana = :jornada, codigo_departamento = :departamento
        WHERE codigo = :codigo";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($empleado);
    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $codigo = $_GET['codigo'];
    $consultaSQL = "SELECT * FROM empleado WHERE codigo =" . $codigo;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $empleado = $sentencia->fetch(PDO::FETCH_ASSOC);
	
	$consultaJornadaSQL = "SELECT * FROM jornada";
	$sentenciaJornada = $conexion->prepare($consultaJornadaSQL);
	$sentenciaJornada->execute();
	$jornadas = $sentenciaJornada->fetchAll();
	
	
    $consultaDeptoSQL = "SELECT * FROM departamento";
    $sentenciaDepto = $conexion->prepare($consultaDeptoSQL);
    $sentenciaDepto->execute();
    $departamentos = $sentenciaDepto->fetchAll();

    if (!$empleado) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado el empleado';
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
if (isset($empleado) && $empleado) {
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Editando el Empleado <?= escapar($empleado['CODIGO']) . ' - ' . escapar($empleado['NOMBRE'])  ?></h2>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?= escapar($empleado['NOMBRE']) ?>" class="form-control">
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
                        <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                        <a class="btn btn-primary" href="empleados.php">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>

<?php require "templates/footer.php"; ?>