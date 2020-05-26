// variables
var clase_icono_selec = 'icono_selec';
//var dir_base = ['public_html', 'wwwroot', 'ssl', 'www'];
var sep_dir = '/', elem_buscado = '';
var dir_seleccionados = [], arch_seleccionados = [];
var ruta_icono_dir = 'vistas/imagenes/dir-5.png';
var ruta_icono_arch = 'vistas/imagenes/archivo-1.png'; 

// funciones
function borrarDirSelec(nombre){
    var pos = buscarDirSelec(nombre);
    
    if (pos >= 0){
        dir_seleccionados[pos] = '';
    }
}

function contarDirSelec(){
    var retorno = 0;
    
    for (var i=0; i < dir_seleccionados.length; i++){
        if (dir_seleccionados[i] !== '') retorno++;
    }
    
    return retorno;
}

function buscarDirSelec(nombre){
    var retorno = -1;
    
    for (var i=0; i < dir_seleccionados.length; i++){
        if (dir_seleccionados[i] == nombre){
            retorno = i;
            break;
        }
    }
    
    return retorno;
}

function agregarDirSelec(nombre){
    if (buscarDirSelec(nombre) < 0){
        var pos = dir_seleccionados.length;
        var pos_libre = buscarDirSelecLibre();

        if (pos_libre >= 0) pos = pos_libre;

        dir_seleccionados[pos] = nombre;
    }
}

function buscarDirSelecLibre(){
    var retorno = -1;
    
    for (var i=0; i < dir_seleccionados.length; i++){
        if (dir_seleccionados[i] == ''){
            retorno = i;
            break;
        }
    }
    
    return retorno;
}

function borrarArchSelec(nombre){
    var pos = buscarArchSelec(nombre);
    
    if (pos >= 0){
        arch_seleccionados[pos] = '';
    }
}

function contarArchSelec(){
    var retorno = 0;
    
    for (var i=0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] !== '') retorno++;
    }
    
    return retorno;
}

function buscarArchSelec(nombre){
    var retorno = -1;
    
    for (var i=0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] == nombre){
            retorno = i;
            break;
        }
    }
    
    return retorno;
}

function agregarArchSelec(nombre){
    if (buscarArchSelec(nombre) < 0){
        var pos = arch_seleccionados.length;
        var pos_libre = buscarArchSelecLibre();

        if (pos_libre >= 0) pos = pos_libre;

        arch_seleccionados[pos] = nombre;
    }
}

function buscarArchSelecLibre(){
    var retorno = -1;
    
    for (var i=0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] == ''){
            retorno = i;
            break;
        }
    }
    
    return retorno;
}

function mostrarTotalSelec(){
    var texto = '', tot_arch_selec = 0, tot_dir_selec = 0, icono_dir = '', icono_arch = '';
    
    tot_arch_selec = contarArchSelec();
    tot_dir_selec = contarDirSelec();
    
    icono_dir = '<img src="' + ruta_icono_dir + '" alt="Dir" class="icono_selec_1">';
    icono_arch = '<img src="' + ruta_icono_arch + '" alt="Arch" class="icono_selec_1">';

    if (tot_dir_selec > 0) texto += (icono_dir + '&nbsp;' + tot_dir_selec + ' dir');
    
    if (tot_arch_selec > 0){
        if (texto !== '') texto += ' - ';
        
        texto += (icono_arch + '&nbsp;' + tot_arch_selec + ' arch');
    }
    
    //if (texto !== '') texto = 'Sel:&nbsp;&nbsp;' + texto;
    
    if ((tot_arch_selec < 1) && (tot_dir_selec < 1)){
        $('#bcopiar').attr('disabled', true);
        $('#bcortar').attr('disabled', true);
        $('#bpegar').attr('disabled', true);
        $('#beliminar').attr('disabled', true);
        $('#bcomprimir').attr('disabled', true);
    }else{
        $('#bcopiar').attr('disabled', false);
        $('#bcortar').attr('disabled', false);
        $('#bpegar').attr('disabled', false);
        $('#beliminar').attr('disabled', false);
        $('#bcomprimir').attr('disabled', false);
    }
    
    if ((tot_arch_selec + tot_dir_selec) == 1){
        $('#brenombrar').attr('disabled', false);
        $('#binfo').attr('disabled', false);
    }else{
        $('#brenombrar').attr('disabled', true);
        $('#binfo').attr('disabled', true);
    }
    
    if ((tot_arch_selec === 1) && (tot_dir_selec === 0)){
        $('#bdownload').attr('disabled', false);
        $('#bmodificararch').attr('disabled', false);
    }else{
        $('#bdownload').attr('disabled', true);
        $('#bmodificararch').attr('disabled', true);
    }

    var strboton = '&nbsp;&nbsp;<a class="btn btn-xs btn-default" href="javascript:void()"' + 
    ' role="button" data-toggle="modal" data-target="#modal_detalle_selec" title="Ver detalle...">' + 
    '<span class="glyphicon glyphicon-list-alt"></span></a>';

    if (texto !== '') texto += strboton;

    $('#dtotales_selec').html(texto);
}

