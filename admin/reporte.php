<?php 

$PageTitle = "Reporte de servicios - Admin";
$HeaderLinks = '
    <script src="resources/js/reporte.js"></script>
    <script type="text/javascript" src="../resources/datatables/datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../resources/datatables/datatables.min.css"/>
    <script src="../resources/js/gusi_input_validator.js" type="text/javascript"></script>';
require('includes/header.php');


require('../commons/bd_connection.php');

?>
<div class="container mt-2">
    <h1>Reporte de servicios</h1>
    <hr>
    <div class="col-md-4">
        <label for="fecha">Seleccione el mes</label>
        <input class="form-control" type="month" id="anio_mes" value="<?php echo date('Y-m'); ?>">
    </div>
    <hr>
    <h4 class="text-center">Reporte de servicios del mes de <label id="nombre_mes"></label> de <label id="anio"></label></h4>
    <table id="DTBL_Reporte" class="table DataTable CenterDTBL">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Encargado</th>
                <th scope="col">Servicio y/o atención</th>
                <th scope="col">Semana 1</th>
                <th scope="col">Semana 2</th>
                <th scope="col">Semana 3</th>
                <th scope="col">Semana 4</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<?php

require('includes/footer.php');