<?php
    //crear un servicio para un shortcode
    add_shortcode( "MAPTKWP", "tkwp_mapatkwp");
    //creacion de servicio en wordpress
    add_action("wp_ajax_apikeyc","tkwp_loadApiKeyC");//va a la pantalla de poner id
    add_action("wp_ajax_sendapi","tkwp_loadSendApi");//va a la pantalla de permisos
    add_action("wp_ajax_thanks","tkwp_thanksTKWP");//va a la pantalla de agradecimientos
    add_action("wp_ajax_savepermission","tkwp_guardarTodaConfiguracion");//guarda la configuracion
    add_action("wp_ajax_loadpath","tkwp_loadPath");//carga las rutas
    add_action("wp_ajax_createmap","tkwp_createMap");//crea un mapa
    add_action("wp_ajax_deletemap","tkwp_deleteMap");//borra un mapa
    add_action("wp_ajax_createposition","tkwp_createPosition");//crea una posicion
    add_action("wp_ajax_deleteposition","tkwp_deletePosition");//borra una posicion
    add_action("wp_ajax_getposition","tkwp_getPosition");//obtiene la informacion de una posicion
    add_action("wp_ajax_nopriv_getposition","tkwp_getPosition");//obtiene la informacion de una posicion
    add_action("wp_ajax_loadtracks","tkwp_loadTrackers");//servicio para cargar trackers
    add_action("wp_ajax_uploadtext","tkwp_cargarMultimediaTexto");//servicio para cargar multimedia de tipo texto
    add_action("wp_ajax_uploadphoto","tkwp_cargarFoto");//servicio para cargar fotos
    add_action("wp_ajax_uploadvideo","tkwp_cargarVideo");//servicio para cargar videos
    add_action("wp_ajax_uploadmusica","tkwp_cargarMusica");//servicio para cargar musica
    add_action("wp_ajax_uploadfile","tkwp_cargarFile");//servicio para cargar archivos
    add_action("wp_ajax_uploadurl","tkwp_cargarUrl");//servicio para cargar urls
    add_action("wp_ajax_deletemultimedia","tkwp_borrarMultimedia");//servicio para borrar un elemento multimedia
    add_action("wp_ajax_getmultimedias","tkwp_getMultimediasPosicion");//servicio para borrar un elemento multimedia
?>