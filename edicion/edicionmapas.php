<?php
function comprobarMapa(){
  require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';
  $mapa = new Mapa();
  $devolucion = $mapa->recuperar($_REQUEST['stc']);
  if($devolucion != null){
    $GLOBALS["MAPA"] = $mapa;
    return true;
  }
  return false;
}

if(!$_REQUEST['stc'] || !comprobarMapa()){
  ?>
    <p style="color:orange;font-size:24px;"><b>TKWP: </b>Este mapa no existe</p>
    <div class="tkwp_botones">
        <button class="info x" onclick="redireccionar('<?php echo admin_url('admin.php?page=tracker-wp/edicion/gestormapas.php'); ?> ')" >Ir al Gestor de Mapas</button>
    </div>
  <?php
}else{

?>
<div class="wrap">
<?php 


$maxUpload      = (ini_get('upload_max_filesize')); //get y otras
$maxPost        = (ini_get('post_max_size')); //para peticiones post


?>
  <h1 class="wp-heading-inline">Edicion de Mapa "<b><?php echo $GLOBALS['MAPA']->getNombre(); ?></b>"</h1>
  
  <div id="hori">
    <div id="izq" class="middle">
      <div id="map">
      </div>
    </div>
    <div id="der">
      <div class="tkwp_botones">
        <button class="info x" onclick="activarModal('mAddPosition')"><span style="font-size:20px;"> + </span>Agregar Posicion</button>
        <button class="x" id="addRuta" onclick="openSelectFile('upload_path')">Importar Ruta </button>
        <button class="x" id="addTrack" onclick="openSelectFile('upload_tracks')">Importar Trackers</button>
         <!--<button class="x" id="addRuta">Exportar Mapa</button> version 1.1 -->
        <button class="danger x" onclick="deleteMap('<?php echo $GLOBALS['MAPA']->getShortcode(); ?>')" >Borrar mapa</button> 
        <input type='file' style="display:none" id='upload_path' name='upload_path' accept=".csv,.json" onchange="documentWrapper(this.files,'loadpath','<?php echo $GLOBALS['MAPA']->getShortcode(); ?>')">
        <input type='file' style="display:none" id='upload_tracks' name='upload_tracks' accept=".gpx,.kml"  onchange="documentWrapper(this.files,'loadtracks','<?php echo $GLOBALS['MAPA']->getShortcode(); ?>')">
      </div>
      <div id="contenedor"></div>
      <?php 
      if(count($GLOBALS['MAPA']->getPosiciones()) > 0)
      {
      ?>
      <div class="tkwp_tabla row5">
        <div class="tkwp_encabezado">
          <div class="tkwp_campo">Nombre</div>
          <div class="tkwp_campo">Descripcion</div>
          <div class="tkwp_campo">Latitud</div>
          <div class="tkwp_campo">Longitud</div>
          <div class="tkwp_campo">Ruta</div>
        </div>
        <?php
          //cargamos la lista de mapas
          require_once WP_PLUGIN_DIR.'/tracker-wp/'.'edicion/utils/CargadorMapas.php';
          $cargador = new CargadorMapas();
          $cargador->cargarPosiciones($GLOBALS['MAPA']->getShortcode());
        ?>
      </div>
        <?php } 
        else {
          //si no hay posiciones, lo indicamos.
            ?>
            <p style="font-size:20px;"><b>No hay posiciones en este mapa.</b></p>
          <?php
        } ?>
    </div>
  </div>
  <?php
    include "modals/addPosition.php"; 
    include "modals/addMultimedia.php";
    include "modals/addArchivo.html";
    include "modals/addVideo.html";
    include "modals/addMusica.html";
    include "modals/addPhoto.html";
    include "modals/addTexto.html";
    include "modals/addUrl.html";
  ?>
</div>
<?php
  require_once WP_PLUGIN_DIR.'/tracker-wp/'.'visualizacion/utils/loadMaps.php';
  $loader = new loadMaps();
  $loader->setIdMapa($GLOBALS['MAPA']->getShortcode());
  wp_enqueue_script("mapedit_js",plugin_dir_url( __DIR__ ).'visualizacion/js/dataMaps.js');
  wp_add_inline_script("mapedit_js",$loader->getPositionsForJavascript());
  wp_add_inline_script("mapedit_js",$loader->declararServiciosRutas());
}
?>