function guardarHdir(ruta){
    $('#hdir').val(ruta);
}

function mostrarTotalesListado(){
    var texto = '', tot_dir = 0, tot_arch = 0;
    
    tot_dir = $('#dlistado input[data-tipo="dir"]').length;
    tot_arch = $('#dlistado input[data-tipo="arch"]').length;
    
    icono_dir = '<img src="' + ruta_icono_dir + '" alt="Dir" class="icono_selec_1">';
    icono_arch = '<img src="' + ruta_icono_arch + '" alt="Arch" class="icono_selec_1">';

    texto += (icono_dir + '&nbsp;' + tot_dir + ' directorio(s)') + ' | ' + 
    (icono_arch + '&nbsp;' + tot_arch + ' archivo(s)');
    
    $('#dtotales_listado').html(texto);
}

function buscarSepDir(){
    var ruta = $('#hdir').val();
    var partes = ruta.split('/');
    
    if (partes.length < 2){
        partes = ruta.split('\\');
        
        if (partes.length > 1) sep_dir = '\\'; //para windows
    }
}

function cambiarDir(nombre_dir){
    var ruta = $('#hdir').val();
    var xruta = '';
    var partes = ruta.split('/');
    var pos_buscada = -1;
    var i, j;
    
    if (partes.length < 2) partes = ruta.split('\\');
    
    if (nombre_dir === ''){
        //subo un directorio
        pos_buscada = (partes.length > 1)?(partes.length - 2):0;
        
        //reviso que el dir actual no sea el dir raiz
        for (j = 0; j < dir_base.length; j++){
            if (partes[(partes.length - 1)] == dir_base[j]){
                pos_buscada = (partes.length - 1);
                break;
            }
        }
    }else{
        if (partes.length > 1){
            for (i = 0; i < partes.length; i++){
               for (j = 0; j < dir_base.length; j++){
                   if (partes[i] === nombre_dir){
                       pos_buscada = i;
                       break;
                   }
               }
               
               if (pos_buscada >= 0) break;
            }
        }else{
            pos_buscada = 0;
        }
    }
        
    for (i = 0; i <= pos_buscada; i++){
        xruta += partes[i];
        
        if (i < pos_buscada) xruta += sep_dir;
    }
    
    $('#hdir').val(xruta);
    enviarDatos('flistado', cargarListado);
}

function mostrarRutaActual(){
    var ruta = $('#hdir').val();
    var xruta = '';
    var partes = ruta.split('/');
    var pos_buscada = -1;
    var i, j;
    
    if (partes.length < 2) partes = ruta.split('\\');
    
    if (partes.length > 1){
        for (i = 0; i < partes.length; i++){
            for (j = 0; j < dir_base.length; j++){
                if (partes[i] == dir_base[j]){
                    pos_buscada = i;
                    break;
                }
            }
            
            if (pos_buscada >= 0) break;
        }
    }else{
        pos_buscada = 0;
    }
    
    for (i = pos_buscada; i < partes.length; i++){
        if (i == (partes.length - 1)){
            xruta += '<li class="active">' + partes[i] + '</li>';
        }else{
            xruta += '<li><a href="javascript:cambiarDir(\'' + partes[i] + '\')">' +
            partes[i] + '</a></li>';
        }
    }
    
    $('ol.breadcrumb').html('');
    $('ol.breadcrumb').html(xruta);
}

