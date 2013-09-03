function invalidUsername(uname) {
	var emailRegexStr = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	return (emailRegexStr.test(uname)); 
}
function invalidPassword(pass) {
	var passRegexStr = /^[a-zA-Z0-9\.,-\/#!$%\^&\*;:{}=\-_`~()]+$/g;
	var capsRegexStr = /[A-Z]/g;
	var punctRegexStr = /[\.,-\/#!$%\^&\*;:{}=\-_`~()]/g;
	return !(passRegexStr.test(pass) && 
		(pass.search(capsRegexStr)>=0) && 
		(pass.search(punctRegexStr)>=0)); 
}
$(document).ready(function () {
    $('#signup-form').submit(function () {
	var inputs = $('#signup-form :input');
	var inv_user=false;
	var inv_pass=false;
	if (invalidUsername(inputs[0].val())){
		inv_user = true;
		$('.username-invalid-msg').show();
	} else {
		$('.username-invalid-msg').hide();
	}
	if (invalidPassword(inputs[1].val())) {
		inv_pass = true;
		$('.password-invalid-msg').show();
	} else {
		$('.password-invalid-msg').hide();
	}
	if (inv_user || inv_pass) {
		return false;
	}
	$.post('/excel/signupverify.php'+'?ajax=true&'+window.location.search.substr(1), 
		$('#login-form').serialize(), 
		function(data) {
			var pdata = JSON.parse(data);
			if (pdata.error != 0) {
				$('#loginmotd').show();
				return;
			}
		
			if (pdata.redirect == '') {
				window.location.assign('/excel/verifyEmail.php');
			} else {
				window.location.assign(pdata.redirect);
			}
		});
	return false;
    });
});
