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
                $mensaje = new Mensaje($tupla);
                $usuario->getBuzon()->addMensaje($mensaje);
                $tupla = $resultado->fetch_assoc();
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

        public function borrarMensaje($id) {
            
            $sql = "delete from mensajes where id='$id'";
            $exito = $this->conexion->query($sql);
            return $exito;

        }

        public function cerrar() {
            $this->conexion->close();
        }
    }

?>