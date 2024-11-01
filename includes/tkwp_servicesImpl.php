<?php
 
 //cargamos la pagina para introducir api key [Primera configuracion]
 function tkwp_loadApiKeyC(){
	require_once WP_PLUGIN_DIR.'/tracker-wp/configuracion/apikey.php';
	wp_die();
 }
 
//pagina conf > permisos
function tkwp_loadSendApi(){

	//validation and sanitize
	if(isset($_REQUEST['apikey']) && sanitize_key($_REQUEST['apikey'])){
		tkwp_guardarKey();
	}
	
	require_once WP_PLUGIN_DIR.'/tracker-wp/configuracion/permisos.php';
	wp_die();
 }

  //pagina conf > agradecimientos
 function tkwp_thanksTKWP(){
	
	//recibimos permisos
	tkwp_guardarPermisos();

	newConfig("configurado",true);

	require_once WP_PLUGIN_DIR.'/tracker-wp/configuracion/agradecimientos.php';
	wp_die();
 }

 //guarda la configuracion completa
 function tkwp_guardarTodaConfiguracion(){
	$guard = false;
	$guard2 = false;
	$guard = tkwp_guardarPermisos();
	$guard2 = tkwp_guardarKey();
	if($guard && $guard2){
		_e("<p style='color:green;'>" . esc_html("Se guardaron tus cambios correctamente") . "</p>" );
	}
	else{
		_e("<p style='color:red;'>" . esc_html("No se pudo guardar sus cambios") . "</p>");
	}
	wp_die();
 }

 /**
 * Cargamos las rutas para un mapa
 */
function tkwp_loadPath(){

	if(!isset($_FILES['file']['name']) && !isset($_REQUEST['IDMapa'])){
		$error->msg = "Ha fallado la carga por favor revisa el documento si esta bien formado.Puede ver mas informacion en el menu de configuracion > guia > seccion de rutas).";
		$error->status = 404;
		echo json_encode($error);
		wp_die("","",[
			"response" => "404"
		]);
	}

	$name = sanitize_file_name($_FILES['file']['name']);
	$map = sanitize_text_field($_REQUEST['IDMapa']);
	$extension = pathinfo($name)['extension'];

	if($map != null && ($extension == "csv" || $extension == "json")){
		if($extension == "csv"){
			//csv
			if(file_exists($_FILES['file']['tmp_name'])){
				$contenido = file_get_contents($_FILES['file']['tmp_name']);
				$posiciones = explode(PHP_EOL, $contenido);
				foreach( $posiciones as $value){
					$valores = explode(",", $value);
					if(count($valores) > 0){
						if($valores[0] != null){
							tkwp_newPosition($valores[0],$valores[1],$valores[2],$valores[3],$name,$map);
						}
					}
				}
			}
		}else{
			//json
			//lo pasamos a objeto
			if(file_exists($_FILES['file']['tmp_name'])){
				$contenido = file_get_contents($_FILES['file']['tmp_name']);
				$lista = json_decode($contenido);
				foreach( $lista as $posicion){
					if($posicion->nombre != null){
						tkwp_newPosition($posicion->nombre,$posicion->descripcion,$posicion->latitud,$posicion->longitud,$posicion->ruta,$map);
					}
				}
			}
		}

		$ok->msg = esc_js("Se creo la ruta correctamente");
		$ok->status = 202;
		echo json_encode($ok);
		wp_die("","",[
			"response" => "202"
		]);

	}else{
		//error
		$error->msg = "Ha fallado la carga por favor revisa el documento si esta bien formado.Puede ver mas informacion en el menu de configuracion > guia > seccion de rutas).";
		$error->status = 404;
		echo json_encode($error);
		wp_die("","",[
			"response" => "404"
		]);
	}
 }
/**
 * Creamos un mapa
 */
 function tkwp_createMap(){
	if(isset($_REQUEST['nameMap']) && !empty($_REQUEST['nameMap'])){
		require_once WP_PLUGIN_DIR."/tracker-wp/modelos/Mapa.php";
		$mapa = new Mapa();
		$mapa->setNombre(sanitize_text_field($_REQUEST['nameMap']));
		$idnuevomapa = $mapa->guardar();

		//ok
		
		$ok->msg = admin_url("admin.php?page=tracker-wp/edicion/edicionmapas.php&stc=".$idnuevomapa);
		$ok->status = 202;
		echo json_encode($ok);
		wp_die("","",[
			"response" => "202"
		]);
	}else{
		//error
		$err->msg = "Revise que como minimo el nombre del mapa tiene un caracter.";
		$err->status = 404;
		echo json_encode($err);
		wp_die("","",[
			"response" => "404"
		]);
	}
}
/**
 * Borramos un mapa
 */
