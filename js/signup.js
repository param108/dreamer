function validUsername(uname) {
	var emailRegexStr = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	return (emailRegexStr.test(uname)); 
}
function validPassword(pass) {
	var passRegexStr = /^[a-zA-Z0-9\.,-\/#!$%\^&\*;:{}=\-_~()]+$/g;
	var capsRegexStr = /[A-Z]/g;
	var punctRegexStr = /[\.,-\/#!$%\^&\*;:{}=\-_~()]/g;
	return (passRegexStr.test(pass) && 
		(pass.search(capsRegexStr)>=0) && 
		(pass.search(punctRegexStr)>=0)); 
}

function hideAllMessages() {
	$('.signup-invalid-token-msg').hide();
	$('.signup-username-used-msg').hide();
	$('.password-invalid-msg').hide();
	$('.username-invalid-msg').hide();
}

$(document).ready(function () {
    $('#signup-form').submit(function () {
	var inputs = $('#signup-form :input');
	var inv_user=false;
	var inv_pass=false;
	if (!validUsername(inputs[1].value)){
		inv_user = true;
		$('.username-invalid-msg').show();
	} else {
		$('.username-invalid-msg').hide();
	}
	if (!validPassword(inputs[2].value)) {
		inv_pass = true;
		$('.password-invalid-msg').show();
	} else {
		$('.password-invalid-msg').hide();
	}
	if (inv_user || inv_pass) {
		return false;
	}
	$.post('/excel/signupverify.php'+'?ajax=true&'+window.location.search.substr(1), 
		$('#signup-form').serialize(), 
		function(data) {
			var pdata = JSON.parse(data);
			hideAllMessages();
			if (pdata.error != '') {
				switch(pdata.error) {
					case 't':
						$('.signup-invalid-token-msg').show();
						break;
					case 'uu':
						$('.signup-username-used-msg').show();
						break;
					case 'pu':
						$('.password-invalid-msg').show();
						$('.username-invalid-msg').show();
						break;
					case 'u':
						$('.username-invalid-msg').show();
						break;
					case 'p':
						$('.password-invalid-msg').show();
						break;
				}
				return;
			}
		
			window.location.assign('/excel/emailverify.php');
		});
	return false;
    });
});
