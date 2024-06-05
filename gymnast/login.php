<!DOCTYPE html>
<html lang="en">
<?php
session_start();
// Datos de conexión
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
            background-image: url('./img/login.jpg'); 
            background-size: cover; /* Esto hará que la imagen cubra todo el cuerpo */
            color: #fff; /* Cambia el color del texto a blanco para que sea visible en el fondo oscuro */
            color: #fff; /* Cambia el color del texto a blanco para que sea visible en el fondo oscuro */
        }
        form {
            margin-left: 20px; /* Añade margen a la izquierda del formulario */
        }
        form label, form input {
            font-size: 2em; /* Aumenta el tamaño de la fuente de los elementos label e input */
        }
    </style>
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="" class="navbar-brand">
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
        </nav>
        <form action="login.php" method="post">
            <label for="email">Correo:</label><br>
            <input type="email" name="correo" required placeholder="Correo"><br>
            <label for="pwd">Contraseña:</label><br>
            <input type="password" name="contrasena" required placeholder="Contraseña"><br><br><br>
            <button type="submit">Iniciar sesión</button><br><br>
        </form>
    </div>
    <!-- Navbar End -->
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    if($correo == "admin@admin.admin" && $contrasena == "admin") {
        $_SESSION["TIPO"] = "admin";
        header("Location: admin.php");
    }

    $stid = oci_parse($conn, 'BEGIN :result := paquete_cliente.iniciar_sesion_cliente(:p_correo, :p_contrasena); END;');
    oci_bind_by_name($stid, ':p_correo', $correo);
    oci_bind_by_name($stid, ':p_contrasena', $contrasena);
    oci_bind_by_name($stid, ':result', $result, 20);
    oci_execute($stid);

    if ($result == 0) {
        $stid = oci_parse($conn, 'BEGIN :result := paquete_empleados.iniciar_sesion_monitor(:p_correo, :p_contrasena); END;');
        oci_bind_by_name($stid, ':p_correo', $correo);
        oci_bind_by_name($stid, ':p_contrasena', $contrasena);
        oci_bind_by_name($stid, ':result', $result, 20);
        oci_execute($stid);
        if ($result == 0) {
            $stid = oci_parse($conn, 'BEGIN :result := paquete_empleados.iniciar_sesion_entrenador(:p_correo, :p_contrasena); END;');
            oci_bind_by_name($stid, ':p_correo', $correo);
            oci_bind_by_name($stid, ':p_contrasena', $contrasena);
            oci_bind_by_name($stid, ':result', $result, 20);
            oci_execute($stid);
            if ($result == 0) {
                echo "<script>alert('Correo o contraseña incorrectos');</script>";
            }
            else {
                $_SESSION["id"] = $result;
                $_SESSION["TIPO"] = "entrenador";
                $sql = "BEGIN :result := paquete_empleados.obtener_id_gimnasio_por_entrenador(:id_empleado); END;";
                $stmt = oci_parse($conn, $sql);

            // Vincular los parámetros
            oci_bind_by_name($stmt, ':id_empleado', $result);
            oci_bind_by_name($stmt, ':result', $id_gimnasio, 100);
            // Ejecutar la declaración
            oci_execute($stmt);
            $_SESSION["id_gimnasio"] = $id_gimnasio;
                header("Location: index.php");
            }
        }
        else {
            $_SESSION["id"] = $result;
            $_SESSION["TIPO"] = "monitor";
            $sql = "BEGIN :result := paquete_empleados.obtener_id_gimnasio_por_monitor(:id_empleado); END;";

            // Preparar la declaración OCI
            $stmt = oci_parse($conn, $sql);

            // Vincular los parámetros
            oci_bind_by_name($stmt, ':id_empleado', $result);
            oci_bind_by_name($stmt, ':result', $id_gimnasio, 100);

            // Ejecutar la declaración
            oci_execute($stmt);
            $_SESSION["id_gimnasio"] = $id_gimnasio;
            header("Location: index.php");
        }
    } else {
        $_SESSION["id"] = $result;
        $_SESSION["TIPO"] = "cliente";
        $sql = "BEGIN :result := paquete_cliente.obtener_id_gimnasio(:id_cliente); END;";

        // Preparar la declaración OCI
        $stmt = oci_parse($conn, $sql);

        // Vincular los parámetros
        oci_bind_by_name($stmt, ':id_cliente', $result);
        oci_bind_by_name($stmt, ':result', $id_gimnasio, 100);
        // Ejecutar la declaración
        oci_execute($stmt);
        $_SESSION["id_gimnasio"] = $id_gimnasio;
        header("Location: index.php");
    }
}
?>
</html>