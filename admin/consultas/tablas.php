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
    if (isset($_POST['target'])) {
        require('../../commons/bd_connection.php');
        switch ($_POST['target']) {
            case 'lista-ambulancias':
                if ($isDBConnected) {
                    try {
                        $Consulta = "SELECT a.IDAmbulancia, a.NumeroAmbulancia, a.Placas, a.Descripcion, c.Nombre FROM ambulancias a INNER JOIN conductores c ON c.IDConductor = a.LINK_Conductor";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'registrar-ambulancia':
                if (isset($_POST['numero']) && isset($_POST['placas']) && isset($_POST['descripcion']) && isset($_POST['conductor'])) {
                    if (validInput($_POST['numero'], 1, 15, false) && validInput($_POST['placas'], 1, 15, false) && validInput($_POST['descripcion'], 1, 150, true)) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "INSERT INTO `ambulancias`(`NumeroAmbulancia`, `Placas`, `Descripcion`, `LINK_Conductor`) VALUES (:numero, :placas, :descripcion, :conductor)";
                                $STMT = $pdo->prepare($Consulta);
                                $STMT->execute([':numero' => $_POST['numero'], ':placas' => $_POST['placas'], ':descripcion' => $_POST['descripcion'], ':conductor' => $_POST['conductor']]);
                                if ($STMT->rowCount() == 1) {
                                    $Response = BuildResponse(200, 'Ambulancia registrada');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido registrar la ambulancia');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'eliminar-ambulancia':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "DELETE FROM `ambulancias` WHERE `IDAmbulancia` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Ambulancia eliminada');
                            } else {
                                $Response = BuildResponse(400, 'No se ha podido eliminar la ambulancia');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-ambulancia':
                if (isset($_POST['numero']) && isset($_POST['placas']) && isset($_POST['descripcion']) && isset($_POST['conductor']) && isset($_POST['ID'])) {
                    if (validInput($_POST['numero'], 1, 15, false) && validInput($_POST['placas'], 1, 15, false) && validInput($_POST['descripcion'], 1, 150, true)) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "UPDATE `ambulancias` SET `NumeroAmbulancia`= :numero,`Placas`= :placas, `Descripcion`= :descripcion, `LINK_Conductor`= :conductor WHERE `IDAmbulancia` = :id";
                                $STMT = $pdo->prepare($Consulta);
                                if ($STMT->execute([':numero' => $_POST['numero'], ':placas' => $_POST['placas'], ':descripcion' => $_POST['descripcion'], ':conductor' => $_POST['conductor'], ':id' => $_POST['ID']])) {
                                    $Response = BuildResponse(200, 'Ambulancia actualizada');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido actualizar los datos de la ambulancia');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'obtener-ambulancia':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `ambulancias` WHERE `IDAmbulancia` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró la ambulancia solicitada, actualize la table para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'lista-conductores':
                if ($isDBConnected) {
                    try {
                        $Consulta = "SELECT * FROM conductores";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'registrar-conductor':
                if (isset($_POST['nombre']) && isset($_POST['control']) && isset($_POST['telefono']) && isset($_POST['clave'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['control'], 1, 18, false) && validInput($_POST['telefono'], 1, 15, true)&& validInput($_POST['clave'], 1, 20, false)) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "INSERT INTO `conductores`(`Nombre`, `NumControl`, `Clave`, `Telefono`) VALUES (:nombre,:control,:clave,:telefono)";
                                $STMT = $pdo->prepare($Consulta);
                                $STMT->execute([':nombre' => $_POST['nombre'], ':control' => $_POST['control'], ':clave' => password_hash($_POST['clave'], PASSWORD_DEFAULT), ':telefono' => $_POST['telefono']]);
                                if ($STMT->rowCount() == 1) {
                                    $Response = BuildResponse(200, 'Conductor registrado');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido registrar el conductor');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'eliminar-conductor':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "DELETE FROM `conductores` WHERE `IDConductor` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Conductor eliminado');
                            } else {
                                $Response = BuildResponse(400, 'No se ha podido eliminar el conductor');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-conductor':
                if (isset($_POST['nombre']) && isset($_POST['control']) && isset($_POST['telefono']) && isset($_POST['clave']) && isset($_POST['cambio_clave']) && isset($_POST['ID'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['control'], 1, 18, false) && validInput($_POST['telefono'], 1, 15, true) && preg_match('/^[0-1]$/', $_POST['cambio_clave'])) {
                        if ($isDBConnected) {
                            try {
                                if (!boolval($_POST['cambio_clave'])) {
                                    $Consulta = "UPDATE `conductores` SET `Nombre`= :nombre,`NumControl`=:control,`Telefono`=:telefono WHERE `IDConductor` = :id";
                                    $STMT = $pdo->prepare($Consulta);
                                    if ($STMT->execute([':nombre' => $_POST['nombre'], ':control' => $_POST['control'], ':telefono' => $_POST['telefono'], ':id' => $_POST['ID']])) {
                                        $Response = BuildResponse(200, 'Conductor actualizado');
                                    } else {
                                        $Response = BuildResponse(400, 'No se ha podido actualizar los datos de el conductor');
                                    }
                                } else {
                                    $Consulta = "UPDATE `conductores` SET `Nombre`= :nombre,`NumControl`=:control,`Clave`=:clave,`Telefono`=:telefono WHERE `IDConductor` = :id";
                                    $STMT = $pdo->prepare($Consulta);
                                    if ($STMT->execute([':nombre' => $_POST['nombre'], ':control' => $_POST['control'], ':clave' => PASSWORD_HASH($_POST['clave'], PASSWORD_DEFAULT),':telefono' => $_POST['telefono'], ':id' => $_POST['ID']])) {
                                        $Response = BuildResponse(200, 'Conductor actualizado');
                                    } else {
                                        $Response = BuildResponse(400, 'No se ha podido actualizar los datos de el conductor');
                                    }
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'obtener-conductor':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `conductores` WHERE `IDConductor` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró el conductor solicitado, actualize la table para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'lista-lugares':
                if ($isDBConnected) {
                    try {
                        $Consulta = "SELECT `ID_Destino`, `NombreLugar`, CONCAT(HOUR(`TiempoViaje`), 'Hrs. ', MINUTE(`TiempoViaje`), 'Mins.') AS 'TiempoViaje' FROM `destinos`";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'registrar-lugar':
                if (isset($_POST['destino']) && isset($_POST['tiempo'])) {
                    if (validInput($_POST['destino'], 1, 100, false) && preg_match("/^\d{2}:\d{2}$/", $_POST['tiempo'])) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "INSERT INTO `destinos`(`NombreLugar`, `TiempoViaje`) VALUES (:nombre, :tiempo)";
                                $STMT = $pdo->prepare($Consulta);
                                $STMT->execute([':nombre' => $_POST['destino'], ':tiempo' => $_POST['tiempo']]);
                                if ($STMT->rowCount() == 1) {
                                    $Response = BuildResponse(200, 'Lugar registrado');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido registrar el Lugar');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'eliminar-lugar':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "DELETE FROM `destinos` WHERE `ID_Destino` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Lugar eliminado');
                            } else {
                                $Response = BuildResponse(400, 'No se ha podido eliminar el Lugar');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-lugar':
                if (isset($_POST['destino']) && isset($_POST['tiempo']) && isset($_POST['ID'])) {
                    if (validInput($_POST['destino'], 1, 100, false) && preg_match("/^\d{2}:\d{2}$/", $_POST['tiempo'])) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "UPDATE `destinos` SET `NombreLugar`= :nombre,`TiempoViaje`= :tiempo WHERE `ID_Destino` = :id";
                                $STMT = $pdo->prepare($Consulta);
                                if ($STMT->execute([':nombre' => $_POST['destino'], ':tiempo' => $_POST['tiempo'], ':id' => $_POST['ID']])) {
                                    $Response = BuildResponse(200, 'Lugar actualizado');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido actualizar los datos de el Lugar');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'obtener-lugar':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `destinos` WHERE `ID_Destino` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró el destino solicitado, actualize la table para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
            break;
            case 'lista-citas':
                if ($isDBConnected) {
                    try {
                        $Consulta = 
                        "SELECT 
                            a.IDRegistro,
                            a.CodigoTraslado, 
                            a.Paciente, 
                            a.Telefono, 
                            a.Estatus, 
                            CONCAT(a.Calle, ', No. ', a.Numero, ', Col. ', a.Colonia, '. ', a.Municipio) AS 'Domicilio', 
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
                            LEFT JOIN ambulancias x ON x.IDAmbulancia = a.LINK_Ambulancia";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'lista-solicitudes-serv':
                if ($isDBConnected) {
                    try {
                        $Consulta = 
                        "SELECT 
                        *
                        FROM solicitudes_servicios";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'obtener-solicitud-servicio':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `solicitudes_servicios` WHERE `IDSolicitud` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() > 0) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró la cita solicitada, actualize la tabla para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'autorizar-solicitud-serv':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $query = "UPDATE `solicitudes_servicios` SET `Estatus` = 'AUTORIZADO' WHERE (`IDSolicitud` = :id);";
                            $STMTCarnet = $pdo->prepare($query);
                            $STMTCarnet->execute([':id' => $_POST['ID']]);
                            if ($STMTCarnet->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Autorizada correctamente');
                            } else {
                                $Response = BuildResponse(400, 'No se pudo autorizar la cita');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-solicitud-serv':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $query = "UPDATE `solicitudes_servicios` SET `Fecha` = :fecha, `Solicitante` = :nombre, `Telefono` = :telefono, `Observaciones` = :obs WHERE (`IDSolicitud` = :id)";
                            $STMTCarnet = $pdo->prepare($query);
                            $STMTCarnet->execute([':id' => $_POST['ID'], ':nombre' => $_POST['nombre'], ':fecha' => $_POST['fecha'], ':telefono' => $_POST['telefono'], ':obs' => $_POST['observaciones']]);
                            if ($STMTCarnet->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Autorizada correctamente');
                            } else {
                                $Response = BuildResponse(400, 'No se pudo autorizar la cita');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'registrar-cita':
                if (isset($_POST['nombre']) && isset($_POST['telefono']) && isset($_POST['correo']) && isset($_POST['fecha_nacimiento']) && isset($_POST['calle']) && isset($_POST['numero']) && isset($_POST['colonia']) && isset($_POST['comunidad']) && isset($_POST['custom']) && isset($_POST['destino']) && isset($_POST['lugar']) && isset($_POST['fecha']) && isset($_POST['salida']) && isset($_POST['observaciones']) && !empty($_FILES)) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['telefono'], 10, 15, false) && validInput($_POST['correo'], 1, 60, false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha_nacimiento']) && validInput($_POST['calle'], 1, 60, false) && validInput($_POST['numero'], 1, 15, false) && validInput($_POST['colonia'], 1, 60, false) && validInput($_POST['comunidad'], 1, 60, false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha']) && preg_match('/^\d{2}:\d{2}$/', $_POST['salida']) && validInput($_POST['observaciones'], 0, 250, true) && preg_match('/^[0-1]$/', $_POST['custom']) && ($_POST['custom'] == 1 ? validInput($_POST['lugar'], 1, 350, false) : validInput($_POST['lugar'], 0, 350, false))) {
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
                            $target_path = '../../carnets/'.$NewFileName;
        
                            if (move_uploaded_file($source_path, $target_path)) {
                                try {
                                    $SQL_AmbulanciaDisponible = 
                                    "SELECT 
                                        a.IDAmbulancia, 
                                        a.NumeroAmbulancia 
                                    FROM ambulancias a                                 
                                    WHERE(
                                        SELECT 
                                            COUNT(x.IDRegistro) 
                                        FROM agenda_traslados x 
                                        INNER JOIN destinos d ON d.ID_Destino = x.LINK_Destino 
                                        WHERE 
                                            a.IDAmbulancia = x.LINK_Ambulancia AND 
                                            x.Fecha = :fecha AND 
                                            CASE
                                                WHEN x.DestinoCustom = 0
                                                THEN
                                                    :hora <= ADDTIME(x.Hora, 
                                                                CONCAT(
                                                                    HOUR(
                                                                        (SELECT k.TiempoViaje
                                                                        FROM destinos k 
                                                                        WHERE k.ID_Destino = x.LINK_Destino)
                                                                    ), ':', MINUTE(
                                                                        (SELECT k.TiempoViaje FROM destinos k WHERE k.ID_Destino = x.LINK_Destino)
                                                                    ), ':00')
                                                            ) AND
                                                    ADDTIME(:hora,
                                                                CASE
                                                                    WHEN :custom = 0
                                                                    THEN
                                                                        CONCAT(
                                                                            HOUR(
                                                                                (SELECT k.TiempoViaje 
                                                                                    FROM destinos k 
                                                                                    WHERE k.ID_Destino = :destino)
                                                                            ), ':', 
                                                                            MINUTE(
                                                                                (SELECT k.TiempoViaje 
                                                                                    FROM destinos k 
                                                                                    WHERE k.ID_Destino = :destino)
                                                                            ), ':00')
                                                                    ELSE
                                                                        '08:00:00'
                                                                END
                                                    ) >= x.Hora
                                                ELSE
                                                    :hora <= ADDTIME(x.Hora, 
                                                                CONCAT(
                                                                    HOUR(
                                                                        (SELECT k.TiempoViaje
                                                                        FROM destinos k 
                                                                        WHERE k.ID_Destino = x.LINK_Destino)
                                                                    ), ':', MINUTE(
                                                                        (SELECT k.TiempoViaje FROM destinos k WHERE k.ID_Destino = x.LINK_Destino)
                                                                    ), ':00')
                                                            ) AND
                                                    ADDTIME(:hora, '08:00:00') >= x.Hora
                                            END
                                    ) = 0";
                                    $STMT_HayAmbulancia = $pdo->prepare($SQL_AmbulanciaDisponible);
                                    $STMT_HayAmbulancia->execute([':fecha' => $_POST['fecha'], ':hora' => $_POST['salida'], ':destino' => $_POST['destino'], ':custom' => $_POST['custom']]);
                                    if ($STMT_HayAmbulancia->rowCount() > 0) {
                                        $DataAmbulancia = $STMT_HayAmbulancia->fetch(PDO::FETCH_ASSOC);
        
                                        $SQL_Registrar = 
                                            "INSERT INTO `agenda_traslados`
                                                (`CodigoTraslado`, `Paciente`, `Telefono`, `Correo`, `FechaNacimiento`, `Calle`, `Numero`, `Colonia`, `Municipio`, `DestinoCustom`, `LINK_Destino`, `LugarCustom`, `LINK_Ambulancia`, `Fecha`, `Hora`, `Observaciones`, `Carnet`) 
                                            VALUES 
                                                (:codigo, :paciente, :telefono, :correo, :nacimiento, :calle, :numero, :colonia, :comunidad, :custom, :destino, :lugar, :ambulancia, :fecha, :hora, :observaciones, :carnet)";
                                        $STMT_RegistrarCita = $pdo->prepare($SQL_Registrar);
                                        $STMT_RegistrarCita->execute([':codigo' => $Codigo, ':paciente' => $_POST['nombre'], ':telefono' => $_POST['telefono'], ':correo' => $_POST['correo'], ':nacimiento' => $_POST['fecha_nacimiento'], ':calle' => $_POST['calle'], ':numero' => $_POST['numero'], ':colonia' => $_POST['colonia'], ':comunidad' => $_POST['comunidad'], ':custom' => $_POST['custom'], ':destino' => $_POST['destino'], ':lugar' => $_POST['lugar'], ':ambulancia' => $DataAmbulancia['IDAmbulancia'], ':fecha' => $_POST['fecha'], ':hora' => $_POST['salida'], ':observaciones' => $_POST['observaciones'], ':carnet' => $NewFileName]);
                                        if ($STMT_RegistrarCita->rowCount() == 1) {
                                            $Response = BuildResponse(200, 'Cita registrada, código de cita: ' . $Codigo);
                                        } else {
                                            $Response = BuildResponse(500, 'No se ha registrado la cita, intente nuévamente.');
                                            unlink($target_path);
                                        }
                                    } else {
                                        $Response = BuildResponse(400, 'No hay ambulancias disponibles para la hora elegida en este día, intenta elegir una hora distinta.');
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
                        $Response = BuildResponse(400, ERR_REGEX);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_DATOS_FALTANTES);
                }
                break;
            case 'autorizar-cita':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $ObtenerCarnet = "UPDATE `dif_san_jose_dev`.`agenda_traslados` SET `Estatus` = 'AUTORIZADO' WHERE (`IDRegistro` = :id);";
                            $STMTCarnet = $pdo->prepare($ObtenerCarnet);
                            $STMTCarnet->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Autorizada correctamente');
                            } else {
                                $Response = BuildResponse(400, 'No se pudo autorizar la cita');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-cita':
                if (isset($_POST['ID']) && isset($_POST['nombre']) && isset($_POST['telefono']) && isset($_POST['correo']) && isset($_POST['fecha_nacimiento']) && isset($_POST['calle']) && isset($_POST['numero']) && isset($_POST['colonia']) && isset($_POST['comunidad']) && isset($_POST['custom']) && isset($_POST['destino']) && isset($_POST['lugar']) && isset($_POST['fecha']) && isset($_POST['salida']) && isset($_POST['observaciones']) && isset($_POST['cancelado'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['telefono'], 10, 15, false) && validInput($_POST['correo'], 1, 60, false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha_nacimiento']) && validInput($_POST['calle'], 1, 60, false) && validInput($_POST['numero'], 1, 15, false) && validInput($_POST['colonia'], 1, 60, false) && validInput($_POST['comunidad'], 1, 60, false) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_POST['fecha']) && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $_POST['salida']) && validInput($_POST['observaciones'], 0, 250, true) && preg_match('/^[0-1]$/', $_POST['custom']) && ($_POST['custom'] == 1 ? validInput($_POST['lugar'], 1, 350, false) : validInput($_POST['lugar'], 0, 350, false)) && preg_match('/^[0-1]$/', $_POST['cancelado']) ) {
                        if ($isDBConnected) {
                            try {
                                $SQL_AmbulanciaDisponible = 
                                "SELECT 
                                    a.IDAmbulancia, 
                                    a.NumeroAmbulancia 
                                FROM ambulancias a                                 
                                WHERE(
                                    SELECT 
                                        COUNT(x.IDRegistro) 
                                    FROM agenda_traslados x 
                                    INNER JOIN destinos d ON d.ID_Destino = x.LINK_Destino 
                                    WHERE 
                                        a.IDAmbulancia = x.LINK_Ambulancia AND 
                                        x.Fecha = :fecha AND 
                                        CASE
                                            WHEN x.DestinoCustom = 0
                                            THEN
                                                :hora <= ADDTIME(x.Hora, 
                                                            CONCAT(
                                                                HOUR(
                                                                    (SELECT k.TiempoViaje
                                                                    FROM destinos k 
                                                                    WHERE k.ID_Destino = x.LINK_Destino)
                                                                ), ':', MINUTE(
                                                                    (SELECT k.TiempoViaje FROM destinos k WHERE k.ID_Destino = x.LINK_Destino)
                                                                ), ':00')
                                                        ) AND
                                                ADDTIME(:hora,
                                                            CASE
                                                                WHEN :custom = 0
                                                                THEN
                                                                    CONCAT(
                                                                        HOUR(
                                                                            (SELECT k.TiempoViaje 
                                                                                FROM destinos k 
                                                                                WHERE k.ID_Destino = :destino)
                                                                        ), ':', 
                                                                        MINUTE(
                                                                            (SELECT k.TiempoViaje 
                                                                                FROM destinos k 
                                                                                WHERE k.ID_Destino = :destino)
                                                                        ), ':00')
                                                                ELSE
                                                                    '08:00:00'
                                                            END
                                                ) >= x.Hora
                                            ELSE
                                                :hora <= ADDTIME(x.Hora, 
                                                            CONCAT(
                                                                HOUR(
                                                                    (SELECT k.TiempoViaje
                                                                    FROM destinos k 
                                                                    WHERE k.ID_Destino = x.LINK_Destino)
                                                                ), ':', MINUTE(
                                                                    (SELECT k.TiempoViaje FROM destinos k WHERE k.ID_Destino = x.LINK_Destino)
                                                                ), ':00')
                                                        ) AND
                                                ADDTIME(:hora, '08:00:00') >= x.Hora
                                        END
                                ) = 0";
                                $STMT_HayAmbulancia = $pdo->prepare($SQL_AmbulanciaDisponible);
                                $STMT_HayAmbulancia->execute([':fecha' => $_POST['fecha'], ':hora' => $_POST['salida'], ':destino' => $_POST['destino'], ':custom' => $_POST['custom']]);
                                if ($STMT_HayAmbulancia->rowCount() > 0) {
                                    $DataAmbulancia = $STMT_HayAmbulancia->fetch(PDO::FETCH_ASSOC);
    
                                    $SQL_Actualizar = 
                                        "UPDATE `agenda_traslados` SET `Paciente`= :paciente,`Telefono`= :telefono,`Correo`= :correo,`FechaNacimiento`=:nacimiento,`Calle`= :calle,`Numero`= :numero,`Colonia`= :colonia,`Municipio`= :comunidad,`DestinoCustom`= :custom,`LINK_Destino`= :destino,`LugarCustom`= :lugar, `LINK_Ambulancia`= :ambulancia,`Fecha`= :fecha,`Hora`= :hora,`Observaciones`= :observaciones,`Cancelado`= :cancelado WHERE `IDRegistro` = :id";
                                    $STMT_EditarCita = $pdo->prepare($SQL_Actualizar);
                                    if ($STMT_EditarCita->execute([':paciente' => $_POST['nombre'], ':telefono' => $_POST['telefono'], ':correo' => $_POST['correo'], ':nacimiento' => $_POST['fecha_nacimiento'], ':calle' => $_POST['calle'], ':numero' => $_POST['numero'], ':colonia' => $_POST['colonia'], ':comunidad' => $_POST['comunidad'], ':custom' => $_POST['custom'], ':destino' => $_POST['destino'], ':lugar' => $_POST['lugar'], ':ambulancia' => $DataAmbulancia['IDAmbulancia'], ':fecha' => $_POST['fecha'], ':hora' => $_POST['salida'], ':observaciones' => $_POST['observaciones'], ':cancelado' => $_POST['cancelado'], ':id' => $_POST['ID']])) {
                                        $Response = BuildResponse(200, 'Cita actualizada');
                                    } else {
                                        $Response = BuildResponse(500, 'No se ha actualizado la cita.');
                                    }
                                } else {
                                    $Response = BuildResponse(400, 'No hay ambulancias disponibles para la hora elegida en este día, intenta elegir una hora distinta.');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'obtener-cita':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `agenda_traslados` WHERE `IDRegistro` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró la cita solicitada, actualize la tabla para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'obtener-cita-completa':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM agenda_traslados a INNER JOIN destinos d ON a.LINK_Destino = d.ID_Destino WHERE `IDRegistro` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró la cita solicitada, actualize la tabla para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'ambulancias-disponibles':
                if ($isDBConnected) {
                    try {
                        $Consulta = "SELECT 
                            a.IDAmbulancia, 
                            a.NumeroAmbulancia,
                            c.Nombre
                        FROM ambulancias a    
                        INNER JOIN conductores c 
                            ON a.LINK_Conductor = c.IDConductor                             
                        WHERE(
                            SELECT 
                                COUNT(x.IDRegistro) 
                            FROM agenda_traslados x 
                            INNER JOIN destinos d ON d.ID_Destino = x.LINK_Destino 
                            WHERE 
                                a.IDAmbulancia = x.LINK_Ambulancia AND 
                                x.Fecha = :fecha AND 
                                CASE
                                    WHEN x.DestinoCustom = 0
                                    THEN
                                        :hora <= ADDTIME(x.Hora, 
                                                    CONCAT(
                                                        HOUR(
                                                            (SELECT k.TiempoViaje
                                                            FROM destinos k 
                                                            WHERE k.ID_Destino = x.LINK_Destino)
                                                        ), ':', MINUTE(
                                                            (SELECT k.TiempoViaje FROM destinos k WHERE k.ID_Destino = x.LINK_Destino)
                                                        ), ':00')
                                                ) AND
                                        ADDTIME(:hora,
                                                    CASE
                                                        WHEN :custom = 0
                                                        THEN
                                                            CONCAT(
                                                                HOUR(
                                                                    (SELECT k.TiempoViaje 
                                                                        FROM destinos k 
                                                                        WHERE k.ID_Destino = :destino)
                                                                ), ':', 
                                                                MINUTE(
                                                                    (SELECT k.TiempoViaje 
                                                                        FROM destinos k 
                                                                        WHERE k.ID_Destino = :destino)
                                                                ), ':00')
                                                        ELSE
                                                            '08:00:00'
                                                    END
                                        ) >= x.Hora
                                    ELSE
                                        :hora <= ADDTIME(x.Hora, 
                                                    CONCAT(
                                                        HOUR(
                                                            (SELECT k.TiempoViaje
                                                            FROM destinos k 
                                                            WHERE k.ID_Destino = x.LINK_Destino)
                                                        ), ':', MINUTE(
                                                            (SELECT k.TiempoViaje FROM destinos k WHERE k.ID_Destino = x.LINK_Destino)
                                                        ), ':00')
                                                ) AND
                                        ADDTIME(:hora, '08:00:00') >= x.Hora
                                END
                        ) = 0";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute([':fecha' => $_POST['fecha'], ':hora' => $_POST['salida'], ':destino' => $_POST['destino'], ':custom' => $_POST['custom']]);
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'asignar-ambulancia':
                if ($isDBConnected) {
                    try {
                        $Consulta = 
                        "UPDATE `agenda_traslados` SET `LINK_Ambulancia` = :ambulancia, `Estatus` = 'AUTORIZADO' WHERE `agenda_traslados`.`CodigoTraslado` = :codigo";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute([':ambulancia' => $_POST['ambulancia'], ':codigo' => $_POST['codigo']]);
                        $Response = BuildResponse(200, 'Asignado correctamente');
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'lista-admins':
                if ($isDBConnected) {
                    try {
                        $Consulta = 
                        "SELECT * FROM `administradores`";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'registrar-admin':
                if (isset($_POST['nombre']) && isset($_POST['nocontrol']) && isset($_POST['clave']) && isset($_POST['telefono']) && isset($_POST['puesto'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['nocontrol'], 1, 15, false) && validInput($_POST['clave'], 1, 20, false) && validInput($_POST['telefono'], 1, 10, false) && validInput($_POST['puesto'], 1, 50, false)) {
                        $Consulta = "INSERT INTO `administradores`(`Nombre`, `NumControl`, `Clave`, `Telefono`, `Puesto`) VALUES (:nombre, :nocontrol, :clave, :telefono, :puesto)";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute([':nombre' => $_POST['nombre'], ':nocontrol' => $_POST['nocontrol'], ':clave' => password_hash($_POST['clave'], PASSWORD_DEFAULT), ':telefono' => $_POST['telefono'], ':puesto' => $_POST['puesto']]);
                        if ($STMT->rowCount() == 1) {
                            $Response = BuildResponse(200, 'Administrador registrado');
                        } else {
                            $Response = BuildResponse(500, 'No se ha registrado el administrador, intente nuévamente.');
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_REGEX);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_DATOS_FALTANTES);
                }
                break;
            case 'eliminar-admin':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "DELETE FROM `administradores` WHERE `IDAdmin` = :id AND `IDAdmin` != :own";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID'], ':own' => $_SESSION['admin_id']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Administrador eliminado');
                            } else {
                                $Response = BuildResponse(400, 'No se ha podido eliminar el administrador');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-admin':
                if (isset($_POST['ID']) && isset($_POST['nombre']) && isset($_POST['nocontrol']) && isset($_POST['cambio_clave']) && isset($_POST['clave']) && isset($_POST['telefono']) && isset($_POST['puesto'])) {
                    if (validInput($_POST['nombre'], 1, 100, false) && validInput($_POST['nocontrol'], 1, 15, false) && preg_match('/^[0-1]$/', $_POST['cambio_clave']) && validInput($_POST['telefono'], 1, 10, false) && validInput($_POST['puesto'], 1, 50, false)) {
                        if ($isDBConnected) {
                            try {
                                if(boolval($_POST['cambio_clave'])){
                                    $Consulta = "UPDATE `administradores` SET `Nombre`= :nombre,`NumControl`= :nocontrol,`Clave`= :clave,`Telefono`= :telefono,`Puesto`= :puesto WHERE `IDAdmin` = :id";
                                    $STMT = $pdo->prepare($Consulta);
                                    if ($STMT->execute([':nombre' => $_POST['nombre'], ':nocontrol' => $_POST['nocontrol'], ':clave' => password_hash($_POST['clave'], PASSWORD_DEFAULT), ':telefono' => $_POST['telefono'], ':puesto' => $_POST['puesto'], ':id' => $_POST['ID']])) {
                                        $Response = BuildResponse(200, 'Administrador actualizado');
                                    } else {
                                        $Response = BuildResponse(500, 'No se ha actualizado el administrador, intente nuévamente.');
                                    }
                                } else {
                                    $Consulta = "UPDATE `administradores` SET `Nombre`= :nombre,`NumControl`= :nocontrol, `Telefono`= :telefono,`Puesto`= :puesto WHERE `IDAdmin` = :id";
                                    $STMT = $pdo->prepare($Consulta);
                                    if ($STMT->execute([':nombre' => $_POST['nombre'], ':nocontrol' => $_POST['nocontrol'], ':telefono' => $_POST['telefono'], ':puesto' => $_POST['puesto'], ':id' => $_POST['ID']])) {
                                        $Response = BuildResponse(200, 'Administrador actualizado');
                                    } else {
                                        $Response = BuildResponse(500, 'No se ha actualizado el administrador, intente nuévamente.');
                                    }
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'obtener-admin':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `administradores` WHERE `IDAdmin` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró el administrador, actualize la tabla para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'lista-servicios':
                if ($isDBConnected) {
                    try {
                        $Consulta = "SELECT * FROM `servicios`";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute();
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            case 'registrar-servicio':
                if (isset($_POST['nombre']) && isset($_POST['encargado'])) {
                    if (validInput($_POST['nombre'], 1, 60, false) && validInput($_POST['encargado'], 1, 100, false)) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "INSERT INTO `servicios`(`NombreServicio`, `EncargadoServicio`) VALUES (:nombre,:encargado)";
                                $STMT = $pdo->prepare($Consulta);
                                $STMT->execute([':nombre' => $_POST['nombre'], ':encargado' => $_POST['encargado']]);
                                if ($STMT->rowCount() == 1) {
                                    $Response = BuildResponse(200, 'Servicio registrado');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido registrar el Servicio');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'eliminar-servicio':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "DELETE FROM `servicios` WHERE `IDServicio` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, 'Servicio eliminado');
                            } else {
                                $Response = BuildResponse(400, 'No se ha podido eliminar el Servicio');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'editar-servicio':
                if (isset($_POST['nombre']) && isset($_POST['encargado']) && isset($_POST['ID'])) {
                    if (validInput($_POST['nombre'], 1, 60, false) && validInput($_POST['encargado'], 1, 100, false)) {
                        if ($isDBConnected) {
                            try {
                                $Consulta = "UPDATE `servicios` SET `NombreServicio`= :nombre,`EncargadoServicio`= :encargado WHERE `IDServicio` = :id";
                                $STMT = $pdo->prepare($Consulta);
                                if ($STMT->execute([':nombre' => $_POST['nombre'], ':encargado' => $_POST['encargado'], ':id' => $_POST['ID']])) {
                                    $Response = BuildResponse(200, 'Servicio actualizado');
                                } else {
                                    $Response = BuildResponse(400, 'No se ha podido actualizar los datos de el Servicio');
                                }
                            } catch (\Throwable $th) {
                                $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
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
            case 'obtener-servicio':
                if (isset($_POST['ID'])) {
                    if ($isDBConnected) {
                        try {
                            $Consulta = "SELECT * FROM `servicios` WHERE `IDServicio` = :id";
                            $STMT = $pdo->prepare($Consulta);
                            $STMT->execute([':id' => $_POST['ID']]);
                            if ($STMT->rowCount() == 1) {
                                $Response = BuildResponse(200, $STMT->fetch(PDO::FETCH_ASSOC));
                            } else {
                                $Response = BuildResponse(400, 'No se encontró el servicio solicitado, actualize la table para comprobar si se ha eliminado o editado.');
                            }
                        } catch (\Throwable $th) {
                            $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                        }
                    } else {
                        $Response = BuildResponse(400, ERR_BD_OFF);
                    }
                } else {
                    $Response = BuildResponse(400, ERR_NO_ID);
                }
                break;
            case 'reporte-mes':
                if ($isDBConnected) {
                    try {
                        $Consulta = 
                        "SELECT DISTINCT 
                            x.IDServicio, 
                            x.EncargadoServicio, 
                            x.NombreServicio, 
                            (SELECT COUNT(k.IDSolicitud) FROM solicitudes_servicios k WHERE ( WEEK(k.Fecha) - WEEK(CONCAT(YEAR(k.Fecha), '-', MONTH(k.Fecha), '-01')) + 1) = 1 AND k.LINK_Servicio = x.IDServicio AND (MONTH(k.Fecha) = :mes) AND (YEAR(k.Fecha) = :anio)) AS 'Sem1', 
                            (SELECT COUNT(k.IDSolicitud) FROM solicitudes_servicios k WHERE ( WEEK(k.Fecha) - WEEK(CONCAT(YEAR(k.Fecha), '-', MONTH(k.Fecha), '-01')) + 1) = 2 AND k.LINK_Servicio = x.IDServicio AND (MONTH(k.Fecha) = :mes) AND (YEAR(k.Fecha) = :anio)) AS 'Sem2', 
                            (SELECT COUNT(k.IDSolicitud) FROM solicitudes_servicios k WHERE ( WEEK(k.Fecha) - WEEK(CONCAT(YEAR(k.Fecha), '-', MONTH(k.Fecha), '-01')) + 1) = 3 AND k.LINK_Servicio = x.IDServicio AND (MONTH(k.Fecha) = :mes) AND (YEAR(k.Fecha) = :anio)) AS 'Sem3', 
                            (SELECT COUNT(k.IDSolicitud) FROM solicitudes_servicios k WHERE ( WEEK(k.Fecha) - WEEK(CONCAT(YEAR(k.Fecha), '-', MONTH(k.Fecha), '-01')) + 1) = 4 AND k.LINK_Servicio = x.IDServicio AND (MONTH(k.Fecha) = :mes) AND (YEAR(k.Fecha) = :anio)) AS 'Sem4' 
                        FROM 
                            solicitudes_servicios s 
                            INNER JOIN servicios x ON x.IDServicio = s.LINK_Servicio";
                        $STMT = $pdo->prepare($Consulta);
                        $STMT->execute([':mes' => $_POST['mes'], ':anio' => $_POST['anio']]);
                        $Response['data'] = $STMT->fetchAll(PDO::FETCH_ASSOC);
                    } catch (\Throwable $th) {
                        $Response = BuildResponse(500, ERR_EXCEPCION . ($DebugEnabled ? $th->getMessage() : ''));
                    }
                } else {
                    $Response = BuildResponse(400, ERR_BD_OFF);
                }
                break;
            default:
                //SELECT a.CodigoTraslado, a.Paciente, a.Telefono, a.Correo, a.Domicilio, d.NombreLugar, x.NumeroAmbulancia, DATE_FORMAT(a.Fecha, '%d/%m/%Y') AS 'Fecha', a.Hora, a.Observaciones FROM agenda_traslados a INNER JOIN destinos d ON d.ID_Destino = a.LINK_Destino INNER JOIN ambulancias x ON x.IDAmbulancia = a.LINK_Ambulancia
                break;
        }
    } else {
        $Response = BuildResponse(400, 'Consulta inválida');
    }
} else {
    $Response = BuildResponse(400, 'Error en la solicitud');
}
header('Content-Type: application/json');
echo json_encode($Response);