function tkwp_deleteMap(){
	if(isset($_REQUEST['IDMapa']) && !empty($_REQUEST['IDMapa'])){
		require_once WP_PLUGIN_DIR."/tracker-wp/modelos/Mapa.php";
		$mapa = new Mapa();
		$mapa->recuperar(sanitize_text_field($_REQUEST['IDMapa']));
		$result = $mapa->borrar();//se borra el mapa / The map is delete.
		if($result){
			$OK->msg = admin_url('admin.php?page=tracker-wp/edicion/gestormapas.php');
			$OK->status = 202;
			echo json_encode($OK);
			wp_die("","",[
				"response" => "202"
			]);
		}
	}

	//error
	$ERROR->msg = "No existe el campo 'IDMapa' o es nulo.";
	$ERROR->status = 404;
	echo json_encode($ERROR);
	wp_die("","",[
		"response" => "404"
	]);

}

/**
 * Crea una posicion de un mapa
 */
function tkwp_createPosition(){

	$nombre = sanitize_text_field($_REQUEST['namePosition']);
	$description = sanitize_textarea_field($_REQUEST['desPosition']);
	$latitude = sanitize_text_field($_REQUEST['latPosition']);
	$longitude = sanitize_text_field($_REQUEST['lonPosition']);
	$ruta = sanitize_text_field($_REQUEST['rutPosition']);
	$mapa = sanitize_text_field($_REQUEST['IDMapa']);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);

	if(isset($mapa) && isset($longitude) && isset($latitude) && isset($nombre)){
		
		if(strlen($posicion) > 0){
			tkwp_newPositionFull($posicion,$nombre,$description,$latitude,$longitude,$ruta,$mapa);
			$OK->msg = "Se modifico correctamente";
		}else{
			tkwp_newPosition($nombre,$description,$latitude,$longitude,$ruta,$mapa);
			$OK->msg = "Se creo correctamente";
		}

		if(true){
			$OK->status = 202;
			echo json_encode($OK);
			wp_die("","",[
				"response" => "202"
			]);
		}

	}

	//error
	$ERROR->msg = "$mapa - $longitude - $latitude - $nombre";
	$ERROR->status = 404;
	echo json_encode($ERROR);
	wp_die("","",[
		"response" => "404"
	]);
}

/**
 * Borra una posicion de un mapa
 */
function tkwp_deletePosition(){

	if(isset($_REQUEST['idPosi'])  && isset($_REQUEST['IDMapa'])){
		
		require_once WP_PLUGIN_DIR."/tracker-wp/modelos/Posicion.php";
		
		$posi = new Posicion();
		$posi->recuperar(sanitize_text_field($_REQUEST['idPosi']));
		$posi->borrar();

		$resp->msg = "Borrado correcto";
		$resp->obj = $posi;
		$resp->status = 200;
		echo json_encode($resp);
		wp_die("","",[
			"response" => "200"
		]);
	}

	//error
	$ERROR->msg = "No existe el campo 'IDMapa' o es nulo.";
	$ERROR->status = 404;
	echo json_encode($ERROR);
	wp_die("","",[
		"response" => "404"
	]);
}

/**
 * Obtiene la informacion en json de una posicion
 */
function tkwp_getPosition(){
	
	if(isset($_REQUEST['idPosicion'])){
		require_once WP_PLUGIN_DIR."/tracker-wp/modelos/Posicion.php";
		$posi = new Posicion();
		$posi->recuperar(sanitize_text_field($_REQUEST['idPosicion']));
		$resp->msg = "Carga correcto";
		$resp->obj = $posi;
		$resp->status = 202;
		echo json_encode($resp);
		wp_die("","",[
			"response" => "202"
		]);

	}

	//error
	$ERROR->msg = "No existe el campo 'idPosicion' o es nulo.";
	$ERROR->status = 404;
	echo json_encode($ERROR);
	wp_die("","",[
		"response" => "404"
	]);


}

/**
 * Carga los trackers GPX o KML
 */
