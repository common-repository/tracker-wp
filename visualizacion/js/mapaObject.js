class MapaObject{
    
    constructor(){
        /**
         * El id del mapa en la base de datos
         */
        this.id = null;
        /**
         * El id para esta session
         */
        this.idRequest = null;
        /**
         * Contiene los marcadores o posiciones
         */
        this.posiciones = [];
        /**
         * Contiene las rutas
         */
        this.rutas = [];
        /**
         * Contiene todos los recorridos
         */
        this.trackers = [];
        /**
         * Esta variable indica el tipo de mapa que es:
         * Google maps = 1
         * MapBox = 2
         * MapLayer = 3
         * etc...
         */
        this.tipo = 1;
        /**
         * Este objeto valdra para instanciar diferentes elementos del tipo de mapa 
         */
        this.service = null;
    }
    
}