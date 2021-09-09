<?php
include 'funciones.php';

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['codigo'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'La jornada no existe';
}

if (isset($_POST['submit'])) {
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $jornada = [
            "codigo"        => $_GET['codigo'],
            "nombre"    => $_POST['nombre'],
            "entrada"    => $_POST['entrada'],
            "salida"    => $_POST['salida']
        ];

        $consultaSQL = "UPDATE jornada SET
        nombre = :nombre, entrada = :entrada, salida = :salida
        WHERE codigo = :codigo";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($jornada);
    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $codigo = $_GET['codigo'];
    $consultaSQL = "SELECT * FROM jornada WHERE codigo =" . $codigo;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $jornada = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$jornada) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado la jornada';
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
                    La jornada ha sido actualizado correctamente
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php
if (isset($jornada) && $jornada) {
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Editando la jornada <?= escapar($jornada['CODIGO']) . ' - ' . escapar($jornada['NOMBRE'])  ?></h2>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?= escapar($jornada['NOMBRE']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="entrada">Entrada</label>
                        <input type="time" name="entrada" id="entrada" value="<?= escapar($jornada['ENTRADA']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="salida">Salida</label>
                        <input type="time" name="salida" id="salida" value="<?= escapar($jornada['SALIDA']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                        <a class="btn btn-primary" href="jornada.php">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>

<?php require "templates/footer.php"; ?>