<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Iniciar una nueva sesión o reanudar la existente
// Datos de conexión
include 'conexionbasededatos.php';
echo "<div style='background-color: #333; padding: 20px; text-align: center;'>\n";
echo "<a href='logout.php' style='display: inline-block; padding: 10px 20px; background-color: red; color: white; text-decoration: none; margin: 10px;'>Log Out</a>\n";
echo "<a href='admin_gimnasio.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Gimnasio</a>\n";
echo "<a href='admin_entrenadores.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Entrenadores</a>\n";
echo "<a href='admin_monitores.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Monitores</a>\n";
echo "<a href='admin_clientes.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Clientes</a>\n";
echo "<a href='admin_maquinas.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin: 10px;'>Máquinas</a>\n";
echo "</div>\n";

$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
$search_correo = isset($_GET['search_correo']) ? $_GET['search_correo'] : '';
$query = 'SELECT id_Empleado, nombre_Empleado, apellido_Empleado, telefono_Empleado, correo_Empleado, direccion_Empleado, fecha_nacimiento_Empleado, contrasena_Empleado, turno FROM Tabla_monitores';
$where = [];

if (!empty($search_id)) {
    $where[] = "id_Empleado = $search_id";
}
if (!empty($search_correo)) {
    $where[] = "correo_Empleado LIKE '%$search_correo%'";
}
if (!empty($where)) {
  $query .= ' WHERE ' . implode(' AND ', $where);
}
$stid = oci_parse($conn, $query);
oci_execute($stid);
echo "<h2>Registrar nuevo monitor</h2>\n";
echo "<form method='POST' action='funciones/insertar_monitor.php'>\n";
$stid2 = oci_parse($conn, 'SELECT ID_GIMNASIO, NOMBRE_GIMNASIO, DIRECCION_GIMNASIO FROM gimnasio');
         oci_execute($stid2);
     
         // Crear el elemento select
         echo 'Gimnasio:';
         echo '<select id="id_gimnasio" name="id_gimnasio" required>';
         echo '<option value="" disabled selected>Seleccione una</option>';
         // Iterar sobre los resultados de la consulta
         while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
             // Generar una opción para el elemento select
             echo '<option value="' . $row['ID_GIMNASIO'] . '">' ."Nombre:". $row['NOMBRE_GIMNASIO'] ."  UBICACION:".  $row['DIRECCION_GIMNASIO'] .'</option>';
         }
     
         echo '</select><br>';

echo "Nombre: <input type='text' name='nombre' required>\n";
echo "Apellido: <input type='text' name='apellido' required>\n";
echo "Teléfono: <input type='text' name='telefono' required>\n";
echo "Correo: <input type='email' name='correo' required>\n";
echo "<br>";
echo "Dirección: <input type='text' name='direccion' required>\n";
echo "Fecha de Nacimiento: <input type='date' name='fecha_nacimiento' required>\n";
echo "Contraseña: <input type='password' name='contrasena' required>\n";
echo "Turno: <select name='turno' required>\n";
echo "<option value='mañana'>Mañana</option>\n";
echo "<option value='tarde'>Tarde</option>\n";
echo "</select>\n";
echo "<input type='submit' value='Registrar'>\n";
echo "</form>\n";
echo "<br>\n";

echo "<h1>Tabla de Monitores</h1>\n";
echo "<form method='GET' action='admin_monitores.php'>\n";
echo "Buscar por ID: <input type='text' name='search_id'>\n";
echo "Buscar por correo: <input type='text' name='search_correo'>\n";
echo "<input type='submit' value='Buscar'>\n";
echo "</form>\n";
echo "<br>";
echo "<table border='1' cellspacing='0' cellpadding='10' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr style='background-color: #f2f2f2;'>\n";
echo "<th>ID</th>\n";
echo "<th>Nombre</th>\n";
echo "<th>Apellido</th>\n";
echo "<th>Teléfono</th>\n";
echo "<th>Correo</th>\n";
echo "<th>Dirección</th>\n";
echo "<th>Fecha de Nacimiento</th>\n";
echo "<th>Contraseña</th>\n";
echo "<th>Turno</th>\n";
echo "<th>ID_gimnasio</th>\n";
echo "<th>Acción</th>\n";
echo "</tr>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    $sql = "BEGIN :result := paquete_empleados.obtener_id_gimnasio_por_monitor(:id_empleado); END;";

    // Preparar la declaración OCI
    $stmt = oci_parse($conn, $sql);

    // Vincular los parámetros
    oci_bind_by_name($stmt, ':id_empleado', $row['ID_EMPLEADO']);
    oci_bind_by_name($stmt, ':result', $result, 100);

    // Ejecutar la declaración
    oci_execute($stmt);
    echo "<td>" . $result . "</td>\n";
    echo "<td><form method='POST' action='funciones/eliminar_monitor.php'><input type='hidden' name='id_empleado' value='" . $row['ID_EMPLEADO'] . "'/><input type='submit' value='Eliminar' style='background-color: red; color: white;'/></form></td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
?>
<h1>Actualizar</h1>
<form method="POST" action="funciones/actualizar_monitor.php">
    <?php 
        $stid2 = oci_parse($conn, 'SELECT ID_empleado, NOMBRE_empleado FROM tabla_monitores');
         oci_execute($stid2);
     
         // Crear el elemento select
         echo 'Empleado:';
         echo '<select id="id_Empleado" name="id_Empleado" required>';
         echo '<option value="" disabled selected>Seleccione una</option>';
         // Iterar sobre los resultados de la consulta
         while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
             // Generar una opción para el elemento select
             echo '<option value="' . $row['ID_EMPLEADO'] . '">' ."ID:".$row['ID_EMPLEADO']."Nombre:". $row['NOMBRE_EMPLEADO'] .'</option>';
         }
     
         echo '</select>';
         ?>
    <label for="campo">Campo a actualizar:</label>
    <select id="campo" name="campo" required>
        <option value="nombre_EMPLEADO">Nombre del EMPLEADO</option>
        <option value="apellido_EMPLEADO">Apellidos del EMPLEADO</option>
        <option value="telefono_EMPLEADO">telefono del EMPLEADO</option>
        <option value="correo_EMPLEADO">Correo del EMPLEADO</option>
        <option value="direccion_EMPLEADO">Dirección del EMPLEADO</option>
        <!-- Agrega aquí más opciones según los campos que quieras permitir actualizar -->
    </select>

    <label for="nuevo_valor">Nuevo Valor:</label>
    <input type="text" id="nuevo_valor" name="nuevo_valor" required>
    <input type='hidden' name='tabla' id='tabla' value="Tabla_monitores">

    <input type="submit" value="Actualizar">
</form>
</body>


</html>