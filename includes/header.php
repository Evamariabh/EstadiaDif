<!DOCTYPE html>
<html lang="es-MX">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="resources/js/gusi_framework_behavior.js"></script>
    <link rel="stylesheet" href="resources/css/gusi-framework.css">
    <link rel="stylesheet" href="resources/css/fontawesome5.15.1.all.min.css">
    <link rel="stylesheet" href="resources/css/templates.css">
    <meta name="description" content="Proyecto">
    <link rel="shortcut icon" href="favicon.png" type="image/png">
    <title>
        <?php 
        
        if (isset($PageTitle)) {
            echo $PageTitle;
        } else {
            echo "DIF San Felipe";
        }

        ?>
    </title>
    <link rel="stylesheet" href="resources/css/general.css">
    <script src="resources/js/general.js"></script>
    <?php 
        
        if (isset($HeaderLinks)) {
            echo $HeaderLinks;
        }

    ?>
</head>

<body>
    <button class="btn nav-close"><i class="fas fa-bars"></i></button>
    <nav>
        <div class="logo-head">
            <img src="resources/images/hospital_logo.svg" alt="Logo">
            <span>DIF San Felipe</span>
        </div>
        <div class="nav-links-container">
            <a href="index.php" class="nav-link nav-btn-highlight">Inicio <i class="fas fa-home"></i></a>
            <a href="solicitar-traslado.php" class="nav-link nav-btn-highlight">Solicitar traslado <i class="fas fa-ambulance"></i></a>
            <a href="consultar.php" class="nav-link nav-btn-highlight">Consultar Cita <i class="fas fa-calendar"></i></a>
            <a href="acceso.php" class="nav-link nav-btn-highlight">Iniciar sesión <i class="fas fa-user-circle"></i></a>
        </div>
    </nav>
    <div class="screen">