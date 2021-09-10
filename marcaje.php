<?php

include 'funciones.php';
$config = include 'config.php';

if (isset($_POST['submit'])) {

    $resultado = [
        'error' => false,
        'mensaje' => 'El marcaje ha sido agregado con éxito'
    ];

    try {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

        $date = new DateTime();

        $marcaje = [
            "codigo_empleado" => $_POST['codigo_empleado'],
            "tipo_marcaje" => $_POST['tipo_marcaje'],
            "fecha" => $date->format('Y-m-d H:i:s'),
        ];
        $consultaSQL = "INSERT INTO marcaje (codigo_empleado, tipo_marcaje, fecha) values (:" . implode(", :", array_keys($marcaje)) . ")";

        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->execute($marcaje);
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
    <h1 class="main_time" id="date"></h1>
    <h1 class="main_time" id="time"></h1>
</div>
<form method="post">
    <div class="container">
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
    </div>
    <div class="container">
        <div class="form-group">
            <label for="tipo_marcaje">Tipo Marcaje</label>
            <select class="form-select" aria-label="Marcaje" id="tipo_marcaje" name="tipo_marcaje">
                <option value="Entrada">Entrada</option>
                <option value="Salida" selected>Salida</option>
            </select>
        </div>
    </div>
    <div class="container">
        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Marcar">
            <a class="btn btn-primary" href="index.php">Regresar</a>
        </div>
    </div>
</form>

<script src="main.js">
</script>
<?php include "templates/footer.php"; ?>