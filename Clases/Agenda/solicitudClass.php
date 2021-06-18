<?php

    Class SolicitudClass{
        public $idSolicitud;
        public $idEstadoSolicitud;
        public $nombreAprendiz;
        public $nombreTutor;
        public $idAprendiz;
        public $idTutor;
        public $idHorarioAprendiz;
        public $idHorarioTutor;
        public $descAsignatura;
        public $periodofecha;
        public $periodoHoraEntrada;
        public $periodoHhoraSalida;
        public $precioDesc;
        public $precioMonto;
        public $precioTipo;
        public $eventoDescripcion;
        public $comentarioTutor;
        public $dia;

        public function __construct(){
            
        }
        public function inicializar($id,$aprendiz,$tutor,$fecha,$entrada,$salida,$descSol,$asignatura,$descCosto,$monto,$tipoTutoria,$estadoSolicitud,$nombreA,$nombreT,$horarioA,$horarioT,){
            $this->idSolicitud = $id;
            $this->idAprendiz = $aprendiz;
            $this->idTutor = $tutor;
            $this->periodofecha = $fecha;
            $this->periodoHoraEntrada = $entrada;
            $this->periodoHhoraSalida = $salida;
            $this->eventoDescripcion = str_replace(Array("\r\n", "\r", "\n"), "<br>", $descSol);
            //echo($this->eventoDescripcion);
            $this->descAsignatura = str_replace(Array("\r\n", "\r", "\n"), "<br>", $asignatura);
            $this->precioDesc = str_replace(Array("\r\n", "\r", "\n"), "<br>",$descCosto);
            $this->precioMonto = $monto;
            $this->precioTipo = $tipoTutoria;
            $this->idEstadoSolicitud = $estadoSolicitud;
            $this->nombreAprendiz = $nombreA;
            $this->nombreTutor = $nombreT;
            $this->idHorarioAprendiz = $horarioA;
            $this->idHorarioTutor = $horarioT;
            $this->dia = date('w', strtotime($fecha));

            //echo "si entra";
        }

        public function get_Dia(){
            switch ($this->dia) {
                case 0:
                    return "DOMINGO";
                case 1:
                    return "LUNES";
                case 2:
                    return "MARTES";
                case 3:
                    return "MIÉRCOLES";
                case 4:
                    return "JUEVES";
                case 5:
                    return "VIERNES";
                case 6:
                    return "SÁBADO";
            }
        }

        public function get_tipoTutoria(){
            $variable = $this->precioTipo;

            if(strcmp($variable,'E')==0){
                return "Extendido";
            }else{
                return "Individual";
            }
        }
    }

?>