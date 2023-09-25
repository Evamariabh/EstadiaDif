<?php

$PageTitle = "Solicitar traslado";

// $HeaderLinks = '<script src="resources/js/solicitud.js" type="text/javascript"></script>';
//                 ;

$HeaderLinks = '<script src="resources/js/solicitud.js" type="text/javascript"></script>
                <script src="resources/js/gusi_input_validator.js" type="text/javascript"></script>';

require("includes/header.php");
require('commons/bd_connection.php');


$OptionsDestinos = '<option disabled selected>Sin lugares predefinidos que mostrar</option>';
$OptionsServicios = '<label>Sin servicios que mostrar</label>';

try {
    $STMT = $pdo->prepare("SELECT `ID_Destino`, CONCAT(`NombreLugar`, ' (', HOUR(`TiempoViaje`), 'hrs, ', MINUTE(`TiempoViaje`), 'min.)') AS 'Destino' FROM `destinos` WHERE `ID_Destino` > 0");
    $STMT->execute();
    if ($STMT->rowCount() > 0) {
        $OptionsDestinos = '';    
        $Data = $STMT->fetchAll(PDO::FETCH_ASSOC);
        foreach ($Data as $Destino) {
            $OptionsDestinos .= '<option value="'. $Destino['ID_Destino'] .'">' . $Destino['Destino'] . '</option>'; 
        }
        $OptionsDestinos .= '<option value="-1">Otro destino (Definir)</option>';
    }
} catch (\Throwable $th) {
    $OptionsDestinos .= '<option value="-1">Otro destino (Definir)</option>';
}

try {
    $STMT = $pdo->prepare("SELECT * FROM `servicios`");
    $STMT->execute();
    if ($STMT->rowCount() > 0) {
        $OptionsServicios = '';
        $Data = $STMT->fetchAll(PDO::FETCH_ASSOC);
        foreach ($Data as $Servicios) {
            $OptionsServicios .= 
            '<div class="form-checkbox">
                <input type="checkbox" onclick="agregarServicio(' . $Servicios['IDServicio'] . ')" id="' . $Servicios['IDServicio'] . '" value="' . $Servicios['NombreServicio'] . '">
                <label>' . $Servicios['NombreServicio'] . '</label>
            </div>'; 
            
        }
    }
} catch (\Throwable $th) {
    $OptionsServicios = '<label>Sin resultados</label>';
}

?>

<div class="form-auto">
    <h1>Solicitar traslado</h1>
    <p class="mb-2">Solicita un servicio de traslado a donde lo necesites, sólo rellena el siguiente formulario para agendar tu cita.</p>
    <div id="form-solicitud">
        <label for="nombre">CURP*</label>
        <input type="text" placeholder="Ingrese la CURP" id="curp" maxlength="18" onchange="BuscarCURP()" required>
        <div class="row mrg-top-10 mrg-bott-10">
            <div class="col-md-8">
                <label for="nombre">Nombre completo*</label>
                <input type="text" placeholder="Nombre completo" id="nombre" maxlength="100" required>
            </div>
            <div class="col-md-4">
                <label for="fecha_nacimiento">Fecha de nacimiento*</label>
                <input type="date" id="fecha_nacimiento" placeholder="Ingresa la fecha de nacimiento" required>
            </div>
        </div>
        <div class="row mrg-top-10 mrg-bott-10">
            <div class="col-md-4">
                <label for="telefono">Teléfono*</label>
                <input type="tel" id="telefono" placeholder="Número de teléfono" maxlength="10" required>
            </div>
            <div class="col-md-8">
                <label for="correo">Correo</label>
                <input type="email" id="correo" placeholder="alguien@ejemplo.com" maxlength="60" required>
            </div>
        </div>
        <p class="font-bold size-18 mt-2">Domicilio</p>
        <div class="row mt-1">
            <div class="col-md-4">
                <label for="estado">Estado*</label>
                <select id="select_estados" onchange="SeleccionaEstado()">
                    <option value="">Seleccione una opción</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="municipio">Ciudad / municipio*</label>
                <select id="select_municipios" onchange="SeleccionaMunicipio()">
                    <option value="">Seleccione una opción</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="numero">Colonia / localidad*</label>
                <select id="select_colonias">
                    <option value="">Seleccione una opción</option>
                </select>
            </div>
        </div>
        <div class="row mt-1 mb-2">
            <div class="col-md-6">
                <label for="colonia">Calle*</label>
                <input type="text" id="calle" placeholder="Ingrese el nombre de la calle" maxlength="60">
            </div>
            <div class="col-md-6">
                <label for="numero">Número*</label>
                <input type="text" id="numero" placeholder="Ingrese el número interior" maxlength="10">
            </div>
        </div>
        <label for="destino">Destino*</label>
        <select onchange="CheckCustom(this);" id="destino">
            <?php
            echo $OptionsDestinos;
            ?>
        </select>
        <div id="destinoCustomContainer" class="hidden mt-1">
            <label for="custom">Ingresa la dirección</label>
            <input type="text" id="custom" custom-check placeholder="Ingrese el destino (Max. 350 caracteres)">
            <p class="mrg-top-10 mb-1 size-14"><i><strong>Importante:</strong> los destinos no definidos tendrán un redondeo de tiempo a 8hrs de tiempo de traslado</i></p>
        </div>
        <div class="row mrg-top-10 mrg-bott-10">
            <div class="col-md-6">
                <label for="fecha">Fecha de traslado*</label>
                <input type="date" id="fecha_traslado">
            </div>
            <div class="col-md-6">
                <label for="salida">Hora de traslado*</label>
                <input type="time" id="hora_salida">
            </div>
        </div>
        <label for="observaciones">Observaciones</label>
        <textarea class="mb-1" id="observaciones" cols="30" rows="7" placeholder="Observaciones (Opcional)" maxlength="250"></textarea>
        <label for="carnet">Escaneo de carnet de citas</label>
        <input type="file" id="carnet" onchange="fileValidation();">
        <p class="mt-2 text-al-right">¿Ya tienes un traslado? <a href="consultar.php"><i class="fas fa-calendar-day"></i> Consultar mi cita</a></p>
        <div class="form-checkbox">
            <input type="checkbox" id="mostrar_Servicios" onclick="mostrarServicios()">
            <label>Requiero otro servicio</label>
        </div>
        <div id="servicios_extra" style="display: none">
            <hr>
            <?php
                echo $OptionsServicios;
            ?>
        </div>
        <div id="tabla_datos" style="display: none">
            <div class="row mrg-top-10 mrg-bott-10">
                <div class="col-md-6">
                    <label>Servicio</label>
                </div>
                <div class="col-md-6">
                    <label>Fecha</label>
                </div>
            </div>
            <div class="row mrg-top-10 mrg-bott-10">
                <div class="col-md-6" id="col_servicio">
                </div>
                <div class="col-md-6" id="col_fecha">
                </div>
            </div>
        </div>
        <hr>
        <button class="mrg-top-20 w100" onclick="Solicitar(this);" type="button">Agendar cita</button>
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