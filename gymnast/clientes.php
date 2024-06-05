<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Iniciar una nueva sesión o reanudar la existente
// Datos de conexión
if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"]; // Recuperar el valor de $_SESSION["id"]
    if(isset($_SESSION["TIPO"])){
        $tipo = $_SESSION["TIPO"];
    }
}
include 'conexionbasededatos.php';
?>
<head>
    <meta charset="utf-8">
    <title>Gymnast - Gym Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Flaticon Font -->
    <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.min.css" rel="stylesheet">
    <style>
        body {
        background-color: #A9A9A9; /* This is a gray color */
        }
        .move-button {
            position: relative;
            top: -10px; /* Mueve el botón 10px hacia arriba */
            left: 50px; /* Mueve el botón 10px hacia la izquierda */
        }
        td {
    
    background-color: white;
}
    </style>
</head>
<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0 display-4 font-weight-bold text-uppercase text-white">Gymnast</h1>
            </a>
            <?php
                if($id == null){
                echo '<a href="login.php" class="btn btn-lg btn-outline-light mt-2 mt-md-4 py-md-3 px-md-5 move-button">Login</a>';
                }
                else{
                    echo '<a href="logout.php" class="btn btn-lg btn-outline-light mt-2 mt-md-4 py-md-3 px-md-5 move-button">Log out</a>';
                }
            ?>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4 bg-secondary">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.php" class="nav-item nav-link">About Us</a>
                    <?php
                    if($id !=null){
                        echo '<a href="equipment.php" class="nav-item nav-link">Our equipment</a>';
                    }
                    if($tipo == 'cliente'){
                        echo '<a href="team.php" class="nav-item nav-link">Our trainers</a>';
                    }
                    else if($tipo == 'entrenador'){
                            echo '<a href="clientes.php" class="nav-item nav-link  active">Your Clients</a>';
                    }
                    ?>
                    <a href="class.php" class="nav-item nav-link">Classes</a>
                    
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
            </div>
        </nav>
    </div>
    <br>      <br><br><br>          
<?php
$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
$search_correo = isset($_GET['search_correo']) ? $_GET['search_correo'] : '';
$query = "SELECT c.id_Cliente, c.nombre_Cliente, c.apellido_Cliente,c.telefono_Cliente, c.correo_Cliente, c.direccion_Cliente, c.fecha_nacimiento_Cliente
FROM Cliente c
JOIN Tabla_Entrenadores te ON c.Entrenado = REF(te)
WHERE te.id_empleado = $id";

$where = [];

if (!empty($search_id)) {
    $where[] = "id_Cliente = $search_id";
}
if (!empty($search_correo)) {
    $where[] = "correo_Cliente LIKE '%$search_correo%'";
}
if (!empty($where)) {
  $query .= ' AND ' . implode(' AND ', $where);
}
$stid = oci_parse($conn, $query);
oci_execute($stid);

echo "<h1>Tabla de Clientes</h1>\n";
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
echo "<th>Acción</th>\n";
echo "</tr>\n";
while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "  <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "<td><form method='POST'><input type='hidden' name='id_cliente' value='" . $row['ID_CLIENTE'] . "'/><input type='hidden' name='id_entrenador' value='" . $id . "'/><input type='submit' name='quitar_entrenador' value='Quitar Cliente' style='background-color: blue; color: white;'/></form></td>\n";
    echo "</tr>\n";
}
echo "</table>\n";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quitar_entrenador'])) {
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
    header("Location:clientes.php");
}
?>

</body>
</html>