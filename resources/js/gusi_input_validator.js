function GUSI_INPUT_VALIDATOR() {
    let arr_val_dates = [];
    this.Check = function(selectors, showError = false, className = 'color-red') {
        let Pass = true;
        document.querySelectorAll('[input-validator-error]').forEach(textError => {
            textError.remove();
        });
        document.querySelectorAll(selectors).forEach(input => {
            switch (input.tagName.toLowerCase()) {
                case 'input':
                    if (input.value == '' | input.value.length == 0 && input.id !== 'custom') {
                        if (showError) {
                            let errorElement = document.createElement('p');
                            errorElement.className = className;
                            switch (input.type) {
                                case 'text':
                                    errorElement.innerText = 'Rellene este campo con la información solicitada.';
                                    break;
                                case 'number':
                                    errorElement.innerText = 'Ingrese el valor numérico solicitado.';
                                    break;
                                case 'search':
                                    errorElement.innerText = 'Ingrese un término para buscar.';
                                    break;
                                case 'email':
                                    errorElement.innerText = 'Ingrese un correo electrónico.';
                                    break;
                                case 'file':
                                    errorElement.innerText = 'Seleccione un archivo para cargar.';
                                    break;
                                case 'date':
                                    errorElement.innerText = 'Elija una fecha.';
                                    break;
                                case 'time':
                                    errorElement.innerText = 'Elija una hora.';
                                    break;
                                case 'tel':
                                    errorElement.innerText = 'Ingrese un número de teléfono.';
                                    break;
                                case 'url':
                                    errorElement.innerText = 'Ingrese una dirección.';
                                    break;
                                case 'password':
                                    errorElement.innerText = 'Ingrese una contraseña.';
                                    break;
                                case 'color':
                                    errorElement.innerText = 'Elija un color.';
                                    break;
                                default:
                                    errorElement.innerText = 'Complete este campo con la información solicitada.';
                                    break;
                            }
                            errorElement.setAttribute('input-validator-error', true);
                            input.parentElement.insertBefore(errorElement, input.nextSibling);
                        }
                        Pass = false;
                    } else {
                        let HasError = false;
                        let ErrorMsg = '';
                        switch (input.type) {
                            case 'text':
                                if(input.id === 'curp'){
                                    var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/, validado = document.getElementById('curp').value.match(re);
                                    if(!validado){
                                        ErrorMsg = 'Ingrese una CURP válida.';
                                        HasError = true;
                                    }
                                } else if (input.id === 'nombre') {
                                    if (isNumeric(input.value)) {
                                        ErrorMsg = 'El campo nombre no debe incluir números.';
                                        HasError = true;
                                    }
                                }
                                break;
                            case 'search':
                                if (input.maxlenght != -1) {
                                    if (input.value.length > input.maxlenght) {
                                        ErrorMsg = 'Ingrese sólo ' + input.maxlenght + ' caracteres.';
                                        HasError = true;
                                    }
                                }
                                break;
                            case 'number':
                                if (!(/^\d+((\.)\d{1,5})?$/).test(input.value)) {
                                    HasError = true;
                                    ErrorMsg = 'Ingrese un número válido';
                                }
                                break;
                            case 'email':
                                if (!(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/).test(input.value)) {
                                    HasError = true;
                                    ErrorMsg = 'Ingrese un correo electrónico válido.';
                                }
                                break;
                            case 'date':
                                console.log('');
                                if (!(/^\d{4}-\d{2}-\d{2}$/).test(input.value)) {
                                    HasError = true;
                                    ErrorMsg = 'Ingrese una fecha válida.';
                                } else if (input.id === 'fecha_nacimiento'){
                                    let arr_date = input.value.split("-");
                                    if (new Date(arr_date) > new Date()) {
                                        HasError = true;
                                        ErrorMsg = 'Ingrese una fecha de nacimiento válida.';     
                                    }
                                } 
                                else if (input.id === 'fecha_traslado'){
                                    let fecha_elegida;
                                    let arr_date = input.value.split("-");
                                    let today = new Date();
                                    fecha_elegida = `${today.getMonth()}/${today.getDate()}/${today.getFullYear()}`;
                                    if (new Date(arr_date) < new Date(fecha_elegida)) {
                                        HasError = true;
                                        ErrorMsg = 'Ingrese una fecha válida.';
                                }} else if (input.id === 'inputF_1' || input.id === 'inputF_2' || input.id === 'inputF_3' || input.id === 'inputF_4' 
                                || input.id === 'inputF_5' || input.id === 'inputF_6' || input.id === 'inputF_7' || input.id === 'inputF_8' || input.id === 'inputF_9') {
                                    arr_val_dates.push(input.value);
                                    let find = arr_val_dates.filter(a => a === input.value);
                                    if(find.length > 2){
                                        HasError = true;
                                        ErrorMsg = 'Solo se permiten 2 servicios por día.';  
                                    }
                                }
                                break;
                            case 'time':
                                if (!(/^\d{2}:\d{2}(:\d{2})?$/).test(input.value)) {
                                    HasError = true;
                                    ErrorMsg = 'Ingrese una hora válida.';
                                }
                                break;
                            case 'tel':
                                if (!(/^\d{10}$/).test(input.value)) {
                                    HasError = true;
                                    ErrorMsg = 'Ingrese un teléfono a 10 dígitos sin espacios ni símbolos.';
                                }
                                break;
                            case 'color':
                                if (!(/^#(([0-9a-f]{3,4})|([0-9a-f]{6})|([0-9a-f]{8}))$/).test(input.value)) {
                                    HasError = true;
                                    ErrorMsg = 'Seleccione un color válido en HEX.';
                                }
                                break;
                        }
                        if (HasError) {
                            Pass = false;
                            if (showError) {
                                let errorElement = document.createElement('p');
                                errorElement.className = className;
                                errorElement.innerText = ErrorMsg;
                                errorElement.setAttribute('input-validator-error', true);
                                input.parentElement.insertBefore(errorElement, input.nextSibling);
                            }
                        }
                    }
                    break;
                case 'select':
                    if (input.selectedIndex == -1 | input.value == '') {
                        if (showError) {
                            let errorElement = document.createElement('p');
                            errorElement.className = className;
                            errorElement.innerText = 'Seleccione una opción.';
                            errorElement.setAttribute('input-validator-error', true);
                            input.parentElement.insertBefore(errorElement, input.nextSibling);
                            Pass = false;
                        }
                    }
                    break;
                case 'textarea':
                    if (input.value == '' | input.value.length == 0) {
                        if (showError) {
                            let errorElement = document.createElement('p');
                            errorElement.className = className;
                            errorElement.innerText = 'Agregue información a este campo.';
                            errorElement.setAttribute('input-validator-error', true);
                            input.parentElement.insertBefore(errorElement, input.nextSibling);
                            Pass = false;
                        }
                    }
                    break;
                default:
                    console.error(input, ' No es un elemento válido para comprobar ');
                    break;
            }
        });
        return Pass;
    }
}
function isNumeric(val) {
    let existeNumero = 0;
    for (let i = 0; i < val.length; i++) {
        if(val[i] != ' ' && !isNaN(val[i])){
            existeNumero+=1;  
        }      
    }
    return existeNumero>0?true:false;
}