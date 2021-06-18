<?php

    Class horarioClass{
        public $idCuenta;
        public $idHorario;
        public $tipoHorario;
        public $periodos  = Array();

        public function __construct(){
            
        }
        public function inicializar($id,$hora,$tipo){
            $this->idCuenta = $id;
            $this->idHorario = $hora;
            $this->tipoHorario = $hora;
            //echo "si entra";
        }

        public function add_periodo($idPeriodo,$idDia,$fecha, $entrada, $salida, $idEvento=''){
            $periodo_ = Array();
            array_push($periodo_,$idPeriodo, $idDia, $fecha, $entrada, $salida, $idEvento);
            array_push($this->periodos, $periodo_);
            //print_r($periodo_);
        }
    }

?>