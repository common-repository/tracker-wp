function activarModal(windowModal){
	var elemento = document.querySelector('#'+windowModal);
	if(!elemento.classList.contains('active')){
		elemento.classList.add('active');
		if(elemento.classList.contains('desactive')){
			elemento.classList.remove('desactive');
		}
	}
}

function desactivarModal(windowModal){
	var elemento = document.querySelector('#'+windowModal);
	if(!elemento.classList.contains('desactive')){
		elemento.classList.add('desactive');
		if(elemento.classList.contains('active')){
			elemento.classList.remove('active');
		}
	}
}
/**
 * Cancela los eventos
 */
function cancelClose(){
	event.stopPropagation();//evitamos que salte al padre
}