<?php

    class Usuario {

        private $nombre;
        private $clave;
        private $nombreCompleto;
        private $buzon;

        public function __construct($datos) {
            $this->nombre = $datos["nombre"];
            $this->clave = $datos["clave"];
            if (isset($datos["nombreCompleto"])){
                $this->nombreCompleto = $datos["nombreCompleto"];
            } else {
                $this->nombreCompleto = "";
            }
            $this->buzon = new Buzon;
        }

        public function setNombre($n) {
            $this->nombre=$n;
        }

        public function getNombre() {
            return $this->nombre;
        }

        public function setClave($c) {
            $this->clave=$c;
        }

        public function getClave() {
            return $this->clave;
        }

        public function setNombreCompleto($nc) {
            $this->nombreCompleto=$nc;
        }

        public function getNombreCompleto() {
            return $this->nombreCompleto;
        }

        public function getBuzon() {
            return $this->buzon;
        }

        public function toArray() {
            $a = get_object_vars($this);
            $a["buzon"]=$this->buzon->toArray();
            return ($a);
        }

        public function getObjectVars() {
            return (get_object_vars($this));
        }
    }

?>