<?php
    class loadMaps{
        //id del mapa 
        private $idmapa;
        //objeto del mapa
        private $mapa;

        function __construct(){
            $this->$idmapa = null;
            $this->$mapa = null;
        }

        function setIdMapa($newIdMapa){
            $this->idmapa = $newIdMapa;
        }

        function getPositionsForJavascript(){
            /*Obtenemos todas las posiciones*/
            require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';
            
            $co = "";

            $newMapa = new Mapa();

            $del = $newMapa->recuperar($this->idmapa);

            if($del != null){

                $mapa = $newMapa;
                $posiciones = $mapa->getPosiciones();
                /*Creamos un hasmap que divida las rutas*/
                if(count($posiciones) > 0){
                    $pines = Array();//google maps marker
                    $rutas = Array();//google maps directions
                    $trakers = Array();//google maps polyline
                    //organizamos
                    foreach($posiciones as $posicion){
                        if($posicion->getRuta() && strlen($posicion->getRuta()) > 0 && $posicion->getTracker() == ""){
                            //es una ruta
                            
                            if($rutas[$posicion->getRuta()] != null){
                                //existe lo agregamos
                                array_push($rutas[$posicion->getRuta()],"[".$posicion->getLatitud().",".$posicion->getLongitud()."]");     
                            }
                            else{
                                //no existe, creamos el array y lo agregamos
                                $rutas[$posicion->getRuta()] = Array();
                                array_push($rutas[$posicion->getRuta()],"[".$posicion->getLatitud().",".$posicion->getLongitud()."]");   
                            }
                            
                        } 
                        else{

                            if($posicion->getTracker() == ""){
                                //se agrega su posicion a pines
                                array_push($pines,"[".$posicion->getLatitud().",".$posicion->getLongitud().",".$posicion->getId().",'".$posicion->getNombre()."']"); 
                            }else{
                                //se agrega su posicion a pines
                                array_push($trakers,$posicion->getTracker()); 
                            }
                                                   
                        }
                    }

                    /*Pintamos posiciones y rutas*/
                    //echo "let rutas = ".count($rutas).";";
                    $rutas = array_values($rutas);//indexa numericamente el array y los que haya dentro
                    if(count($rutas) > 0){
                        $co .= "var rutas = [";
                        for($i = 0; $i < count($rutas);$i++){
                            
                            if($i == 0){
                                $co .= "[";
                            }else{
                                $co .= ",[";
                            }
                            $ar = $rutas[$i];
                            if(count($ar) > 0){
                                for($e = 0; $e < count($ar);$e++){
                                    if($e == 0){
                                        $co .= $ar[$e];
                                    }else{
                                        $co .= ",".$ar[$e];
                                    }
                                }
                            }

                            $co .= "]";
                        }
                        $co .= "];";
                    }
                   
                    //echo "let posicionesNormales = ".count($pines).";";
                    if(count($pines) > 0){
                        $co .= "var posiciones = [";
                        for($i = 0; $i < count($pines);$i++){
                            if($i == 0){
                                $co .= $pines[$i];
                            }else{
                                $co .= ",".$pines[$i];
                            }
                        }
                        $co .= "];";
                    }

                    //Trackers
                    if(count($trakers) > 0){
                        $co .= "var trackers = [";
                        for($i = 0; $i < count($trakers);$i++){
                            $in = explode(":", $trakers[$i]);
                            if($i == 0){
                                $co .= "[";
                            }else{
                                $co .= ",[";
                            }
                            for($e = 0; $e < count($in);$e++){
                                $en = explode(",",$in[$e]);
                                if($e == 0){
                                    $co .= "{lat:".$en[0].",lng:".$en[1]."}";
                                }else{
                                    $co .= ",{lat:".$en[0].",lng:".$en[1]."}";
                                }
                            }
                            $co .= "]";

                        }
                        $co .= "];"; 
                    }

                }
            }
            return $co;
        }

        function declararServiciosRutas(){
            $co = "";
            $co .= "var image = '".plugin_dir_url( dirname( __FILE__ ) )."/img/marcador3.png';";
            $co .= "var directionsService = new google.maps.DirectionsService();";
            $co .= "var directionsRenderer = new google.maps.DirectionsRenderer();";
            $co .= "initMap();";
            return $co;
        }



        /**
         * Parte cliente js
         */
        function cargarCallToGoogle($id){
            $co .= 'iniciarMapa(mapaObj'.$id.');';	
            return $co;
        }

        /* Parte cliente html*/
        function cargarDivMapaCL($id,$idTemporal){
            $co .= '<div class="mapAlign">';
            $co .= '    <div id="TKWPmapa'.$id.$idTemporal.'" class="mapClient">';
            $co .= '    </div>';
            $co .= '</div>';
            return $co;
        }

        /**
         * Obtenemos las posiciones del mapa instanciado
         */
        function getPositionsForJavascriptCL($idmapa,$idTemporal){
            /*Obtenemos todas las posiciones*/
            require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';

            $newMapa = new Mapa();
            $co = "";
            $del = $newMapa->recuperar($this->idmapa);
            $co .= "var mapaObj$idmapa = new MapaObject();";
            $co .= "mapaObj$idmapa.id = $idmapa;";
            $co .= "mapaObj$idmapa.idRequest = 'TKWPmapa$idmapa$idTemporal';";
            

            if($del != null){

                $mapa = $newMapa;
                $posiciones = $mapa->getPosiciones();
                /*Creamos un hasmap que divida las rutas*/
                if(count($posiciones) > 0){
                    $pines = Array();//google maps marker
                    $rutas = Array();//google maps directions
                    $trakers = Array();//google maps polyline
                    //organizamos
                    foreach($posiciones as $posicion){
                        if($posicion->getRuta() && strlen($posicion->getRuta()) > 0 && $posicion->getTracker() == ""){
                            //es una ruta
                            
                            if($rutas[$posicion->getRuta()] != null){
                                //existe lo agregamos
                                array_push($rutas[$posicion->getRuta()],"[".$posicion->getLatitud().",".$posicion->getLongitud()."]");     
                            }
                            else{
                                //no existe, creamos el array y lo agregamos
                                $rutas[$posicion->getRuta()] = Array();
                                array_push($rutas[$posicion->getRuta()],"[".$posicion->getLatitud().",".$posicion->getLongitud()."]");   
                            }
                            
                        } 
                        else{
                            if($posicion->getTracker() == ""){
                                //se agrega su posicion a pines
                                array_push($pines,"[".$posicion->getLatitud().",".$posicion->getLongitud().",".$posicion->getId().",'".$posicion->getNombre()."']"); 
                            }else{
                                //se agrega su posicion a pines
                                array_push($trakers,$posicion->getTracker()); 
                            }                                                
                        }
                    }

                    /*Pintamos posiciones y rutas*/
                    //echo "let rutas = ".count($rutas).";";
                    $rutas = array_values($rutas);//indexa numericamente el array y los que haya dentro
                    if(count($rutas) > 0){
                        $co .= "mapaObj$idmapa.rutas = [";
                        for($i = 0; $i < count($rutas);$i++){
                            
                            if($i == 0){
                                $co .= "[";
                            }else{
                                $co .= ",[";
                            }
                            $ar = $rutas[$i];
                            if(count($ar) > 0){
                                for($e = 0; $e < count($ar);$e++){
                                    if($e == 0){
                                        $co .= $ar[$e];
                                    }else{
                                        $co .= ",".$ar[$e];
                                    }
                                }
                            }

                            $co .= "]";
                        }
                        $co .= "];";
                    }
                   
                    //echo "let posicionesNormales = ".count($pines).";";
                    if(count($pines) > 0){
                        $co .= "mapaObj$idmapa.posiciones = [";
                        for($i = 0; $i < count($pines);$i++){
                            if($i == 0){
                                $co .= $pines[$i];
                            }else{
                                $co .= ",".$pines[$i];
                            }
                        }
                        $co .= "];";
                    }


                    //Trackers
                    if(count($trakers) > 0){
                        $co .= "mapaObj$idmapa.trackers = [";
                        for($i = 0; $i < count($trakers);$i++){
                            $in = explode(":", $trakers[$i]);
                            if($i == 0){
                                $co .= "[";
                            }else{
                                $co .= ",[";
                            }
                            for($e = 0; $e < count($in);$e++){
                                $en = explode(",",$in[$e]);
                                if($e == 0){
                                    $co .= "{lat:".$en[0].",lng:".$en[1]."}";
                                }else{
                                    $co .= ",{lat:".$en[0].",lng:".$en[1]."}";
                                }
                            }
                            $co .= "]";

                        }
                        $co .= "];"; 
                    }

                }
            }
            $co .= "mapas.push(mapaObj$idmapa);";
            return $co;
        }
    }
?>