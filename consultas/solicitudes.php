<?php
// $valor = $_GET['valor'];

session_start();
require('../commons/validaciones.php');
require('../commons/bd_connection.php');

// $SQL_Registrar = 
//     "INSERT INTO `servicios`
//         (`IDServicio`, `NombreServicio`, `EncargadoServicio`) 
//     VALUES 
//         (13,'$valor','e')";
// $STMT_RegistrarCita = $pdo->query($SQL_Registrar);
// $Response = BuildResponse(400, 'Cita no registrada');

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

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     console.log("se ejecuta");
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['target'])) {
        switch ($_POST['target']) {
            case 'traslado':
                if (
                isset($_POST['curp']) && 
                isset($_POST['nombre']) && 
                isset($_POST['telefono']) && 
                isset($_POST['correo']) && 
                isset($_POST['fecha_nacimiento']) && 
                isset($_POST['calle']) && 
                isset($_POST['numero']) && 
                isset($_POST['colonia']) && 
                isset($_POST['municipio']) && 
                isset($_POST['estado']) && 
                isset($_POST['destino']) && 
                isset($_POST['lugar']) && 
                isset($_POST['fecha']) && 
                isset($_POST['salida']) && 
                isset($_POST['observaciones']) && 
                !empty($_FILES)) {
                        if ($isDBConnected) {
                            $Archivo = '';
                            if (is_uploaded_file($_FILES['carnet']['tmp_name'])) {
                                $FileName =  $_FILES['carnet']['name'];
                                $ext = explode(".", $FileName);
                                $ext = $ext[count($ext) - 1];
                                $Curdate = date_create();
                                $bytes = random_bytes(4);
                                $Codigo = strtoupper(bin2hex($bytes));
                                $NewFileName = $Codigo . "_carnet_" . date_timestamp_get($Curdate) . "." .$ext;
                                $source_path = $_FILES['carnet']['tmp_name'];
                                $target_path = '../carnets/'.$NewFileName;
            
                                if (move_uploaded_file($source_path, $target_path)) {
                                    try {
                                        $SQL_CitaRepetida = "SELECT * FROM agenda_traslados WHERE CURP = :curp AND Estatus = 'PENDIENTE' AND Fecha = :fecha AND Hora = :hora";
                                        $STMT_HayCitaRepetida = $pdo->prepare($SQL_CitaRepetida);
                                        $STMT_HayCitaRepetida->execute(['curp' => $_POST['curp'], ':fecha' => $_POST['fecha'], ':hora' => $_POST['salida']]);
                                        if ($STMT_HayCitaRepetida->rowCount() <= 1) {
                                            $SQL_HorariosRepetidos = "SELECT * FROM agenda_traslados WHERE Estatus = 'PENDIENTE' AND Fecha = :fecha AND Hora = :hora";
                                            $STMT_HayHorarioRepetido = $pdo->prepare($SQL_HorariosRepetidos);
                                            $STMT_HayHorarioRepetido->execute([':fecha' => $_POST['fecha'], ':hora' => $_POST['salida']]);

                                            $SQL_Conductores = "SELECT * FROM conductores";
                                            $STMT_CantidadConductores = $pdo->query($SQL_Conductores);
                                            
                                            if ($STMT_HayHorarioRepetido->rowCount() <= $STMT_CantidadConductores->rowCount()) {
                                                    $SQL_Registrar = "INSERT INTO `agenda_traslados`
                                                    (`CodigoTraslado`, 
                                                    `CURP`,
                                                    `Paciente`,
                                                    `Telefono`,
                                                    `Correo`,
                                                    `FechaNacimiento`,
                                                    `Calle`,
                                                    `Numero`,
                                                    `Colonia`,
                                                    `Municipio`,
                                                    `Estado`,
                                                    `DestinoCustom`,
                                                    `LINK_Destino`,
                                                    `LugarCustom`,
                                                    `Fecha`,
                                                    `Hora`,
                                                    `Observaciones`,
                                                    `Carnet`)
                                                    VALUES
                                                    (
                                                    '{$Codigo}',
                                                    '{$_POST['curp']}',
                                                    '{$_POST['nombre']}',
                                                    '{$_POST['telefono']}',
                                                    '{$_POST['correo']}',
                                                    '{$_POST['fecha_nacimiento']}',
                                                    '{$_POST['calle']}',
                                                    '{$_POST['numero']}',
                                                    '{$_POST['colonia']}',
                                                    '{$_POST['municipio']}',
                                                    '{$_POST['estado']}',
                                                    {$_POST['custom']},
                                                    {$_POST['destino']},
                                                    '{$_POST['lugar']}',
                                                    '{$_POST['fecha']}',
                                                    '{$_POST['salida']}',
                                                    '{$_POST['observaciones']}', 
                                                    '{$NewFileName}')"; 
                                                    $STMT_RegistrarCita = $pdo->query($SQL_Registrar);
                                                    
                                                    if ($STMT_RegistrarCita->rowCount() == 1) {

                                                        if (strlen($_POST['multiservicio']) > 2) {
                                                            $multiservicios = json_decode($_POST['multiservicio']);
                                                            foreach($multiservicios as $fila) {
                                                                $bytesServ = random_bytes(4);
                                                                $CodigoServ = strtoupper(bin2hex($bytesServ));
                                                                $Consulta = "INSERT INTO `solicitudes_servicios`(`LINK_Servicio`, `CURP`, `CodigoSolicitud`, `CodigoTraslado`, `Fecha`, `Solicitante`, `Telefono`, `Observaciones`) VALUES (:servicio, :curp, :codigo, '{$Codigo}', :fecha, :solicitante, :telefono, :observaciones)";
                                                                $STMT = $pdo->prepare($Consulta);
                                                                $STMT->execute([':servicio' => $fila->id, ':curp' => $_POST['curp'], ':codigo' => $CodigoServ, ':fecha' => $fila->fecha, ':solicitante' => $_POST['nombre'], ':telefono' => $_POST['telefono'], ':observaciones' => $_POST['observaciones']]);
                                                                if ($STMT->rowCount() >= 1) {
                                                                    $Response = BuildResponse(200, 'Traslado y servicios registrados, código de traslado: ' . $Codigo);
                                                                } else {
                                                                    $Response = BuildResponse(400, 'No se pudo registrar la cita');
                                                                }
                                                            }
                                                        } else {
                                                            $Response = BuildResponse(200, 'Cita de traslado registrada, código de traslado: ' . $Codigo);
                                                            unlink($target_path);
                                                        }
                                                    } else {
                                                        $Response = BuildResponse(500, 'No se ha registrado la cita, intente nuévamente.');
                                                        unlink($target_path);
                                                    }
                                            } else {
                                                $Response = BuildResponse(400, 'Ya existen varias citas pendientes para la fecha y hora elegida, por favor eliga una diferente.');
                                                unlink($target_path);
                                            }
                                        } else {
                                            $Response = BuildResponse(400, 'El paciente ya cuenta con una cita en la fecha y hora elegida, por favor eliga una diferente.');
                                            unlink($target_path);
                                        }
                                    } catch (\Throwable $th) {
                                        $Response = BuildResponse(500, 'Ocurrió un error al procesar la operación.');
                                        unlink($target_path);
                                    }
                                } else {
                                    $Response = BuildResponse(500, 'Ocurrió un error al ubicar tu archivo.');
                                    unlink($source_path);
                                }
                            } else {
                                $Response = BuildResponse(500, 'Ocurrió un error al subir tu archivo.');
                            }
                        } else {
                            $Response = BuildResponse(400, 'Conexión a la base de datos no disponible');
                        }
                } else {
                    $Response = BuildResponse(400, 'Consulta inválida');
                }
                break;
            case 'servicio':
                if (isset($_POST['servicio']) && isset($_POST['nombre']) && isset($_POST['fecha']) && isset($_POST['telefono']) && isset($_POST['observaciones']) && isset($_POST['curp'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['telefono'], 10, 10, false) && validInput($_POST['observaciones'], 0, 250, false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha'])) {
                        $bytes = random_bytes(4);
                        $Codigo = strtoupper(bin2hex($bytes));
                        $Consulta = "INSERT INTO `solicitudes_servicios`(`LINK_Servicio`, `CURP`, `CodigoSolicitud`, `Fecha`, `Solicitante`, `Telefono`, `Observaciones`) VALUES (:servicio, :curp, :codigo, :fecha, :solicitante, :telefono, :observaciones)";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute([':servicio' => $_POST['servicio'], ':curp' => $_POST['curp'], ':codigo' => $Codigo, ':fecha' => $_POST['fecha'], ':solicitante' => $_POST['nombre'], ':telefono' => $_POST['telefono'], ':observaciones' => $_POST['observaciones']]);
                        if ($STMT->rowCount() == 1) {
                            $Response = BuildResponse(200, $Codigo);
                        } else {
                            $Response = BuildResponse(400, 'Cita no registrada');
                        }
                    } else {
                        $Response = BuildResponse(400, 'Error se reconoció un error de formato o caracter no admitido, compruebe no haber ingresado los símbolos < >.');
                    }
                } else {
                    $Response = BuildResponse(400, 'No se recibió toda la información para la operación');
                }
                break;
            default:
            $Response = BuildResponse(400, 'Acción no válida');
                break;
        }
    } else {
        $Response = BuildResponse(400, 'Parámetro necesario no recibido');
    }
} else {
    $Response = BuildResponse(400, 'Error en la solicitud');
}
header('Content-Type: application/json');
echo json_encode($Response);
?>