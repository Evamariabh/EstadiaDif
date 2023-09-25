<?php

$PageTitle = "Consultar cita";

$HeaderLinks = '<link rel="stylesheet" href="resources/css/print.css"><script src="resources/js/consulta.js" type="text/javascript"></script>';
require("includes/header.php");
?>

<div class="container">
    <div class="form-auto">
        <h1 class="no-print"><i aria-hidden="true" class="fas fa-calendar-check no-print"></i> Consultar mi cita</h1>
        <div class="no-print">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" id="cita_codigo" placeholder="Ingresa el código de cita">
                </div>
                <div class="col-md-5">
                    <input type="text" id="curp" placeholder="Ingresa tu CURP">
                </div>
                <div class="col-md-2">
                    <button onclick="Consultar(this);" class="btn btn-primary default-theme h100 w100" id="btnConsulta">Consultar &nbsp;<i aria-hidden="true" class="fas fa-arrow-right"></i></button>
                </div>
            </div>
            <p class="mt-2 text-al-right">Olvidaste tu código? <a href="recuperar_codigo.php"><i class="fas fa-calendar-day"></i> Consultar mi código</a></p>
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
                    <h2>Traslado de paciente (<b id="estatus"></b>)</h2>
                    <p class="font-bold">Paciente:</p>
                    <p id="paciente" clear-on-request></p>
                    <div class="flex-gap-10 flex-wrap">
                        <p><span class="font-bold">Teléfono: </span><a href="#" id="telefono" clear-on-request></a></p>
                        <p><span class="font-bold">Correo: </span><a href="#" id="correo" clear-on-request></a></p>
                    </div>
                    <h3>Detalles del traslado</h3>
                    <div class="row-info-cita">
                        <div class="col-icon">
                            <div class="icon-container">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Origen: </span>
                                <span id="origen"clear-on-request></span>
                            </div>
                        </div>
                        <div class="col-icon">
                            <div class="icon-container">
                                <i aria-hidden="true" class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Destino: </span>
                                <span id="destino"clear-on-request></span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-1">
                        <div class="col-icon">
                            <div class="icon-container">
                                <i aria-hidden="true" class="fas fa-ambulance"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Conductor: </span>
                                <span id="conductor"clear-on-request></span>
                                <p><span class="font-bold">N° de ambulancia: </span><span id="numero-ambulancia"clear-on-request></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row-info-cita mt-1">
                        <div class="col-icon">
                            <div class="icon-container">
                                <i aria-hidden="true" class="fas fa-calendar-day"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Fecha de traslado: </span>
                                <p id="fecha"clear-on-request>dd/mm/yyyy</p>
                            </div>
                        </div>
                        <div class="col-icon">
                            <div class="icon-container">
                                <i aria-hidden="true" class="fas fa-clock"></i>
                            </div>
                            <div class="col-content">
                                <span class="font-bold">Hora de salida: </span>
                                <p id="salida"clear-on-request>HH:mm.</p>
                            </div>
                        </div>
                    </div>
                    <div class="border-1-primary pd10 mt-1">
                        <span class="font-bold">Observaciones: </span>
                        <span id="observaciones" clear-on-request><i>Sin observaciones</i></span>
                    </div>

                    <div id="div_incluye_serv" style="display: none">
                        <h3>Se incluyen servicios</h3>
                        <div id="incluye_serv">
                            
                        </div>
                        
                        <!-- <div class="row-info-cita mt-1">
                            <div class="col-icon">
                                <div class="icon-container">
                                    <i aria-hidden="true" class="fas fa-check-circle"></i>
                                </div>
                                <div class="col-content" id="col_servicio">
                                    <span class="font-bold">Servicio: </span>
                                    <span id="servicio"clear-on-request></span>
                                </div>
                            </div>
                            <div class="col-icon">
                                <div class="icon-container">
                                    <i aria-hidden="true" class="fas fa-clock"></i>
                                </div>
                                <div class="col-content"  id="col_fecha">
                                    <span class="font-bold">Fecha: </span>
                                    <p id="fecha"clear-on-request>dd/mm/yyyy</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                    <p class="mt-2 color-primary font-bold">Código de traslado:</p>
                    <p id="codigo-traslado" class="card-cita-codigo" clear-on-request>XXX</p>
                    <span class="size-12">Importante: por disposición oficial es necesario que los acompañantes usen cubrebocas para poder abordar la unidad, asímismo el paciente deberá usar cubrebocas a menos que presente problemas u obstaculización para su uso. El paciente debe estar listo para el traslado aproximadamente 10 mins antes de la hora de llegada de la ambulancia.</span>
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