<?php

$PageTitle = "Registrarse";

$HeaderLinks = 
    '<script src="resources/js/registro.js" type="text/javascript"></script>
    <script src="resources/js/gusi_input_validator.js" type="text/javascript"></script>';
require("includes/header.php");
?>

<div class="container">
    <div class="form-auto">
        <h1><i class="fas fa-user-plus"></i> Registrar administrator</h1>
        <div id="formRegistro" class="mrg-top-10">
            <label for="nombre">Nombre completo</label>
            <input autocomplete="nope" type="text" id="nombre" placeholder="Max. 100 caracteres" maxlength="100">
            <label for="nocontrol">Número de control</label>
            <input autocomplete="nope" type="text" id="nocontrol" placeholder="Max. 15 caracteres" maxlength="15">
            <label for="clave">Contraseña</label>
            <input autocomplete="nope" type="password" id="clave" placeholder="Max. 20 caracteres, sin símbolos < >" maxlength="20">
            <label for="telefono">Teléfono</label>
            <input autocomplete="nope" type="tel" id="telefono" placeholder="Teléfono a 10 dígitos, sin espacios o símbolos" maxlength="10">
            <label for="puesto">Puesto</label>
            <input autocomplete="nope" type="text" id="puesto" placeholder="Definir puesto (Max. 50 caracteres)" maxlength="50">
            <hr>
            <p class="mb-2">¿Ya tienes cuenta? <a href="acceso.php">Acceder</a>.</p>
            <button class="btn btn-primary default-theme w100" onclick="Registrar(this);">Registrarme</button>
        </div>     
    </div>
</div>

<?php

require("includes/footer.php");