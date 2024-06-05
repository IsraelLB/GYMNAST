<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

    // Obtener los IDs del cliente y del entrenador de la solicitud POST
    $id_gimnasio = $_POST['id_gimnasio'];
    $campo = $_POST['campo'];
    $nuevo_valor = $_POST['nuevo_valor'];
    $tabla = $_POST['tabla'];

    $query = "UPDATE $tabla SET $campo = :nuevo_valor WHERE id_gimnasio = :id_gimnasio";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':nuevo_valor', $nuevo_valor);
    oci_bind_by_name($stid, ':id_gimnasio', $id_gimnasio);
    oci_execute($stid);
    header("Location: ../admin_gimnasio.php");
?>