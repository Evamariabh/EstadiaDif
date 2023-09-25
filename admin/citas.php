<?php 

$PageTitle = "Agenda de traslados - Admin";
$HeaderLinks = '
    <script src="resources/js/agenda.js"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>
    <script src="../resources/js/gusi_input_validator.js" type="text/javascript"></script>';
require('includes/header.php');
require('../commons/bd_connection.php');

$OptionsDestinos = '<option disabled selected>Sin lugares predefinidos que mostrar</option>';

try {
    $STMT = $pdo->prepare("SELECT `ID_Destino`, CONCAT(`NombreLugar`, ' (', HOUR(`TiempoViaje`), 'hrs, ', MINUTE(`TiempoViaje`), 'min.)') AS 'Destino' FROM `destinos`");
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

?>
<div class="container-fluid mt-2">
    <h1>Agenda de traslados</h1>
    <hr>
    <table id="DTBL_Agenda" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Código</th>
                <th scope="col">Paciente</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Origen</th>
                <th scope="col">Destino</th>
                <th scope="col">Estatus</th>
                <th scope="col">Salida</th>
                <th scope="col">#Ambulancia</th>
                <th scope="col" class="accionesColDTBL">Carnet</th>
                <th scope="col" class="accionesColDTBL">Opciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="modal fade" id="MDL_Agregar" tabindex="-1" aria-labelledby="MDL_AgregarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_AgregarLabel">Registrar traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="A_nombre">Nombre completo</label>
                <input class="form-control" type="text" placeholder="Nombre completo" id="A_nombre" maxlength="100" required>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label for="A_telefono">Teléfono</label>
                        <input class="form-control" type="tel" id="A_telefono" placeholder="Ingresa el teléfono a 10 dígitos, introduzca sólo números" maxlength="15" required>
                    </div>
                    <div class="col-md-6">
                        <label for="A_correo">Correo</label>
                        <input class="form-control" type="email" id="A_correo" placeholder="alguien@ejemplo.com" maxlength="60" required>
                    </div>
                </div>
                <label for="A_fecha_nacimiento">Fecha de nacimiento del paciente</label>
                <input class="form-control" type="date" id="A_fecha_nacimiento" placeholder="Ingresa la fecha de nacimiento" required>
                <p class="font-bold size-18 mt-2">Domicilio</p>
                <div class="row mt-1">
                    <div class="col-md-8">
                        <label for="A_calle">Calle</label>
                        <input class="form-control" type="text" id="A_calle" maxlength="60" placeholder="Max. 60 caracteres">
                    </div>
                    <div class="col-md-4">
                        <label for="A_numero">Número</label>
                        <input class="form-control" type="text" id="A_numero" maxlength="15" placeholder="Max. 15 caracteres">
                    </div>
                </div>
                <div class="row mt-1 mb-2">
                    <div class="col-md-6">
                        <label for="A_colonia">Colonia</label>
                        <input class="form-control" type="text" id="A_colonia" maxlength="60" placeholder="Max. 60 caracteres">
                    </div>
                    <div class="col-md-6">
                        <label for="A_municipio">Municipio</label>
                        <input class="form-control" type="text" id="A_municipio" maxlength="60" placeholder="Max. 60 caracteres">
                    </div>
                </div>
                <label for="A_destino">Destino</label>
                <select onchange="A_CheckCustom(this);" id="A_destino" class="form-select">
                    <?php
                    
                    echo $OptionsDestinos;

                    ?>
                </select>
                <div id="A_destinoCustomContainer" class="hidden mt-1">
                    <label for="A_custom">Ingresa la dirección</label>
                    <input class="form-control" type="text" id="A_custom" custom-check placeholder="Ingrese el destino (Max. 350 caracteres)">
                    <p class="mrg-top-10 mb-1 size-14"><i><strong>Importante:</strong> los destinos no definidos tendrán un redondeo de tiempo a 8hrs de tiempo de traslado</i></p>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label for="A_fecha">Fecha de traslado</label>
                        <input class="form-control" type="date" id="A_fecha">
                    </div>
                    <div class="col-md-6">
                        <label for="A_salida">Hora de traslado</label>
                        <input class="form-control" type="time" id="A_salida">
                    </div>
                </div>
                <label for="A_observaciones">Observaciones</label>
                <textarea class="form-control" class="mb-1" id="A_observaciones" cols="30" rows="10" placeholder="Observaciones (Opcional)" maxlength="250"></textarea>
                <label for="A_carnet">Escaneo de carnet de citas</label>
                <input class="form-control" type="file" id="A_carnet">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnRegistrar" type="button" class="btn btn-primary" actionable>Registrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="MDL_Editar" tabindex="-1" aria-labelledby="MDL_EditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_EditarLabel">Editar Traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ID_Element">ID del elemento</label>
                <input type="text" id="ID_Element" class="form-control" disabled>
                <label for="E_nombre">Nombre completo</label>
                <input class="form-control" type="text" placeholder="Nombre completo" id="E_nombre" maxlength="100" required>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label for="E_telefono">Teléfono</label>
                        <input class="form-control" type="tel" id="E_telefono" placeholder="Ingresa el teléfono a 10 dígitos, introduzca sólo números" maxlength="15" required>
                    </div>
                    <div class="col-md-6">
                        <label for="E_correo">Correo</label>
                        <input class="form-control" type="email" id="E_correo" placeholder="alguien@ejemplo.com" maxlength="60" required>
                    </div>
                </div>
                <label for="E_fecha_nacimiento">Fecha de nacimiento del paciente</label>
                <input class="form-control" type="date" id="E_fecha_nacimiento" placeholder="Ingresa la fecha de nacimiento" required>
                <p class="font-bold size-18 mt-2">Domicilio</p>
                <div class="row mt-1">
                    <div class="col-md-8">
                        <label for="E_calle">Calle</label>
                        <input class="form-control" type="text" id="E_calle" maxlength="60" placeholder="Max. 60 caracteres">
                    </div>
                    <div class="col-md-4">
                        <label for="E_numero">Número</label>
                        <input class="form-control" type="text" id="E_numero" maxlength="15" placeholder="Max. 15 caracteres">
                    </div>
                </div>
                <div class="row mt-1 mb-2">
                    <div class="col-md-6">
                        <label for="E_colonia">Colonia</label>
                        <input class="form-control" type="text" id="E_colonia" maxlength="60" placeholder="Max. 60 caracteres">
                    </div>
                    <div class="col-md-6">
                        <label for="E_municipio">Municipio</label>
                        <input class="form-control" type="text" id="E_municipio" maxlength="60" placeholder="Max. 60 caracteres">
                    </div>
                </div>
                <label for="E_destino">Destino</label>
                <select onchange="E_CheckCustom(this);" id="E_destino" class="form-select">
                    <?php
                    
                    echo $OptionsDestinos;

                    ?>
                </select>
                <div id="E_destinoCustomContainer" class="hidden mt-1">
                    <label for="E_custom">Ingresa la dirección</label>
                    <input class="form-control" type="text" id="E_custom" custom-check placeholder="Ingrese el destino (Max. 350 caracteres)">
                    <p class="mrg-top-10 mb-1 size-14"><i><strong>Importante:</strong> los destinos no definidos tendrán un redondeo de tiempo a 8hrs de tiempo de traslado</i></p>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label for="E_fecha">Fecha de traslado</label>
                        <input class="form-control" type="date" id="E_fecha">
                    </div>
                    <div class="col-md-6">
                        <label for="E_salida">Hora de traslado</label>
                        <input class="form-control" type="time" id="E_salida">
                    </div>
                </div>
                <label for="E_observaciones">Observaciones</label>
                <textarea class="form-control" class="mb-1" id="E_observaciones" cols="30" rows="4" placeholder="Observaciones (Opcional)" maxlength="250"></textarea>
                <div class="form-check form-switch mt-1">
                    <input class="form-check-input" type="checkbox" role="switch" id="E_Cancelado">
                    <label class="form-check-label" for="E_Cancelado">Cancelar cita</label>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnEditar" type="button" class="btn btn-primary" actionable>Actualizar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="MDL_Detalle" tabindex="-1" aria-labelledby="MDL_DetalleLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_DetalleLabel">Detalle Traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Traslado: <b id="D_Traslado"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Código: <b id="D_Codigo"></b></label>
                    </div>
                </div>
                <hr>
                <p class="font-bold size-18 mt-2">Información personal</p>
                <label>Nombre: <b id="D_Nombre"></b></label>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>CURP: <br><b id="D_CURP"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Fecha Nacimiento: <br><b id="D_FechaNacimiento"></b></label>
                    </div>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Teléfono: <br><b id="D_Telefono"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Correo: <br><b id="D_Correo"></b></label>
                    </div>
                </div>
                <hr>
                <p class="font-bold size-18 mt-2">Domicilio</p>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Estado: <br><b id="D_Estado"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Municipio: <br><b id="D_Municipio"></b></label>
                    </div>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Colonia: <br><b id="D_Colonia"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Calle y Numero: <br><b id="D_CalleNo"></b></label>
                    </div>
                </div>
                <hr>
                <p class="font-bold size-18 mt-2">Solicitud</p>
                <label>Destino: <b id="D_Destino"></b></label>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Fecha: <b id="D_Fecha"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Hora: <b id="D_Hora"></b></label>
                    </div>
                </div>
                <label>Observaciones: <b id="D_Observaciones"></b></label>
                <div class="hidden" id="div_servicios">
                    <p class="font-bold size-18 mt-2">Servicios</p>
                    <hr>
                    <div id="servicios"></div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="MDL_Asignar" tabindex="-1" aria-labelledby="MDL_AsignarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_AtorizarLabel">Asignar conductor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Solicitud: <b id="A_solicitud"></b></label>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Fecha de traslado: <b id="Asignar_fecha"></b></label>
                    </div>
                    <div class="col-md-6">
                        <label>Hora de traslado: <b id="A_hora"></b></label>
                    </div>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Ambulancias disponibles:</label>
                        <select class="form-select" onchange="seleccionarAmbulancia();" id="A_ambulancias_disponibles">
                            <option value="-1">Seleccione una opción</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="E_Chofer">Chofer a cargo:</label>
                        <b id="A_chofer">--</b>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button onclick="Limpiar()" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnAsignar" type="button" class="btn btn-primary" actionable>Asignar</button>
            </div>
        </div>
    </div>
</div>
<?php

require('includes/footer.php');