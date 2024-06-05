<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

    // Obtener los IDs del cliente y del entrenador de la solicitud POST
    $id_CLIENTE = $_POST['id_CLIENTE'];
    $campo = $_POST['campo'];
    $nuevo_valor = $_POST['nuevo_valor'];
    $tabla = $_POST['tabla'];

    $query = "UPDATE $tabla SET $campo = :nuevo_valor WHERE id_CLIENTE = :id_CLIENTE";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':nuevo_valor', $nuevo_valor);
    oci_bind_by_name($stid, ':id_CLIENTE', $id_CLIENTE);
    oci_execute($stid);
    header("Location: ../admin_clientes.php");
?>