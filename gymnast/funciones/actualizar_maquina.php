<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

    // Obtener los IDs del cliente y del entrenador de la solicitud POST
    $id_maquina = $_POST['id_maquina'];
    $campo = $_POST['campo'];
    $nuevo_valor = $_POST['nuevo_valor'];
    if(isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != '') {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        $query = "UPDATE MaquinasTabla SET imagen = :imagen WHERE id_maquina = :id_maquina";
        $stid1 = oci_parse($conn, $query);
        oci_bind_by_name($stid1, ':id_maquina', $id_maquina);
        $blob = oci_new_descriptor($conn, OCI_D_LOB);
        $blob->writeTemporary($imagen, OCI_TEMP_BLOB);
        oci_bind_by_name($stid1, ':imagen', $blob, -1, OCI_B_BLOB);
        oci_execute($stid1);
        echo oci_error();
    }
    else{
        $query = "UPDATE MaquinasTabla SET $campo = :nuevo_valor WHERE id_maquina = :id_maquina";
    $stid = oci_parse($conn, $query);
    oci_bind_by_name($stid, ':nuevo_valor', $nuevo_valor);
    oci_bind_by_name($stid, ':id_maquina', $id_maquina);
    oci_execute($stid);
    }
    header("Location: ../admin_maquinas.php");
?>