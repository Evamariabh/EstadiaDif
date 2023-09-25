<?php 

//Los select tienen el indice 0 seleccionado por defecto, cuya opción es inválida
//de forma temporal, esta función valida que se reciba cualquier opción menos la inválida
function SelectedOption($element, $ZeroAllowed = false)
{
    if ($ZeroAllowed) {
        return ($element >= 0);
    } else {
        return ($element > 0);
    }
}

//Esta función valida que la entrada esté dentro del rango de longitud admitida en la base
//min puede ser simplemente 1, pero dependiendo del caso puede que cierta entrada deba tener
//mas de ciertos caracteres y siempre igual o menor al máximo
function validLength($strInput, $min, $max)
{
    return (strlen($strInput) >= $min && strlen($strInput) <= $max);
}

//Esta opción valida que no sea nulo o vacío el valor de la variable que tome
function NotNullOrEmpty($value)
{
    return ($value != null && strlen($value) > 0);
}

//Esta función evalúa con una expresión regular que los caracteres de la contraseña sean los permitidos
function SafePassword($TextInput)
{
    return (preg_match('/^([a-zA-Z0-9áéíóúñüÁÉÍÓÚÑ@_ -.]+)$/', $TextInput) && strlen($TextInput) >= 6 && strlen($TextInput) <= 18);
}

//Función con expresión regular que evalúa que la entrada de datos tenga caracteres admitidos
function SafeString($strInput, $AllowNewLine = true)
{
    if ($AllowNewLine) {
        return preg_match('/^[^<>]{0,}$/', $strInput);
    } else {
        return preg_match('/^[^<>\n]{0,}$/', $strInput);
    }
}

//Función con expresión regular que evalúa que el email ingresado sea válido
function RealEmail($Email)
{
    return preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $Email);
}

//Función general que evalúa que la entrada de datos tenga caracteres admitidos y que esté dentro de un rango de longitud
function validInput($Input, $min, $max, $AllowNewLine)
{
    return (validLength($Input, $min, $max) && SafeString($Input, $AllowNewLine));
}

//Función para controlar los nombre de archivos
function safeFileName($name)
{
    return preg_match('/^([\d\.\-A-Za-z _áéíóúÁÉÍÓÚÑ]){3,}$/', $name);
}