function desseleccionarIconos(){
    $('div.dicono').each(function(){
        if ($(this).hasClass(clase_icono_selec)) $(this).removeClass(clase_icono_selec);
    });
}

function seleccionarDirArch(objetivo){
    $('#dlistado input[type="checkbox"]').each(function(){
        if ($(this).attr('data-nombre') == objetivo){
            $(this).attr('checked', 'checked');
            return false;
        }
    });
}

function remarcarElementos(arreglo){
    var dir_actual = $('#hdir').val();
    //var partes = [], elemento_sel = '';
    var pos_ultimo_sep = 0, solo_ruta = '', solo_elem = '';
    
    if (arreglo.length > 0){
        for (var i=0; i < arreglo.length; i++){
            if (jQuery.trim(arreglo[i]) === '') continue;
            
            pos_ultimo_sep = arreglo[i].lastIndexOf('/');
            solo_ruta = arreglo[i].substring(0, pos_ultimo_sep);
            solo_elem = arreglo[i].substring((pos_ultimo_sep + 1));
            
            if (solo_ruta == dir_actual) seleccionarDirArch(solo_elem);
        }
    }
}

function cargarListado(data){
    var destino='#dlistado';
    
	$(destino).html('');
	$(destino).html(data);
    
    mostrarRutaActual();
    
    if (sep_dir === '') buscarSepDir();
    
    remarcarElementos(dir_seleccionados);
    remarcarElementos(arch_seleccionados);
}

function mostrarAccion(){
    mostrarTotalSelec();
    
    var texto = $('#dtotales_selec').text();
    
    if (texto !== '') texto += ' | ';
    
    if ($('#haccion').val() !== '') texto += ' Accion: ' + $('#haccion').val();
    
    $('#dtotales_selec').html(texto);
}

function despuesPegar(){
    dir_seleccionados=[];
    arch_seleccionados= [];
    
    $('#hdirselec').val('');
    $('#harchselec').val('');
    $('#haccion').val('');
    
    mostrarTotalSelec();
    mostrarAccion();
}

function prepararAccion(accion){
    $('#haccion').val(accion);
    
    if ($('#bpegar').attr('disabled') == 'disabled') $('#bpegar').attr('disabled', false);
    
    mostrarAccion();
}

function buscarNomSelecArchDir(){
    var retorno = '';
    
    for (var i=0; i < arch_seleccionados.length; i++){
        if (jQuery.trim(arch_seleccionados[i]) !== ''){
            retorno = arch_seleccionados[i];
            break;
        }
    }
    
    for (var i=0; i < dir_seleccionados.length; i++){
        if (jQuery.trim(dir_seleccionados[i]) !== ''){
            retorno = dir_seleccionados[i];
            break;
        }
    }
    
    return retorno;
}

function despuesRenombrar(){
    dir_seleccionados=[];
    arch_seleccionados= [];
    
    $('#hcambiarant').val('');
    $('#hcambiarnuevo').val('');
    
    mostrarTotalSelec();
    mostrarAccion();
}

function despuesComprimir(){
    dir_seleccionados=[];
    arch_seleccionados= [];
    
    $('#hdirselec').val('');
    $('#harchselec').val('');
    
    $('#hnomcomprimir').val('');
    
    mostrarTotalSelec();
    mostrarAccion();
}

function pegar(){
    var f_action_ant = $('#flistado').attr('action');
    var f_action = f_action_ant.replace('directorio', 'varios');
    f_action = f_action.replace('explorar', 'pegar');
    
    var dir_selec_json = JSON.stringify(dir_seleccionados);
    var arch_selec_json = JSON.stringify(arch_seleccionados);
    
    $('#hdirselec').val(dir_selec_json);
    $('#harchselec').val(arch_selec_json);

    $('#flistado').attr('action', f_action);
    
    enviarDatos('flistado', function(data){
        var datos;
        
        eval('datos=' + data);
        
        if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
        
        despuesPegar();
        
        //
        $('#flistado').attr('action', f_action_ant);
        
        if (datos.error !== ''){
            alertify.alert(('Error: ' + datos.error));
        }
        
        enviarDatos('flistado', cargarListado);
    });
    
    $('#flistado').attr('action', f_action_ant);
}

