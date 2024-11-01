<?php

/* This function will intall the tracker-wp plugin */
function tkwp_install()
{
	require_once WP_PLUGIN_DIR."/tracker-wp/".'configuracion/bdcreate.php';
	create_database();
}

 /*This function will deactivate the tracker-wp plugin */
function tkwp_deactivation()
{
    flush_rewrite_rules();
}

/**
 * carga las traducciones del plugin
 */
function tkwp_textdomain() {
	
	$text_domain	= 'alltr';
	$path_languages = basename(dirname(__FILE__)).'/languages/';

 	load_plugin_textdomain($text_domain, false, $path_languages );
}

/**
 * Desistalacion del plugin
 */
function tkwp_desistalar(){
	require_once WP_PLUGIN_DIR."/tracker-wp/".'configuracion/bdcreate.php';
	delete_database();
}

/*it set the variable pluginsCargados to true */
function tkwp_activar(){
	$tkwp_pluginsCargados = true;
}

/**
 * Creamos los diferentes menus del plugin
*/
function tkwp_menus(){

    estaConfigurado();//actualizamos si esta configurado
    
    if($GLOBALS['tkwp_configurado']){
		add_menu_page('Tracker wp | Gestor de mapas', 'Tracker wp','manage_options',"tracker-wp/edicion/gestormapas.php",'','data:image/svg+xml;base64,'.$GLOBALS['tkwp_iconDataBase64']);
		add_submenu_page("tracker-wp/edicion/gestormapas.php",'TKWP | Gestor de mapas', 'Gestor de mapas','manage_options',"tracker-wp/edicion/gestormapas.php");
		add_submenu_page("tracker-wp/edicion/gestormapas.php",'TKWP | Configuracion', 'Configuracion', 'manage_options',"tracker-wp/configuracion/configuracioncomplete.php");
		add_submenu_page("tracker-wp/edicion/gestormapas.php",'TKWP | Guia', 'Guia y consejos', 'manage_options',"tracker-wp/edicion/guia.html");
		add_submenu_page(null,'TKWP | Configuracion2', 'Configuracion2', 'manage_options',"tracker-wp/edicion/edicionmapas.php");
		/*add_menu_page('Tracker wp | Gestor de mapas', 'Tracker wp','manage_options',"tracker-wp/edicion/gestormapas.php", null, '',null);
		add_submenu_page("tracker-wp/edicion/gestormapas.php",'TKWP | Configuracion', 'Configuracion', 'manage_options',"tracker-wp/configuracion/configuracioncomplete.php", null,2);
		add_submenu_page("tracker-wp/edicion/gestormapas.php",'Edicion de Mapa','Edicion de Mapa','manage_options',"tracker-wp/configuracion/edicionmapas.php",null,1);*/
	}else{
		add_menu_page('Tracker wp', 'Tracker wp','manage_options',"tracker-wp/configuracion/welcome.php", null, 'data:image/svg+xml;base64,'.$GLOBALS['tkwp_iconDataBase64'],null);
		add_submenu_page("tracker-wp/configuracion/welcome.php", "Configuracion", "Configuracion", 'manage_options', "tracker-wp/configuracion/apikey.php", null,1);
	}

}

/**
 * Definimos si esta configurado por primera vez
 */
function estaConfigurado(){

	if(isset($GLOBALS["tkwp_ruta"])){
		require_once $GLOBALS["tkwp_ruta"]."modelos/Configuracion.php";
	}else{
		require_once WP_PLUGIN_DIR."/tracker-wp/"."modelos/Configuracion.php";
	}
    

	$config = new Configuracion();
	if($config->recuperar("configurado")){
		$GLOBALS['tkwp_configurado'] = true;
		return true;
	}else{
		$GLOBALS['tkwp_configurado'] = false;
	}

	return false;
}

/** 
 * Cargamos css y scripts para usuarios sin logear
 */
