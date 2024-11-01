<?php
    /*
    Esta clase contiene el modelo de la tabla Configuracion
    Autor: Jose V.
    */

    include_once 'interface/Base.php';
    include_once 'util/bd.php';

    class Configuracion implements Base
    {
        private $campo;
        private $valor;
        
        function __construct() {
            $this->campo = null;
            $this->valor = null;
        }

        /**
         * Recupera de la base de datos este objeto
         */
        function recuperar($identificacion)
        {
            global $wpdb;
            $tabla = $wpdb->prefix.'tkwp_configuration';
            $fivesdrafts = $wpdb->get_results( 
                "
                    SELECT * 
                    FROM $tabla
                    WHERE Campo = '$identificacion'
                    LIMIT 1
                "
            );
             
            foreach ( $fivesdrafts as $fivesdraft ) {
                $this->campo = $fivesdraft->Campo;
                $this->valor = $fivesdraft->Valor;
                return $this;
            }

            return null;
        }  

        /**
         * Guarda en la base de datos este objeto
         */
        function guardar()
        {
            global $wpdb;
            $table = $wpdb->prefix.'tkwp_configuration';
            $data = array('Campo' => $this->campo, 'Valor' => $this->valor);
            $format = array('%s','%s');
            $wpdb->insert($table,$data,$format);
            $my_id = $wpdb->insert_id;
            if(! $my_id){
                //si ya existe actualizamos
                $where = array('Campo' => $this->campo);
                $updated = $wpdb->update( $table, $data, $where);
            }
        }

        /**
         * Borra este objeto de la base de datos si existe
         */
        function borrar(){

            $existe = $this->recuperar($this->campo);

            if($existe != null){
                //existe se borra
                global $wpdb;
                $table = $wpdb->prefix.'tkwp_configuration';
                $where = array('Campo' => $this->campo);
                $devolucion = $wpdb->delete($table,$where);
            }

            if($devolucion){
                return true;
            }
            return false;

        }

        function getCampo(){
            return $this->campo;
        }

        function setCampo($newCampo){
            $this->campo = $newCampo;
        }

        function getValor(){
            return $this->valor;
        }

        function setValor($newValor){
            $this->valor = $newValor;
        }

        function toString(){
            return "[campo: $this->campo,valor: $this->valor]";
        }

    }

?>