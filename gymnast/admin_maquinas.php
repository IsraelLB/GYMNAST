<!DOCTYPE html>
<html lang="en">
<?php
ob_start();
session_start(); // Iniciar una nueva sesión o reanudar la existente
ob_start(); 

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
$search_nombre = isset($_GET['search_nombre']) ? $_GET['search_nombre'] : '';
$query = 'SELECT id_maquina,nombre_maquina, descripcion_maquina,imagen FROM MaquinasTabla';
$where = [];

if (!empty($search_id)) {
    $where[] = "id_maquina = $search_id";
}
if (!empty($search_nombre)) {
    $where[] = "nombre_maquina LIKE '%$search_nombre%'";
}
if (!empty($where)) {
  $query .= ' WHERE ' . implode(' AND ', $where);
}
$stid = oci_parse($conn, $query);
oci_execute($stid);
echo "<h2>Registrar nueva maquina</h2>\n";
echo "<form method='POST' action='' enctype='multipart/form-data'>\n";

echo "Nombre: <input type='text' name='nombre' required>\n";
echo "Descripción: <input type='text' name='descripcion' required>\n";
echo "Imagen: <input type='file' name='imagen'>\n";
echo "<input type='submit' value='Registrar'>\n";
echo "</form>\n";
echo "<br>\n";

echo "<h1>Tabla de maquinas</h1>\n";
echo "<form method='GET' action='admin_maquinas.php'>\n";
echo "Buscar por ID: <input type='text' name='search_id'>\n";
echo "Buscar por nombre: <input type='text' name='search_nombre'>\n";
echo "<input type='submit' value='Buscar'>\n";
echo "</form>\n";
echo "<br>";
echo "<table border='1' cellspacing='0' cellpadding='10' style='border-collapse: collapse; width: 100%;'>\n";
echo "<tr style='background-color: #f2f2f2;'>\n";
echo "<th>ID</th>\n";
echo "<th>Nombre</th>\n";
echo "<th>Descripción</th>\n";
echo "<th>ID_gimnasio</th>\n";
echo "<th>Imagen</th>\n";
echo "<th>Acción</th>\n";
echo "</tr>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_LOBS)) {
    echo "<tr>\n";
    $count = 0;
    foreach ($row as $key => $item) {
        if ($count < 3) {
            echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        if ($key == 'IMAGEN') {
            // Codificar la imagen en base64 y mostrarla
            $sql = "BEGIN :result := paquete_maquinas.obtener_id_gimnasio_por_maquina(:id_maquina); END;";

            // Preparar la declaración OCI
            $stmt = oci_parse($conn, $sql);

            // Vincular los parámetros
            oci_bind_by_name($stmt, ':id_maquina', $row['ID_MAQUINA']);
            oci_bind_by_name($stmt, ':result', $result, 100);

            // Ejecutar la declaración
            oci_execute($stmt);
            echo "<td>" . $result . "</td>\n";

            echo "  <td><img src='data:image/jpeg;base64," . base64_encode($item) . "' width='100' height='100'></td>\n";
        }
        $count++;
    }
    if($count == 3){
        $sql = "BEGIN :result := paquete_maquinas.obtener_id_gimnasio_por_maquina(:id_maquina); END;";

            // Preparar la declaración OCI
            $stmt = oci_parse($conn, $sql);

            // Vincular los parámetros
            oci_bind_by_name($stmt, ':id_maquina', $row['ID_MAQUINA']);
            oci_bind_by_name($stmt, ':result', $result, 100);

            // Ejecutar la declaración
            oci_execute($stmt);
            echo "<td>" . $result . "</td>\n";
            echo "<td>" . NULL. "</td>\n";
    }
    echo "<td><form method='POST' action='funciones/eliminar_maquina.php'><input type='hidden' name='id_maquina' value='" . $row['ID_MAQUINA'] . "'/><input type='submit' value='Eliminar' style='background-color: red; color: white;'/></form>";
    echo "<br>";
    if ($result!=NULL){
        echo "<form method='POST' action='funciones/desvincular_maquina.php'>
                <input type='hidden' name='id_maquina' value='" . $row['ID_MAQUINA'] . "'/>
                <input type='hidden' name='id_gimnasio' value='" . $result . "'/>
                <input type='submit' name='quitar_maquina' value='Desvincular Maquina' style='background-color: blue; color: white;'/>
                </form></td>\n";
    }
    else{
        $stid2 = oci_parse($conn, 'SELECT ID_GIMNASIO, NOMBRE_GIMNASIO, DIRECCION_GIMNASIO FROM gimnasio');
        oci_execute($stid2);
        echo "<form method='POST' action='funciones/asignar_maquina.php'>
        <input type='hidden' name='id_maquina' value='" . $row['ID_MAQUINA'] . "'/>";
        echo '<select id="id_gimnasio" name="id_gimnasio" required>';
        echo '<option value="" disabled selected>Seleccione una</option>';
        // Iterar sobre los resultados de la consulta
        while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
            // Generar una opción para el elemento select
            echo '<option value="' . $row['ID_GIMNASIO'] . '">' ."Nombre:". $row['NOMBRE_GIMNASIO'] ."  UBICACION:".  $row['DIRECCION_GIMNASIO'] .'</option>';
        }
        echo '</select><br>';
        echo "<input type='submit' name='asignar_maquina' value='Asignar Maquina' style='background-color: blue; color: white;'/>
        </form></td>\n";
    }
    echo "</td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
