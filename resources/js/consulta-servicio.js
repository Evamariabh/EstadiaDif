window.addEventListener("load", function() {
    document.getElementById('Print').addEventListener('click', () => {
        window.print();
    });

    const cita = new URL(window.location.href).searchParams.get('codigo');
    if (cita) {
        document.getElementById('solicitud_codigo').value = cita;
        document.getElementById('btnConsulta').click();
    }
});

function Consultar(btn) {
    let POSTData = new FormData();
    POSTData.append('codigo_solicitud', document.getElementById('solicitud_codigo').value);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            btn.disabled = false;
            if (response.code == 200) {
                document.getElementById('paciente').innerText = response.content.Solicitante;
                document.getElementById('telefono').innerText = response.content.Telefono;
                document.getElementById('telefono').setAttribute('href', 'tel:' + response.content.Telefono);
                document.getElementById('servicio').innerText = response.content.NombreServicio;
                document.getElementById('responsable').innerText = response.content.EncargadoServicio;
                document.getElementById('fecha').innerText = response.content.Fecha;
                if (response.content.Observaciones.length > 0) {
                    document.getElementById('observaciones').innerText = response.content.Observaciones;
                } else {
                    document.getElementById('observaciones').innerText = 'Sin observaciones.';
                }
                document.getElementById('codigo-solicitud').innerText = response.content.CodigoSolicitud;

                document.getElementById('CardCita').style.display = "block";
            } else {
                MostrarMensaje(response.content);
            }
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            btn.disabled = false;
            MostrarMensaje("Ha ocurrido un error inesperado al realizar la consulta");
        }
    }
    xhr.onloadstart = () => {
        LimpiarCita();
        btn.disabled = true;
    }
    xhr.onerror = () => {
        btn.disabled = false;
    }
    xhr.open('POST', 'consultas/servicios.php');
    xhr.send(POSTData);
}

function LimpiarCita() {
    document.querySelectorAll('[clear-on-request]').forEach(element => {
        element.innerText = "";
        element.innerHTML = "";
    });
    document.getElementById('CardError').style.display = "none";
    document.getElementById('CardCita').style.display = "none";
}