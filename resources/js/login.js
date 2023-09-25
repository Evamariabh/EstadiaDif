function Login(btn) {
    let Validator = new GUSI_INPUT_VALIDATOR();
    if (Validator.Check('#formLogin > input', true)) {
        let POSTData = new FormData();
        POSTData.append('target', 'login-admin');
        POSTData.append('nocontrol', document.getElementById('NumControl').value);
        POSTData.append('clave', document.getElementById('Clave').value);
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                btn.disabled = false;
                if (response.code == 200) {
                    MostrarMensaje(response.content);
                    setTimeout(() => {
                        window.location.href = 'admin/principal.php';
                    }, 300);
                } else {
                    MostrarMensaje(response.content);
                }
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