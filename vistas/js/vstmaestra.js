$(function(){
    // cerrar el menu responsive al hacer click en algun menu
    // (pequeño arreglo)
    $('.navbar-collapse a').click(function(){
        if ($(this).attr('href') != '#'){
            if ($('button.navbar-toggle').css('display') == 'block'){
                $('button.navbar-toggle').click();
            }
        }
    });
});
