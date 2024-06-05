<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php
 // Iniciar una nueva sesión o reanudar la existente
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
$query = 'SELECT id_Cliente, nombre_Cliente, apellido_Cliente, telefono_Cliente, correo_Cliente, direccion_Cliente, fecha_nacimiento_Cliente, contrasena_Cliente FROM Cliente';
$where = [];

if (!empty($search_id)) {
    $where[] = "id_Cliente = $search_id";
}
if (!empty($search_correo)) {
    $where[] = "correo_Cliente LIKE '%$search_correo%'";
}
if (!empty($where)) {
  $query .= ' WHERE ' . implode(' AND ', $where);
}
$stid = oci_parse($conn, $query);
oci_execute($stid);

echo "<h1>Tabla de Clientes</h1>\n";
echo "<a href='register.php' style='display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; margin-top: 10px;'>Registrar nuevo cliente</a>\n";
echo '<br><br>';
echo "<form method='GET' action='admin_clientes.php'>\n";
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
echo "<th>ID_gimnasio</th>\n";
echo "<th>ID_Entrenador</th>\n";
echo "<th>Acción</th>\n";
echo "</tr>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    $sql = "BEGIN :result := paquete_cliente.obtener_id_gimnasio(:id_cliente); END;";

    // Preparar la declaración OCI
    $stmt = oci_parse($conn, $sql);

    // Vincular los parámetros
    oci_bind_by_name($stmt, ':id_cliente', $row['ID_CLIENTE']);
    oci_bind_by_name($stmt, ':result', $result, 100);

    // Ejecutar la declaración
    oci_execute($stmt);
    echo "<td>" . $result . "</td>\n";

    $sql1 = "BEGIN :result1 := paquete_cliente.obtener_id_entrenador(:id_cliente); END;";

    // Preparar la declaración OCI
    $stmt1 = oci_parse($conn, $sql1);

    // Vincular los parámetros
    oci_bind_by_name($stmt1, ':id_cliente', $row['ID_CLIENTE']);
    oci_bind_by_name($stmt1, ':result1', $result1, 100);

    // Ejecutar la declaración
    oci_execute($stmt1);
    echo "<td>" . $result1 . "</td>\n";
    if ($result1 != null) {
        echo "<td><form method='POST' action='funciones/quitar_entrenador.php'><input type='hidden' name='id_cliente' value='" . $row['ID_CLIENTE'] . "'/><input type='hidden' name='id_entrenador' value='" . $result1 . "'/><input type='submit' name='quitar_entrenador' value='Quitar Entrenador' style='background-color: blue; color: white;'/></form><br><form method='POST' action='funciones/eliminar_cliente.php'><input type='hidden' name='id_cliente' value='" . $row['ID_CLIENTE'] . "'/><input type='submit' value='Eliminar' style='background-color: red; color: white;'/></form></td>\n";  
    }
    else{
        echo "<td><form method='POST' action='funciones/eliminar_cliente.php'><input type='hidden' name='id_cliente' value='" . $row['ID_CLIENTE'] . "'/><input type='submit' value='Eliminar' style='background-color: red; color: white;'/></form>";
        $stid2 = oci_parse($conn, 'SELECT ID_EMPLEADO, NOMBRE_EMPLEADO FROM tabla_entrenadores');
        oci_execute($stid2);
        echo "<br>";
        echo "<form method='POST' action='funciones/asignar_cliente.php'>
        <input type='hidden' name='id_cliente' value='" . $row['ID_CLIENTE'] . "'/>";
        echo '<select id="id_entrenador" name="id_entrenador" required>';
        echo '<option value="" disabled selected>Seleccione una</option>';
        // Iterar sobre los resultados de la consulta
        while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            // Generar una opción para el elemento select
            echo '<option value="' . $row['ID_EMPLEADO'] . '">' ."Nombre:". $row['NOMBRE_EMPLEADO'].'</option>';
        }
        echo '</select><br>';
        echo "<input type='submit' name='asignar_cliente' value='Asignar Entrenador' style='background-color: blue; color: white;'/>
        </form></td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";
?>
<h1>Actualizar</h1>
<form method="POST" action="funciones/actualizar_cliente.php">
    <?php 
        $stid2 = oci_parse($conn, 'SELECT ID_CLIENTE, NOMBRE_CLIENTE, DIRECCION_CLIENTE FROM CLIENTE');
         oci_execute($stid2);
     
         // Crear el elemento select
         echo 'CLIENTE:';
         echo '<select id="id_CLIENTE" name="id_CLIENTE" required>';
         echo '<option value="" disabled selected>Seleccione una</option>';
         // Iterar sobre los resultados de la consulta
         while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
             // Generar una opción para el elemento select
             echo '<option value="' . $row['ID_CLIENTE'] . '">' ."ID:".$row['ID_CLIENTE']."Nombre:". $row['NOMBRE_CLIENTE'] .'</option>';
         }
     
         echo '</select>';
         ?>
    <label for="campo">Campo a actualizar:</label>
    <select id="campo" name="campo" required>
        <option value="nombre_CLIENTE">Nombre del Cliente</option>
        <option value="apellido_CLIENTE">Dirección del Cliente</option>
        <option value="telefono_CLIENTE">telefono del Cliente</option>
        <option value="correo_CLIENTE">Correo del Cliente</option>
        <option value="direccion_CLIENTE">Dirección del Cliente</option>
        <!-- Agrega aquí más opciones según los campos que quieras permitir actualizar -->
    </select>

    <label for="nuevo_valor">Nuevo Valor:</label>
    <input type="text" id="nuevo_valor" name="nuevo_valor" required>
    <input type='hidden' name='tabla' id='tabla' value="CLIENTE">

    <input type="submit" value="Actualizar">
</form>



</html>