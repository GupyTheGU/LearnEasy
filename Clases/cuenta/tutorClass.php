<?php
    Class tutorClass{
        public $idTutor;
        public $nombre;
        public $pApellido;
        public $sApellido;
        public $telefono;
        public $edad;
        public $correo;
        public $descripcion;
        public $idHorarioDisp;
        public $valoracionT;
        public $areas  = Array();
        public $valoraciones  = Array();
        public $precios = Array();
        public $singleArea;

        public function __construct(){
            
        }
        public function inicializar($id,$nom,$paterno,$materno="",$tel,$age,$mail,$desc="",$idHorariodisponibilidad=0,$puntos,$unicaArea=""){
            $this->idTutor = $id;
            $this->nombre = $nom;
            $this->pApellido = $paterno;
            $this->sApellido = $materno;
            $this->telefono = $tel;
            $this->edad    = $age;
            $this->correo = $mail;
            $this->descripcion = $desc;
            $this->idHorarioDisp = $idHorariodisponibilidad;
            $this->valoracionT = $puntos;
            $this->singleArea = str_replace(Array("\r\n", "\r", "\n"), "<br>", $unicaArea);
            //echo "si entra";
        }

        public function get_nombreCompleto(){
            return "{$this->nombre} {$this->pApellido} {$this->sApellido}";
        }

        public function get_valoracion(){
            $puntos = ($this->valoracionT) * 20;
            if($puntos < 10){
                $puntos = 10;
            }
            return $puntos;
        }

        public function add_areaConocimiento($idArea, $areaDesc){
            $asignatura = Array();
            array_push($asignatura,$idArea,$areaDesc);
            array_push($this->areas, $asignatura);
            //print_r($asignatura);
        }

        public function add_precio($idCosto, $desc,$monto,$tipoTutoria){
            $plan = Array();
            $descrip = str_replace(Array("\r\n", "\r", "\n"), "<br>", $desc);
            array_push($plan,$idCosto,$descrip,$monto,$tipoTutoria);
            array_push($this->precios, $plan);
            //print_r($plan);
        }

        public function get_tipoTutoria($plan){
            $variable = $plan[3];

            if(strcmp($variable,'E')==0){
                return "Extendido";
            }else{
                return "Individual";
            }
        }

    }

?>