<?php
    class CargadorMapas{
        /**
         * Constructor
         */
        function __construct() {

        }
        
        function getMapas($maxMaps){
            global $wpdb;
            //obtenemos tablas del plugin
            $tabla = $wpdb->prefix.'tkwp_maps';
            //obtenemos los mapas con un limite
            if($maxMaps == -1){
                $select = "SELECT Shortcode FROM $tabla ORDER BY Shortcode ASC";
            }else{
                $select = "SELECT Shortcode FROM $tabla ORDER BY Shortcode ASC LIMIT $maxMaps";
            }
            
            //ejecutamos la select
            $result = $wpdb->get_results($select);
            //creamos el array
            $mapas = Array();
            //recorremos los mapas y los metemos en un objeto mapa
            if(count($result) > 0){
                require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';
                foreach ( $result as $map ) {
                    $mapu = new Mapa();
                    $mapu->recuperar($map->Shortcode);
                    array_push($mapas,$mapu);
                }
            }

            return $mapas;
        }

        function getMap($from,$maxMaps){
            global $wpdb;
            //obtenemos tablas del plugin
            $tabla = $wpdb->prefix.'tkwp_maps';
            //obtenemos los mapas con un limite
            $select = "SELECT Shortcode
            FROM $tabla
            WHERE Shortcode > $from
            ORDER BY Shortcode ASC LIMIT $maxMaps";
            //ejecutamos la select
            $result = $wpdb->get_results($select);
            //creamos el array
            $mapas = Array();
            //recorremos los mapas y los metemos en un objeto mapa
            if(count($result) > 0){
                require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';
                foreach ( $result as $map ) {
                    $mapu = new Mapa();
                    $mapu->recuperar($map->Shortcode);
                    array_push($mapas,$mapu);
                }
            }

            return $mapas;
        }

        function cargarPosiciones($shortcodeMapa){
            require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';
            $mapa = new Mapa();
            $mapa->recuperar($shortcodeMapa);
            foreach ($mapa->getPosiciones() as $campo) {
                ?>
                  <div class="tkwp_fila" onclick="getPosicion('<?php echo $campo->getId();?>')">
                    <div class="tkwp_campo"><?php echo $campo->getNombre(); ?></div>
                    <div class="tkwp_campo"><?php echo $campo->getDescripcion(); ?></div>
                    <div class="tkwp_campo"><?php echo $campo->getLatitud();  ?></div>
                    <div class="tkwp_campo"><?php echo $campo->getLongitud(); ?></div>
                    <div class="tkwp_campo"><?php echo $campo->getRuta(); ?></div>
                  </div>
                <?php
            }
        }
    }
?>