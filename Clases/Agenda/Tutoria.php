<?php
    Class Tutoria{
        public $idTutoria;
        public $idEstadoPago;
        public $nombreAprendiz;
        public $nombreTutor;
        public $idAprendiz;
        public $idTutor;
        public $idHorarioAprendiz;
        public $idHorarioTutor;
        public $descAsignatura;
        public $periodoFecha;
        public $periodoHoraEntrada;
        public $periodoHoraSalida;
        public $precioDesc;
        public $precioMonto;
        public $precioTipo;
        public $eventoDescripcion;
        public $comentarioTutor;
        public $dia;
        public $linkConferencia;
        public $asistenciaAprendiz;
        public $asistenciaTutor;
        public $estadoTutoria;
        public $estadoCertificado;

        public function __construct(){
            
        }

        public function inicializar($id,$aprendiz,$tutor,$fecha,$entrada,$salida,$descSol,$asignatura,$descCosto,$monto,$tipoTutoria,$estadoPagado,$nombreA,$nombreT,$horarioA,$horarioT,$valoracion,$conferencia,$asisA,$asisT,$estadoTuto,$certificado){
            $this->idTutoria = $id;
            $this->idAprendiz = $aprendiz;
            $this->idTutor = $tutor;
            $this->periodoFecha = $fecha;
            $this->periodoHoraEntrada = $entrada;
            $this->periodoHoraSalida = $salida;
            $this->eventoDescripcion = str_replace(Array("\r\n", "\r", "\n"), "<br>", $descSol);
            //echo($this->eventoDescripcion);
            $this->descAsignatura = str_replace(Array("\r\n", "\r", "\n"), "<br>", $asignatura);
            $this->precioDesc = str_replace(Array("\r\n", "\r", "\n"), "<br>",$descCosto);
            $this->precioMonto = $monto;
            $this->precioTipo = $this->get_tipoTutoria($tipoTutoria);
            $this->idEstadoPago = $estadoPagado;
            $this->nombreAprendiz = $nombreA;
            $this->nombreTutor = $nombreT;
            $this->idHorarioAprendiz = $horarioA;
            $this->idHorarioTutor = $horarioT;
            $this->idValoracion = $valoracion;
            $this->linkConferencia = $conferencia;
            $this->asistenciaAprendiz = $asisA;
            $this->asistenciaTutor = $asisT;
            $this->estadoTutoria = $estadoTuto;
            $this->estadoCertificado = $certificado;
            $this->dia = $this->get_Dia(date('w', strtotime($fecha)));

            //echo "si entra";
        }

        public function get_Dia($dia){
            switch ($dia) {
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

        public function get_tipoTutoria($tipoPrecio){

            if(strcmp($tipoPrecio,'E')==0){
                return "Extendido";
            }else{
                return "Individual";
            }
        }
    }


?>