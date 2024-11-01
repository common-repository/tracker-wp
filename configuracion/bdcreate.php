<?php

function create_database()
{
    global $table_prefix, $wpdb;

    #Check to see if the table exists already, if not, then create it
    //if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
    //{
        $tableConf ="CREATE TABLE ".$wpdb->prefix."tkwp_configuration(Campo varchar(255) NOT NULL,Valor varchar(255),PRIMARY KEY (Campo))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";
        $tableMaps = "CREATE TABLE ".$wpdb->prefix."tkwp_maps(Shortcode int NOT NULL AUTO_INCREMENT, nombre varchar(255),PRIMARY KEY (shortcode))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";
        $tablePos = "CREATE TABLE ".$wpdb->prefix."tkwp_posiciones(Id int NOT NULL AUTO_INCREMENT,Map_shortcode int NOT NULL, nombre varchar(50), descripcion varchar(255),latitud varchar(30),longitud varchar(30),ruta varchar(30),tracker MEDIUMTEXT,PRIMARY KEY (Id),FOREIGN KEY (Map_shortcode) REFERENCES ".$wpdb->prefix."tkwp_maps"."(Shortcode))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";
        $tableMulti = "CREATE TABLE ".$wpdb->prefix."tkwp_multimedias(Id int NOT NULL AUTO_INCREMENT,Posiciones_Id int NOT NULL, nombre varchar(50), tipo int,valor varchar(255),PRIMARY KEY (Id),FOREIGN KEY (Posiciones_Id) REFERENCES ".$wpdb->prefix."tkwp_posiciones"."(Id))ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        $res = dbDelta($tableConf);
        $res = dbDelta($tableMaps);
        $res = dbDelta($tablePos);
        $res = dbDelta($tableMulti);
        //$res = dbDelta($tableMulti);
    //}
    //eturn $tableConf;
}

function delete_database(){

    //drop estandar para tablas
    $delete = "DROP TABLE IF EXISTS ";
    global $table_prefix, $wpdb;

    //definimos nombres de tablas
    $tab1=$delete.$wpdb->prefix."tkwp_configuration".";";
    $tab2=$delete.$wpdb->prefix."tkwp_maps".";";
    $tab3=$delete.$wpdb->prefix."tkwp_posiciones".";";
    $tab4=$delete.$wpdb->prefix."tkwp_multimedias".";";

    //Orden inversa por relacion uno a muchos
    global $wpdb;
    $wpdb->query($tab4);//Multimedia
    $wpdb->query($tab3);//Posicion
    $wpdb->query($tab2);//Mapas
    $wpdb->query($tab1);//Configuraciones
}

function prefix_console_log_message( $message ) {

    $message = htmlspecialchars( stripslashes( $message ) );
    //Replacing Quotes, so that it does not mess up the script
    $message = str_replace( '"', "-", $message );
    $message = str_replace( "'", "-", $message );

    return "<script>console.log('{$message}')</script>";
}

?>