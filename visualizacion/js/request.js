var modalActiva = null;

/**
 * Coge el id interior del modal y carga los datos de la posicion
 * @param {*} $modal 
 */
function cargarMultimediaPosicion(modal){
    modalActiva = modal;//pone como modal activa la ultima modal
    data = new FormData();
    data.append("idPosicion",modal.idmodal);
    getRequest("getposition",data,getPosition,errorJson)
}

/**
 * Realiza una peticion get al parametro servicio, con la informacion de data.
 * Si todo va bien llama a funok 
 * Si ha ido mal llama a funerror
 * @param {*} servicio 
 * @param {*} data 
 * @param {*} funok 
 * @param {*} funerror 
 */
function getRequest(servicio,data,funok,funerror){
    
    //cogemos a donde va dirigida la peticion
    let datos = "?action="+servicio;
    
    // Display the key/value pairs
    for (var valores of data.entries()) {
        datos = datos+"&"+valores[0]+"="+valores[1];
    }

    let initData = {
        method: "get"
    };

    fetch(inyecion.url+datos,initData).then(function(response) {
        return response.json();
    }).then(function(json){
        if(json.status >= 200 && json.status < 300){
            //ok
            funok(json);
        }
        else{
            //error
            funerror(json);
        }
    });

}

/**
 * Error generico
 * @param {*} json 
 */
function errorJson(json){
    alert("Ocurrio un error: "+json.msg);
}

/**
 * Pasamos la informacion a la modal activa
 * @param {*} json 
 */
function getPosition(json){
    modal.setDatos(json.obj);
    modal.modeTwoWindows();
    modal.activar();
}