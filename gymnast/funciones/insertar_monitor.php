<?php
// Incluir el archivo de conexión a la base de datos
include '../conexionbasededatos.php';

    $id_gimnasio = $_POST['id_gimnasio'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $contrasena = $_POST['contrasena'];
    $turno = $_POST['turno'];

    $stid1 = oci_parse($conn, 'BEGIN paquete_empleados.insertar_empleado_monitor(:id_gimnasio, :nombre, :apellido, :telefono, :correo, :direccion, TO_DATE(:fecha_nacimiento, \'YYYY-MM-DD\'), :contrasena, :turno); END;');
    oci_bind_by_name($stid1, ':id_gimnasio', $id_gimnasio);
    oci_bind_by_name($stid1, ':nombre', $nombre);
    oci_bind_by_name($stid1, ':apellido', $apellido);
    oci_bind_by_name($stid1, ':telefono', $telefono);
    oci_bind_by_name($stid1, ':correo', $correo);
    oci_bind_by_name($stid1, ':direccion', $direccion);
    oci_bind_by_name($stid1, ':fecha_nacimiento', $fecha_nacimiento);
    oci_bind_by_name($stid1, ':contrasena', $contrasena);
    oci_bind_by_name($stid1, ':turno', $turno);
    oci_execute($stid1);
    header("Location: ../admin_monitores.php");
?>