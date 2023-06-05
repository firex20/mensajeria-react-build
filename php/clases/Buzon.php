<?php

class Buzon {
    private $mensajes;

    public function __construct() {
        $this->mensajes = array();
    }

    public function getMensajes() {
        return $this->mensajes;
    }

    public function addMensaje ($m) {
        array_push($this->mensajes, $m);
    }

    public function getMensajesEntrantes ($usuario) {
        $entrantes = array();
        foreach ($this->mensajes as $mensaje){
            if ($mensaje->getDestinatario() == $usuario) {
                array_push($entrantes, $mensaje);
            }
        }
        return $entrantes;
    }

    public function getMensajesSalientes ($usuario) {
        $salientes = array();
        foreach ($this->mensajes as $mensaje){
            if ($mensaje->getRemitente() == $usuario) {
                array_push($salientes, $mensaje);
            }
        }
        return $salientes;
    }

    public function toArray () {
        $a = array();
        foreach ($this->mensajes as $mensaje) {
            array_push($a, $mensaje->toArray());
        }
        return $a;
    }

    public function limpiar() {
        unset($this->mensajes);
        $this->mensajes = array();
    }
}

?>