<?php
    Class Valoracion{
        public $idValoracion;
        public $nombre;
        public $fecha;
        public $comentario;
        public $puntuacion;

        public function __construct(){
        }

        public function inicializar($id,$nom,$dia,$coment,$puntos){
            $this->idValoracion = $id;
            $this->nombre = $nom;
            $this->fecha = $dia;
            $this->comentario = str_replace(Array("\r\n", "\r", "\n"), "<br>", $coment);
            $this->puntuacion = $puntos;
        }
    }

?>