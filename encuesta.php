<?php
require('conexion.php');
$dbFile = 'encuestas.db';
$conex = new SQLite3($dbFile);
$id = $_GET['id'];

if (!isset($_GET['id'])) {
    header('location: index.php');
}

if (isset($_POST['votar'])) {
    if (isset($_POST['valor'])) {
        $opciones = $_POST['valor'];
        $stmt = $conex->prepare("SELECT * FROM opciones WHERE id = :id");
        $stmt->bindValue(':id', $opciones, SQLITE3_INTEGER);
        $result = $stmt->execute();
        
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $valor = $row['valor'] + 1;
            $stmt = $conex->prepare("UPDATE opciones SET valor = :valor WHERE id = :id");
            $stmt->bindValue(':valor', $valor, SQLITE3_INTEGER);
            $stmt->bindValue(':id', $opciones, SQLITE3_INTEGER);
            $stmt->execute();
        }
        header('location: resultado.php?id=' . $id);
    }
}
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
    <form action="" method="post">
        <?php
        $aux = 0;
        $sql = "SELECT a.titulo as titulo, a.fecha as fecha, b.id as id, b.nombre as nombre, b.valor as valor FROM encuestas a INNER JOIN opciones b ON a.id = b.id_encuesta WHERE a.id = " . $id;
        $stmt = $conex->prepare($sql);
        $result = $stmt->execute();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

            if ($aux == 0) {
                echo '<h1>' . $row['titulo'] . '</h1>';

                echo '<ul class="votacion">';
                $aux = 1;
            }

            echo '<li><label><input name="valor" type="radio" value="' . $row['id'] . '"><span>' . $row['nombre'] . '</span></label></li>';
        }
        echo '</ul>';

        if (!isset($_POST['valor'])) {
            echo "<div class='error'>Selecciona una opcion.</div>";
        }

        echo '<input name="votar" type="submit" value="Votar" class="votar">';
        echo '<a href="resultado.php?id=' . $id . '" class="resultado">Ver Resultados</a>';
        echo '<a href="index.php" class="volver">← Volver</a>';

        ?>
    </form>
</div>
</body>
</html>
