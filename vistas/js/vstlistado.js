$(function(){
    $('div.dicono img').click(function(){
        if ($(this).attr('data-tipo') === 'dir'){
            if ($(this).parent().hasClass(clase_icono_selec)){
                var destino = $('#hdir').val() + '/' + $(this).attr('data-nombre');
                
                $('#hdir').val(destino);
                
                enviarDatos('flistado', cargarListado);
            }
        }else{
            if ($(this).parent().hasClass(clase_icono_selec)) return;
        }
        
        //la funcion esta en el archivo vstexplorador.js
        desseleccionarIconos();
        
        $(this).parent().addClass(clase_icono_selec);
    });
    
    $('div.dicono input[type=checkbox]').click(function(){
        var rutayarch=$('#hdir').val();
        
        if (rutayarch.substring(-1) != sep_dir) rutayarch += sep_dir;
        
        rutayarch += $(this).attr('data-nombre');
        
        if ($(this).is(':checked')){
            if ($(this).attr('data-tipo') == 'arch'){
                agregarArchSelec(rutayarch);
            }else{
                agregarDirSelec(rutayarch);
            }
        }else{
            if ($(this).attr('data-tipo') == 'arch'){
                borrarArchSelec(rutayarch);
            }else{
                borrarDirSelec(rutayarch);
            }
        }
        
        mostrarTotalSelec();
    });
    
    mostrarTotalesListado();
});