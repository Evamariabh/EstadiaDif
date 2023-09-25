let token = '4df55873-126a-4dc1-ba1a-81d51e351908';
BuscarEstados();

// function Solicitar(btn){

//     // queryParams = [{
//     //     nombre: document.getElementById('nombre').value,
//     //     telefono: document.getElementById('telefono').value,
//     //     correo: document.getElementById('correo').value,
//     //     fecha_nacimiento: document.getElementById('fecha_nacimiento').value,
//     //     calle: document.getElementById('calle').value,
//     //     colonia: document.getElementById('select_colonias').value,
//     //     municipio: document.getElementById('select_municipios').value,
//     //     estado: document.getElementById('select_estados').value,
//     //     destino: document.getElementById('destino').value,
//     //     lugar: document.getElementById('lugar').value,
//     //     fecha: document.getElementById('fecha').value,
//     //     salida: document.getElementById('salida').value,
//     //     observaciones: document.getElementById('observaciones').value,
//     //     carnet: document.getElementById('carnet').value,
//     //   }];

//     // console.log(btn)
//     let DT = new FormData();
//     DT.append('curp', 'gshjgjkas');
//     let xhr = new XMLHttpRequest();
//     xhr.open('POST', 'consultas/solicitudes.php');
//     xhr.send(DT);


//     // fetch('consultas/solicitudes.php',{method: 'post', body: {valor:"xdddddd"}})
//     // .then(res => {
//     //     console.log(res);
//     // })

//     // fetch('consultas/solicitudes.php?valor=valor')
//     // .then(res => {
//     //     console.log(res);
//     // })
// //     .then((Variable3) =>{return Variable3.json();})
// //     .then((Variable4)=>{console.log(Variable4);})
// }


