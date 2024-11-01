<?php
    /*
    Esta clase contiene el modelo de la tabla Mapas
    Autor: Jose V.
    */

    include_once 'interface/Base.php';
    include_once 'util/bd.php';

    class Mapa implements Base
    {
        private $shortcode;
        private $nombre;
        private $posiciones;

        function __construct() {
            $this->shortcode = null;
            $this->nombre = null;
            $this->posiciones = null;
        }

        function recuperar($identificacion)
        {
            global $wpdb;
            $tabla = $wpdb->prefix.'tkwp_maps';
            $resulto = $wpdb->get_results( 
                "
                    SELECT * 
                    FROM $tabla
                    WHERE Shortcode = '$identificacion'
                    LIMIT 1
                "
            );

            foreach ( $resulto as $val ) {
                $this->shortcode = $val->Shortcode;
                $this->nombre = $val->nombre;
               //obtener posiciones
                $tabla = $wpdb->prefix.'tkwp_posiciones';
                
                $result = $wpdb->get_results( 
                    "
                        SELECT * 
                        FROM $tabla
                        WHERE Map_shortcode = '$identificacion'
                    "
                );
                $this->posiciones = Array();
                if(count($result) > 0){
                    require_once "Posicion.php";
                    foreach ( $result as $posici ) {
                        $posi = new Posicion();
                        $posi->recuperar($posici->Id);
                        array_push($this->posiciones,$posi);
                    }
                }

                return $this;
            }

            return null;
        }  

        function guardar()
        {
            global $wpdb;
            $table = $wpdb->prefix.'tkwp_maps';
            $data = array('Shortcode' => $this->shortcode, 'nombre' => $this->nombre);
            $format = array('%s','%s');
            $wpdb->insert($table,$data,$format);
            $my_id = $wpdb->insert_id;

            if(! $my_id){
                //si ya existe actualizamos
                $where = array('Shortcode' => $this->shortcode);
                $updated = $wpdb->update( $table, $data, $where);
                $my_id = $this->shortcode;
            }

            return $my_id;
        }  

        function borrar(){

            $existe = $this->recuperar($this->shortcode);

            if($existe != null){
                //existe se borra
                global $wpdb;
                //BEGIN POSICION 
                if($this->posiciones != null && count($this->posiciones) > 0){
                    //existe posiciones, las recorremos utilizando borrar
                    foreach($this->posiciones as $posicion){
                        $posicion->borrar();
                    }
                }
                //END POSICION
                $table = $wpdb->prefix.'tkwp_maps';
                $where = array('Shortcode' => $this->shortcode);
                $devolucion = $wpdb->delete($table,$where);
               
            }

            if($devolucion){
                return true;
            }
            return false;
            
        }

        function getShortcode(){
            return $this->shortcode;
        }

        function getNombre(){
            return $this->nombre;
        }

        function getPosiciones(){
            return $this->posiciones;
        }

        function setShortcode($newShortcode){
            $this->shortcode = $newShortcode;
        }

        function setNombre($newNombre){
            $this->nombre = $newNombre;
        }

        function toString(){
            return "[id: $this->shortcode,nombre: $this->nombre]";
        }
    }

?>