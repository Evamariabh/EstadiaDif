let ModalMSG_Element = document.getElementById('MDL_Mensaje');
let ModalMSG = new bootstrap.Modal(ModalMSG_Element, {});



function MostrarMensaje(MSG) {
    ModalMSG.show(ModalMSG_Element);
    document.getElementById('MDL_Mensaje_Content').innerText = MSG;
}


function EnableActions() {
    document.querySelectorAll('[actionable]').forEach(actionable => {
        actionable.disabled = false;
    });
}

function DisableActions() {
    document.querySelectorAll('[actionable]').forEach(actionable => {
        actionable.disabled = true;
    });
}

function ClearFields(selectorModal) {
    document.querySelector(selectorModal).querySelectorAll('input,textarea').forEach(input => {
        input.value = '';
    });
}