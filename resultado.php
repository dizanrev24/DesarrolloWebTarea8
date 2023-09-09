<?php
require('conexion.php');


$dbFile = 'encuestas.db';
$conex = new SQLite3($dbFile);
if (!isset($_GET['id'])) {
    header('location: index.php');
}
 
$suma = 0;
$id = $_GET['id'];

// Calcular la suma de votos
$mod = $conex->query("SELECT SUM(valor) as valor FROM opciones WHERE id_encuesta = ".$id);
while ($result = $mod->fetchArray(SQLITE3_ASSOC)) {
    $suma = $result['valor'];
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
<form action="" method="post">
<?php
$aux = 0;
$sql = "SELECT nombre, valor FROM opciones WHERE id_encuesta = " . $id; // Solo necesitamos nombre y valor
$req = $conex->query($sql);
 
while ($result = $req->fetchArray(SQLITE3_ASSOC)) {
    if ($aux == 0) {
        // Recuperar el título de la encuesta
        $tituloQuery = $conex->query("SELECT titulo FROM encuestas WHERE id = " . $id);
        $tituloResult = $tituloQuery->fetchArray(SQLITE3_ASSOC);
        $titulo = $tituloResult['titulo'];
        echo "<h1>".$titulo."</h1>";
        echo "<ul class='votacion'>";
        $aux = 1;
    }
    echo '<li><div class="fl">'.$result['nombre'].'</div><div class="fr">Votos: '.$result['valor'].'</div>';
    if ($suma == 0) {
        echo '<div class="barra cero" style="width:0%;"></div></li>';
    } else {
        echo '<div class="barra" style="width:'.($result['valor']*100/$suma).'%;">'.round($result['valor']*100/$suma).'%</div></li>';
    }
 
}
echo '</ul>'; 
 
if (isset($aux)) {
    echo '<span class="fr">Total: '.$suma.'</span>';
    echo '<a href="encuesta.php?id='.$id.'"" class="volver">← Volver</a>';
}
 
?>
</ul>
</form>
</div>
</body>
</html>
