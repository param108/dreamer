function createDialog(text, callback, btnok=true, btncancel=true) {
	$('body').append('<div class="modaldialogbk"></div>');	
	$('body').append($('<div class="modaldialog"></div>'));
	$(".modaldialog").append($('<div class="modaldialogtext"><p>'+text+'</p></div>'));

	var bkgd = $(".modaldialogbk");

	// the click stops here.
	bkgd.click(function(event) {
			event.stopPropagation(); 
	});
	$(".modaldialogtext").fadeIn();
}