function tkwp_loadTrackers(){
    
	$name = sanitize_file_name($_FILES['file']['name']);
	$map = sanitize_text_field($_REQUEST['IDMapa']);
	$extension = pathinfo($name)['extension'];
	$archivo = $_FILES['file']['tmp_name'];
	if(isset($name) && isset($map) && ($extension == "gpx" || $extension == "kml") && file_exists($archivo)){

        require_once WP_PLUGIN_DIR."/tracker-wp/edicion/utils/ManagementXML.php";
        
        $manaXML = new ManagementXML();
		$xml = $manaXML->loadPath($archivo);
		$conversion = "";
		$nameTracker = "";

		if($extension == "gpx"){
			//gpx
			$nameTracker = $xml->trk->name;
			$points = $xml->trk->trkseg->trkpt;
			$primero = true;
	
			foreach( $points as $point){
				if($primero){
					$conversion .= $point['lat'].",".$point['lon'];
					$primero = false;
				}else{
					$conversion .= ":".$point['lat'].",".$point['lon'];
				}
				
			}

			tkwp_addTracker($nameTracker,$conversion,$map);
		}else{

			//kml 
			$folders = $xml->Document->Folder;
			$freePlacemarks = $xml->Document->Placemark;

			if(count($folders) > 0){
				//comprobamos los folders (agrupaciones)
				foreach ($folders as $value) {
					foreach ($value->Placemark as $place) {
						$name = $place->name;
						if($place->Point){
							$conversion = $place->Point->coordinates;
							tkwp_addPoint($name,$conversion,$map);
						}else if($place->LineString){
							$conversion = $place->LineString->coordinates;
							$parseo = preg_replace('/\s+/', '@espacio',$conversion);
							$posiciones = explode("@espacio",$parseo);
							$trackers = "";
							$primero = true;
							foreach ($posiciones as $value) {
								$posi = explode(",",$value);
								if(count($posi) >= 2){
									if($primero){
										$trackers .= $posi[1].",".$posi[0];
										$primero = false;
									}else{
										$trackers .= ":".$posi[1].",".$posi[0];
									}
								}
							}
							$conversion = $trackers;
							tkwp_addTracker($name,$conversion,$map);
						}
					}
				}
			}

			if(count($freePlacemarks) > 0){
				//comprobar los placemark (sueltos) 
				foreach ($freePlacemarks as $place) {
					$name = $place->name;
					if($place->Point){
						$conversion = $place->Point->coordinates;
						tkwp_addPoint($name,$conversion,$map);
					}else if($place->LineString){
						$conversion = $place->LineString->coordinates;
						$parseo = preg_replace('/\s+/', '@espacio',$conversion);
						$posiciones = explode("@espacio",$parseo);
						$trackers = "";
						$primero = true;
						foreach ($posiciones as $value) {
							$posi = explode(",",$value);
							if(count($posi) >= 2){
								if($primero){
									$trackers .= $posi[1].",".$posi[0];
									$primero = false;
								}else{
									$trackers .= ":".$posi[1].",".$posi[0];
								}
							}
						}
						$conversion = $trackers;
						tkwp_addTracker($name,$conversion,$map);
					}
				}
			}

		}
		

		if($xml != null){
			$ok->msg = "Se agrego los trackers correctamente.";
			$ok->status = 202;
			echo json_encode($ok);
			wp_die("","",[
				"response" => "202"
			]);
		}
	}


	//error
	$ERROR->msg = "El archivo esta corrupto o no existe.";
	$ERROR->status = 404;
	echo json_encode($ERROR);
	wp_die("","",[
		"response" => "404"
	]);
}

/**
 * Guarda un elemento multimedia
 */
function tkwp_cargarMultimediaTexto(){
	
	$tipo = sanitize_text_field($_REQUEST["type"]);
	$contenido = sanitize_text_field($_REQUEST["info"]);
	$titulo = sanitize_text_field($_REQUEST["name"]);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);

	if(isset($contenido) && isset($titulo)){
		tkwp_crearMultimedia($posicion,$titulo,5,$contenido);
		$ok->msg = "Se agrego multimedia.";
		$ok->status = 202;
		$ok->multimedias = tkwp_getAllMultimedias($posicion);
		echo json_encode($ok);
		wp_die("","",[
			"response" => "202"
		]);
	}

	$ok->msg = "Archivo demasidado grande.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "413"
	]);

}

/* Carga una foto vinculado a un mapa y posicion */
function tkwp_cargarFoto(){

	$tipo = sanitize_text_field($_REQUEST["type"]);
	$contenido = sanitize_file_name($_FILES['file']['name']);
	$titulo = sanitize_text_field($_REQUEST["name"]);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);
	$uploadFileDir = WP_PLUGIN_DIR.'/tracker-wp/files/';
	$rutaSubida =  tkwp_getNameFileFree($uploadFileDir,$contenido);

	if(file_exists($_FILES['file']['tmp_name']) && move_uploaded_file($_FILES['file']['tmp_name'], $rutaSubida)){
		
		$nombreFile = pathinfo($rutaSubida)['basename'];
		tkwp_crearMultimedia($posicion,$titulo,1, plugin_dir_url( __DIR__ ).'files/'.$nombreFile );

		if(file_exists($rutaSubida)){

			$ok->msg = "Se agrego multimedia a $rutaSubida.";
			$ok->status = 202;
			$ok->multimedias = tkwp_getAllMultimedias($posicion);
			echo json_encode($ok);
			wp_die("","",[
				"response" => "202"
			]);
		}
	}

	$ok->msg = "Archivo demasidado grande.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "413"
	]);


}

