<?php

$PageTitle = "Consultar cita";

$HeaderLinks = '<link rel="stylesheet" href="resources/css/print.css"><script src="resources/js/recuperar_codigo.js" type="text/javascript"></script>';
require("includes/header.php");
?>

<div class="container">
    <div class="form-auto">
        <h1 class="no-print"><i aria-hidden="true" class="fas fa-calendar-check no-print"></i> Consulta tus citas</h1>
        <div class="no-print">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" id="curp" placeholder="Ingresa tu CURP">
                </div>
                <div class="col-md-2">
                    <button onclick="Consultar(this);" class="btn btn-primary default-theme h100 w100" id="btnConsulta">Consultar &nbsp;<i aria-hidden="true" class="fas fa-arrow-right"></i></button>
                </div>
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
                    <div id="div_traslados" class="hidden">
                        <h2>Últimos traslados registrados</h2>
                        <div id="traslados" clear-on-request>Sin registros
                        </div>
                    </div>
                    <div id="div_servicios" class="hidden">
                        <h2>Últimos servicios solicitados</h2>
                        <div id="servicios" clear-on-request>Sin registros
                        </div>
                    </div>
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