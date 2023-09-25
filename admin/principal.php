<?php 

require('includes/header.php');

?>
<div class="container mt-2">
    <h1>Hola <?php echo $_SESSION['admin_nombre'];  ?></h1>
    <br>
    <h2>Puesto: <?php echo $_SESSION['admin_puesto'];  ?></h2>
    <p class="mt-4">Selecciona una opción del menú superior derecho para empezar</p>
</div>

<?php

require('includes/footer.php');