/* Carga una foto vinculado a un mapa y posicion */
function tkwp_cargarVideo(){

	$tipo = sanitize_text_field($_REQUEST["type"]);
	$contenido = sanitize_file_name($_FILES['file']['name']);
	$titulo = sanitize_text_field($_REQUEST["name"]);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);
	$uploadFileDir = WP_PLUGIN_DIR.'/tracker-wp/files/';
	$rutaSubida =  tkwp_getNameFileFree($uploadFileDir,$contenido);

	if(file_exist($_FILES['file']['tmp_name']) && move_uploaded_file($_FILES['file']['tmp_name'], $rutaSubida)){
		$nombreFile = pathinfo($rutaSubida)['basename'];
		tkwp_crearMultimedia($posicion,$titulo,2, plugin_dir_url( __DIR__ ).'files/'.$nombreFile );
		if(file_exists($rutaSubida)){
			$ok->msg = "Se agrego multimedia a $rutaSubida.";
			$ok->status = 202;
			$ok->multimedias = tkwp_getAllMultimedias($posicion);
			echo json_encode($ok);
			wp_die("","",[
				"response" => "202"
			]);
		}
	}

	$ok->msg = "Archivo demasidado grande.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "413"
	]);


}
/**
 * Carga un archivo de musica o audio
 */
function tkwp_cargarMusica(){
	$tipo = sanitize_text_field($_REQUEST["type"]);
	$contenido = sanitize_file_name($_FILES['file']['name']);
	$titulo = sanitize_text_field($_REQUEST["name"]);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);
	$uploadFileDir = WP_PLUGIN_DIR.'/tracker-wp/files/';
	$rutaSubida =  tkwp_getNameFileFree($uploadFileDir,$contenido);

	if(file_exists($_FILES['file']['tmp_name']) && move_uploaded_file($_FILES['file']['tmp_name'], $rutaSubida)){
		$nombreFile = pathinfo($rutaSubida)['basename'];
		tkwp_crearMultimedia($posicion,$titulo,3, plugin_dir_url( __DIR__ ).'files/'.$nombreFile );
		if(file_exists($rutaSubida)){
			$ok->msg = "Se agrego multimedia a $rutaSubida.";
			$ok->status = 202;
			$ok->multimedias = tkwp_getAllMultimedias($posicion);
			echo json_encode($ok);
			wp_die("","",[
				"response" => "202"
			]);
		}
	}

	$ok->msg = "Archivo demasidado grande.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "413"
	]);
}
/**
 * Carga un archivo
 */
function tkwp_cargarFile(){
	$tipo = sanitize_text_field($_REQUEST["type"]);
	$contenido = sanitize_file_name($_FILES['file']['name']);
	$titulo = sanitize_text_field($_REQUEST["name"]);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);
	$uploadFileDir = WP_PLUGIN_DIR.'/tracker-wp/files/';
	$rutaSubida =  tkwp_getNameFileFree($uploadFileDir,$contenido);

	if(file_exists($_FILES['file']['tmp_name']) && move_uploaded_file($_FILES['file']['tmp_name'], $rutaSubida)){
		$nombreFile = pathinfo($rutaSubida)['basename'];
		tkwp_crearMultimedia($posicion,$titulo,4, plugin_dir_url( __DIR__ ).'files/'.$nombreFile );
		if(file_exists($rutaSubida)){
			$ok->msg = "Se agrego multimedia a $rutaSubida.";
			$ok->status = 202;
			$ok->multimedias = tkwp_getAllMultimedias($posicion);
			echo json_encode($ok);
			wp_die("","",[
				"response" => "202"
			]);
		}

	}

	$ok->msg = "Archivo es demasidado grande.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "413"
	]);
}
/**
 * Carga una url
 */
