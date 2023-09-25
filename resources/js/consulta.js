window.addEventListener("load", function() {
    document.getElementById('Print').addEventListener('click', () => {
        window.print();
    });

    const cita = new URL(window.location.href).searchParams.get('cita');
    if (cita) {
        document.getElementById('cita_codigo').value = cita;
        document.getElementById('btnConsulta').click();
    }
});

function Consultar(btn) {
    let POSTData = new FormData();
    POSTData.append('codigo_cita', document.getElementById('cita_codigo').value);
    POSTData.append('curp', document.getElementById('curp').value);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            btn.disabled = false;
            if (response.code == 200) {
                if (response.content.Cancelado == '0') {
                    document.getElementById('paciente').innerText = response.content.Paciente + ' (' + response.content.Edad + ' años).';
                    document.getElementById('telefono').innerText = response.content.Telefono;
                    document.getElementById('telefono').setAttribute('href', 'tel:' + response.content.Telefono);
                    document.getElementById('correo').innerText = response.content.Correo;
                    document.getElementById('correo').setAttribute('href', 'mailto:' + response.content.Correo);
                    document.getElementById('origen').innerText = response.content.Domicilio;
                    document.getElementById('destino').innerText = response.content.Destino;
                    document.getElementById('estatus').innerText = response.content.Estatus;
                    document.getElementById('conductor').innerText = response.content.Conductor || 'Por asignar';
                    document.getElementById('numero-ambulancia').innerText = response.content.Ambulancia || 'Por asignar';
                    document.getElementById('fecha').innerText = response.content.Fecha;
                    document.getElementById('salida').innerText = response.content.Hora;
                    ConsultarServicios(document.getElementById('cita_codigo').value);
                    if (response.content.Observaciones.length > 0) {
                        document.getElementById('observaciones').innerText = response.content.Observaciones;
                    } else {
                        document.getElementById('observaciones').innerText = 'Sin observaciones.';
                    }
                    document.getElementById('codigo-traslado').innerText = response.content.CodigoTraslado;

                    document.getElementById('CardCita').style.display = "block";
                } else {
                    document.getElementById('CardError').style.display = "block";
                    document.getElementById('ErrorDesc').innerText = "La cita ha sido cancelada por un administrador";
                }
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
    xhr.open('POST', 'consultas/traslados.php');
    xhr.send(POSTData);
}

function ConsultarServicios(Codigo) {
    let POSTData = new FormData();
    POSTData.append('codigo_cita', Codigo);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.code == 200) {
                if (response.content !== 'Cita no encontrada'){
                    agregarServiciosIncluidos(response.content);
                }
            } else {
                MostrarMensaje(response.content);
            }
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            MostrarMensaje("Ha ocurrido un error inesperado al realizar la consulta");
        }
    }
    xhr.open('POST', 'consultas/incluye_serv.php');
    xhr.send(POSTData);
}

function agregarServiciosIncluidos(res){
    document.getElementById("div_incluye_serv").style.display = "block";
    var div_servicios = document.getElementById('incluye_serv');
    let totalHTML = '';

    for (let i = 0; i < res.length; i++) {
        totalHTML += 
        `<div class="row-info-cita mt-1">
            <div class="col-icon">
                <div class="icon-container">
                    <i aria-hidden="true" class="fas fa-project-diagram"></i>
                </div>
                <div class="col-content">
                    <span class="font-bold">Servicio: </span>
                    <span id="servicio"clear-on-request>${res[i].NombreServicio}</span>
                </div>
            </div>
            <div class="col-icon">
                <div class="icon-container">
                    <i aria-hidden="true" class="fas fa-clock"></i>
                </div>
                <div class="col-content">
                    <span class="font-bold">Fecha: </span>
                    <p id="fecha"clear-on-request>${res[i].Fecha}</p>
                </div>
            </div>
        </div>`;
    }
    div_servicios.innerHTML = totalHTML;
}

function LimpiarCita() {
    document.querySelectorAll('[clear-on-request]').forEach(element => {
        element.innerText = "";
        element.innerHTML = "";
    });
    document.getElementById('CardError').style.display = "none";
    document.getElementById('CardCita').style.display = "none";
}