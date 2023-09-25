<?php

$PageTitle = "Acceder";

$HeaderLinks = 
'<script src="resources/js/login.js" type="text/javascript"></script>
<script src="resources/js/gusi_input_validator.js" type="text/javascript"></script>';
require("includes/header.php");
?>

<div class="container">
    <div class="form-auto">
        <h1><i class="fas fa-user-circle"></i> Acceder al sistema</h1>
        <a class="btn btn-dark theme-default mb-1" href="acceso-conductor.php"><i class="fas fa-external-link-alt"></i> Acceder como conductor</a>
        <div id="formLogin" class="mrg-top-10">
            <label for="NumControl">Número de control de empleado</label>
            <input type="text" id="NumControl" placeholder="Número de control" required>
            <label for="Clave">Contraseña</label>
            <input type="password" id="Clave" placeholder="Ingresa la contraseña" required>
            <hr>
            <!-- <a href="registro.php">Registrarme como administrator</a> -->
            <button class="btn btn-primary default-theme w100 mt-2" onclick="Login(this);">Acceder</button>
        </div>
        
    </div>
</div>

<?php

require("includes/footer.php");