function eliminar(){
    var texto_1="Confirma eliminar los elementos seleccionados?";

    alertify.confirm(texto_1, function (e) {
       if (e){
         var f_action_ant = $('#flistado').attr('action');
         var f_action = f_action_ant.replace('directorio', 'varios');
         f_action = f_action.replace('explorar', 'eliminar');
         
         var dir_selec_json = JSON.stringify(dir_seleccionados);
         var arch_selec_json = JSON.stringify(arch_seleccionados);
         
         $('#hdirselec').val(dir_selec_json);
         $('#harchselec').val(arch_selec_json);
         
         $('#flistado').attr('action', f_action);
         
         enviarDatos('flistado', function(data){
             var datos;
             
             eval('datos=' + data);
             
             if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
             
             despuesPegar();
             
             //
             $('#flistado').attr('action', f_action_ant);
             
             if (datos.error !== ''){
                 alertify.alert(('Error: ' + datos.error));
             }
             
             enviarDatos('flistado', cargarListado);
         });
     
         $('#flistado').attr('action', f_action_ant);
       }
    });
}

function crearDir(){
    var texto = "Nombre directorio...";
    var pordefecto = "nuevo";
    
    // prompt dialog
    alertify.prompt(texto, function (e, str) {
        // str is the input text
        if (e) {
            // user clicked "ok"
            if (jQuery.trim(str) === ''){
                alertify.alert(('Error: el nombre no puede ser vacio'));
            }else{
                var f_action_ant = $('#flistado').attr('action');
                var f_action = f_action_ant.replace('explorar', 'nuevodir');
                
                $('#hnuevodir').val(str);
                $('#flistado').attr('action', f_action);
                
                enviarDatos('flistado', function(data){
                    var datos;
                    
                    eval('datos=' + data);
                    
                    if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
                    
                    $('#hnuevodir').val('');
                    $('#flistado').attr('action', f_action_ant);
                    
                    if (datos.error !== ''){
                        alertify.alert(('Error: ' + datos.error));
                    }
                    
                    enviarDatos('flistado', cargarListado);
                });
    
                $('#flistado').attr('action', f_action_ant);
            }
        }
    }, pordefecto);
}

function renombrar(){
    var texto = "Cambiar nombre...";
    var pordefecto = '';
    
    $('#dlistado input[type="checkbox"]').each(function(){
        if ($(this).is(':checked')){
            pordefecto = $(this).attr('data-nombre');
            return false;
        }
    });
    
    // prompt dialog
    alertify.prompt(texto, function (e, str) {
        // str is the input text
        if (e) {
            // user clicked "ok"
            if (jQuery.trim(str) === ''){
                alertify.alert(('Error: el nombre no puede ser vacio'));
            }else{
                var f_action_ant = $('#flistado').attr('action');
                var f_action = f_action_ant.replace('directorio', 'varios');
                f_action = f_action.replace('explorar', 'renombrar');
                
                $('#hcambiarant').val(buscarNomSelecArchDir());
                $('#hcambiarnuevo').val(str);
                $('#flistado').attr('action', f_action);
                
                enviarDatos('flistado', function(data){
                    var datos;
                    
                    eval('datos=' + data);
                    
                    if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
                    
                    despuesRenombrar();
                    
                    $('#flistado').attr('action', f_action_ant);

                    if (datos.error !== ''){
                        alertify.alert(('Error: ' + datos.error));
                    }
                    
                    enviarDatos('flistado', cargarListado);
                });
    
                $('#flistado').attr('action', f_action_ant);
            }
        }
    }, pordefecto);
}

