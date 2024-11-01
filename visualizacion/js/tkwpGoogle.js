class TkwpGoogle{

    /**
     * Aqui esta todo lo necesario para que un mapa de google funcione el solo
     */
    constructor(mapaObjecto){
        //We defined the properties of Google maps
        this.directionService = new google.maps.DirectionsService();
        this.directionRenderer = new google.maps.DirectionsRenderer();
        this.centerWorld = {lat:39.38350216976109 ,lng: -3.5641369023834386};
        this.map = null;
        this.init(mapaObjecto);
    }

    setCenterWorld(centro){
        if(centro != null && centro != undefined){
            this.centerWorld = centro;
        }
    }

    createMap(idCanvas){
        if(idCanvas != null && idCanvas.length > 0){
            this.map = new google.maps.Map(document.getElementById(idCanvas), {
                center: this.centerWorld,
                zoom: 8
            });
        }
    }

    init(mapaObjecto){

        if(typeof mapaObjecto.posiciones !== 'undefined'){
            if(mapaObjecto.posiciones[0][0] != undefined && mapaObjecto.posiciones[0][1]){
                this.setCenterWorld({lat:mapaObjecto.posiciones[0][0],lng:mapaObjecto.posiciones[0][1]});
            }
        }
        
        this.createMap(mapaObjecto.idRequest);

        this.directionRenderer.setMap(this.map);

        if(typeof mapaObjecto.posiciones !== 'undefined'){
                agregarPosiciones(mapaObjecto.posiciones,this.map);
        }

        if(typeof mapaObjecto.rutas !== 'undefined'){
            agregarRutas(mapaObjecto.rutas,this.map,this.directionService);
        }
        
        if(typeof mapaObjecto.trackers !== 'undefined'){
            agregarTrackers(mapaObjecto.trackers,this.map);
        }
    }

}