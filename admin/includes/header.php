<?php

session_start();

if (!isset($_SESSION['session_started']) && !isset($_SESSION['session_type'])) {
    if ($_SESSION['session_type'] != 1) {
        header('location: ../acceso.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../resources/css/bootstrap.min.css">
    <script src="../resources/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../resources/css/fontawesome5.15.1.all.min.css">
    <link rel="stylesheet" href="../resources/css/templates.css">
    <script src="../resources/js/jquery.min.js"></script>
    <meta name="description" content="Proyecto">
    <link rel="shortcut icon" href="../favicon.png" type="image/png">
    <title>
        <?php 
    
        if (isset($PageTitle)) {
            echo $PageTitle;
        } else {
            echo "Administrador de sistema";
        }

        ?>
    </title>
    <link rel="stylesheet" href="../resources/css/general.css">
    <link rel="stylesheet" href="resources/css/general.css">
    <link rel="stylesheet" href="resources/css/dataTables-custom.css">
    <?php 
        
        if (isset($HeaderLinks)) {
            echo $HeaderLinks;
        }

    ?>
</head>

<body class="fixed-navbar">
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="principal.php">
                <img src="../resources/images/hospital_logo.svg" alt="logo" width="24" height="24">
                DIF San Felipe
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end bg-dark" tabindex="-1" id="offcanvasNavbar" aria-labelledby="MenuOCV">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="MenuOCV"><span class="color-white">Menú</span></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav offcanvas-options justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="btn btn-dark" href="personal.php"><i class="fas fa-users"></i> Personal</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dark" href="servicios.php"><i class="fas fa-sitemap"></i> Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dark" href="citas.php"><i class="fas fa-calendar-alt"></i> Citas agendadas</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dark" href="solicitud_servicios.php"><i class="fas fa-list-ol"></i> Solicitud de Servicios</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dark" href="lugares.php"><i class="fas fa-map-marker-alt"></i> Lugares</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dark" href="conductores.php"><i class="fas fa-user-friends"></i> Conductores</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-dark" href="ambulancias.php"><i class="fas fa-ambulance"></i> Vehículos de traslado</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-danger" href="logout.php"><i class="fas fa-power-off"></i> Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>