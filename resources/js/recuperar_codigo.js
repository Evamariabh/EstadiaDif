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

function Consultar() {
    TrasladosCurp();
    ServiciosCurp();
    document.getElementById('CardCita').style.display = "block";
}

function TrasladosCurp() {
    let POSTData = new FormData();
    POSTData.append('curp', document.getElementById('curp').value);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.code == 200) {
                agregarTraslados(response.content);
                document.getElementById('div_traslados').style.display = "block";
            } else {
                MostrarMensaje(response.content);
            }
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            MostrarMensaje("Ha ocurrido un error inesperado al realizar la consulta");
        }
    }
    xhr.onloadstart = () => {
        LimpiarCita();
    }
    xhr.onerror = () => {
    }
    xhr.open('POST', 'consultas/traslados_por_curp.php');
    xhr.send(POSTData);
}

function ServiciosCurp() {
    let POSTData = new FormData();
    POSTData.append('curp', document.getElementById('curp').value);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.code == 200) {
                agregarServicios(response.content);
                document.getElementById('div_servicios').style.display = "block";
            } else {
                MostrarMensaje(response.content);
            }
        } else if (xhr.readyState == 4 && xhr.status != 200) {
            MostrarMensaje("Ha ocurrido un error inesperado al realizar la consulta");
        }
    }
    xhr.onloadstart = () => {
        LimpiarCita();
    }
    xhr.onerror = () => {
    }
    xhr.open('POST', 'consultas/servicios_por_curp.php');
    xhr.send(POSTData);
}

function agregarTraslados(res){
    var div_servicios = document.getElementById('traslados');
    let totalHTML = '';

    for (let i = 0; i < res.length; i++) {
        totalHTML += 
        `<div class="row-info-cita mt-1">
            <div class="col-icon">
                <div class="icon-container">
                    <i aria-hidden="true" class="fas fa-project-diagram"></i>
                </div>
                <div class="col-content">
                <span class="font-bold">Código: </span>
                <span id="servicio"clear-on-request>${res[i].CodigoTraslado}</span><br>
                <span class="font-bold">Servicio: </span>
                <span id="servicio"clear-on-request>Traslado</span>
                </div>
            </div>
            <div class="col-icon">
                <div class="icon-container">
                    <i aria-hidden="true" class="fas fa-clock"></i>
                </div>
                <div class="col-content">
                    <span class="font-bold">Fecha y hora solicitada: </span>
                    <p id="fecha"clear-on-request>${res[i].FechaCreacion}</p>
                </div>
            </div>
        </div>`;
    }
    div_servicios.innerHTML = totalHTML;
}

function agregarServicios(res){
    var div_servicios = document.getElementById('servicios');
    let totalHTML = '';

    for (let i = 0; i < res.length; i++) {
        totalHTML += 
        `<div class="row-info-cita mt-1">
            <div class="col-icon">
                <div class="icon-container">
                    <i aria-hidden="true" class="fas fa-project-diagram"></i>
                </div>
                <div class="col-content">
                <span class="font-bold">Código: </span>
                <span id="servicio"clear-on-request>${res[i].CodigoSolicitud}</span><br>
                <span class="font-bold">Servicio: </span>
                <span id="servicio"clear-on-request>${res[i].NombreServicio}</span>
                </div>
            </div>
            <div class="col-icon">
                <div class="icon-container">
                    <i aria-hidden="true" class="fas fa-clock"></i>
                </div>
                <div class="col-content">
                    <span class="font-bold">Fecha y hora solicitada: </span>
                    <p id="fecha"clear-on-request>${res[i].FechaCreacion}</p>
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