function Solicitar(btn) {
    let Validator = new GUSI_INPUT_VALIDATOR();
    if (CheckCustom(document.querySelector('#destino'))) {
        if (Validator.Check('#form-solicitud input, #form-solicitud select', true)) {
            let POSTData = new FormData();
            POSTData.append('target', 'traslado');
            POSTData.append('curp', document.getElementById('curp').value);
            POSTData.append('nombre', document.getElementById('nombre').value);
            POSTData.append('telefono', document.getElementById('telefono').value);
            POSTData.append('correo', document.getElementById('correo').value);
            POSTData.append('fecha_nacimiento', document.getElementById('fecha_nacimiento').value);
            POSTData.append('calle', document.getElementById('calle').value);
            POSTData.append('numero', document.getElementById('numero').value);
            POSTData.append('colonia', document.getElementById('select_colonias').value);
            POSTData.append('municipio', document.getElementById('select_municipios').value);
            POSTData.append('estado', document.getElementById('select_estados').value);
            POSTData.append('custom', 1);
            POSTData.append('destino', parseInt(document.getElementById('destino').value));
            POSTData.append('lugar', document.getElementById('custom').value);
            POSTData.append('fecha', document.getElementById('fecha_traslado').value);
            POSTData.append('salida', document.getElementById('hora_salida').value);
            POSTData.append('observaciones', document.getElementById('observaciones').value);
            POSTData.append('carnet', document.getElementById('carnet').files[0]);
            if(estructurarMultiservicios().length > 0){
                POSTData.append('multiservicio', estructurarMultiservicios());
            }
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr);
                    let response = JSON.parse(xhr.responseText);
                    btn.disabled = false;
                    if (response.code == 200) {
                        document.getElementById('form-solicitud').style.display = 'none';
                        document.getElementById('msgRegistro').classList.remove('hidden');
                        document.getElementById('solicitudMSG').innerText = response.content;
                        document.getElementById('cita-redirect').setAttribute('href', 'consultar.php?codigo=' + response.content);
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
    } else {
        if (Validator.Check('#form-solicitud input:not([custom]), #form-solicitud select', true)) {
            let POSTData = new FormData();
            POSTData.append('target', 'traslado');
            POSTData.append('curp', document.getElementById('curp').value);
            POSTData.append('nombre', document.getElementById('nombre').value);
            POSTData.append('telefono', document.getElementById('telefono').value);
            POSTData.append('correo', document.getElementById('correo').value);
            POSTData.append('fecha_nacimiento', document.getElementById('fecha_nacimiento').value);
            POSTData.append('calle', document.getElementById('calle').value);
            POSTData.append('numero', document.getElementById('numero').value);
            POSTData.append('colonia', document.getElementById('select_colonias').value);
            POSTData.append('municipio', document.getElementById('select_municipios').value);
            POSTData.append('estado', document.getElementById('select_estados').value);
            POSTData.append('custom', 0);
            POSTData.append('destino', parseInt(document.getElementById('destino').value));
            POSTData.append('lugar', '');
            POSTData.append('fecha', document.getElementById('fecha_traslado').value);
            POSTData.append('salida', document.getElementById('hora_salida').value);
            POSTData.append('observaciones', document.getElementById('observaciones').value);
            POSTData.append('carnet', document.getElementById('carnet').files[0]);
            if(estructurarMultiservicios().length > 0){
                POSTData.append('multiservicio', estructurarMultiservicios());
            }
            
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let response = JSON.parse(xhr.responseText);
                    btn.disabled = false;
                    if (response.code == 200) {
                        document.getElementById('form-solicitud').style.display = 'none';
                        document.getElementById('msgRegistro').classList.remove('hidden');
                        document.getElementById('solicitudMSG').innerText = response.content;
                        document.getElementById('cita-reditect').setAttribute('href', 'consultar.php?cita=' + response.content.split(' ').pop());
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
}

function estructurarMultiservicios() {
    let ubicacion = 1;
    let cadena = '[';
    for (let i = 1; i < 9; i++) {    
        if (document.getElementById(`${i}`).checked) {
            let fecha_cita = document.getElementById(`inputF_${i}`).value;
            let car = ubicacion === 1 ? `{"id":"${i}","fecha":"${fecha_cita}"}` : `,{"id":"${i}","fecha":"${fecha_cita}"}`
            cadena+=car;
            ubicacion++;
        }
   }
   cadena+=']';
   return cadena;
}

function CheckCustom(select) {
    if (select.value == '-1') {
        document.getElementById('destinoCustomContainer').style.display = 'block';
    } else {
        document.getElementById('destinoCustomContainer').style.display = 'none';
    }
    return select.value == '-1';
}


function ConsultarCURP_bd() {
    let POSTData = new FormData();
    POSTData.append('curp', document.getElementById('curp').value);
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            if (response.code == 200) {
                document.getElementById('telefono').value = response.content.Telefono;
                document.getElementById('correo').value = response.content.Correo;
                document.getElementById('nombre').value = response.content.Paciente;
                document.getElementById('fecha_nacimiento').value = response.content.FechaNacimiento;
                createOption(response.content.Estado,'select_estados');
                document.getElementById('select_estados').value = response.content.Estado;
                createOption(response.content.Municipio,'select_municipios');
                document.getElementById('select_municipios').value = response.content.Municipio;
                createOption(response.content.Colonia,'select_colonias');
                document.getElementById('select_colonias').value = response.content.Colonia;
                document.getElementById('calle').value = response.content.Calle;
                document.getElementById('numero').value = response.content.Numero;
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

function BuscarCURP(){
    ConsultarCURP_bd();
}

function ConsultarCURP_api (){
    limpiarCampos();
    // const options = {
        //     method: 'POST',
        //     headers: {
        //         'content-type': 'application/json',
        //         'X-RapidAPI-Key': '05539c5fb2msh7de36ba085c371cp14dc7fjsnfb05fcf07607',
        //         'X-RapidAPI-Host': 'curp-renapo.p.rapidapi.com'
        //     },
        //     body: '{"curp":"HECE030110MHGRRLA8"}'
        // };
        
        // fetch('https://curp-renapo.p.rapidapi.com/v1/curp', options)
        //     .then(response => response.json())
        //     .then(response => {
        //         console.log(response);
        //         document.getElementById('fecha_nacimiento').value = "2002-01-10";
        //         document.getElementById('nombre').value= "Elizabeth";
        //     })
        //     .catch(err => console.error(err));
    
        let arr = {
            birthdate: "10/01/2010",
            curp: "HECE030110MHGRRLA5",
            entity_birth: "HG",
            homonimo: false,
            mothers_maiden_name: "CRUZ",
            names: "ALEJANDRO",
            nationality: "MEXICO",
            paternal_surname: "HERNANDEZ",
            probation_document: "ACTA DE NACIMIENTO",
            probation_document_data: {
                anioReg: "2003",
                crip: "",
                cveEntidadEmisora: "",
                cveMunicipioReg: "073",
                foja: "",
                folioCarta: "",
                libro: "",
                numActa: "01102",
                numEntidadReg: "13",
                numRegExtranjeros: "",
                tomo: ""
            },
            renapo_valid: true,
            sex: "MUJER",
            status_curp: "RCN",
            transaction_id: "92486cd25a31613aa69490ce0ea3fd8b858110514a22eb68121375dab855e90e"
        }
    
        let date = arr.birthdate.split("/");
        document.getElementById('fecha_nacimiento').value = `${date[2]}-${date[1]}-${date[0]}`;
        document.getElementById('nombre').value= `${arr.names} ${arr.paternal_surname} ${arr.mothers_maiden_name}`;
}

function limpiarCampos() {
    document.getElementById('telefono').value = '';
    document.getElementById('correo').value = '';
    document.getElementById('select_estados').value = '';
    document.getElementById('select_municipios').value = '';
    document.getElementById('select_colonias').value = '';
    document.getElementById('calle').value = '';
    document.getElementById('numero').value = '';
    document.getElementById('fecha_nacimiento').value = '';
    document.getElementById('nombre').value = '';
}
// function BuscarCP(cp){    
//     fetch(`https://api.copomex.com/query/info_cp/${cp.value}?type=simplified&token=pruebas`)
//     .then(response => response.json())
//     .then(response => {
//         console.log(response);
//         document.getElementById('select_municipios').value = `${response.response.municipio}`;
//         document.getElementById('colonia').value = `${response.response.ciudad}`;
//     })
//     .catch(err => console.error(err));  
// }

function BuscarEstados(){    
    fetch(`https://api.copomex.com/query/get_estados?token=${token}`)
    .then(response => response.json())
    .then(response => {
        fillSelector(response.response.estado, "select_estados");
    })
    .catch(err => console.error(err));
       
}

function BuscarMunicipios(){    
    fetch(`https://api.copomex.com/query/get_municipio_por_estado/${document.getElementById('select_estados').value}?token=${token}`)
    // fetch(`https://api.copomex.com/query/get_municipio_por_estado/Hidalgo?token=pruebas`)
    .then(response => response.json())
    .then(response => {
        LimpiarSelect("select_municipios");
        fillSelector(response.response.municipios, "select_municipios");
    })
    .catch(err => console.error(err));   
}

function BuscarColonias(){    
    fetch(`https://api.copomex.com/query/get_colonia_por_municipio/${document.getElementById('select_municipios').value}?token=${token}`)
    // fetch(`https://api.copomex.com/query/get_colonia_por_municipio/Huejutla de Reyes?token=pruebas`)
    .then(response => response.json())
    .then(response => {
        LimpiarSelect("select_colonias");
        fillSelector(response.response.colonia, "select_colonias");
    })
    .catch(err => console.error(err));
}

function LimpiarSelect(id_input) {
    for (let i = document.getElementById(id_input).options.length; i >= 1; i--) {
        document.getElementById(id_input).remove(i);
    }
};

function SeleccionaEstado(){
    BuscarMunicipios();   
}

function SeleccionaMunicipio(){
    BuscarColonias();
}

function fillSelector(arr, id_input) {
    var modelList = document.getElementById(id_input);
    for (let i = 0; i < arr.length; i++) {
        var opt = document.createElement("option");
        opt.value = arr[i];
        opt.textContent = arr[i];
        modelList.options.add(opt);
    }
} 

function createOption(str, id_input) {
    var modelList = document.getElementById(id_input);
    var opt = document.createElement("option");
    opt.value = str;
    opt.textContent = str;
    modelList.options.add(opt);
} 

function fileValidation(){
    var fileInput = document.getElementById('carnet');
    var filePath = fileInput.value;
    var allowedExtensions = /(.jpg|.jpeg|.png|.gif)$/i;
    if(!allowedExtensions.exec(filePath)){
        MostrarMensaje("Solo se admiten archivos de tipo imagen");
        fileInput.value = '';
    }
}

function mostrarServicios(){
    var div = document.getElementById("servicios_extra");
    if (document.getElementById("mostrar_Servicios").checked) {
        div.style.display = "block";
    } else {
        div.style.display = "none";
    }
}

function agregarServicio(id){
    mostrarTabla();
    var div_Servicio = document.getElementById('col_servicio');
    var div_Fecha = document.getElementById('col_fecha');
    if (document.getElementById(`${id}`).checked) {
        var inputS = document.createElement("input");
        inputS.type = 'text';
        inputS.id = `inputS_${id}`;
        inputS.value = `${document.getElementById(id).value}`;
        inputS.disabled = 'true';
        div_Servicio.appendChild(inputS);

        var inputF = document.createElement("input");
        inputF.type = 'date';
        inputF.id = `inputF_${id}`;
        div_Fecha.appendChild(inputF);
    } else {
        document.getElementById(`inputS_${id}`).remove();
        document.getElementById(`inputF_${id}`).remove();
    }
}

function mostrarTabla(){
    var div = document.getElementById("tabla_datos");
    let cantCheck = 0;
    for (let i = 1; i < 9; i++) {    
         if (document.getElementById(`${i}`).checked) {
            cantCheck+=1;
         }
    }
    div.style.display = cantCheck>0? "block" : "none";
}

// function curpValidation(){
//     var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/, validado = document.getElementById('curp').value.match(re);
//     if(validado){
//         MostrarMensaje("Valido");
//     } else {
//         MostrarMensaje("Invalido");
//     }
// }