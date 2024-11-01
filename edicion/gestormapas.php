<?php
//comprobamos permisos

//cargamos todos los mapas de 30 en 30 
require_once 'utils/CargadorMapas.php';
$loader = new CargadorMapas();
$MAPAS = $loader->getMapas(-1);
?>

<div class="wrap">

    <h1 class="wp-heading-inline">Gestor de Mapas</h1>
    <input type="hidden" name="clipboard" id="clipboard">
    <div id="hori">
        <div id="izq">
            <div class="tkwp_botones">
                <button class="info x" onclick="activarModal('infoMapa')">Agregar Mapa</button>
            </div>
            <h2>Mapas Creados:</h2>
            <?php
            if (count($MAPAS) > 0) {
                $contador = 0;
                ?>
                <div class="tkwp_tabla row2 adapter">
                    <div class="tkwp_encabezado">
                        <div class="tkwp_campo">Nombre</div>
                        <div class="tkwp_campo">Shortcode</div>
                    </div>
                <?php
                foreach($MAPAS as $map){
                    $id_nombre = "name".$contador;
                    $id_shortcode = "short".$contador;
                    ?>
                        <div class="tkwp_fila">
                            <div class="tkwp_campo" id="<?php echo $id_nombre; ?>" onclick="redireccionar('<?php echo admin_url("admin.php?page=tracker-wp/edicion/edicionmapas.php&stc=".$map->getShortcode()); ?>')"><?php echo $map->getNombre(); ?></div>
                            
                            <div class="tkwp_campo" id="<?php echo $id_shortcode; ?>" onclick="copyToClipboard('<?php echo $id_shortcode; ?>')"><?php echo "[MAPTKWP id=\"".$map->getShortcode()."\"]";?></div>
                        </div>
                    <?php
                    $contador++;
                }
                ?>
                </div>
                <?php
            } else {
                ?>
                <p style="font-size:20px;"><b>No hay mapas.</b></p>
              <?php
            }
            ?>
        </div>
        <div id="der" class="der_middle">
            <?php 
                $consejo = rand(1,5);
                switch($consejo){
                    case 1:
                        require_once "tips/tip1.html";
                    break;
                    case 2:
                        require_once "tips/tip2.html";
                    break;
                    case 3:
                        require_once "tips/tip3.html";
                    break;
                    case 4:
                        require_once "tips/tip4.html";
                    break;
                    case 5:
                        require_once "tips/tip5.html";
                    break;
                }
            ?>
        </div>
    </div>
</div>
<?php
    require_once "modals/infoMapa.html";
?>
