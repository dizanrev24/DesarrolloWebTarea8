<?php
require('conexion.php');
$sql = "SELECT * FROM encuestas ORDER BY id DESC";
$dbFile = 'encuestas.db';
$conex = new SQLite3($dbFile);
$resultado = $conex->query($sql);
if (!$conex) {
    die("No se pudo conectar a la base de datos SQLite");
}
if ($resultado !== false) { // Verifica si la consulta se ejecutó correctamente

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Sistema de encuestas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="wrap">
        <h1>Encuestas</h1>
        <ul class="votacion index">
        <?php
           while($result = $resultado->fetchArray(SQLITE3_ASSOC)){
            echo '<li><a href="encuesta.php?id='.$result['id'].'">'.$result['titulo'].'</a></li>';
        }
        ?>
        </ul>
        <a href="agregar.php">+ Agregar nueva encuesta</a>
    </div>
</body>
</html>

<?php
} else {
    die("Error en la consulta: " . $conex->lastErrorMsg());
}
?>