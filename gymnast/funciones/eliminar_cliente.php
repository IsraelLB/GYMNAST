<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

// Comprobar si se ha enviado el ID del cliente
if (isset($_POST['id_cliente'])) {
    $id_cliente = $_POST['id_cliente'];

    // Preparar la declaración SQL para llamar al procedimiento almacenado
    $sql = "BEGIN paquete_cliente.Eliminar_Cliente(:id_cliente); END;";

    // Preparar la declaración OCI
    $stmt = oci_parse($conn, $sql);

    // Vincular los parámetros
    oci_bind_by_name($stmt, ':id_cliente', $id_cliente);

    // Ejecutar la declaración
    $result = oci_execute($stmt);

    // Comprobar si la declaración se ejecutó correctamente
    if (!$result) {
        $m = oci_error($stmt);
        echo $m['message'], "\n";
        exit;
    }

    // Redirigir al usuario de vuelta a la página de administración
    header('Location: ../admin_clientes.php');
} else {
    // Manejar el caso en que no se envió un ID de cliente
    echo "No se proporcionó un ID de cliente.";
}
?>