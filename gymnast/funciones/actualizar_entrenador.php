<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

    // Obtener los IDs del cliente y del entrenador de la solicitud POST
    $id_empleado = $_POST['id_empleado'];
    $campo = $_POST['campo'];
    $nuevo_valor = $_POST['nuevo_valor'];
    if(isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != '') {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        $query = "UPDATE Tabla_entrenadores SET imagen = :imagen WHERE id_empleado = :id_empleado";
        $stid1 = oci_parse($conn, $query);
        oci_bind_by_name($stid1, ':id_empleado', $id_empleado);
        $blob = oci_new_descriptor($conn, OCI_D_LOB);
        $blob->writeTemporary($imagen, OCI_TEMP_BLOB);
        oci_bind_by_name($stid1, ':imagen', $blob, -1, OCI_B_BLOB);
        oci_execute($stid1);
    }
    else{
        $query = "UPDATE Tabla_entrenadores SET $campo = :nuevo_valor WHERE id_empleado = :id_empleado";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':nuevo_valor', $nuevo_valor);
    oci_bind_by_name($stid, ':id_empleado', $id_empleado);
    oci_execute($stid);
    }
    header("Location: ../admin_entrenadores.php");
?>