function tkwp_cargarUrl(){
	$tipo = sanitize_text_field($_REQUEST["type"]);
	$contenido = sanitize_text_field($_REQUEST["url"]);
	$titulo = sanitize_text_field($_REQUEST["name"]);
	$posicion = sanitize_text_field($_REQUEST['idPosi']);

	if(isset($contenido) && isset($titulo)){
		tkwp_crearMultimedia($posicion,$titulo,6,$contenido);
		$ok->msg = "Se agrego multimedia.";
		$ok->status = 202;
		$ok->multimedias = tkwp_getAllMultimedias($posicion);
		echo json_encode($ok);
		wp_die("","",[
			"response" => "202"
		]);
	}

	$ok->msg = "Archivo es demasidado grande.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "413"
	]);
}

/**
 * Borra un elemento multimedia
 */
function tkwp_borrarMultimedia(){
	
	$id = sanitize_text_field($_REQUEST["idMultimedia"]);
	$posicion = sanitize_text_field($_REQUEST["idPosicion"]);
	if(isset($id) && isset($posicion)){
		require_once WP_PLUGIN_DIR."/tracker-wp/modelos/Multimedia.php";
		$multimedia = new Multimedia();
		$multimedia->recuperar($id);

		$archivo = $multimedia->valor;
		if(isset($archivo)){
			$archivo = WP_PLUGIN_DIR.'/tracker-wp/files/'.basename($archivo);
			if(file_exists($archivo)){
				unlink($archivo);
			}
		}

		$multimedia->borrar();

		$ok->msg = "Se borro multimedia.";
		$ok->status = 202;
		$ok->multimedias = tkwp_getAllMultimedias($posicion);
		echo json_encode($ok);
		wp_die("","",[
			"response" => "202"
		]);
	}

	$ok->msg = "No se encuentra multimedia a borrar.";
	$ok->status = 413;
	echo json_encode($ok);
	wp_die("","",[
		"response" => "400"
	]);

}
/**
 * devuelve los elementos multimedia de una posicion
 */
function tkwp_getMultimediasPosicion(){
	$posicion = sanitize_text_field($_REQUEST["idPosicion"]);
	if(isset($posicion) &&  !empty($posicion)){
		$ok->msg = "Devolucion multimedia de $posicion .";
		$ok->status = 202;
		$ok->multimedias = tkwp_getAllMultimedias($posicion);
		echo json_encode($ok);
		wp_die("","",[
			"response" => "202"
		]);
	}
}

//Carga de un shortcode
function tkwp_mapatkwp($atributos){
 
	//verificamos los permisos
	$permisos = tkwp_obtenerPermisosVisualizar();
	if(is_user_logged_in()){
		//comprobamos segun el usuario
		$user = wp_get_current_user();
		$roles = $user->roles[0];
		//administrator
		if($roles == 'administrator'){
			if(!($permisos[0] === "true")){
				$co = "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
				return $co;	
			}
		}else if($roles == 'editor'){
			if(!($permisos[1] === "true")){
				$co = "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
				return $co;	
			}
		}else if($roles == 'author'){
			if(!($permisos[2] === "true")){
				$co = "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
				return $co;	
			}
		}else if($roles == 'contributor'){
			if(!($permisos[3] === "true")){
				$co = "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
				return $co;	
			}
		}else if($roles == 'subscriber'){
			if(!($permisos[4] === "true")){
				$co = "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
				return $co;	
			}
		}
	}else{
		//revisamos si esta permitido los invitados o no logeados

		if(!($permisos[5] === "true")){
			$co = "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
			return $co;
		}
	}

	//verificamos el id 
	if($atributos['id'] && $atributos['id'] != null){
		require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Mapa.php';
		$mapa = new Mapa();
		$mapa = $mapa->recuperar($atributos['id']);
		$marcaTiempo =  uniqid();
		//verificamos el mapa
		if($mapa != null){
			require_once WP_PLUGIN_DIR.'/tracker-wp/'.'visualizacion/utils/loadMaps.php';
			$loader = new loadMaps();
			$loader->setIdMapa($atributos['id']);
			$apikey = tkwp_obtenerApiKey();
			$co = '';
			$co .= $loader->cargarDivMapaCL($atributos['id'],$marcaTiempo);
			wp_enqueue_script("tkwp$marcaTiempo",plugin_dir_url( __DIR__ ).'visualizacion/js/dataMaps.js');
			$seagrego = wp_add_inline_script("tkwp$marcaTiempo",$loader->getPositionsForJavascriptCL($atributos['id'],$marcaTiempo));
			$seagrego = wp_add_inline_script("tkwp$marcaTiempo",$loader->cargarCallToGoogle($atributos['id']));
		}else{
			$co = "<p><b>No existe este mapa.</b></p>";
		}
	}else{
		$co = "<p><b>El identificador del mapa no ha sido correctamente enviado.</b></p>";
	}
	
	return $co;
	
}


?>