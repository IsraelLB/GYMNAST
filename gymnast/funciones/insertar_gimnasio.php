<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

$nombre_gimnasio = $_POST['nombre_gimnasio'];
$direccion_gimnasio = $_POST['direccion_gimnasio'];
$telefono_gimnasio = $_POST['telefono_gimnasio'];
$correo_gimnasio = $_POST['correo_gimnasio'];

$stid = oci_parse($conn, 'BEGIN paquete_gimnasio.Insertar_Gimnasio(:nombre_gimnasio, :direccion_gimnasio, :telefono_gimnasio, :correo_gimnasio); END;');
oci_bind_by_name($stid, ':nombre_gimnasio', $nombre_gimnasio);
oci_bind_by_name($stid, ':direccion_gimnasio', $direccion_gimnasio);
oci_bind_by_name($stid, ':telefono_gimnasio', $telefono_gimnasio);
oci_bind_by_name($stid, ':correo_gimnasio', $correo_gimnasio);
oci_execute($stid);
    header("Location: ../admin_gimnasio.php");
?>