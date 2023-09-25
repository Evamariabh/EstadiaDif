<?php

$PageTitle = "Solicitar Servicio - DIF San Felipe";

$HeaderLinks = '<script src="resources/js/solicitud-servicio.js" type="text/javascript"></script>
                <script src="resources/js/gusi_input_validator.js" type="text/javascript"></script>';

require("includes/header.php");
require('commons/bd_connection.php');


$OptionsServicios = '<option disabled selected>Sin servicios disponibles por el momento</option>';

try {
    $STMT = $pdo->prepare("SELECT `IDServicio`, `NombreServicio` FROM `servicios`");
    $STMT->execute();
    if ($STMT->rowCount() > 0) {
        $OptionsServicios = '';    
        $Data = $STMT->fetchAll(PDO::FETCH_ASSOC);
        foreach ($Data as $Servicio) {
            $OptionsServicios .= '<option value="'. $Servicio['IDServicio'] .'">' . $Servicio['NombreServicio'] . '</option>'; 
        }
    }
} catch (\Throwable $th) {
    $OptionsServicios = '<option value="-1">Ocurrió un error al obtener los servicios disponibles</option>';
}

?>

<div class="form-auto">
    <h1>Solicitar Servicio</h1>
    <p class="mb-2">Complete el siguiente formulario de solicitud.</p>
    <div id="form-solicitud">
        <label for="servicio">Servicio que solicita</label>
        <select id="servicio">
            <?php 
            
            echo $OptionsServicios;

            ?>
        </select>
        <p class="size-14 mrg-bott-10 mrg-top-10"><strong>Importante: </strong>las solicitudes para servicios de traslado tienen su propio formulario, para solicitar un traslado haga <a href="solicitar-traslado.php">clic aquí</a>.</p>
        <label for="nombre">CURP*</label>
        <input type="text" placeholder="Ingrese la CURP" id="curp" maxlength="18" onchange="BuscarCURP()" required>
        <div class="row mrg-top-10 mrg-bott-10">
            <div class="col-md-8">
                <label for="nombre">Nombre completo</label>
                <input type="text" placeholder="Nombre completo" id="nombre" maxlength="100" required>
            </div>
            <div class="col-md-4">
                <label for="telefono">Teléfono*</label>
                <input type="tel" id="telefono" placeholder="Número de teléfono" maxlength="10" required>
            </div>
        </div>
        <label for="fecha">Fecha</label>
        <input type="date" id="fecha" placeholder="Seleccione la fecha" required>
        <label for="observaciones">Observaciones</label>
        <textarea class="mb-1" id="observaciones" cols="30" rows="10" placeholder="Observaciones (Opcional)" maxlength="250"></textarea>
        <p class="mt-2 text-al-right">¿Ya tienes una solicitud de servicio? <a href="consulta-servicio.php"><i class="fas fa-check"></i> Consultar</a></p>
        <hr>
        <button class="mrg-top-20 w100" onclick="Solicitar(this);" type="button">Solicitar</button>
    </div>
    <div id="msgRegistro" class="hidden">
        <div class="display-flex flex-column rounded bgcolor-secondary flex-center-vh">
            <h2 class="color-white" id="solicitudMSG">Cita agendada, código: xxxxxxxx</h2>
            <p class="mb-2 color-white">Haz clic <a href="consultar.php" id="cita-redirect" style="color:white !important;">aquí</a> para consultar tu cita con el código proporcionado.</p>
        </div>
    </div>
</div>

<?php

require("includes/footer.php");