<!DOCTYPE html>
<html lang="en">
<?php
session_start();
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
            background-image: url('./img/register.jpg'); 
            background-size: cover; /* Esto hará que la imagen cubra todo el cuerpo */
            color: #fff; /* Cambia el color del texto a blanco para que sea visible en el fondo oscuro */
            color: #fff; /* Cambia el color del texto a blanco para que sea visible en el fondo oscuro */
        }
        form {
            margin-left: 20px; /* Añade margen a la izquierda del formulario */
        }

        form label, form input {
            font-size: 1.5em; /* Aumenta el tamaño de la fuente de los elementos label e input */
        }
    </style>
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <?php
    if (isset($_SESSION["TIPO"])!="admin") {
        echo '<nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">';
        echo  '<a href="" class="navbar-brand">
                <h1 class="m-0 display-4 font-weight-bold text-uppercase text-white">Gymnast</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4 bg-secondary">
                    <a href="index.php" class="nav-item nav-link active">Home</a>
                    <a href="about.php" class="nav-item nav-link">About Us</a>
                    <a href="feature.php" class="nav-item nav-link">Our Features</a>
                    <a href="class.php" class="nav-item nav-link">Classes</a>
                    
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
            </div>
        </nav>';}
        ?>
        <form action="register.php" method="post">
        <?php
         $stid = oci_parse($conn, 'SELECT ID_GIMNASIO, NOMBRE_GIMNASIO, DIRECCION_GIMNASIO FROM gimnasio');
         oci_execute($stid);
     
         // Crear el elemento select
         echo '<label for="gym-id">Gimnasio:</label><br>';
         echo '<select id="gym-id" name="gym-id" required>';
         echo '<option value="" disabled selected>Seleccione una</option>';
         // Iterar sobre los resultados de la consulta
         while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
             // Generar una opción para el elemento select
             echo '<option value="' . $row['ID_GIMNASIO'] . '">' ."Nombre:". $row['NOMBRE_GIMNASIO'] ."  UBICACION:".  $row['DIRECCION_GIMNASIO'] .'</option>';
         }
     
         echo '</select><br>';
     ?>

        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br>

        <label for="apellido">Apellido:</label><br>
        <input type="text" id="apellido" name="apellido" required><br>

        <label for="telefono">Teléfono:</label><br>
        <input type="text" id="telefono" name="telefono" required><br>

        <label for="correo">Correo:</label><br>
        <input type="email" id="correol" name="correo" required><br>

        <label for="direccion">Dirección:</label><br>
        <input type="text" id="direccion" name="direccion" required><br>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label><br>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required><br>

        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena" required><br>
        <br>
        <input type="submit" value="Registrarse"><br><br>
    </form>
    </div>
    <!-- Navbar End -->
</body>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los valores del formulario
        $id_gimnasio = intval($_POST["gym-id"]);
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $telefono = $_POST["telefono"];
        $correo = $_POST["correo"];
        $direccion = $_POST["direccion"];
        $fecha_nacimiento = $_POST["fecha_nacimiento"];
         // Convertir la fecha de nacimiento a un formato que Oracle pueda entender
         $fecha_nacimiento = date('d/m/y', strtotime($fecha_nacimiento));
        $contrasena = $_POST["contrasena"];
        $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);


        // Preparar la declaración de llamada al procedimiento
        $stid = oci_parse($conn, 'BEGIN paquete_cliente.insertar_cliente(:p_id_Gimnasio, :p_nombre, :p_apellido, :p_telefono, :p_correo, :p_direccion, TO_DATE(:p_fecha_nacimiento, \'DD/MM/RR\'), :p_contrasena); END;');

        // Vincular los parámetros
        oci_bind_by_name($stid, ':p_id_Gimnasio', $id_gimnasio);
        oci_bind_by_name($stid, ':p_nombre', $nombre);
        oci_bind_by_name($stid, ':p_apellido', $apellido);
        oci_bind_by_name($stid, ':p_telefono', $telefono);
        oci_bind_by_name($stid, ':p_correo', $correo);
        oci_bind_by_name($stid, ':p_direccion', $direccion);
        oci_bind_by_name($stid, ':p_fecha_nacimiento', $fecha_nacimiento);
        oci_bind_by_name($stid, ':p_contrasena', $contrasena);

        // Ejecutar la declaración
        $result = oci_execute($stid);

        if ($result) {
            // Si el registro fue exitoso, redirigir al usuario a login.php
            if(isset($_SESSION["TIPO"])=="admin"){
                header('Location: admin_clientes.php');
                exit;
            }else{
                header('Location: login.php');
                exit;
            }
        } else {
            // Si hubo un error, manejarlo aquí
            echo "<script>alert('Hubo un error al registrar el usuario');</script>";
        }

    }
?>
</html>