<?php
// Datos de conexión
$user = 'tabd';
$pass = 'f3dd336642';
$host = 'localhost'; // O el host donde está instalada tu base de datos
$port = '1521'; // El puerto por defecto de Oracle es 1521
$service_name = 'XEPDB1'; // Nombre del servicio de la base de datos

// Crear la conexión
$dsn = "(DESCRIPTION =
            (ADDRESS = (PROTOCOL = TCP)(HOST = $host)(PORT = $port))
            (CONNECT_DATA =
              (SERVICE_NAME = $service_name)
            )
          )";
$conn = oci_connect($user, $pass, $dsn);

// Verificar la conexión
if (!$conn) {
    $m = oci_error();
    echo $m['message'], "\n";
    exit;
} 
?>