function comprimir(opcion){
    var texto = "Nombre archivo comprimido...";
    var pordefecto = 'nuevo';
    
    if ((opcion == 'zip') && (contarDirSelec() > 0)){
        alertify.alert('No se pueden seleccionar directorios para generar un Zip');
        return;
    }
    
    // prompt dialog
    alertify.prompt(texto, function (e, str) {
        // str is the input text
        if (e) {
            // user clicked "ok"
            if (jQuery.trim(str) === ''){
                alertify.alert(('Error: el nombre no puede ser vacio'));
            }else{
                var f_action_ant = $('#flistado').attr('action');
                
                var f_action = f_action_ant.replace('directorio',
                ((opcion == 'zip')?'archivo':'varios'));
                
                f_action = f_action.replace('explorar',
                ((opcion == 'zip')?'comprimir_zip':'comprimir'));
                
                if (opcion == 'targzip'){
                    var dir_selec_json = JSON.stringify(dir_seleccionados);
                    
                    $('#hdirselec').val(dir_selec_json);
                }
                
                var arch_selec_json = JSON.stringify(arch_seleccionados);
                
                $('#harchselec').val(arch_selec_json);
        
                $('#hnomcomprimir').val(str);
                
                $('#flistado').attr('action', f_action);
                
                enviarDatos('flistado', function(data){
                    var datos;
                    
                    eval('datos=' + data);
                    
                    if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
                    
                    //
                    despuesComprimir();
                    
                    $('#flistado').attr('action', f_action_ant);

                    if (datos.error !== ''){
                        alertify.alert(('Error: ' + datos.error));
                    }
                    
                    enviarDatos('flistado', cargarListado);
                });
    
                $('#flistado').attr('action', f_action_ant);
            }
        }
    }, pordefecto);
}

function subir_archivo(){
    $('#hdir_upload').val($('#hdir').val());
    
    enviarDatosConArchivos('fsubir', function(data){
        var datos;
        
        eval('datos=' + data);
        
        if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
        
        if (datos.error === ''){
            $('#modal_upload').modal('hide');
        }else{
            alertify.alert(('Error: ' + datos.error));
        }
        
        enviarDatos('flistado', cargarListado);
    });
}

function bajar_archivo(){
    var arch_selec = '';
    
    for (var i = 0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] !== ''){
            arch_selec = arch_seleccionados[i];
            break;
        }
    }
    
    $('#harchbajar').val(arch_selec);
    
    $('#modal_download').modal('show');
}

function mostrarInfo(){
    var f_action_ant = $('#fdownload').attr('action');
    var f_action = f_action_ant.replace('archivo', 'varios');
    f_action = f_action.replace('download', 'mostrarinfo');
    
    var arch_selec = '';
    
    for (var i = 0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] !== ''){
            arch_selec = arch_seleccionados[i];
            break;
        }
    }
    
    if (arch_selec === ''){
        for (i = 0; i < dir_seleccionados.length; i++){
            if (dir_seleccionados[i] !== ''){
                arch_selec = dir_seleccionados[i];
                break;
            }
        }
    }
    
    $('#harchbajar').val(arch_selec);
    $('#fdownload').attr('action', f_action);
    
    enviarDatosConArchivos('fdownload', function(data){
        $('#harchbajar').val('');
        $('#fdownload').attr('action', f_action_ant);
        
        $('#dinfo_datos').html('');
        $('#dinfo_datos').html(data);
        
        $('#modal_info').modal('show');
    });
}

