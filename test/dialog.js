function modalDialogOnOk() {
	console.log("Hitting Ok");
}
function createDialog(text, callback, btnok, btncancel) {
	$('body').append('<div class="modaldialogbk"></div>');	
	var dlgbk = $(".modaldialogbk");
	$('body').append($('<div class="modaldialog"></div>'));
	var dlg = $(".modaldialog");
	if (btncancel) {
		dlg.append($('<div class="modaldialogbtnsright"></div>'));
		var btns = $(".modaldialogbtnsright");
		btns.append('<button class="modaldialogcancelbtn" type="button">X</button>');
		$(".modaldialogcancelbtn").click(modalDialogOnOk);
	}
	dlg.append($('<div class="modaldialogtext"><p>'+text+'</p></div>'));
	if (btnok) {
		dlg.append($('<div class="modaldialogbtns"></div>'));
		var btns = $(".modaldialogbtns");
		btns.append('<button class="modaldialogokbtn" type="button">Ok</button>');
		$(".modaldialogokbtn").click(modalDialogOnOk);
	}
	var bkgd = $(".modaldialogbk");

	// the click stops here.
	bkgd.click(function(event) {
			event.stopPropagation(); 
	});
}

function showDialog() {
	$(".modaldialogbk").show();
	$(".modaldialog").fadeIn();
	$(".modaldialogtext").fadeIn();
}

