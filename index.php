<?php
require('includes/header.php');
require('commons/bd_connection.php');


$LinkServicio = '<p class="color-gray">Sin servicios disponibles por el momento</p>';

try {
    $STMT = $pdo->prepare("SELECT `IDServicio`, `NombreServicio` FROM `servicios`");
    $STMT->execute();
    if ($STMT->rowCount() > 0) {
        $LinkServicio = '';    
        $Data = $STMT->fetchAll(PDO::FETCH_ASSOC);
        foreach ($Data as $Servicio) {
            $LinkServicio .= '<a href="solicitar-servicio.php?preSelect='. $Servicio['IDServicio'] .'" class="btn btn-highlight">' . $Servicio['NombreServicio'] . '</a>';
        }
    }
} catch (\Throwable $th) {
    $LinkServicio = '<p class="color-gray">No se ha podido consultar la lista de servicios en este momento</p>';
}


?>

<div class="container">
    <div class="landing-cover rounded">
        <h2>Tu salud en las mejores manos</h2>
    </div>
    <img src="resources/images/imagen4.jpeg" alt="Hospital" class="w100 shadow-5">
    <div class="feature-info swaped">
        <img src="resources/images/imagen1.jpeg" alt="Info">
        <div class="info-text">
            <h2>Lo mejor para tu salud.</h2>
            <p>Tu salud merece la atención de médicos capaces e instalaciones adecuadas para un diagnóstico correcto y tratamiento eficaz.
            </p>
        </div>
    </div>
    <div class="quick-action rounded">
        <h2>¿Necesitas un traslado?</h2>
        <a href="solicitar-traslado.php" class="btn default-theme">Solicitar ya <i class="fas fa-ambulance"></i></a>
    </div>
    <hr>
    <h2>Nuestro servicios</h2>
    <p class="mb-1">Ponemos a su disposición los siguientes servicios:</p>
    <div class="row-servicios">
        <?php 
        
        echo $LinkServicio;
        
        ?>
    </div>
    <hr>
</div>

<?php

require("includes/footer.php");