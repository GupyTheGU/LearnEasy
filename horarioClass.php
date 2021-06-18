<?php
    Class HorarioClass{
        public $idCuenta;
        public $idHorario;
        public $lunes = Array();
        public $martes = Array();
        public $miercoles = Array();
        public $jueves = Array();
        public $viernes = Array();
        public $sabado = Array();
        public $domingo = Array();

        public function __construct(){
            
        }

        public function inicializar($idCuenta='0',$idHorario){

        }

        public function add_periodoDia($idPeriodo, $idDia, $hInicio, $hSalida){
            $periodo = Array();
            array_push($periodo,$idPeriodo,$idDia,$hInicio,$hSalida);

            if(strcmp($idDia,'1')==0){
                array_push($this->lunes, $periodo);
            }elseif(strcmp($idDia,'2')){
                array_push($this->martes, $periodo);
            }elseif(strcmp($idDia,'3')){
                array_push($this->miercoles, $periodo);
            }elseif(strcmp($idDia,'4')){
                array_push($this->jueves, $periodo);
            }elseif(strcmp($idDia,'5')){
                array_push($this->viernes, $periodo);
            }elseif(strcmp($idDia,'6')){
                array_push($this->sabado, $periodo);
            }elseif(strcmp($idDia,'7')){
                array_push($this->domingo, $periodo);
            }
            print_r($periodo);
        }


    }
?>