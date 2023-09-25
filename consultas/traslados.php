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
    if (isset($_POST['codigo_cita'])) {
        require('../commons/bd_connection.php');
        if ($isDBConnected) {
            try {
                $Consulta = 
                "SELECT 
                    g.CodigoTraslado, 
                    g.Paciente, 
                    g.Telefono, 
                    g.Correo, 
                    g.FechaNacimiento, 
                    g.Estatus, 
                    TIMESTAMPDIFF(YEAR, g.FechaNacimiento, CURDATE()) AS 'Edad', 
                    CONCAT(g.Calle, ', No. ', g.Numero, ', Col. ', g.Colonia, '. ', g.Municipio) AS 'Domicilio', 
                    c.Nombre AS 'Conductor', 
                    a.NumeroAmbulancia AS 'Ambulancia', 
                    CASE
                        WHEN g.DestinoCustom = 1
                        THEN CONCAT(g.LugarCustom, ' (8hrs.)')
                        ELSE
                            (SELECT 
                                CASE 
                                    WHEN COUNT(d.ID_Destino) = 1
                                    THEN CONCAT(d.NombreLugar, ' (', HOUR(d.TiempoViaje), 'hrs, ', MINUTE(d.TiempoViaje), 'mins.)')
                                    ELSE 'Destino predefinido no disponible'
                                END
                            FROM destinos d 
                            WHERE d.ID_Destino = g.LINK_Destino)
                    END as 'Destino',
                    DATE_FORMAT(g.Fecha, '%d/%m/%Y') AS 'Fecha', 
                    TIME_FORMAT(g.Hora, '%T') AS 'Hora', 
                    g.Observaciones, 
                    g.Cancelado 
                FROM 
                    agenda_traslados g
                    LEFT JOIN ambulancias a ON a.IDAmbulancia = g.LINK_Ambulancia 
                    LEFT JOIN conductores c ON c.IDConductor = a.LINK_Conductor 
                WHERE g.CodigoTraslado = :codigo AND g.CURP = :curp";
                $STMT = $pdo->prepare($Consulta);
                $STMT->execute(
                    [':codigo' => $_POST['codigo_cita'],
                    ':curp' => $_POST['curp']
                ]);
                if ($STMT->rowCount() == 1) {
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