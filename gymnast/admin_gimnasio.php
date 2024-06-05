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
$query = 'SELECT id_Gimnasio, nombre_Gimnasio, direccion_Gimnasio, telefono_Gimnasio, correo_Gimnasio FROM Gimnasio';
$where = [];

if (!empty($search_id)) {
    $where[] = "id_Gimnasio = $search_id";
}
if (!empty($search_correo)) {
    $where[] = "correo_Gimnasio LIKE '%$search_correo%'";
}
if (!empty($where)) {
  $query .= ' WHERE ' . implode(' AND ', $where);
}
$stid = oci_parse($conn, $query);
oci_execute($stid);

echo "<h2>Registrar nuevo gimnasio</h2>\n";
echo "<form method='POST' action='funciones/insertar_gimnasio.php'>\n";
echo "Nombre: <input type='text' name='nombre_gimnasio' required>\n";
echo "Dirección: <input type='text' name='direccion_gimnasio' required>\n";
echo "Teléfono: <input type='text' name='telefono_gimnasio' required>\n";
echo "Correo: <input type='email' name='correo_gimnasio' required>\n";
echo "<input type='submit' value='Registrar'>\n";
echo "</form>\n";
echo "<br>\n";
echo "<h1>Tabla de Gimnasios</h1>\n";
echo '<br><br>';
echo "<form method='GET' action='admin_gimnasio.php'>\n";
echo "Buscar por ID: <input type='text' name='search_id'>\n";
echo "Buscar por correo: <input type='text' name='search_correo'>\n";
echo "<input type='submit' value='Buscar'>\n";
echo "</form>\n";
echo "<br>";
echo "<table border='1' cellspacing='0' cellpadding='10' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr style='background-color: #f2f2f2;'>\n";
echo "<th>ID</th>\n";
echo "<th>Nombre</th>\n";
echo "<th>Dirección</th>\n";
echo "<th>Teléfono</th>\n";
echo "<th>Correo</th>\n";
echo "<th>Clientes</th>\n";
echo "<th>Entrenadores</th>\n";
echo "<th>Monitores</th>\n";
echo "<th>Maquinas</th>\n";
echo "<th>Acción</th>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    $id_gimnasio = $row['ID_GIMNASIO'];
    $stid2 = oci_parse($conn, "BEGIN :ret :=paquete_gimnasio.Obtener_Numero_Clientes_Gimnasio(:id_gimnasio); END;");
    oci_bind_by_name($stid2, ':ret', $numero_clientes, 200);
    oci_bind_by_name($stid2, ':id_gimnasio', $id_gimnasio);
    oci_execute($stid2);
    echo "  <td>" . htmlentities($numero_clientes, ENT_QUOTES) . "</td>\n";

    $stid3 = oci_parse($conn, "BEGIN :ret :=paquete_gimnasio.Obtener_Numero_Entrenadores_Gimnasio(:id_gimnasio); END;");
    oci_bind_by_name($stid3, ':ret', $numero_entrenadores, 200);
    oci_bind_by_name($stid3, ':id_gimnasio', $id_gimnasio);
    oci_execute($stid3);
    echo "  <td>" . htmlentities($numero_entrenadores, ENT_QUOTES) . "</td>\n";
    
    $stid4 = oci_parse($conn, "BEGIN :ret :=paquete_gimnasio.Obtener_Numero_Monitores_Gimnasio(:id_gimnasio); END;");
    oci_bind_by_name($stid4, ':ret', $numero_monitores, 200);
    oci_bind_by_name($stid4, ':id_gimnasio', $id_gimnasio);
    oci_execute($stid4);
    echo "  <td>" . htmlentities($numero_monitores, ENT_QUOTES) . "</td>\n";

    $stid5 = oci_parse($conn, "BEGIN :ret :=paquete_gimnasio.Obtener_Numero_Maquinas_Gimnasio(:id_gimnasio); END;");
    oci_bind_by_name($stid5, ':ret', $numero_maquinas, 200);
    oci_bind_by_name($stid5, ':id_gimnasio', $id_gimnasio);
    oci_execute($stid5);
    echo "  <td>" . htmlentities($numero_maquinas, ENT_QUOTES) . "</td>\n";

    echo "<td><form method='POST' action='funciones/eliminar_gimnasio.php'><input type='hidden' name='id_gimnasio' value='" . $row['ID_GIMNASIO'] . "'/><input type='submit' value='Eliminar' style='background-color: red; color: white;'/></form></td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
?>
<h1>Actualizar</h1>
<form method="POST" action="funciones/actualizar_gimnasio.php">
    <?php 
        $stid2 = oci_parse($conn, 'SELECT ID_GIMNASIO, NOMBRE_GIMNASIO, DIRECCION_GIMNASIO FROM gimnasio');
         oci_execute($stid2);
     
         // Crear el elemento select
         echo 'Gimnasio:';
         echo '<select id="id_gimnasio" name="id_gimnasio" required>';
         echo '<option value="" disabled selected>Seleccione una</option>';
         // Iterar sobre los resultados de la consulta
         while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
             // Generar una opción para el elemento select
             echo '<option value="' . $row['ID_GIMNASIO'] . '">' ."ID:".$row['ID_GIMNASIO']."Nombre:". $row['NOMBRE_GIMNASIO'] ."  UBICACION:".  $row['DIRECCION_GIMNASIO'] .'</option>';
         }
     
         echo '</select>';
         ?>
    <label for="campo">Campo a actualizar:</label>
    <select id="campo" name="campo" required>
        <option value="nombre_gimnasio">Nombre del Gimnasio</option>
        <option value="direccion_gimnasio">Dirección del Gimnasio</option>
        <option value="telefono_gimnasio">telefono</option>
        <option value="correo_gimnasio">Correo del Gimnasio</option>
        <!-- Agrega aquí más opciones según los campos que quieras permitir actualizar -->
    </select>

    <label for="nuevo_valor">Nuevo Valor:</label>
    <input type="text" id="nuevo_valor" name="nuevo_valor" required>
    <input type='hidden' name='tabla' id='tabla' value="gimnasio">

    <input type="submit" value="Actualizar">
</form>


</html>