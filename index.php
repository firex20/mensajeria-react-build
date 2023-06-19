<?php
    require_once("php/clases/Mensaje.php");
    require_once("php/clases/Buzon.php");
    require_once("php/clases/Usuario.php");
    require_once("php/clases/UsuarioDB.php");
    require_once("php/cabeceras.php");

    $datos = json_decode(file_get_contents("php://input"), true);
    $accion = $datos['accion'];

    $usuarioDB = new UsuarioDB();

    switch ($accion) {
        case 'acceder':
            $usuario = new Usuario($datos["usuario"]);
            $exito = $usuarioDB->comprueba($usuario);
            if ($exito) {
                $usuarioDB->leerMensajes($usuario);
            }
            $respuesta = array("respuesta" => $exito, "usuario" => $usuario->toArray());
            echo json_encode($respuesta);
            break;
        case 'leerdestinatarios':
            $usuario = new Usuario($datos["usuario"]);
            $destinatarios = $usuarioDB->leerDestinatarios($usuario);
            $a = array();
            foreach ($destinatarios as $destinatario) {
                array_push($a, $destinatario->getObjectVars());
            };
            echo json_encode($a);
            break;
        case "enviarmensaje":
            $mensaje=new Mensaje($datos["mensaje"]);
            $exito=$usuarioDB->enviarMensaje($mensaje);
            $json = array("exito" => $exito);
            echo json_encode($json);
            break;
        case "leermensajes":
            $usuario = new Usuario($datos["usuario"]);
            $usuarioDB->leerMensajes($usuario);
            $a = $usuario->getBuzon()->toArray();
            echo json_encode($a);
            break;
        case "borrarmensaje":
            $id=$datos["id"];
            $exito=$usuarioDB->borrarMensaje($id);
            $json = array("exito" => $exito);
            echo json_encode($json);
            break;
        default:
            $error = array ('error' => 'error');
            echo json_encode($error);
            break;
    }
    
    $usuarioDB->cerrar();
?>