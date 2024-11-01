<?php

 function tkwp_guardarPermisos(){
	
	//sanitize
	newConfig("edit_admin",sanitize_text_field($_REQUEST['admin']));
	newConfig("edit_editor",sanitize_text_field($_REQUEST['editor']));
	newConfig("edit_autor",sanitize_text_field($_REQUEST['autor']));
	newConfig("edit_colaborador",sanitize_text_field($_REQUEST['colaborador']));

	newConfig("show_admin",sanitize_text_field($_REQUEST['admin2']));
	newConfig("show_editor",sanitize_text_field($_REQUEST['editor2']));
	newConfig("show_autor",sanitize_text_field($_REQUEST['autor2']));
	newConfig("show_colaborador",sanitize_text_field($_REQUEST['colaborador2']));
	newConfig("show_sus",sanitize_text_field($_REQUEST['sus2']));
	newConfig("show_invitado",sanitize_text_field($_REQUEST['inv2']));
	return true;
 }

 function tkwp_guardarKey(){
	//creamos un modelo y lo guardamos
	//validation and sanitize
	if(isset($_REQUEST['apikey'])){
		require_once  WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Configuracion.php";
		$config = new Configuracion();
		$config->setCampo("api_google_javascript");
		$config->setValor(sanitize_text_field($_REQUEST['apikey']));
		$config->guardar();
		return true;
	}
	else{
		return false;
	}
 }


  //creamos una configuracion y la guardamos o actualizamos
  function newConfig($campo,$valor){
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Configuracion.php";
	$config = new Configuracion();
	$config->setCampo($campo);
	if($valor == null){
		$config->setValor('false');
	}else{
		$config->setValor($valor);
	}
	
	$config->guardar();
  }


  function tkwp_getConfig($id){
		require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Configuracion.php";
		$config = new Configuracion();
		if($config->recuperar($id)){
			return $config->getValor();
		}else{
			return null;
		}
  }

//creamos una posicion y la guardamos o actualizamos
function tkwp_newPosition($name,$description,$latitude,$longitude,$path,$map){
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Posicion.php";
	$pos = new Posicion();
	$pos->setLatitud($latitude);
	$pos->setLongitud($longitude);
	$pos->setNombre($name);
	$pos->setDescripcion($description);
	$pos->setMapShortcode($map);
	$pos->setRuta($path);
	$pos->guardar();
}

//creamos una posicion y la guardamos o actualizamos
function tkwp_newPositionFull($id,$name,$description,$latitude,$longitude,$path,$map){
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Posicion.php";
	$pos = new Posicion();
	$pos->setId($id);
	$pos->setLatitud($latitude);
	$pos->setLongitud($longitude);
	$pos->setNombre($name);
	$pos->setDescripcion($description);
	$pos->setMapShortcode($map);
	$pos->setRuta($path);
	$pos->guardar();
}

/**
 * Permisos de visualizacion
 */
function tkwp_obtenerPermisosVisualizar(){
	$permisos = Array();
	array_push($permisos,tkwp_getConfig("show_admin"));
	array_push($permisos,tkwp_getConfig("show_editor"));
	array_push($permisos,tkwp_getConfig("show_autor"));
	array_push($permisos,tkwp_getConfig("show_colaborador"));
	array_push($permisos,tkwp_getConfig("show_sus"));
	array_push($permisos,tkwp_getConfig("show_invitado"));
	return $permisos;
}

/**
 * Permisos de ediccion
 */
function tkwp_obtenerPermisosEdicion(){
	$permisos = Array();
	array_push($permisos,tkwp_getConfig("edit_admin"));
	array_push($permisos,tkwp_getConfig("edit_editor"));
	array_push($permisos,tkwp_getConfig("edit_autor"));
	array_push($permisos,tkwp_getConfig("edit_colaborador"));
	return $permisos;
}

/**
 * Devuelve el api key
 */
function tkwp_obtenerApiKey(){
	$apikey = tkwp_getConfig("api_google_javascript");
	return $apikey;
}

/**
 * Crear un tracker
 */
function tkwp_addTracker($nameTracker,$conversion,$map){
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Posicion.php";
	$pos = new Posicion();
	$pos->setNombre($nameTracker);
	$pos->setTracker($conversion);
	$pos->setMapShortcode($map);
	$pos->guardar();
}

/**
 * Crear un tracker
 */
function tkwp_addPoint($nameTracker,$conversion,$map){
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Posicion.php";
	$pos = new Posicion();
	$pos->setNombre($nameTracker);
	$posis = explode(",",$conversion);
	$pos->setLongitud($posis[0]);
	$pos->setLatitud($posis[1]);
	$pos->setMapShortcode($map);
	$pos->guardar();
}

/* Utilidad que comprueba si existe un archivo igual en la carpeta y lo incrementa hasta dar con uno libre*/
/* version 5.2 php -> pathinfo[extension] */
function tkwp_getNameFileFree($source,$file){
	
	$contador = 0;
	$salir = true;
	$path = $source.$file;
	$nameFile = pathinfo($path)['filename'];//extraemos el nombre del archivo
	$extensionFile = pathinfo($path)['extension'];//extraemos la extension del archivo
	$newPath = "file.desc";
	
	while($salir){
		$newPath = $source.$nameFile.$contador.".".$extensionFile;
		if(!file_exists($newPath)){
			//no existe lo devolvemos
			$salir = false;
		}else{
			//existe incrementamos el contador
			$contador++;
		}
	}

	return $newPath;
}

/* Crea un elemento multimedia */
function tkwp_crearMultimedia($posicion,$nombre,$tipo,$valor){
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Multimedia.php";
	$multimedia = new Multimedia();
	$multimedia->setPosicion($posicion);//foreign key
	$multimedia->setValor($valor);//valor del elemento multimedia
	$multimedia->setNombre($nombre);//titulo o nombre del elemento
	$multimedia->setTipo($tipo);//tipo de elemento multimedia
	//finalmente guardamos la posicion en la base de datos
	$multimedia->guardar();
}

/* Obtenemos todos los elementos multimedia pertenecientes a una posicion */
function tkwp_getAllMultimedias($posicion){
	
	require_once WP_PLUGIN_DIR.'/tracker-wp/'."modelos/Posicion.php";
	
	$posi = new Posicion(); //instanciamos una posicion
	$posi->recuperar($posicion);//recuperamos de la base de datos una posicion
	$multimedias = $posi->getMultimedias();//obtenemos todos los multimedias asociados a el
	
	return $multimedias; 
}

?>