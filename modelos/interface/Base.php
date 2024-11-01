<?php

/*Contiene las funciones que deben llevar todos los modelos obligatoriamente*/

interface Base{
    /**
     * Recupera de la base de datos el objeto del modelo con la identificacion proporcionada
     */
    function recuperar($identificacion);
    /**
     * Guarda en la base de datos el modelo
     */
    function guardar();
    /**
     * Borra este objeto de la bd
     */
    function borrar();
}

?>