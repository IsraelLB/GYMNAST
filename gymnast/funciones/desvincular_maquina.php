<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

// Comprobar si se ha enviado el ID del maquina
if (isset($_POST['id_maquina'])) {
    $id_maquina = $_POST['id_maquina'];
    $id_gimnasio = $_POST['id_gimnasio'];

    // Preparar la declaración SQL para llamar al procedimiento almacenado
    $sql = "BEGIN paquete_gimnasio.Eliminar_Maquina_Gimnasio(:id_maquina,:id_gimnasio); END;";

    // Preparar la declaración OCI
    $stmt = oci_parse($conn, $sql);

    // Vincular los parámetros
    oci_bind_by_name($stmt, ':id_maquina', $id_maquina);
    oci_bind_by_name($stmt, ':id_gimnasio', $id_gimnasio);

    // Ejecutar la declaración
    $result = oci_execute($stmt);

    // Comprobar si la declaración se ejecutó correctamente
    if (!$result) {
        $m = oci_error($stmt);
        echo $m['message'], "\n";
        exit;
    }

    // Redirigir al usuario de vuelta a la página de administración
    header('Location: ../admin_maquinas.php');
} else {
    // Manejar el caso en que no se envió un ID de maquina
    echo "No se proporcionó un ID de maquina.";
}
?>