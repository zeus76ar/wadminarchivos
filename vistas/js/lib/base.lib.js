function prepararIdParaJquery(id_objeto){
    if (id_objeto.substring(0,1)!= '#') id_objeto='#'+id_objeto;
    
    return id_objeto;
}

function enviarDatos(id_form, nom_funcion){
    id_form=prepararIdParaJquery(id_form);
	
    var url=$(id_form).attr("action");
    
    NProgress.start();
    
    $.post(url, $(id_form).serializeArray(), function(data){
        NProgress.done();
		
		nom_funcion(data);
	});
}

function mostrarAviso(tipo, mensaje){
    //tipo: 0-success, 1-info, 2-warning, 3-danger
    var tipos=new Array(), tipos_textos=new Array();
    
    tipos[0]='success';
    tipos[1]='info';
    tipos[2]='warning';
    tipos[3]='danger';
    
    tipos_textos[0]='Exito';
    tipos_textos[1]='Info';
    tipos_textos[2]='Atencion';
    tipos_textos[3]='Error';
    
    var aviso='<div class="alert alert-' + tipos[tipo] + '" role="alert">' +
    '<strong>'+tipos_textos[tipo]+':</strong> ' + mensaje + '</div>';
    
    if ($('div#borde_aviso') == null) $('body').append('<div id="borde_aviso"></div>');
    
    $('div#borde_aviso').html(aviso);
    
    ocultarAviso();
}

function ocultarAviso(){
    window.setTimeout(function(){
        $('div#borde_aviso').html('');
    }, 2000);
}

function salir(url){
    window.location=url;
}

function cargarContenido(url, destino, nom_funcion){
    NProgress.start();
    
	$.get(url, function(data){
        NProgress.done();
        
		$(destino).html('');
		$(destino).html(data);
        
        nom_funcion(data);
	});
}

function prepararIdParaJquery(id_objeto){
    if (id_objeto.substring(0,1)!= '#') id_objeto='#'+id_objeto;
    
    return id_objeto;
}

function enviarDatosConArchivos(id_form, nom_funcion){
    // *** ejemplo tomado de http://uno-de-piera.com/subir-imagenes-con-php-y-jquery/ ***
    
    //var formData = new FormData($(".formulario")[0]);
    var formData = new FormData(document.getElementById(id_form));
    
    NProgress.start();
    
    //hacemos la petición ajax
    $.ajax({
        url: $(prepararIdParaJquery(id_form)).attr('action'),  
        type: 'POST',
        // Form data - datos del formulario
        data: formData,
        //necesario para subir archivos via ajax
        cache: false,
        contentType: false,
        processData: false,
        //una vez finalizado el envio
        complete: function(jqXHR, textStatus){
            //textStatus: "success", "notmodified", "nocontent",
            //"error", "timeout", "abort", or "parsererror"
            
            NProgress.done();
            
            var datos='';
            
            if (jqXHR.responseText != undefined) {
                datos=jqXHR.responseText;
            }else if (test) {
                datos=jqXHR.responseXML;
            }
            
            nom_funcion(datos);
        }
    });
}