function extraer(opcion){
    var texto = '';
    var c_arch = 0, c_dir = 0;
    
    c_dir = contarDirSelec();
    
    if (c_dir > 0){
        texto = 'Atencion: Solo puede seleccionar archivos para extraer';
        alertify.alert(texto);
        return;
    }
    
    c_arch = contarArchSelec();
    
    if (c_arch > 1){
        texto = 'Atencion: Solo puede seleccionar 1 (uno) archivo para extraer';
        alertify.alert(texto);
        return;
    }else{
        if (c_arch < 1){
            texto = 'Atencion: Debe seleccionar 1 (uno) archivo para extraer';
            alertify.alert(texto);
            return;    
        }
    }
    
    var arch_selec = '';
    
    for (var i = 0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] !== ''){
            arch_selec = arch_seleccionados[i];
            break;
        }
    }
    
    //arch_selec = 'nuevo.tar.gz'
    //12 - 7 = 5
    var extension = ((opcion == 'zip')?'.zip':'.tar.gz');
    var pos_inicial = arch_selec.length - extension.length;
    
    if (arch_selec.substring(pos_inicial) != extension){
        texto = 'Atencion: Solo se puede extraer 1 (uno) archivo ' + extension;
        alertify.alert(texto);
        return;
    }
    
    var f_action_ant = $('#flistado').attr('action');
    var f_action = f_action_ant.replace('directorio', 'archivo');
    
    f_action = f_action.replace('explorar',
    ((opcion == 'zip')?'extraer_zip':'extraer'));
    
    $('#harchextraer').val(arch_selec);
    $('#flistado').attr('action', f_action);
    
    enviarDatos('flistado', function(data){
        var datos;
        
        eval('datos=' + data);
        
        if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
        
        //
        despuesComprimir();
        
        $('#harchextraer').val('');
        $('#flistado').attr('action', f_action_ant);
        
        if (datos.error !== ''){
            alertify.alert(('Error: ' + datos.error));
        }
        
        enviarDatos('flistado', cargarListado);
    });
    
    $('#flistado').attr('action', f_action_ant);
}

function crearArch(){
    var texto = "Nuevo archivo de texto...";
    var pordefecto = "nuevo.txt";
    
    // prompt dialog
    alertify.prompt(texto, function (e, str) {
        // str is the input text
        if (e) {
            // user clicked "ok"
            if (jQuery.trim(str) === ''){
                alertify.alert(('Error: el nombre no puede ser vacio'));
            }else{
                var f_action_ant = $('#flistado').attr('action');
                var f_action = f_action_ant.replace('directorio', 'archivo');
                f_action = f_action.replace('explorar', 'nuevo_arch');
                
                $('#hnuevodir').val(str);
                $('#flistado').attr('action', f_action);
                
                enviarDatos('flistado', function(data){
                    var datos;
                    
                    eval('datos=' + data);
                    
                    if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
                    
                    $('#hnuevodir').val('');
                    $('#flistado').attr('action', f_action_ant);
                    
                    if (datos.error !== ''){
                        alertify.alert(('Error: ' + datos.error));
                    }
                    
                    enviarDatos('flistado', cargarListado);
                });
    
                $('#flistado').attr('action', f_action_ant);
            }
        }
    }, pordefecto);
}

function obtenerUltimaParteRuta(ruta){
    var partes = ruta.split('/');
    var retorno = partes[(partes.length - 1)];
    
    return retorno;
}

function prepararModificarArchivo(){
    var arch_selec = '';
    
    for (var i = 0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] !== ''){
            arch_selec = arch_seleccionados[i];
            break;
        }
    }
    
    $('#fmodifarch label').text(obtenerUltimaParteRuta(arch_selec));
    
    $('#hrutaarch').val(arch_selec);
    $('#hmodifaccion').val('cargar');
    
    enviarDatos('fmodifarch', function(data){
        var texto_error = '--||Error||--';
        
        $('#tarchivo').val('');
        
        if (data.substring(0, 13) == texto_error){
            alertify.alert(('Error: ' + data.replace(texto_error, '')));
        }else{
            $('#tarchivo').val(data);
            
            $('#modal_modificar_arch').modal('show');
        }
    });
}

function modificarArchivo(){
    $('#hmodifaccion').val('guardar');
    
    enviarDatosConArchivos('fmodifarch', function(data){
        var datos;
        
        eval('datos=' + data);
        
        if (datos.info !== '') alertify.alert(('Info: ' + datos.info));
        
        if (datos.error === ''){
            $('#modal_modificar_arch').modal('hide');
        }else{
            alertify.alert(('Error: ' + datos.error));
        }
    });
}

