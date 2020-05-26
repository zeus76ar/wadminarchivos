$(function(){
    $('input.file-style').filestyle({
		input: true,
		icon: true,
        iconName: "glyphicon glyphicon-folder-open",//glyphs of bootstrap
		badge: false,
		buttonText: "Seleccionar...",
		buttonName: "btn-default",//'btn--default', 'btn-primary'
        buttonBefore: true,
		size: "nr",//'sm', 'nr', 'lg'
		disabled: false
	});
});