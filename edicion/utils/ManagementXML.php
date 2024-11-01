<?php
/**
 * Esta clase se ocupa de leer y devolver un xml en formato array para su sencilla gestion.
 */
    class ManagementXML{
        
        /**
         * Contiene la instancia del archivo xml 
         */
        private $xml = null;
        /**
         * Contiene la ruta del archivo 
         * Este valor se puede definir directamente sin necesidad de llamar a funciones.
         */
        public $archivo = null;

        function ManagementXML(){

        }
        
        /**
         * Carga el archivo pasado como $path y devuelve la instancia xml
         */
        function loadPath($path){
            
            if($path != null && $path != ""){
                $this->archivo = $path;
                $contenido = file_get_contents($this->archivo);
                $this->xml = new SimpleXMLElement($contenido);
                return $this->xml;
            }

            return null;

        }

        /**
         * Carga el archivo interno y devuelve la instancia xml
         */
        function load(){
            if($this->archivo != null){
                $contenido = file_get_contents($this->archivo);
                $this->xml = new SimpleXMLElement($contenido);
                return $this->xml;
            }

            return null;
        }

        /**
         * Devuelve la instancia xml
         */
        function getXML(){
            return $this->xml;
        }

        /**
         * Define el archivo xml, para luego utilizar load();
         */
        function setArchivo($path){
            $this->archivo = $path;
        }

        /**
         * Obtienes la informacion del archivo
         */
        function getArchivo(){
            return $this->archivo;
        }

    }
?>