<?php 

$PageTitle = "Personal - Admin";
$HeaderLinks = '
    <script src="resources/js/personal.js"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>
    <script src="../resources/js/gusi_input_validator.js" type="text/javascript"></script>';
require('includes/header.php');


require('../commons/bd_connection.php');

?>
<div class="container mt-2">
    <h1>Personal administrativo</h1>
    <hr>
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#MDL_Agregar"><i class="fas fa-plus"></i> Agregar administrativo</button>
    <table id="DTBL_Admins" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">NumControl</th>
                <th scope="col">Telefono</th>
                <th scope="col">Puesto</th>
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
                <h5 class="modal-title" id="MDL_AgregarLabel">Registrar administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="nombre">Nombre completo</label>
                <input class="form-control" autocomplete="nope" type="text" id="A_nombre" placeholder="Max. 100 caracteres" maxlength="100">
                <label for="nocontrol">Número de control</label>
                <input class="form-control" autocomplete="nope" type="text" id="A_nocontrol" placeholder="Max. 15 caracteres" maxlength="15">
                <label for="clave">Contraseña</label>
                <input class="form-control" autocomplete="nope" type="password" id="A_clave" placeholder="Max. 20 caracteres, sin símbolos < >" maxlength="20">
                <label for="telefono">Teléfono</label>
                <input class="form-control" autocomplete="nope" type="tel" id="A_telefono" placeholder="Teléfono a 10 dígitos, sin espacios o símbolos" maxlength="10">
                <label for="puesto">Puesto</label>
                <input class="form-control" autocomplete="nope" type="text" id="A_puesto" placeholder="Definir puesto (Max. 50 caracteres)" maxlength="50">
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
                <h5 class="modal-title" id="MDL_EditarLabel">Editar administrador</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ID_Element">ID del elemento</label>
                <input type="text" id="ID_Element" class="form-control" disabled>
                <label for="nombre">Nombre completo</label>
                <input class="form-control" autocomplete="nope" type="text" id="E_nombre" placeholder="Max. 100 caracteres" maxlength="100">
                <label for="nocontrol">Número de control</label>
                <input class="form-control" autocomplete="nope" type="text" id="E_nocontrol" placeholder="Max. 15 caracteres" maxlength="15">
                <label for="clave">Contraseña</label>
                <input class="form-control" autocomplete="nope" type="password" id="E_clave" placeholder="Max. 20 caracteres, sin símbolos < >" maxlength="20">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="E_CambioClave">
                    <label class="form-check-label" for="E_CambioClave">
                        Actualizar contraseña
                    </label>
                </div>
                <label for="telefono">Teléfono</label>
                <input class="form-control" autocomplete="nope" type="tel" id="E_telefono" placeholder="Teléfono a 10 dígitos, sin espacios o símbolos" maxlength="10">
                <label for="puesto">Puesto</label>
                <input class="form-control" autocomplete="nope" type="text" id="E_puesto" placeholder="Definir puesto (Max. 50 caracteres)" maxlength="50">
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