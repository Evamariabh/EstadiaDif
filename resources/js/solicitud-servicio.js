window.addEventListener('load', () => {
    const option = new URL(window.location.href).searchParams.get('preSelect');
    if (option) {
        document.getElementById('servicio').value = option;
        console.log("Pre option: " + option);
    }
});


function Solicitar(btn) {
    let Validator = new GUSI_INPUT_VALIDATOR();
    if (Validator.Check('#form-solicitud input, #form-solicitud select', true)) {
        let POSTData = new FormData();
        POSTData.append('target', 'servicio');
        POSTData.append('servicio', document.getElementById('servicio').value);
        POSTData.append('fecha', document.getElementById('fecha').value);
        POSTData.append('nombre', document.getElementById('nombre').value);
        POSTData.append('telefono', document.getElementById('telefono').value);
        POSTData.append('observaciones', document.getElementById('observaciones').value);
        POSTData.append('curp', document.getElementById('curp').value);
        let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                let response = JSON.parse(xhr.responseText);
                btn.disabled = false;
                if (response.code == 200) {
                    document.getElementById('form-solicitud').style.display = 'none';
                    document.getElementById('msgRegistro').classList.remove('hidden');
                    document.getElementById('solicitudMSG').innerText = 'Solicitud registrada, código: ' + response.content;
                    document.getElementById('cita-redirect').setAttribute('href', 'consulta-servicio.php?codigo=' + response.content);
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
        xhr.open('POST', 'consultas/solicitudes.php');
        xhr.send(POSTData);
    } else {
        MostrarMensaje('Complete los datos obligatorios');
    }
}

function BuscarCURP() {
    let POSTData = new FormData();
    POSTData.append('curp', document.getElementById('curp').value);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.code == 200) {
                document.getElementById('telefono').value = response.content.Telefono;
                document.getElementById('nombre').value = response.content.Paciente;
            } else {
                limpiarCampos();
            }
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            limpiarCampos();
        }
    }
    xhr.open('POST', 'consultas/consultar_curp.php');
    xhr.send(POSTData);
}
function limpiarCampos() {
    document.getElementById('telefono').value = '';
    document.getElementById('nombre').value = '';
}