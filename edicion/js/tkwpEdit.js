
var map;

function initMap() {

    var centerWorld = { lat: 39.38350216976109, lng: -3.5641369023834386 };
    var defaultZoom = 8;

    if (typeof posiciones !== 'undefined') {
        if(posiciones[0][0] != undefined && posiciones[0][1] != undefined){
            centerWorld = { lat: posiciones[0][0], lng: posiciones[0][1] };
            defaultZoom = 10;
        }
    } else if (typeof rutas !== 'undefined') {
        if(rutas[0][0][0] != undefined && rutas[0][0][1] != undefined){
            centerWorld = { lat: rutas[0][0][0], lng: rutas[0][0][1] };
            defaultZoom = 12;
        }
    } else if (typeof trackers !== 'undefined') {
        if(trackers[0][0] != undefined){
            centerWorld = trackers[0][0];
            defaultZoom = 16;
        }
    }

    map = new google.maps.Map(document.getElementById('map'), {
        center: centerWorld,
        zoom: defaultZoom
    });

    directionsRenderer.setMap(map);

    // Configure the click listener.
    map.addListener('click', function (mapsMouseEvent) {
        // Create a new InfoWindow.
        let cadena = mapsMouseEvent.latLng + "";
        cadena = cadena.replace("(", "");
        cadena = cadena.replace(")", "");
        let lt = cadena.split(",");
        openModalPosicion2(lt[0], lt[1])
    });

    if (typeof posiciones !== 'undefined') {
        agregarPosiciones(posiciones);
    }

    if (typeof rutas !== 'undefined') {
        agregarRutas(rutas);
    }

    if (typeof trackers !== 'undefined') {
        agregarTrackers(trackers);
    }

    map.setCenter(centerWorld);
    map.setZoom(defaultZoom);
}

function agregarPosiciones(posicionesArray) {

    for (var i = 0; i < posicionesArray.length; i++) {
        if(posicionesArray[i][0] != undefined && posicionesArray[i][1] != undefined){
            var latLng = { lat: posicionesArray[i][0], lng: posicionesArray[i][1] };
            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                title: posicionesArray[i][3],
                icon: image
            });
            
            marker.idmapa = posicionesArray[i][2];
            agregarEvento(marker, marker.idmapa);
        }
    }
}

function agregarRutas(rutasArray) {

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
    map.setFullscreen = false;
    getPosicion(idPosicion);
}


var idenPosi = "";

//define las propiedades de mapa y posicion
function obtenerMapayPosicion() {
    idenPosi = document.getElementById('idPosi').value;
}

function addMultimedia(tipo) {

    obtenerMapayPosicion();
    //no hay posicion, esta posicion no existe
    if (idenPosi == "") {
        alert("Esta posicion aun no ha sido creada por favor, cree la posicion antes de agregar elementos multimedia.")
        desactivarModal("mAddMulti");
    } else {
        activarModal("mAddMulti" + tipo);
        desactivarModal("mAddMulti");
    }

}



function agregarTrackers(arrayTrackers) {

    arrayTrackers.forEach(element => {
        createPolyline(element, map);
    });

}

function createPolyline(tracks, mapa) {
    var track = new google.maps.Polyline({
        path: tracks,
        geodesic: false,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 2
    });

    track.setMap(mapa);
}