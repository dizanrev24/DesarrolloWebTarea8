<?php
// Cambia la configuración de tu base de datos SQLite
$dbFile = 'encuestas.db';

// Crea una nueva instancia de SQLite3
$db = new SQLite3($dbFile);

if (!$db) {
    die("No se pudo conectar a la base de datos SQLite");
}
?>
