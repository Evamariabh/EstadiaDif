<?php

$PageTitle = "Consultar cita";

$HeaderLinks = '<link rel="stylesheet" href="resources/css/print.css"><script src="resources/js/consulta-servicio.js" type="text/javascript"></script>';
require("includes/header.php");
?>

<div class="container">
    <div class="form-auto">
        <h1><i aria-hidden="true" class="fas fa-calendar-check no-print"></i> Consultar mi solicitud de servicio</h1>
        <p class="mrg-bott-10 no-print">Para consultar citas de traslado haga <a href="consultar.php">clic aquí</a>.</p>
        <div class="row no-print">
            <div class="col-md-8">
                <input type="text" id="solicitud_codigo" placeholder="Ingresa el código de solicitud">
            </div>
            <div class="col-md-2">
                <button onclick="Consultar(this);" class="btn btn-primary default-theme h100 w100" id="btnConsulta">Consultar &nbsp;<i aria-hidden="true" class="fas fa-arrow-right"></i></button>
            </div>
        </div>
        <hr class="no-print">
        <div class="hidden" id="CardCita">
            <div class="cita-card">
                <div class="cita-card-head">
                    <div class="col-md-6 div_logo">
                        <img src="resources/images/Logo_san_felipe.jpg" class="logo_imagen" alt="Logo">
                    </div>
                    <div class="col-md-6 div_logo">
                        <img src="resources/images/Logo_dif_orizatlan.jpeg" class="logo_imagen right-0" alt="Logo">
                    </div>
                </div>
                <div class="cita-card-body">
                    <h2>Solicitud de servicio médico</h2>
                    <p class="font-bold">Paciente:</p>
                    <p id="paciente" clear-on-request>Lorem, ipsum dolor.</p>
                    <br>
                    <span class="font-bold">Teléfono: </span><a href="#" id="telefono" clear-on-request></a>
                    <h3>Detalles de la solicitud</h3>
                    <div class="row-info-cita">
                        <div class="col-icon">
                            <div class="icon-container">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Servicio: </span>
                                <span id="servicio"clear-on-request></span>
                            </div>
                        </div>
                        <div class="col-icon">
                            <div class="icon-container">
                                <i aria-hidden="true" class="fas fa-calendar-day"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Fecha: </span>
                                <p id="fecha"clear-on-request>dd/mm/yyyy</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="col-icon">
                            <div class="icon-container">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Responsable: </span>
                                <span id="responsable"clear-on-request></span>
                            </div>
                        </div>
                    </div>
                    <div class="border-1-primary pd10 mt-1">
                        <span class="font-bold">Observaciones: </span>
                        <span id="observaciones" clear-on-request><i>Sin observaciones</i></span>
                    </div>
                    <p class="mt-2 color-primary font-bold">Código de solicitud:</p>
                    <p id="codigo-solicitud" class="card-cita-codigo" clear-on-request>XXX</p>
                </div>
            </div>
            <button class="mt-2 btn btn-white default-theme no-print" id="Print"><i aria-hidden="true" class="fas fa-print"></i> Imprimir</button>
        </div>
        <div class="card-not-found no-print" style="display: none;" id="CardError">
            <h2 class="color-black"><i class="fas fa-exclamation-triangle"></i> Ooops!</h2>
            <h4 class="color-black mb-2" id="ErrorDesc"></h4>
        </div>
    </div>
</div>

<?php

require("includes/footer.php");