function antesBuscar(){
    dir_seleccionados=[];
    arch_seleccionados= [];
    
    $('#dlistado div.dicono').each(function(){
        $(this).css('display', 'inline-block');
    });
    
    $('#flistado')[0].reset();
    
    mostrarTotalSelec();
    mostrarAccion();
}

function buscar(){
    var texto = "Buscar en el directorio actual...";
    //var pordefecto = "";
    
    // prompt dialog
    alertify.prompt(texto, function (e, str) {
        // str is the input text
        if (e) {
            // user clicked "ok"
            if (jQuery.trim(str) === ''){
                alertify.alert(('Error: el texto no puede ser vacio'));
            }else{
                antesBuscar();
                
                elem_buscado = str;
                
                $('#dlistado input[type="checkbox"]').each(function(){
                    if ($(this).attr('data-nombre').search(str) >= 0){
                        $(this).click();
                    }else{
                        $(this).parent().parent().css('display', 'none');
                    }
                });
                
                if ((contarArchSelec() + contarDirSelec()) < 1){
                    antesBuscar();
                    
                    alertify.alert('Info: No se encontraron coincidencias');
                }
            }
        }
    }, elem_buscado);
}

function mostrarListadoDirSelec(){
    var tabdestino ='#tabdirselec';
    var lista = '';
    var icono = '<img src="' + ruta_icono_dir + '" alt="Dir" class="icono_selec_1">';

    for (var i=0; i < dir_seleccionados.length; i++){
        if (dir_seleccionados[i] != ''){
            lista += icono + '&nbsp;&nbsp;' + dir_seleccionados[i] + '<br>';
        }
    }

    $(tabdestino).html(lista);
}

function mostrarListadoArchSelec(){
    var tabdestino ='#tabarchselec';
    var lista = '';
    var icono = '<img src="' + ruta_icono_arch + '" alt="Arch" class="icono_selec_1">';

    for (var i=0; i < arch_seleccionados.length; i++){
        if (arch_seleccionados[i] != ''){
            lista += icono + '&nbsp;&nbsp;' + arch_seleccionados[i] + '<br>';
        }
    }

    $(tabdestino).html(lista);
}

// main
$(function(){
    $('#bsubir').click(function(){
        cambiarDir('');
    });
    
    $('#bactualizar').click(function(){
        despuesPegar();
        enviarDatos('flistado', cargarListado);
    });
    
    $('#bcopiar').click(function(){
        prepararAccion('copiar');
    });
    
    $('#bcortar').click(function(){
        prepararAccion('cortar');
    });
    
    $('#bpegar').click(function(){
        pegar();
    });
    
    $('#beliminar').click(function(){
        eliminar();
    });
    
    $('#bnuevodir').click(function(){
        crearDir();
    });
    
    $('#brenombrar').click(function(){
        renombrar();
    });
    
    $('#bsubir_arch').click(function(){
        subir_archivo();
    });
    
    $('#modal_upload').on('show.bs.modal', function (e) {
        $('#fsubir')[0].reset();
    });
    
    $('#bdownload').click(function(){
        bajar_archivo();
    });
    
    $('#modal_download').on('hidden.bs.modal', function (e) {
       despuesPegar();
       enviarDatos('flistado', cargarListado);
    });
    
    $('#bdescargar').click(function(){
        $('#modal_download').modal('hide');
    });
    
    $('#binfo').click(function(){
        mostrarInfo();
    });
    
    $('#bnuevoarch').click(function(){
        crearArch();
    });
    
    $('#bmodificararch').click(function(){
        prepararModificarArchivo();
    });
    
    $('#modal_modificar_arch').on('show.bs.modal', function (e) {
        $('#tarchivo').focus();
    });
    
    $('#modal_modificar_arch').on('hidden.bs.modal', function (e) {
        $('#fmodifarch')[0].reset();
    });
    
    $('#bmodif_aceptar').click(function(){
        modificarArchivo();
    });
    
    $('#bbuscar').click(function(){
        buscar();
    });
    
    $('#modal_detalle_selec').on('show.bs.modal', function (event) {
        mostrarListadoDirSelec();
        mostrarListadoArchSelec();
    });

    enviarDatos('flistado', cargarListado);
});
