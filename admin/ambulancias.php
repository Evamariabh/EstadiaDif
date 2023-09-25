<?php 

$PageTitle = "Ambulancias - Admin";
$HeaderLinks = '
    <script src="resources/js/ambulancias.js"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>';
require('includes/header.php');


require('../commons/bd_connection.php');

$OptionsConductores = '<option disabled selected>No hay conductores disponibles</option>';

try {
    $STMTLista = $pdo->prepare("SELECT `IDConductor` AS 'ID', CONCAT( `Nombre` ) AS 'Nombre' FROM `conductores`");
    $STMTLista->execute();
    if ($STMTLista->rowCount() > 0) {
        $OptionsConductores = '<option disabled selected>Seleccione un conductor</option>';
        foreach ($STMTLista->fetchAll(PDO::FETCH_ASSOC) as $Conductor) {
            $OptionsConductores .= '<option value="'. $Conductor['ID'] .'">' . $Conductor['Nombre'] . '</option>';
        }
    }
} catch (\Throwable $th) {
    $OptionsConductores = '<option disabled selected>No se pudo obtener la lista de conductores</option>';
}

?>
<div class="container mt-2">
    <h1>Vehículos de traslado</h1>
    <hr>
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#MDL_Agregar"><i class="fas fa-plus"></i> Agregar vehículo de traslado</button>
    <table id="DTBL_Ambulancias" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Número</th>
                <th scope="col">Placas</th>
                <th scope="col">Descripción</th>
                <th scope="col">Conductor</th>
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
                <h5 class="modal-title" id="MDL_AgregarLabel">Registrar Ambulancia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="A_Numero">Número de vehículo</label>
                <input type="text" class="form-control" id="A_Numero" placeholder="Max. 15 caracteres" maxlength="15">
                <label for="A_Placas">Placas</label>
                <input type="text" class="form-control" id="A_Placas" placeholder="Max. 15 caracteres" maxlength="15">
                <label for="A_Descripcion">Descripción</label>
                <textarea rows="5" cols="10" class="form-control" id="A_Descripcion" placeholder="Max. 150 caracteres" maxlength="150"></textarea>
                <label for="A_Conductor">Conductor</label>
                <select name="" class="form-select" id="A_Conductor">
                    <?php 
                    
                    echo $OptionsConductores;

                    ?>
                </select>
                <p class="size-14 mt-2">Importante: sólo puede haber un conductor por ambulancia.</p>
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
                <h5 class="modal-title" id="MDL_EditarLabel">Editar vehículo de traslado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="ID_Ambulancia">ID del elemento</label>
                <input type="text" id="ID_Ambulancia" class="form-control" disabled>
                <label for="E_Numero">Número de vehículo</label>
                <input type="text" class="form-control" id="E_Numero" placeholder="Max. 15 caracteres" maxlength="15">
                <label for="E_Placas">Placas</label>
                <input type="text" class="form-control" id="E_Placas" placeholder="Max. 15 caracteres" maxlength="15">
                <label for="E_Descripcion">Descripción</label>
                <textarea rows="5" cols="10" class="form-control" id="E_Descripcion" placeholder="Max. 150 caracteres" maxlength="150"></textarea>
                <label for="E_Conductor">Conductor</label>
                <select name="" class="form-select" id="E_Conductor">
                    <?php 
                    
                    echo $OptionsConductores;

                    ?>
                </select>
                <p class="size-14 mt-2">Importante: sólo puede haber un conductor por ambulancia.</p>
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