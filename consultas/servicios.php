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
    if (isset($_POST['codigo_solicitud'])) {
        require('../commons/bd_connection.php');
        if ($isDBConnected) {
            try {
                $Consulta = 
                "SELECT 
                    s.CodigoSolicitud, 
                    x.NombreServicio, 
                    x.EncargadoServicio, 
                    DATE_FORMAT(s.Fecha, '%d/%m/%Y') AS 'Fecha', 
                    s.Solicitante, 
                    s.Telefono, 
                    s.Observaciones 
                FROM 
                    solicitudes_servicios s 
                    INNER JOIN servicios x ON x.IDServicio = s.LINK_Servicio 
                WHERE s.CodigoSolicitud = :codigo";
                $STMT = $pdo->prepare($Consulta);
                $STMT->execute([':codigo' => $_POST['codigo_solicitud']]);
                if ($STMT->rowCount() == 1) {
                    $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                } else {
                    $Response = BuildResponse(400, 'Solicitud no encontrada');
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
    $Response = BuildResponse(400, 'Error al cargar datos');
}
header('Content-Type: application/json');
echo json_encode($Response);


?>