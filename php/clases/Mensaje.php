<?php

class Mensaje {

    private $id;
    private $remitente;
    private $destinatario;
    private $asunto;
    private $cuerpo;

    public function __construct($datos) {
        if ($datos != null){
            $this->id=$datos["id"];
            $this->remitente = $datos["remitente"];
            $this->destinatario = $datos["destinatario"];
            $this->asunto = $datos["asunto"];
            $this->cuerpo = $datos["cuerpo"];
        }else{
            $this->id="0";
            $this->remitente = "";
            $this->destinatario = "";
            $this->asunto = "";
            $this->cuerpo = "";
        }
    }

    public function setId($i){
        $this->id = $i;
    }

    public function getId(){
        return $this->id;
    }

    public function setRemitente($r){
        $this->remitente = $r;
    }

    public function getRemitente(){
        return $this->remitente;
    }

    public function setDestinatario($d){
        $this->destinatario = $d;
    }

    public function getDestinatario(){
        return $this->destinatario;
    }

    public function setAsunto($a){
        $this->asunto = $a;
    }

    public function getAsunto(){
        return $this->asunto;
    }

    public function setCuerpo($c){
        $this->cuerpo = $c;
    }

    public function getCuerpo(){
        return $this->cuerpo;
    }

    public function toArray(){
        return (get_object_vars($this));
    }
}

?>