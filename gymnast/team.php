<!DOCTYPE html>
<html lang="en">
<?php
session_start(); // Iniciar una nueva sesión o reanudar la existente
include 'conexionbasededatos.php';
$id = null; // Inicializar la variable $id
$tipo = null;
if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"]; // Recuperar el valor de $_SESSION["id"]
    // Ahora puedes usar $id en tu código
    if(isset($_SESSION["TIPO"])){
        $tipo = $_SESSION["TIPO"];
    }
    if(isset($_SESSION["id_gimnasio"])){
        $id_gimnasio = $_SESSION["id_gimnasio"];
    }
}
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
        .move-button {
            position: relative;
            top: -10px; /* Mueve el botón 10px hacia arriba */
            left: 50px; /* Mueve el botón 10px hacia la izquierda */
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="" class="navbar-brand">
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
                    ?>
                    <a href="team.php" class="nav-item nav-link active">Our trainers</a>
                    <a href="class.php" class="nav-item nav-link">Classes</a>
                    
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
            <h4 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase font-weight-bold">Our trainers</h4>
            <div class="d-inline-flex">
                <p class="m-0 text-white"><a class="text-white" href="">Home</a></p>
                <p class="m-0 text-white px-2">/</p>
                <p class="m-0 text-white">Our trainers</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
    <!-- Team Start -->
    <div class="container pt-5 team">
        <div class="d-flex flex-column text-center mb-5">
            <h4 class="text-primary font-weight-bold">Our Trainers</h4>
            <h4 class="display-4 font-weight-bold">Meet Our Expert Trainers</h4>
        </div>
        <div class="row">
            <?php
            $query = "SELECT te.nombre_Empleado, te.apellido_Empleado, te.correo_Empleado, te.imagen
            FROM Tabla_Entrenadores te
            JOIN Gimnasio g ON te.trabaja = REF(g)
            WHERE g.id_gimnasio = $id_gimnasio";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            $count=0;
            while (($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                echo "<div class='col-lg-3 col-md-6 mb-5'>\n";
                echo "<div class='card border-0 bg-secondary text-center text-white'>\n";
                if ($row['IMAGEN'] !== null) {
                    $image = $row['IMAGEN']->load();
                    echo "<img class='card-img-top' src='data:image/jpeg;base64," . base64_encode($image) . "' alt=''>\n";
                } else {
                    echo "<img class='card-img-top' src='img/default.png' alt='Default Image'>\n"; // Imagen por defecto
                }            
                echo "<div class='card-social d-flex align-items-center justify-content-center'>\n";
                echo "<a class='btn btn-outline-light rounded-circle text-center mr-2 px-0' style='width: 40px; height: 40px;' href='#'><i class='fab fa-twitter'></i></a>\n";
                echo "<a class='btn btn-outline-light rounded-circle text-center mr-2 px-0' style='width: 40px; height: 40px;' href='#'><i class='fab fa-facebook-f'></i></a>\n";
                echo "<a class='btn btn-outline-light rounded-circle text-center mr-2 px-0' style='width: 40px; height: 40px;' href='#'><i class='fab fa-linkedin-in'></i></a>\n";
                echo "<a class='btn btn-outline-light rounded-circle text-center mr-2 px-0' style='width: 40px; height: 40px;' href='#'><i class='fab fa-instagram'></i></a>\n";
                echo "</div>\n";
                echo "<div class='card-body bg-secondary'>\n";
                echo "<h4 class='card-title text-primary'>" . $row['NOMBRE_EMPLEADO'] . " " . $row['APELLIDO_EMPLEADO'] . "</h4>\n";
                echo "<p class='card-text'>". $row['CORREO_EMPLEADO'] ."</p>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                $count++;
                if($count%4==0 && $count!=1){
                    echo "</div>\n";
                    echo "<div class='row'>\n";
                }
            }
            ?>
        </div>
    </div>
    <!-- Team End -->
    <!-- Footer Start -->
    <div class="footer container-fluid mt-5 py-5 px-sm-3 px-md-5 text-white">
        <div class="row pt-5">
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-primary mb-4">Get In Touch</h4>
                <p><i class="fa fa-map-marker-alt mr-2"></i>123 Street, New York, USA</p>
                <p><i class="fa fa-phone-alt mr-2"></i>+012 345 67890</p>
                <p><i class="fa fa-envelope mr-2"></i>info@example.com</p>
                <div class="d-flex justify-content-start mt-4">
                    <a class="btn btn-outline-light rounded-circle text-center mr-2 px-0" style="width: 40px; height: 40px;" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light rounded-circle text-center mr-2 px-0" style="width: 40px; height: 40px;" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light rounded-circle text-center mr-2 px-0" style="width: 40px; height: 40px;" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-outline-light rounded-circle text-center mr-2 px-0" style="width: 40px; height: 40px;" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-primary mb-4">Quick Links</h4>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Home</a>
                    <a class="text-white mb-2" href="about.php"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                    <a class="text-white mb-2" href="team.php"><i class="fa fa-angle-right mr-2"></i>Our Trainers</a>
                    <a class="text-white mb-2" href="class.php"><i class="fa fa-angle-right mr-2"></i>Classes</a>
                    <a class="text-white" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-primary mb-4">Popular Links</h4>
                <div class="d-flex flex-column justify-content-start">
                    <a class="text-white mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Home</a>
                    <a class="text-white mb-2" href="about.php"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                    <a class="text-white mb-2" href="team.php"><i class="fa fa-angle-right mr-2"></i>Our Trainers</a>
                    <a class="text-white mb-2" href="class.php"><i class="fa fa-angle-right mr-2"></i>Classes</a>
                    <a class="text-white" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-5">
                <h4 class="text-primary mb-4">Opening Hours</h4>
                <h5 class="text-white">Monday - Friday</h5>
                <p>8.00 AM - 8.00 PM</p>
                <h5 class="text-white">Saturday - Sunday</h5>
                <p>2.00 PM - 8.00 PM</p>
            </div>
        </div>
        <div class="container border-top border-dark pt-5">
            <p class="m-0 text-center text-white">
                &copy; <a class="text-white font-weight-bold" href="#">Your Site Name</a>. All Rights Reserved. Designed by
                <a class="text-white font-weight-bold" href="https://htmlcodex.com">HTML Codex</a>
            </p>
        </div>
    </div>
    <!-- Footer End -->


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_entrenador = $_POST['entrenador_id'];
    $sql = "BEGIN paquete_cliente.Asignar_Entrenador(:id_cliente,:id_entrenador); END;";

    // Preparar la declaración OCI
    $stmt = oci_parse($conn, $sql);

    // Vincular los parámetros
    oci_bind_by_name($stmt, ':id_cliente', $id);
    oci_bind_by_name($stmt, ':id_entrenador', $id_entrenador);

    // Ejecutar la declaración
    oci_execute($stmt);

    // Redirigir al usuario de vuelta a la página de administración
    header('Location: team.php');
} 
?>
</html>