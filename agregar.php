<?php
require('conexion.php');
$cont = 0;

$dbFile = 'encuestas.db';
$conex = new SQLite3($dbFile);

if (!$conex) {
    die("No se pudo conectar a la base de datos SQLite");
}

$titulo = '';

if(isset($_POST['titulo'])){
    $titulo = trim($_POST['titulo']);
}

if(isset($_POST['enviar'])){
    if($titulo != ""){
        $num = $_POST['opciones'];
        $fecha = date('Y-m-d');

        // Insertar una nueva encuesta en SQLite
        $sql = "INSERT INTO encuestas (titulo, fecha) VALUES (:titulo, :fecha)";
        $stmt = $conex->prepare($sql);
        $stmt->bindValue(':titulo', $titulo, SQLITE3_TEXT);
        $stmt->bindValue(':fecha', $fecha, SQLITE3_TEXT);
        $stmt->execute();

        $id_encuesta = $conex->lastInsertRowID();

        for($i = 1; $i <= $num; $i++){
            $opcnativa = trim($_POST['opc'.$i]);
            if($opcnativa != ""){
                // Insertar opciones en SQLite
                $sql = "INSERT INTO opciones (id_encuesta, nombre, valor) VALUES (:id_encuesta, :nombre, 0)";
                $stmt = $conex->prepare($sql);
                $stmt->bindValue(':id_encuesta', $id_encuesta, SQLITE3_INTEGER);
                $stmt->bindValue(':nombre', $opcnativa, SQLITE3_TEXT);
                $stmt->execute();
                $cont++;
            }
        }

        if($cont < 2){
            // Eliminar la encuesta si no tiene suficientes opciones
            $sql = "DELETE FROM encuestas WHERE id = :id_encuesta";
            $stmt = $conex->prepare($sql);
            $stmt->bindValue(':id_encuesta', $id_encuesta, SQLITE3_INTEGER);
            $stmt->execute();

            echo "<div class='error'>Tiene que llevar por lo menos 2 opciones.</div>";
        } else {
            header('location: index.php');
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Encuestas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="wrap">
    <h1>Agregar Encuesta</h1>
    <form action="" method="post">
        <div class="fl titulo">
            <label>Titulo:</label>
            <input name="titulo" type="text" value="<?php echo $titulo; ?>" size="26">
        </div>
        <?php
        if(isset($_POST['opc'])){
            $num = $_POST['opciones'];
            for($i = 1; $i <= $num; $i++){
                ?>
                <div class="cf">
                    <label>Opcion <?php echo $i; ?>: </label>
                    <input name="opc<?php echo $i; ?>" type="text" size="43">
                </div>
                <?php
            }
            ?>
            <div class="cf">
                <input name="enviar" type="submit" value="Enviar">
                <input name="opciones" type="hidden" value="<?php echo $num; ?>">
                <input name="cont" type="hidden" value="<?php echo $cont; ?>">
            </div>
            <?php
        } else {
            ?>
            <div class="fl">
                <label>Nº de opciones:</label>
                <select name="opciones">
                    <?php for($i = 2; $i <= 20; $i++){ ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="cf">
                <input name="opc" type="submit" value="Continuar">
            </div>
            <?php
        }
        ?>
        <a href="index.php" class="volver">← Volver</a>
    </form>
</div>
</body>
</html>
