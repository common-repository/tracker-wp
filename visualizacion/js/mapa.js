var mapas = [];
var modal = null;

function agregarPosiciones(posicionesArray,map) {
    for (var i = 0; i < posicionesArray.length; i++) {
        if(posicionesArray[i][0] != undefined && posicionesArray[i][1]){
            var latLng = { lat: posicionesArray[i][0], lng: posicionesArray[i][1] };
            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                title: posicionesArray[i][3]
            });
            marker.idmapa = posicionesArray[i][2];
            agregarEvento(marker, marker.idmapa);
        }
    }
}

function agregarRutas(rutasArray,map,directionsService) {

    //nos recorremos las rutas
    for (var i = 0; i < rutasArray.length; i++) {
        origen = null;
        destino = null;
        puntosIntermedios = [];
        //nos recorremos las posiciones
        for (var e = 0; e < rutasArray[i].length; e++) {
            if (e === 0) {
                //origen de la ruta
                origen = { lat: rutasArray[i][e][0], lng: rutasArray[i][e][1] };
            } else if ((e + 1) === rutasArray[i].length) {
                //destino final de la ruta
                destino = { lat: rutasArray[i][e][0], lng: rutasArray[i][e][1] };
                nuevoRenderer = new google.maps.DirectionsRenderer();
                nuevoRenderer.setMap(map);
                calcularDireccion(directionsService, nuevoRenderer, origen, destino, puntosIntermedios);
            } else {
                //puntos intermedios
                puntosIntermedios.push({
                    location: { lat: rutasArray[i][e][0], lng: rutasArray[i][e][1] },
                    stopover: true
                });
            }
        }
    }
}

function calcularDireccion(direction, renderer, origen, destino, waypts) {
    direction.route(
        {
            origin: origen,
            destination: destino,
            travelMode: 'DRIVING',
            waypoints: waypts
        },
        function (response, status) {
            if (status === 'OK') {
                renderer.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
}

function agregarEvento(marker, idevento) {
    marker.addListener('click', function () {
        abrirModal(idevento);
    });
}

function abrirModal(idPosicion) {
    modal = new Modal();
    modal.setId(idPosicion);
    cargarMultimediaPosicion(modal);
}

function agregarTrackers(arrayTrackers,mapa){
    
    arrayTrackers.forEach(element => {
      createPolyline(element,mapa);
    });

}

function createPolyline(tracks,mapa){
      var track = new google.maps.Polyline({
        path: tracks,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
      });
      
      track.setMap(mapa);
}
/**
 * Sirve para iniciar un mapa segun su tipo
 * @param {*} mapaObjecto  mapaObject
 */
function iniciarMapa(mapaObjecto){
    switch(mapaObjecto.tipo){
        case 1: 
        //google
        definirGoogle(mapaObjecto);
        break;
        default:
        //google
        definirGoogle(mapaObjecto);
    }
}

function definirGoogle(mapaObjecto){
    mapaObjecto.service = new TkwpGoogle(mapaObjecto);
}