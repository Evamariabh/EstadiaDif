<div class="modal fade" id="MDL_Mensaje" tabindex="-1" aria-labelledby="MDL_MensajeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="MDL_MensajeLabel">Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="MDL_Mensaje_Content"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script src="resources/js/general.js" type="text/javascript"></script>
<?php

if (isset($FooterLinks)) {
    echo $FooterLinks;
}

?>
</body>

</html>