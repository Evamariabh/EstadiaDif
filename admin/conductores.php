<?php 

$PageTitle = "Conductores - Admin";
$HeaderLinks = '
    <script src="resources/js/conductores.js"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>';
require('includes/header.php');


require('../commons/bd_connection.php');

?>
<div class="container mt-2">
    <h1>Conductores</h1>
    <hr>
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#MDL_Agregar"><i class="fas fa-plus"></i> Agregar conductor</button>
    <table id="DTBL_Conductores" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Num. Control</th>
                <th scope="col">Teléfono</th>
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
                <h5 class="modal-title" id="MDL_AgregarLabel">Registrar Conductor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="A_Nombre">Nombre completo</label>
                <input type="text" class="form-control" id="A_Nombre" placeholder="Max. 100 caracteres" maxlength="100">
                <label for="A_NoControl">Número de Control de empleado</label>
                <input type="text" class="form-control" id="A_NoControl" placeholder="Los 18 caracteres" maxlength="18">
                <label for="A_Clave">Contraseña</label>
                <input type="password" class="form-control" id="A_Clave" placeholder="Los 20 caracteres" maxlength="20">
                <label for="A_Telefono">Teléfono</label>
                <input type="text" class="form-control" id="A_Telefono" placeholder="A 10 dígitos" maxlength="15">
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
                <h5 class="modal-title" id="MDL_EditarLabel">Editar Conductor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ID_Element">ID del elemento</label>
                <input type="text" id="ID_Element" class="form-control" disabled>
                <label for="E_Nombre">Nombre completo</label>
                <input type="text" class="form-control" id="E_Nombre" placeholder="Max. 100 caracteres" maxlength="100">
                <label for="E_NoControl">Número de control de empleado</label>
                <input type="text" class="form-control" id="E_NoControl" placeholder="Los 18 caracteres" maxlength="18">
                <label for="E_Clave">Contraseña</label>
                <input type="password" class="form-control" id="E_Clave" placeholder="Los 20 caracteres" maxlength="20">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="E_CambioClave">
                    <label class="form-check-label" for="E_CambioClave">
                        Actualizar contraseña
                    </label>
                </div>
                <label for="E_Telefono">Teléfono</label>
                <input type="text" class="form-control" id="E_Telefono" placeholder="A 10 dígitos" maxlength="15">
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