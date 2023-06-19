<?php

    class UsuarioDB {

        private $conexion;
        private $servidor;
        private $usuariodb;
        private $clave;
        private $basedatos;

        
        
        public function __construct() {
            $conf = parse_ini_file("conexion.ini");
            $this->servidor = $conf['servidor'];
            $this->usuariodb = $conf['usuario'];
            $this->clave = $conf['clave'];
            $this->basedatos = $conf['basedatos'];

            $this->conexion = new mysqli($this->servidor, $this->usuariodb, $this->clave, $this->basedatos);
            if($this->conexion->connect_error == true) {
                die("Error de conexion".$this->conexion->connect_error.":".$this->conexion->connect_errno);
            }
        }

        public function comprueba($usuario) {

            $exito = false;
            $login = $usuario->getNombre();
            $clave = $usuario->getClave();
            $sql = "SELECT * FROM usuarios where nombre = '$login' AND clave = '$clave'";
            $resultado = $this->conexion->query($sql);
            if($resultado->num_rows > 0) {
                $exito = true;
            }
            return $exito;
        }

        public function leerMensajes($usuario) {

            $usuario->getBuzon()->limpiar();
            $login = $usuario->getNombre();
            $sql = "select * from mensajes where destinatario='$login' or remitente='$login'";
            $resultado = $this->conexion->query($sql);
            $tupla = $resultado->fetch_assoc();
            while ($tupla != null) {
                if ($tupla["remitente"] == $login && ($tupla["estado"] == 3 || $tupla["estado"] == 2)) {
                    $mensaje = new Mensaje($tupla);
                    $usuario->getBuzon()->addMensaje($mensaje);
                    $tupla = $resultado->fetch_assoc();
                } elseif ($tupla["destinatario"] == $login && ($tupla["estado"] == 3 || $tupla["estado"] == 1)) {
                    $mensaje = new Mensaje($tupla);
                    $usuario->getBuzon()->addMensaje($mensaje);
                    $tupla = $resultado->fetch_assoc();
                } elseif ($tupla["estado"] == 0){
                    $this->borrarMensaje($tupla["id"]);
                    $tupla = $resultado->fetch_assoc();
                } else {
                    $tupla = $resultado->fetch_assoc();
                }
            }
        }

        public function leerDestinatarios($usuario) {

            $destinatarios = array();
            $login = $usuario->getNombre();
            $sql = "select * from usuarios where nombre!='$login'";
            $cursor = $this->conexion->query($sql);
            $tupla = $cursor->fetch_assoc();
            while ($tupla != null) {
                $nuevoUsuario = new Usuario($tupla);
                array_push($destinatarios, $nuevoUsuario);
                $tupla = $cursor->fetch_assoc();
            }
            return $destinatarios;

        }

        public function enviarMensaje($mensaje) {

            $r = $mensaje->getRemitente();
            $d = $mensaje->getDestinatario();
            $a = $mensaje->getAsunto();
            $c = $mensaje->getCuerpo();

            $sql = "INSERT INTO mensajes (remitente, destinatario, asunto, cuerpo) values ('$r','$d','$a','$c')";

            $exito = $this->conexion->query($sql);
            return $exito;
        }

        public function borrarMensaje($id, $user) {
            $sql_check = "select estado, remitente, destinatario from mensajes where id = '$id'";
            $check = $this->conexion->query($sql_check);
            $check_result = $check->fetch_assoc();
            $status = $check_result["estado"];

            if ($check_result["remitente"] == $user) {
                $status = $status - 2;
            } elseif ($check_result["destinatario"] == $user) {
                $status = $status - 1;
            } else {
                $exito=false;
                return $exito;
            }

            if ($status != 0) {
                $sql_cstatus = "update mensajes set estado = $status where id = '$id'";
                $exito = $this->conexion->query($sql_cstatus);
                return $exito;
            } else {
                $sql = "delete from mensajes where id='$id'";
                $exito = $this->conexion->query($sql);
                return $exito;
            }
        }

        public function cerrar() {
            $this->conexion->close();
        }
    }

?>