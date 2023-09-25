<?php 

$PageTitle = "Lugares - Admin";
$HeaderLinks = '
    <script src="resources/js/servicios.js"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>';
require('includes/header.php');


require('../commons/bd_connection.php');

?>
<div class="container mt-2">
    <h1>Servicios</h1>
    <hr>
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#MDL_Agregar"><i class="fas fa-plus"></i> Agregar servicio</button>
    <a class="btn btn-success color-white mb-2" href="reporte.php"><i class="fas fa-file"></i> Reporte de servicios</a>
    <table id="DTBL_Servicios" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Servicio</th>
                <th scope="col">Encargado</th>
                <th scope="col" class="accionesColDTBL">Opciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div class="modal fade" id="MDL_Agregar" tabindex="-1" aria-labelledby="MDL_AgregarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_AgregarLabel">Registrar Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="A_Nombre">Nombre del servicio</label>
                <input type="text" class="form-control" id="A_Nombre" placeholder="Max. 60 caracteres" maxlength="60">
                <label for="A_Encargado">Encargado/a</label>
                <input type="text" class="form-control" id="A_Encargado" placeholder="Nombre del encargado/a o entidad del servicio">
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
                <h5 class="modal-title" id="MDL_EditarLabel">Editar servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ID_Element">ID del elemento</label>
                <input type="text" id="ID_Element" class="form-control" disabled>
                <label label for="E_Nombre">Nombre del servicio</label>
                <input type="text" class="form-control" id="E_Nombre" placeholder="Max. 60 caracteres" maxlength="60">
                <label for="E_Encargado">Encargado/a</label>
                <input type="text" class="form-control" id="E_Encargado" placeholder="Max. 100 caracteres" maxlength="100">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button id="btnEditar" type="button" class="btn btn-primary" actionable>Actualizar</button>
            </div>
        </div>
    </div>
</div>
<?php

require('includes/footer.php');