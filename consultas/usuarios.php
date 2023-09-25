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

const ERR_REGEX = 'Error se reconoció un error de formato o caracter no admitido, compruebe no haber ingresado los símbolos < >.';
const ERR_DATOS_FALTANTES = 'Error, no se han recibido todos los datos para esta operación';
const ERR_EXCEPCION = 'Excepción producida. ';
const ERR_BD_OFF = 'Error de conexión con la base de datos';
const ERR_NO_ID = 'No se recibió el elemento a operar';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['target'])) {
        require('../commons/bd_connection.php');
        switch ($_POST['target']) {
            case 'login-admin':
                if (isset($_POST['nocontrol']) && isset($_POST['clave'])) {
                    if (validInput($_POST['nocontrol'], 1, 15, false) && validInput($_POST['clave'], 1, 20, false)) {
                        if ($isDBConnected) {
                            $Consulta = "SELECT * FROM `administradores` WHERE `NumControl` = :nocontrol";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':nocontrol' => $_POST['nocontrol']]);
                            if($STMT->rowCount() == 1){
                                $DataAdmin = $STMT->fetch(PDO::FETCH_ASSOC);
                                if (password_verify($_POST['clave'], $DataAdmin['Clave'])) {
                                    $_SESSION['session_started'] = true;
                                    $_SESSION['session_type'] = 0;
                                    $_SESSION['admin_id'] = $DataAdmin['IDAdmin'];
                                    $_SESSION['admin_puesto'] = $DataAdmin['Puesto'];
                                    $_SESSION['admin_nombre'] = $DataAdmin['Nombre'];
                                    $_SESSION['num_control'] = $DataAdmin['NumControl'];
                                    $Response = BuildResponse(200, 'Login correcto');
                                } else {
                                    $Response = BuildResponse(400, 'Contraseña incorrecta');
                                }
                            } else {
                                $Response = BuildResponse(400, 'Administrador no encontrado con el número de control proporcionado');
                            }
                        } else {
                            $Response = BuildResponse(400, ERR_BD_OFF);
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_REGEX);
                    }
                } else {
                    $Response = BuildResponse(400, 'Consulta inválida');
                }
                break;
            case 'registro-admin':
                if (isset($_POST['nombre']) && isset($_POST['nocontrol']) && isset($_POST['clave']) && isset($_POST['telefono']) && isset($_POST['puesto'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['nocontrol'], 1, 15, false) && validInput($_POST['clave'], 1, 20, false) && validInput($_POST['telefono'], 1, 10, false) && validInput($_POST['puesto'], 1, 50, false)) {
                        if ($isDBConnected) {
                            $Consulta = "INSERT INTO `administradores`(`Nombre`, `NumControl`, `Clave`, `Telefono`, `Puesto`) VALUES (:nombre, :nocontrol, :clave, :telefono, :puesto)";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':nombre' => $_POST['nombre'], ':nocontrol' => $_POST['nocontrol'], ':clave' => password_hash($_POST['clave'], PASSWORD_DEFAULT), ':telefono' => $_POST['telefono'], ':puesto' => $_POST['puesto']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Administrador registrado');
                            } else {
                                $Response = BuildResponse(400, 'Administrador no registrado');
                            }
                        } else {
                            $Response = BuildResponse(400, ERR_BD_OFF);
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_REGEX);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_DATOS_FALTANTES);
                }
                break;
            case 'login-conductor':
                if (isset($_POST['nocontrol']) && isset($_POST['clave'])) {
                    if (validInput($_POST['nocontrol'], 1, 18, false) && validInput($_POST['clave'], 1, 20, false)) {
                        if ($isDBConnected) {
                            $Consulta = "SELECT * FROM `conductores` WHERE `NumControl` = :nocontrol";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':nocontrol' => $_POST['nocontrol']]);
                            if($STMT->rowCount() == 1){
                                $DataAdmin = $STMT->fetch(PDO::FETCH_ASSOC);
                                if (password_verify($_POST['clave'], $DataAdmin['Clave'])) {
                                    $_SESSION['session_started'] = true;
                                    $_SESSION['session_type'] = 1;
                                    $_SESSION['conductor_id'] = $DataAdmin['IDConductor'];
                                    $_SESSION['conductor_control'] = $DataAdmin['Nombre'];
                                    $_SESSION['num_control'] = $DataAdmin['NumControl'];
                                    $Response = BuildResponse(200, 'Login correcto');
                                } else {
                                    $Response = BuildResponse(400, 'Contraseña incorrecta');
                                }
                            } else {
                                $Response = BuildResponse(400, 'Conductor no encontrado con el número de control proporcionado');
                            }
                        } else {
                            $Response = BuildResponse(400, ERR_BD_OFF);
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_REGEX);
                    }
                } else {
                    $Response = BuildResponse(400, 'Consulta inválida');
                }
                break;
            default:
            $Response = BuildResponse(400, 'Acción no válida');
                break;
        }
    } else {
        $Response = BuildResponse(400, 'Acción no definida');
    }
} else {
    $Response = BuildResponse(400, 'Error en la solicitud');
}
header('Content-Type: application/json');
echo json_encode($Response);
