<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

    // Obtener los IDs del cliente y del entrenador de la solicitud POST
    $id_cliente = $_POST['id_cliente'];
    $id_entrenador = $_POST['id_entrenador'];

    // Preparar la declaración OCI
    $stmt2 = oci_parse($conn, 'BEGIN paquete_empleados.Desvincular_Cliente_Entrenador(:id_cliente, :id_entrenador); END;');

    // Vincular los parámetros
    oci_bind_by_name($stmt2, ':id_cliente', $id_cliente);
    oci_bind_by_name($stmt2, ':id_entrenador', $id_entrenador);

    // Ejecutar la declaración
    oci_execute($stmt2);
    header("Location: ../admin_clientes.php");
?>