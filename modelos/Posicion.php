<?php
    /*
    Esta clase contiene el modelo de la tabla multimedia
    Autor: Jose V.
    */

    include_once 'interface/Base.php';
    include_once 'util/bd.php';

    class Posicion implements Base
    {
        public $id;//primary key
        public $nombre;
        public $descripcion;
        public $latitud;
        public $longitud;
        public $ruta;
        public $tracker;
        public $map_shortcode;//foreign key
        public $multimedias;//multimedias asociados a el

        function __construct() {
            $this->id = null;
            $this->nombre = "";
            $this->descripcion = "";
            $this->latitud = "";
            $this->longitud = "";
            $this->ruta = "";
            $this->tracker = "";
        }

        function recuperar($identificacion)
        {
            global $wpdb;
            $tabla = $wpdb->prefix.'tkwp_posiciones';
            $result = $wpdb->get_results( 
                "
                    SELECT * 
                    FROM $tabla
                    WHERE Id = '$identificacion'
                    LIMIT 1
                "
            );
            
            foreach ( $result as $val ) {
                $this->id = $val->Id;
                $this->nombre = $val->nombre;
                $this->descripcion = $val->descripcion;
                $this->latitud = $val->latitud;
                $this->longitud = $val->longitud;
                $this->ruta = $val->ruta;
                $this->map_shortcode = $val->Map_shortcode;
                $this->tracker = $val->tracker;
                
                //BEGIN MULTIMEDIA
                $tabla = $wpdb->prefix.'tkwp_multimedias';
                $this->multimedias = Array();//inicializamos
                $results2 = $wpdb->get_results( 
                    "
                        SELECT * 
                        FROM $tabla
                        WHERE Posiciones_Id = '$identificacion'
                    "
                );

                if(count($results2) > 0){
                    require_once "Multimedia.php";
                    foreach($results2 as $multi){
                        $newMultimedia = new Multimedia();
                        $newMultimedia->recuperar($multi->Id);
                        array_push($this->multimedias,$newMultimedia);
                    }
                }
                //END MULTIMEDIA

                return $this;
            }

            return null;
        }  

        function guardar()
        {
            global $wpdb;
            $table = $wpdb->prefix.'tkwp_posiciones';
            $data = array('Id' => $this->id, 'nombre' => $this->nombre,'descripcion' => $this->descripcion,'latitud' => $this->latitud,'longitud' => $this->longitud,'ruta' => $this->ruta,'Map_shortcode' => $this->map_shortcode,'tracker' => $this->tracker);
            $wpdb->insert($table,$data);
            $my_id = $wpdb->insert_id;
            if(! $my_id){
                //si ya existe actualizamos
                $where = array('Id' => $this->id);
                $updated = $wpdb->update( $table, $data, $where);
            }
        }

        function borrar(){

            $existe = $this->recuperar($this->id);

            if($existe != null){
                //existe se borra
                global $wpdb;
                //BEGIN MULTIMEDIA 
                if($this->multimedias != null && count($this->multimedias) > 0){
                    //existe posiciones, las recorremos utilizando borrar
                    foreach($this->multimedia as $multi){
                        $multi->borrar();
                    }
                }
                //END MULTIMEDIA
                $table = $wpdb->prefix.'tkwp_posiciones';
                $where = array('Id' => $this->id);
                $devolucion = $wpdb->delete($table,$where);
            }

            if($devolucion){
                return true;
            }
            return false;

        }
        
        function getId(){
            return $this->id;
        }

        function getNombre(){
            return $this->nombre;
        }

        function getDescripcion(){
            return $this->descripcion;
        }

        function getLongitud(){
            return $this->longitud;
        }

        function getLatitud(){
            return $this->latitud;
        }

        function getRuta(){
            return $this->ruta;
        }

        function getMapShortcode(){
            return $this->map_shortcode;
        }

        function getTracker(){
            return $this->tracker;
        }

        function getMultimedias(){
            return $this->multimedias;
        }

        function setId($newId){
            $this->id = $newId;
        }

        function setNombre($newNombre){
            $this->nombre = $newNombre;
        }

        function setDescripcion($newDescripcion){
            $this->descripcion = $newDescripcion;
        }

        function setLongitud($newLongitud){
            $this->longitud = $newLongitud;
        }

        function setLatitud($newLatitud){
            $this->latitud = $newLatitud;
        }

        function setRuta($newRuta){
            $this->ruta = $newRuta;
        }

        function setTracker($newTracker){
            $this->tracker = $newTracker;
        }

        function setMapShortcode($newShort){
            
            require_once "Mapa.php";
            if(is_object($newShort) && is_a($newShort,"Mapa")){
                $this->map_shortcode = $newShort->getShortcode();
            }else{
                $this->map_shortcode = $newShort;
            }

        }

        function toString(){
            return "[id: $this->id, nombre: $this->nombre, descripcion: $this->descripcion, latitud: $this->latitud,longitud: $this->longitud, ruta: $this->ruta, Map_shortcode: $this->map_shortcode, Trackers: $this->tracker]";
        }
    }

?>