function tkwp_loadCssClient() {

	$apikey = tkwp_obtenerApiKey();

	$stylemap = plugin_dir_url( __DIR__ ).'edicion/css/mapas.css';
	wp_enqueue_style('mapas2_css',$stylemap);

	$stylemap = plugin_dir_url( __DIR__ ).'edicion/css/allcode_modal_style.css';
	wp_enqueue_style('modal_css',$stylemap);

	$stylemap = plugin_dir_url( __DIR__ ).'visualizacion/css/modal.css';
	wp_enqueue_style('modalinterior_css',$stylemap);

	$stylemap = plugin_dir_url( __DIR__ ).'visualizacion/js/mapa.js';
	wp_enqueue_script('mapasc2_js',$stylemap);

	$stylemap = plugin_dir_url( __DIR__ ).'visualizacion/js/modal.js';
	wp_enqueue_script('modal_js',$stylemap);
	
	$stylemap = plugin_dir_url( __DIR__ ).'visualizacion/js/request.js';
	wp_enqueue_script('request_js',$stylemap);

	$stylemap = plugin_dir_url( __DIR__ ).'visualizacion/js/mapaObject.js';
	wp_enqueue_script('mapaobject_js',$stylemap);

	$stylemap = plugin_dir_url( __DIR__ ).'visualizacion/js/tkwpGoogle.js';
	wp_enqueue_script('tkwpGoogle_js',$stylemap);

	wp_enqueue_script('mapasc_js','https://maps.googleapis.com/maps/api/js?key='.$apikey);

	/*Inyectamos codigo en el js*/
	wp_localize_script( 'request_js', 'inyecion', [
		'url' => admin_url('admin-ajax.php')
	]);
	
}

/**
 * Cargamos css y scripts para usuarios con logeo
 */
function tkwp_loadCssAdmin($hook){
	//var_dump($hook);

	/*Pruebas */

	//$posi->guardar();
	
	if(!isset($GLOBALS["tkwp_ruta"])){
		return;
	}

	if($hook == "tracker-wp/edicion/edicionmapas.php" || $hook == 'tracker-wp/edicion/gestormapas.php'){
		$apikey = tkwp_obtenerApiKey();
		wp_enqueue_style('mapas_css', plugins_url('tracker-wp/edicion/css/mapas.css',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_style('modal_css', plugins_url('tracker-wp/edicion/css/modal.css',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_style('tables_css', plugins_url('tracker-wp/edicion/css/tables.css',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_style('info_css', plugins_url('tracker-wp/edicion/css/allcode_box_style.css',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_style('modal2_css', plugins_url('tracker-wp/edicion/css/allcode_modal_style.css',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_style('forms_css', plugins_url('tracker-wp/edicion/css/allcode_form_style.css',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_script('modal2_js', plugins_url('tracker-wp/edicion/js/allcode_modal_script.js',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_script('request_js', plugins_url('tracker-wp/configuracion/js/request.js',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_script('mapasc_js','https://maps.googleapis.com/maps/api/js?key='.$apikey);
		wp_enqueue_script('tkwpedit_js',plugins_url('tracker-wp/edicion/js/tkwpEdit.js',$GLOBALS["tkwp_ruta"]));
		/*Inyectamos codigo en el js*/
		wp_localize_script( 'request_js', 'inyecion', [
			'url' => admin_url('admin-ajax.php')
		]);

	}else if($hook == "tracker-wp/configuracion/welcome.php" || $hook == "tracker-wp/configuracion/apikey.php" || $hook == "tracker-wp/configuracion/configuracioncomplete.php"){
		
		/* Agregamos un script y una hoja de diseños */
		wp_enqueue_script('request_js', plugins_url('tracker-wp/configuracion/js/request.js',$GLOBALS["tkwp_ruta"]));
		wp_enqueue_style('validacion_css', plugins_url('tracker-wp/configuracion/css/validacion.css',$GLOBALS["tkwp_ruta"]));  
		/*Inyectamos codigo en el js*/
		wp_localize_script( 'request_js', 'inyecion', [
			'url' => admin_url('admin-ajax.php')
		]);

	}
	
}

?>