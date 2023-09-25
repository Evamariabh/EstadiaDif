<?php

session_start();
require('../commons/validaciones.php');
$DebugEnabled = true;
error_reporting(0);
if ($DebugEnabled) {
    error_reporting(E_ALL);
}

$Response = [];

function BuildResponse($code, $content){
    $tmpResponse = [];
    $tmpResponse['code'] = $code;
    $tmpResponse['content'] = $content;
    return $tmpResponse;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['curp'])) {
        require('../commons/bd_connection.php');
        if ($isDBConnected) {
            try {
                $Consulta = 
                "SELECT 
                    agenda_traslados.Paciente, 
                    agenda_traslados.Telefono, 
                    agenda_traslados.Correo, 
                    agenda_traslados.FechaNacimiento, 
                    agenda_traslados.Numero,
                    agenda_traslados.Calle,
                    agenda_traslados.Colonia,
                    agenda_traslados.Municipio,
                    agenda_traslados.Estado
                FROM 
                    agenda_traslados
                WHERE agenda_traslados.CURP = '{$_POST['curp']}'";
                $STMT = $pdo->query($Consulta);
                if ($STMT->rowCount() > 0) {
                    $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                } else {
                    $Response = BuildResponse(400, 'Cita no encontrada');
                }
            } catch (\Throwable $th) {
                $Response = BuildResponse(500, 'Excepción producida' . ($DebugEnabled ? $th->getMessage() : ''));
            }
        } else {
            $Response = BuildResponse(400, 'Conexión a la base de datos no disponible');
        }
    } else {
        $Response = BuildResponse(400, 'Consulta inválida');
    }
} else {
    $Response = BuildResponse(400, 'Error en la solicitud');
}
header('Content-Type: application/json');
echo json_encode($Response);


?>