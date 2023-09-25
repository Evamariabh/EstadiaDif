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
    <title>Lista de agendas - Conductor</title>
    <link rel="stylesheet" href="../resources/css/general.css">
    <link rel="stylesheet" href="../admin/resources/css/dataTables-custom.css">
    <link rel="stylesheet" href="../admin/resources/css/general.css">
    <script src="js/agenda.js" type="text/javascript"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>
    <style>
        a.btn{
            color: white !important;
        }
    </style>
</head>

<body class="fixed-navbar">
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="../resources/images/hospital_logo.svg" alt="logo" width="24" height="24">
                DIF San Felipe
            </a>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
    </nav>
    <div class="container-fluid mt-2">
        <h1>Agenda de traslados</h1>
        <hr>
        <ul class="nav nav-tabs mb-3" id="tabTraslados" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="traslados-hoy-tab" data-bs-toggle="tab" data-bs-target="#traslados-hoy" type="button" role="tab" aria-controls="traslados-hoy" aria-selected="true">Hoy</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="traslados-total-tab" data-bs-toggle="tab" data-bs-target="#traslados-total" type="button" role="tab" aria-controls="traslados-total" aria-selected="false">Total</button>
            </li>
        </ul>
        <div class="tab-content" id="tabTrasladosContent">
            <div class="tab-pane fade show active" id="traslados-hoy" role="tabpanel" aria-labelledby="traslados-hoy-tab">
                <table id="DTBL_Agenda_Hoy" class="table DataTable CenterDTBL">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Código</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Origen</th>
                            <th scope="col">Destino</th>
                            <th scope="col">Salida</th>
                            <th scope="col" class="accionesColDTBL">Carnet</th>
                            <th scope="col" class="accionesColDTBL">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="traslados-total" role="tabpanel" aria-labelledby="traslados-total-tab">
                <table id="DTBL_Agenda_Total" class="table DataTable CenterDTBL">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Código</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Origen</th>
                            <th scope="col">Destino</th>
                            <th scope="col">Salida</th>
                            <th scope="col" class="accionesColDTBL">Carnet</th>
                            <th scope="col" class="accionesColDTBL">Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="MDL_Mensaje" tabindex="-1" aria-labelledby="MDL_MensajeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="MDL_MensajeLabel">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="MDL_Mensaje_Content"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
<script src="../admin/resources/js/general.js" type="text/javascript"></script>
</body>

</html>