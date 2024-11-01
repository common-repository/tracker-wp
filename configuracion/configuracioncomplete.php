<?php

$permisos = tkwp_obtenerPermisosEdicion();
if(is_user_logged_in()){
    //comprobamos segun el usuario
    $user = wp_get_current_user();
    $roles = $user->roles[0];
    //administrator
    if($roles == 'editor'){
        if(!($permisos[1] === "true")){
            echo "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
            die();
        }
    }else if($roles == 'author'){
        if(!($permisos[2] === "true")){
            echo "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
            die();	
        }
    }else if($roles == 'contributor'){
        if(!($permisos[3] === "true")){
            echo "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
            die();
        }
    }
}else{
    echo "<p style='color:red;'><b>No tienes permisos para visualizar el mapa.</b></p>";
    die();
}
?>
<h1>Configuracion de Tracker WP</h1>
<br>
<form id="formu1" onsubmit="requestGenerica('formu1')" method="post">
<h2>Api Key Google Maps</h2>

<p>Si aun no ha puesto su api key de google maps javascript deberia hacerlo antes de entrar en produccion.</p>
    <?php
        require_once WP_PLUGIN_DIR.'/tracker-wp/'.'modelos/Configuracion.php';

        function obtenerAPIKEY2(){
            $apk = new Configuracion();
            $apk->recuperar("api_google_javascript");
            if($apk != null){
                return $apk->getValor();
            }else{
                return "";
            }
        }

        $apikey = obtenerAPIKEY2();

    ?>
    <label for="apikey">Api Key:  </label><input type="text" name="apikey" id="apikey" size="60" value="<?php echo $apikey; ?>">
    <br>
<h2>Permisos</h2>
<p>Los permisos marcados son los que estan actualmente en proceso, si quiere poner o quitar alguno pulse su cuadrado. </p>
<?php
    function cargarValor($campo){
        $valor = "";
        $apk = new Configuracion();
        $apk->recuperar("$campo");
        $valor = $apk->getValor();
        return $valor;
    }

    function check($val){
        if($val != null && $val != null && $val == "true"){
            echo "checked";
        }
    }
?>
<h4>Permisos de edicion</h4>
    <input type="checkbox" id="admin" name="admin" value="true" <?php check(cargarValor("edit_admin")); ?>>
    <label for="admin">Administradores</label><br>
    
    <input type="checkbox" id="editor" name="editor" value="true" <?php check(cargarValor("edit_editor")); ?>>
    <label for="editor">Editores</label><br>
    
    <input type="checkbox" id="autor" name="autor" value="true" <?php check(cargarValor("edit_autor")); ?>>
    <label for="autor">Autores</label><br>
   
    <input type="checkbox" id="colaborador" name="colaborador" value="true" <?php check(cargarValor("edit_colaborador")); ?>>
    <label for="colaborador">Colaboradores</label><br>
<br>
<h4>Permisos de visualizacion</h4>
<input type="checkbox" id="admin2" name="admin2" value="true" <?php check(cargarValor("show_admin")); ?>>
    <label for="admin2">Administradores</label><br>
    
    <input type="checkbox" id="editor2" name="editor2" value="true" <?php check(cargarValor("show_editor")); ?>>
    <label for="editor2">Editores</label><br>
    
    <input type="checkbox" id="autor2" name="autor2" value="true" <?php check(cargarValor("show_autor")); ?>>
    <label for="autor2">Autores</label><br>
   
    <input type="checkbox" id="colaborador2" name="colaborador2" value="true" <?php check(cargarValor("show_colaborador")); ?>>
    <label for="colaborador2">Colaboradores</label><br>

    <input type="checkbox" id="sus2" name="sus2" value="true" <?php check(cargarValor("show_sus")); ?>>
    <label for="sus2">Suscriptores</label><br>

    <input type="checkbox" id="inv2" name="inv2" value="true" <?php check(cargarValor("show_invitado")); ?>>
    <label for="inv2">Invitados (No logeados)</label><br><br>
<br>
<input type="hidden" name="action" id="action" value="savepermission">
<input  type="submit" onclick="procesando()" name="submit" id="submit" class="button button-primary" value="Guardar Cambios">
</form>
<div id="contenedor">

</div>