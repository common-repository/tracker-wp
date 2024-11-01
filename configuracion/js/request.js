window.addEventListener("load", function(){
    cargarHttpRequest();

    document.addEventListener("submit", function(event){
        event.preventDefault()
      });
});

var xmlhttp;

function cargarHttpRequest(){
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xmlhttp = new XMLHttpRequest();
      } else {
        // code for old IE browsers
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
     } 
}

function loadApis(){
    xmlhttp.onreadystatechange = carga;
    xmlhttp.open("GET", inyecion.url+"?action=apikeyc", true);
    xmlhttp.send();
}

function carga() {
    if (this.readyState == 4 && this.status == 200) {
        alert("Cargo correctamente"+this.responseText);
    }
};

function getLatestLoad(){
    fetch(inyecion.url+"?action=apikeyc").then(function(response) {
        return getTextFromStream(response.body);
    }).then(function(response) {
        document.getElementById("contenedor").innerHTML = response; 
    });

}

//funcion a modificar
async function getTextFromStream(readableStream) {
    let reader = readableStream.getReader();
    let utf8Decoder = new TextDecoder();
    let nextChunk;
    
    let resultStr = '';
    
    while (!(nextChunk = await reader.read()).done) {
        let partialData = nextChunk.value;
        resultStr += utf8Decoder.decode(partialData);
    }
    
    return resultStr;
}

function responseX(status, response){
    if(status == 200){
        //ok
        responseXOk(response);
    }else{
        //error
        responseXError(response);
    }
}

function responseXOk(response){
    alert(response);
}

function responseXError(response){

}

function comprobarApi(){
    let element = document.getElementById("apikey");
    let api = element.value;
    let tam = api.length; 
    if(tam > 9){
        changeClass(element,"correctInput");
        return true;
    }else if(tam === 0)
    {
        changeClass(element,"normalInput");
        return false;
    }else{
        changeClass(element,"incorrectInput");
        return false; 
    }

}

/**
 * Permite pasarle la url completa y donde se va a sustituir el contenido (Peticion get - Recibe html)
 * @param {*} url 
 * @param {*} contenedor 
 */
function getLatestLoadAuto(url,contenedor){
    fetch(url).then(function(response) {
        return getTextFromStream(response.body);
    }).then(function(response) {
        document.getElementById(contenedor).innerHTML = response; 
    });

}

function postLatestLoadAuto(contenedor,formulario){
   
    let formData = new FormData(formulario);
    
    let initData = {
        method: "post",
        body: formData
    };

    fetch(inyecion.url,initData).then(function(response) {
        return getTextFromStream(response.body);
    }).then(function(response) {
        document.getElementById(contenedor).innerHTML = response; 
    });
}
/**
 * sube un archivo al servicio que haya dentro del data
 * @param {*} contenedor donde se sustituye la informacion
 * @param {*} data los datos a enviar
 */
function uploadFile(contenedor,data){

    let initData = {
        method: "post",
        body: data
    };

    fetch(inyecion.url,initData).then(function(response) {
        return getTextFromStream(response.body);
    }).then(function(response) {
        document.getElementById(contenedor).innerHTML = response; 
    });
}

/*Envia la informacion del formulario configuracion > apikey (paso 2)*/
function requestGenerica(formu)
{
    let elemento = document.getElementById(formu);

    if(metodo(elemento.method)){
        postLatestLoadAuto("contenedor",elemento);
    }else{
        getLatestLoadAuto(inyecion.url+elemento.action.value,"contenedor");
    }
}

/** detecta si el tipo es post y devuelve true **/
function metodo(metod){
    if(metod === "post"){
        return true;
    }else{
        return false;
    }
}

/* Cambia los estilos */
async function changeClass(element,clase){
    if(element){
        element.className = clase;
    }
}

/**
 * Redirecciona a la ruta que le pases
 * @param {*} ruta 
 */
function redireccionar(ruta)
{
    window.location=ruta;
}

/**
 * Redirecciona a la ruta que le pases
 * @param {*} ruta 
 */
function redireccionarRe(json)
{
    window.location=json.msg;
}

/**
 * Pone el contenedor de carga en proceso procesando.
 */
function procesando(){
    document.getElementById("contenedor").innerHTML = "<p style='color:blue;'>Procesando... Espere por favor</p>";
}