?>
<h1>Actualizar</h1>
<form method="POST" action="funciones/actualizar_maquina.php" enctype="multipart/form-data">
    <?php 
        $stid2 = oci_parse($conn, 'SELECT ID_MAQUINA, NOMBRE_MAQUINA FROM MaquinasTabla');
         oci_execute($stid2);
     
         // Crear el elemento select
         echo 'Maquina:';
         echo '<select id="id_maquina" name="id_maquina" required>';
         echo '<option value="" disabled selected>Seleccione una</option>';
         // Iterar sobre los resultados de la consulta
         while ($row = oci_fetch_array($stid2, OCI_ASSOC+OCI_RETURN_NULLS)) {
             // Generar una opción para el elemento select
             echo '<option value="' . $row['ID_MAQUINA'] . '">' ."ID:".$row['ID_MAQUINA']."Nombre:". $row['NOMBRE_MAQUINA'] .'</option>';
         }
     
         echo '</select>';
         ?>
    <label for="campo">Campo a actualizar:</label>
    <select id="campo" name="campo" required>
        <option value="nombre_maquina">Nombre de la Maquina</option>
        <option value="descripcion_maquina">Descripcion de la Maquina</option>
        <option value="imagen">imagen</option>
        <!-- Agrega aquí más opciones según los campos que quieras permitir actualizar -->
    </select>

    <label for="nuevo_valor">Nuevo Valor:</label>
    <input type="text" id="nuevo_valor" name="nuevo_valor">
    <label for="imagen">Imagen:</label>
    <input type='file' name='imagen'>

    <input type="submit" value="Actualizar">
</form>
<?php
ob_end_flush();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    if(isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != '') {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
    } else {
        $imagen = null; 
    }

    // Preparar la llamada al procedimiento almacenado
    $stid = oci_parse($conn, 'BEGIN paquete_maquinas.Insertar_Maquina(:nombre, :descripcion, :imagen); END;');

    // Vincular los parámetros
    oci_bind_by_name($stid, ':nombre', $nombre);
    oci_bind_by_name($stid, ':descripcion', $descripcion);
    $blob = oci_new_descriptor($conn, OCI_D_LOB);
    $blob->writeTemporary($imagen, OCI_TEMP_BLOB);
    oci_bind_by_name($stid, ':imagen', $blob, -1, OCI_B_BLOB);

    // Ejecutar el procedimiento almacenado
    oci_execute($stid);

    // Liberar los recursos
    $blob->free();
    oci_free_statement($stid);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
ob_end_flush();
?>


</html>