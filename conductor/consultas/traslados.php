<?php

session_start();
require('../../commons/validaciones.php');
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

const ERR_REGEX = 'Error se reconoció un error de formato o caracter no admitido, compruebe no haber ingresado los símbolos < >.';
const ERR_DATOS_FALTANTES = 'Error, no se han recibido todos los datos para esta operación';
const ERR_EXCEPCION = 'Excepción producida. ';
const ERR_BD_OFF = 'Error de conexión con la base de datos';
const ERR_NO_ID = 'No se recibió el elemento a operar';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['session_started'])) {
        if (isset($_POST['target'])) {
            require('../../commons/bd_connection.php');
            switch ($_POST['target']) {
                case 'agenda-hoy':
                    if ($isDBConnected) {
                        try {
                            $Consulta = 
                            "SELECT 
                                a.IDRegistro,
                                a.CodigoTraslado, 
                                a.Paciente, 
                                a.Telefono, 
                                CONCAT(a.Calle, ', No. ', a.Numero, ', Col. ', a.Colonia, '. ', a.Comunidad) AS 'Domicilio', 
                                CASE
                                    WHEN a.DestinoCustom = 1
                                    THEN CONCAT(a.LugarCustom, ' (8hrs.)')
                                    ELSE
                                        (SELECT 
                                            CASE 
                                                WHEN COUNT(d.ID_Destino) = 1
                                                THEN CONCAT(d.NombreLugar, ' (', HOUR(d.TiempoViaje), 'hrs, ', MINUTE(d.TiempoViaje), 'mins.)')
                                                ELSE 'Destino predefinido no disponible'
                                            END
                                        FROM destinos d
                                        WHERE d.ID_Destino = a.LINK_Destino)
                                END as 'Destino',
                                CONCAT(DATE_FORMAT(a.Fecha, '%d/%m/%Y'), ' ', a.Hora) AS 'Salida',
                                x.NumeroAmbulancia,
                                a.Carnet
                            FROM agenda_traslados a 
                                INNER JOIN ambulancias x ON x.IDAmbulancia = a.LINK_Ambulancia
                            WHERE x.LINK_Conductor = :conductor AND a.Fecha = CURDATE()";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':conductor' => $_SESSION['conductor_id']]);
                            $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION);
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                    break;
                case 'agenda-total':
                    if ($isDBConnected) {
                        try {
                            $Consulta = 
                            "SELECT 
                                a.IDRegistro,
                                a.CodigoTraslado, 
                                a.Paciente, 
                                a.Telefono, 
                                CONCAT(a.Calle, ', No. ', a.Numero, ', Col. ', a.Colonia, '. ', a.Comunidad) AS 'Domicilio', 
                                CASE
                                    WHEN a.DestinoCustom = 1
                                    THEN CONCAT(a.LugarCustom, ' (8hrs.)')
                                    ELSE
                                        (SELECT 
                                            CASE 
                                                WHEN COUNT(d.ID_Destino) = 1
                                                THEN CONCAT(d.NombreLugar, ' (', HOUR(d.TiempoViaje), 'hrs, ', MINUTE(d.TiempoViaje), 'mins.)')
                                                ELSE 'Destino predefinido no disponible'
                                            END
                                        FROM destinos d
                                        WHERE d.ID_Destino = a.LINK_Destino)
                                END as 'Destino',
                                CONCAT(DATE_FORMAT(a.Fecha, '%d/%m/%Y'), ' ', a.Hora) AS 'Salida',
                                x.NumeroAmbulancia,
                                a.Carnet
                            FROM agenda_traslados a 
                                INNER JOIN ambulancias x ON x.IDAmbulancia = a.LINK_Ambulancia
                            WHERE x.LINK_Conductor = :conductor";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':conductor' => $_SESSION['conductor_id']]);
                            $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION);
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                    break;
                default:
                    $Response = BuildResponse(400, 'Opción inválida');
                    break;
            }
        } else {
            $Response = BuildResponse(400, 'Consulta inválida');
        }
    } else {
        $Response = BuildResponse(400, 'Sesión no iniciada');
    }
} else {
    $Response = BuildResponse(400, 'Error en la solicitud');
}
header('Content-Type: application/json');
echo json_encode($Response);
