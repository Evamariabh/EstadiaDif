function Registrar(btn) {
    let Validator = new GUSI_INPUT_VALIDATOR();
    if (Validator.Check('#formRegistro > input', true)) {
        let POSTData = new FormData();
        POSTData.append('target', 'registro-admin');
        POSTData.append('nombre', document.getElementById('nombre').value);
        POSTData.append('nocontrol', document.getElementById('nocontrol').value);
        POSTData.append('clave', document.getElementById('clave').value);
        POSTData.append('telefono', document.getElementById('telefono').value);
        POSTData.append('puesto', document.getElementById('puesto').value);
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                btn.disabled = false;
                MostrarMensaje(response.content);
            } else if (xhr.readyState == 4 && xhr.status != 200) {
                btn.disabled = false;
                MostrarMensaje("Ha ocurrido un error inesperado al realizar la consulta");
            }
        }
        xhr.onloadstart = () => {
            btn.disabled = true;
        }
        xhr.onerror = () => {
            btn.disabled = false;
        }
        xhr.open('POST', 'consultas/usuarios.php');
        xhr.send(POSTData);
    } else {
        MostrarMensaje('Complete toda la información necesaria');
    }
}