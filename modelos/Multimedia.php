<?php
    /*
    Esta clase contiene el modelo de la tabla multimedia
    Autor: Jose V.
    */

    include_once 'interface/Base.php';
    include_once 'util/bd.php';

    class Multimedia implements Base
    {
        public $id;//primary key
        public $nombre;
        public $tipo;
        public $valor;
        public $posiciones_Id;//foreign key -> posicion

        function __construct() {
            $this->id = null;
            $this->nombre = null;
            $this->tipo = -1;
            $this->valor = null;
            $this->posiciones_Id = -1;
        }

        function recuperar($identificacion)
        {
            global $wpdb;
            $tabla = $wpdb->prefix.'tkwp_multimedias';
            $results = $wpdb->get_results( 
                "
                    SELECT * 
                    FROM $tabla
                    WHERE Id = '$identificacion'
                    LIMIT 1
                "
            );
            
            foreach ( $results as $multi ) {
                $this->id = $multi->Id;
                $this->nombre = $multi->nombre;
                $this->tipo = $multi->tipo;
                $this->valor = $multi->valor;
                $this->posiciones_Id = $multi->Posiciones_Id;
                return $this;
            }

            return null;
        }  

        function guardar()
        {
            global $wpdb;
            $table = $wpdb->prefix.'tkwp_multimedias';
            //setear tipos
            settype($this->tipo ,"integer");
            settype($this->posiciones_Id ,"integer");
            $data = array('Id'=> $this->id,'nombre' => $this->nombre,'tipo' => $this->tipo,'valor'=>$this->valor,'Posiciones_Id'=>$this->posiciones_Id);
            $wpdb->insert($table,$data);
            $my_id = $wpdb->insert_id;
        
            if(! $my_id){
                //si ya existe actualizamos
                $where = array('Id' => $this->id);
                $updated = $wpdb->update( $table, $data, $where);
            }else{
                //lo creo
                $this->id = $my_id;
            }
        }

        function borrar(){

            $existe = $this->recuperar($this->id);

            if($existe != null){
                //existe se borra
                global $wpdb;
                $table = $wpdb->prefix.'tkwp_multimedias';
                $where = array('Id' => $this->id);
                $devolucion = $wpdb->delete($table,$where);
            }

            if($devolucion){
                return true;
            }
            return false;

        }

        /**
         * recupera el id
         * @return id en formato int
         */
        function getId(){
            return $this->id;
        }
        /**
         * cambia el valor del id
         * @param newId id en formato int
         */
        function setId($newId){
            $this->id = $newId;
        }

        function getNombre(){
            return $this->nombre;
        }

        function setNombre($newNombre){
            $this->nombre = $newNombre;
        }

        function getTipo(){
            return $this->tipo;
        }

        function getPosicion(){
            return $this->posiciones_Id;
        }

        function setTipo($newtipo){
            $this->tipo = $newtipo;
        }

        function getValor(){
            return $this->valor;
        }

        function setValor($newValor){
            $this->valor = $newValor;
        }

        function setPosicion($nuevaPosicion)
        {
            require_once "Posicion.php";
            if(is_object($newShort) && is_a($newShort,"Posicion")){
                $this->posiciones_Id = $nuevaPosicion->getId();
            }else{
                $this->posiciones_Id = $nuevaPosicion;
            }
        }

        function toString(){
            return "[id: $this->id,tipo: $this->tipo,nombre: $this->nombre,valor: $this->valor,Posicion: $this->posiciones_Id]";
        }
    }
?>