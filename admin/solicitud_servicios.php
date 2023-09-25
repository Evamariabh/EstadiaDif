<?php 

$PageTitle = "Solicitudes de servicios - Admin";
$HeaderLinks = '
    <script src="resources/js/solicitud_servicios.js"></script>
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
    <h1>Solicitudes de servicios</h1>
    <hr>
    <table id="DTBL_Solicitudes_Serv" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Código</th>
                <th scope="col">Servicio</th>
                <th scope="col">Solicitante</th>
                <th scope="col">Fecha</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Fecha Creación</th>
                <th scope="col">Estatus</th>
                <th scope="col">Observaciones</th>
                <th scope="col" class="accionesColDTBL">Opciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="modal fade" id="MDL_Editar_Sol" tabindex="-1" aria-labelledby="MDL_EditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_EditarLabel">Editar Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-4">
                        <label>Identificador: <b id="E_ID"></b></label>
                    </div>
                    <div class="col-md-8">
                        <label>Código: <b id="E_Codigo"></b></label>
                    </div>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-12">
                        <label>Nombre</label>
                        <input class="form-control" type="text" placeholder="Nombre del paciente" id="E_Nombre" maxlength="100" required>
                    </div>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-6">
                        <label>Fecha</label>
                        <input class="form-control" type="date" id="E_Fecha" placeholder="Ingresa la fecha de nacimiento" required>
                    </div>
                    <div class="col-md-6">
                        <label>Teléfono</label>
                        <input class="form-control" type="tel" id="E_Telefono" placeholder="Ingresa el teléfono a 10 dígitos, introduzca sólo números" maxlength="15" required>
                    </div>
                </div>
                <label>Observaciones</label>
                <textarea class="form-control" class="mb-1" id="E_Observaciones" cols="30" rows="4" placeholder="Observaciones (Opcional)" maxlength="250"></textarea>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnEditar" type="button" class="btn btn-primary" actionable>Actualizar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="MDL_Autorizar" tabindex="-1" aria-labelledby="MDL_AutorizarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_AtorizarLabel">Autorizar solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-4">
                        <label>Identificador: <b id="A_ID"></b></label>
                    </div>
                    <div class="col-md-8">
                        <label>Código: <b id="A_Codigo"></b></label>
                    </div>
                </div>
                <div class="row mrg-top-10 mrg-bott-10">
                    <div class="col-md-4">
                        <label>Fecha: <b id="A_Fecha"></b></label>
                    </div>
                    <div class="col-md-8">
                        <label>Solicitante: <b id="A_Solicitante"></b></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnAutorizar" type="button" class="btn btn-primary" actionable>Autorizar</button>
            </div>
        </div>
    </div>
</div>
<?php

require('includes/footer.php');