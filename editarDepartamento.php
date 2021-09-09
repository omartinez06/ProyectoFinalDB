<?php
include 'funciones.php';

$config = include 'config.php';

$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['codigo'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El departamento no existe';
}

if (isset($_POST['submit'])) {
    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $departamento = [
            "codigo"        => $_GET['codigo'],
            "nombre"    => $_POST['nombre']
        ];

        $consultaSQL = "UPDATE departamento SET
        nombre = :nombre
        WHERE codigo = :codigo";

        $consulta = $conexion->prepare($consultaSQL);
        $consulta->execute($departamento);
    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = $error->getMessage();
    }
}

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $codigo = $_GET['codigo'];
    $consultaSQL = "SELECT * FROM departamento WHERE codigo =" . $codigo;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    $departamento = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$departamento) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado el departamento';
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
                    El departamento ha sido actualizado correctamente
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<?php
if (isset($departamento) && $departamento) {
?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-4">Editando el departmento <?= escapar($departamento['CODIGO']) . ' - ' . escapar($departamento['NOMBRE'])  ?></h2>
                <hr>
                <form method="post">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="<?= escapar($departamento['NOMBRE']) ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
                        <a class="btn btn-primary" href="departamentos.php">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>

<?php require "templates/footer.php"; ?>