        <footer class="page-footer">
            <div class="footer-columns">
                <div class="column">
                    <h3>Acerca de</h3>
                    <a class="footer-link" href="#">DIF San Felipe Orizatlan</a>
                </div>
                <div class="column">
                    <h3>Contacto</h3>
                    <a class="footer-link" href="tel:4833630346">483 363 0346</a>
                    <a class="footer-link" href="#"><i class="fab fa-facebook"></i> Facebook</a>
                    <a class="footer-link" href="#"><i class="fas fa-envelope"></i> Correo</a>
                </div>
                <div class="column">
                    <h3>Ubicación</h3>
                    <a class="footer-link" href="#">Orizatlán, México, 43020.</a>
                </div>
            </div>
        </footer>
    </div>
    <div id="MDL_Mensaje" class="modal hidden">
        <div class="modal-dialog" style="max-width: 800px;">
            <div class="modal-header">
                <h2>Mensaje</h2>
                <button data-modal-close="#MDL_Mensaje" class="btn btn-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-content">
                <p id="MDL_Mensaje_Text"></p>
            </div>
            <div class="modal-options">
                <button data-modal-close="#MDL_Mensaje" class="btn">Aceptar</button>
            </div>
        </div>
    </div>
    <?php 
    
    if (isset($FooterLinks)) {
        echo $FooterLinks;
    }

    ?>
</body>
</html>