/**
 * Encapsula el archivo y lo envia
 */
function documentWrapper($listFiles,$servicio,$idMapa){
    var data = new FormData();
    data.append('file', $listFiles[0]);
    data.append('action', $servicio);
    data.append('IDMapa',$idMapa);
    postRequest(data,reload,errorJson);
}

/**
 * Encapsula el archivo y lo envia
 */
function deleteMap($idMapa){
    if(confirm("¿Estas seguro que quieres borrar el mapa?")){
        var data = new FormData();
        data.append('IDMapa',$idMapa);
        data.append('action','deletemap');
        normalRequest(data,redireccionar,error);
    }
}
/**
 * Abre la ventana de explorar archivos
 * @param {*} $ids 
 */
function openSelectFile($ids){
    var input= document.getElementById($ids);
    if(input){
        input.click();
    }
}
/**
 * Recarga la pagina actual
 */
function reload(json){
    location.reload();
}

/**
 *
 */
function copyToClipboard($id){
  /* Get the text field */
  var origin = document.getElementById($id);
  var copyText = document.getElementById("clipboard");
  copyText.value = origin.innerText;
  /* Select the text field */
  copyText.type = "text";
  copyText.select();
  //copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  /* Copy the text inside the text field */
  document.execCommand("copy");
  copyText.type = "hidden";
  /* Alert the copied text */
  alert("Copiado el shortcode: " + copyText.value); 
}

function error(mensaje){
    alert("Ocurrio un error: "+mensaje+".");
}

/**
 * sube un archivo al servicio que haya dentro del data
 * @param {*} data los datos a enviar
 */
function normalRequest(data,funcionOk,funcionError){

    let initData = {
        method: "post",
        body: data
    };

    fetch(inyecion.url,initData).then(function(response) {


        return response.json();
    }).then(function(json){
        if(json.status >= 200 && json.status < 300){
            //ok
            funcionOk(json.msg);
        }
        else{
            //error
            funcionError(json.msg);
        }
    });
}


/**
 * RESPUESTA JSON
 * Realiza una peticion get con una devolucion en json ejecutando la funcion funok con el contenido, si no ejecuta la funcion de error con el contenido
 * V1.0.0
 * @param {*} servicio donde va dirigida  
 * @param {*} data un formdata con la informacion
 * @param {*} funok donde va dirigida  
 * @param {*} funerror un formdata con la informacion
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
            //alert("Ocurrio un error al recuperar la informacion: "+json.msg);
        }
    });

}

/**
 * RESPUESTA JSON
 * Realiza una peticion get con una devolucion en json ejecutando la funcion funok con el contenido, si no ejecuta la funcion de error con el contenido
 * V1.0.0
 * @param {*} servicio donde va dirigida  
 * @param {*} data un formdata con la informacion
 * @param {*} funok donde va dirigida  
 * @param {*} funerror un formdata con la informacion
 */
function postRequest(data,funok,funerror){

    let initData = {
        method: "post",
        body: data
    };

    fetch(inyecion.url,initData).then(function(response) {
        return response.json();
    }).then(function(json){
        if(json.status >= 200 && json.status < 300){
            //ok
            funok(json);
        }
        else{
            //error
            funerror(json);
            //alert("Ocurrio un error al recuperar la informacion: "+json.msg);
        }
    });

}

function openModalPosicion(json){
    //escribe el contenido en el formulario
    let des = document.getElementById('desPosition');
    let nom = document.getElementById('namePosition');
    let lat = document.getElementById('latPosition');
    let lon = document.getElementById('lonPosition');
    let rut = document.getElementById('rutPosition');
    let id = document.getElementById('idPosi');
    let map = document.getElementById('IDMapa');
    let button = document.getElementById('createPButton');

    id.value = json.obj.id;
    map.value = json.obj.map_shortcode;
    lat.value = json.obj.latitud;
    lon.value = json.obj.longitud;
    nom.value = json.obj.nombre;

    if(json.obj.tracker && json.obj.tracker != ""){
        des.value = json.obj.tracker;
        button.innerText = "Modificar Tracker";
    }else{
        des.value = json.obj.descripcion;
        button.innerText = "Modificar Posicion";
    }
    
    rut.value = json.obj.ruta;
    refrescarListaMultimedia(json.obj);
    activarModal('mAddPosition');
}

