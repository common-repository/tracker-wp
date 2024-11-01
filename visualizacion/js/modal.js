class Modal {
/*{ 
        informacio:{
            lat: -123.2000,
            lgn: 1200,
            titulo: "Posicion x",
            descripcion: "asdasdasdasdasd",
            ruta: "",
            tracker: ""
        },
        multimedias:[{
            titulo: "Imagen",
            contenido: "https://s3-us-west-2.amazonaws.com/lasaga-blog/media/original_images/grupo_imagen.jpg",
            tipo: 1
        },{
            titulo: "Video",
            contenido: "http://v2v.cc/~j/theora_testsuite/320x240.ogg",
            tipo: 2
        },{
            titulo: "Sonido",
            contenido: "",
            tipo: 3
        },{
            titulo: "Archivo",
            contenido: "https://allcode.es",
            tipo: 4
        },{
            titulo: "Texto",
            contenido: "lorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssunlorem isssun",
            tipo: 5
        },{
            titulo: "Enlace",
            contenido: "https://allcode.es",
            tipo: 6
        }]}; */

    constructor(){
        this.idmodal = null;
        this.modal = document.createElement("div"); //contenedor general que ocupara toda la pantalla
        this.contenedor = document.createElement("div"); //contenedor central que sera visible    
        this.hori = document.createElement("div"); //horizontal
        this.izq = document.createElement("div"); //izquierda
        this.der = document.createElement("div"); //derecha   
        this.close = document.createElement("button");
        this.close.innerText = "X";
        this.close.classList.add("buttonClose");
        this.modal.classList.add("modal");
        this.modal.classList.add("back");  
        this.contenedor.classList.add("noscrollx");
        this.modal.classList.add("up");//nos pone z-indez 100000 para los paneles de wordpress
        this.modal.appendChild(this.contenedor);
        this.contenedor.appendChild(this.close);
        this.agregarEventos(this);
        this.datos = "";
    }

    setDatos(datas){
        this.datos = datas;
    }

    setId(idmod){
        this.idmodal = idmod;
        this.modal.setAttribute("id", idmod);
    }

    dele(){
        this.modal.remove();
    }

    add(){
        document.body.appendChild(this.modal);
    }

    activarModal(){
        if(!this.modal.classList.contains('active')){
            this.modal.classList.add('active');
            if(this.modal.classList.contains('desactive')){
                this.modal.classList.remove('desactive');
            }
        }
    }

    desactivarModal()
    {
        if(!this.modal.classList.contains('desactive')){
            this.modal.classList.add('desactive');
            if(this.modal.classList.contains('active')){
                this.modal.classList.remove('active');
            }
        }
    }
 
    activar(){
        //le ponemos la clase activar
        this.activarModal();

        //lo agregamos al dom 
        this.add();

        //subimos hasta arriba
        this.topFunction();
    }

    desactivar(){
        //lo ponemos la clase desactivar
        this.desactivarModal();

        //lo quitamos del dom
        this.dele();
    }
       
    cancelClose(){
        event.stopPropagation();//evitamos que salte al padre
    }

    modeTwoWindows(){
        this.hori; //contenedor central que sera visible  
        this.hori.classList.add("hori"); 
        this.izq; //contenedor central que sera visible 
        this.izq.classList.add("izq");
        if(this.datos != "" && this.datos != null){
            this.izq.innerHTML = "<h4>Informacion de <b>"+this.datos.nombre+"</b>:</h4>";
            this.izq.innerHTML += "<p><b>Titulo: </b>"+this.datos.nombre+"</p>";
            this.izq.innerHTML += "<p><b>Descripcion: </b>"+this.datos.descripcion+"</p>";
            this.izq.innerHTML += "<p><b>Latitud: </b>"+this.datos.latitud+"</p>";
            this.izq.innerHTML += "<p><b>Longitud: </b>"+this.datos.longitud+"</p>";
        }
        this.der ; //contenedor central que sera visible  
        this.der.classList.add("der");
        this.der.classList.add("scrolly"); 
        if(this.datos != "" && this.datos != null){
            this.datos.multimedias.forEach(multi => {
                var bloque = document.createElement("div");
                var titulo = document.createElement("h4");
                var elemento = "";
                titulo.innerText = multi.nombre;
                switch(multi.tipo){
                    case "1":
                    //foto
                    elemento = document.createElement("img");
                    elemento.src = multi.valor;
                    break;
                    case "2":
                    //video
                    elemento = document.createElement("video");
                    elemento.setAttribute("controls","controls");
                    elemento.src = multi.valor;                
                    break;
                    case "3":
                    //sonido
                    elemento = document.createElement("audio");
                    elemento.setAttribute("controls","controls");
                    elemento.src = multi.valor;              
                    break;
                    case "4":
                    //archivo
                    elemento = document.createElement("a");
                    elemento.href = multi.valor;
                    elemento.target="_blank";
                    elemento.innerText = "Enlace";
                    break;
                    case "5":
                    //texto
                    elemento = document.createElement("p");
                    elemento.innerText = multi.valor;
                    break;
                    case "6":
                    //enlace
                    elemento = document.createElement("a");
                    elemento.href = multi.valor;
                    elemento.target="_blank";
                    elemento.innerText = "Enlace";
                    break;
                }
                bloque.classList.add("tkwp_multi");
                bloque.appendChild(titulo);
                bloque.appendChild(elemento);
                this.der.appendChild(bloque);
            });
        }

        this.hori.appendChild(this.izq);
        this.hori.appendChild(this.der);
        this.contenedor.appendChild(this.hori);
    }

    agregarEventos(modal){
        modal.modal.addEventListener("click",function(){
            modal.desactivar();
        });
        modal.contenedor.addEventListener("click",function(){
            modal.cancelClose();
        });
        modal.close.addEventListener("click",function(){
            modal.desactivar();
        });
    }

    topFunction() {
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    } 

}