function openModalPosicion2(lati,long){
    //escribe el contenido en el formulario
    let des = document.getElementById('desPosition');
    let nom = document.getElementById('namePosition');
    let lat = document.getElementById('latPosition');
    let lon = document.getElementById('lonPosition');
    let rut = document.getElementById('rutPosition');
    let id = document.getElementById('idPosi');
    let button = document.getElementById('createPButton');

    id.value = "";
    lat.value = lati;
    lon.value = long;
    nom.value = "";
    des.value = "";
    rut.value = "";
    button.innerText = "Crear Posicion";
    activarModal('mAddPosition');
}

/**
 * Llama a la posicion y cuando devuelve la posicion abre la modal
 */
function getPosicion(idPosicion){
    data = new FormData();
    data.append("idPosicion",idPosicion);
    getRequest("getposition",data,openModalPosicion,errorJson);
} 
/**
 * Error generico
 * @param {*} json 
 */
function errorJson(json){
    alert("Ocurrio un error: "+json.msg);
}

/**
 * Crear o modificar posicion
 */
function cargandoPost(formulario,idbotton,fun1,fun2){
    let button = document.getElementById(idbotton);
    let formu = document.getElementById(formulario);
    var data = new FormData(formu);
    button.innerText = "Procesando.....";
    postRequest(data,fun1,fun2);
}

/* Machaca el servicio para enviarlo a uno en concreto */
function cargandoPost2(servicio,formulario,idbotton,fun1,fun2){
    let button = document.getElementById(idbotton);
    let formu = document.getElementById(formulario);
    var data = new FormData(formu);
    data.append("action", servicio);
    button.innerText = "Procesando.....";
    postRequest(data,fun1,fun2);
}

/* Forma de realizar las peticiones para los elementos multimedia */
function addElementoMultimedia(formulario,idbotton){
    let button = document.getElementById(idbotton);
    let formu = document.getElementById(formulario);
    var data = new FormData(formu);
    data.append("idPosi", idenPosi);
    button.innerText = "Agregando.....";
    postRequest(data,refrescarListaMultimedia,errorJson);
}
/**
 * refresca el listado de multimedias
 * @param {*} json 
 */
function refrescarListaMultimedia(json){
    listaMulti = document.getElementById("TablaMultimedias");
    listaMulti.innerHTML = "";
    
    json.multimedias.forEach(multimedia => {
        nuevoMulti = document.createElement("div");
        titulo = document.createElement("h3");
        elemento = "";
        switch(multimedia.tipo){
            case "1":
            //foto
            elemento = document.createElement("img");
            elemento.src = multimedia.valor;
            break;
            case "2":
            //video
            elemento = document.createElement("video");
            elemento.setAttribute("controls","controls");
            elemento.src = multimedia.valor;                
            break;
            case "3":
            //sonido
            elemento = document.createElement("audio");
            elemento.setAttribute("controls","controls");
            elemento.src = multimedia.valor;              
            break;
            case "4":
            //archivo
            elemento = document.createElement("a");
            elemento.href = multimedia.valor;
            elemento.target="_blank";
            elemento.innerText = "Enlace";
            break;
            case "5":
            //texto
            elemento = document.createElement("p");
            elemento.innerText = multimedia.valor;
            break;
            case "6":
            //enlace
            elemento = document.createElement("a");
            elemento.href = multimedia.valor;
            elemento.target="_blank";
            elemento.innerText = "Enlace";
            break;
        }
        nuevoMulti.classList.add("tkwp_multi");
        botonEliminar = document.createElement("button");
        botonEliminar.classList.add("tkwp_button");
        botonEliminar.classList.add("danger");
        botonEliminar.setAttribute("onclick","borrarMultimedia("+multimedia.id+","+multimedia.posiciones_Id+")");
        botonEliminar.innerText = "Eliminar";
        titulo.innerText = multimedia.nombre;


        nuevoMulti.appendChild(titulo);
        nuevoMulti.appendChild(elemento);
        nuevoMulti.appendChild(botonEliminar);
        listaMulti.appendChild(nuevoMulti);
    });
}

function borrarMultimedia(id,posicion){
    $campos = new FormData();
    $campos.append("action","deletemultimedia");
    $campos.append("idMultimedia",id);
    $campos.append("idPosicion",posicion);
    postRequest($campos,refrescarListaMultimedia,errorJson);
}