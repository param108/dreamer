
function validPassword(pass) {
	if (pass.length<8) {
		return false;
	}
	var passRegexStr = /^[a-zA-Z0-9\.,-\/#!$%\^&\*;:{}=\-_~()]+$/g;
	var capsRegexStr = /[A-Z]/g;
	var punctRegexStr = /[\.,-\/#!$%\^&\*;:{}=\-_~()]/g;
	return (passRegexStr.test(pass) && 
		(pass.search(capsRegexStr)>=0) && 
		(pass.search(punctRegexStr)>=0)); 
}

function clearMessages(event) {
	//clear all messages
	$('#pwreset-unmatched-msg').hide();
	$('#pwreset-invalid-msg').hide();
	$('#pwreset-update-failed-msg').hide();
	$('#pwreset-update-success-msg').hide();
}
function pwresetButtonClicked(event) {
	newpwd = $('#pwreset-input-newpwd').val();
	newpwdagn = $('#pwreset-input-newpwdagn').val();

	//clear all messages
	$('#pwreset-unmatched-msg').hide();
	$('#pwreset-invalid-msg').hide();
	$('#pwreset-update-failed-msg').hide();
	$('#pwreset-update-success-msg').hide();

	if (newpwd != newpwdagn) {
		$('#pwreset-unmatched-msg').show();
		return;
	}

	if (!validPassword(newpwd)) {
		$('#pwreset-invalid-msg').show();
		return;
	}

	$.post("ajax/updatePassword.php", { newpwd: newpwd}, function (data) {
		var out = $.parseJSON(data);
		if (out.e != 1) {
			$('#pwreset-update-success-msg').show();
			return;
		} else {
			$('#pwreset-update-failed-msg').show();
			return;
		}
	});
}

$(document).ready(function () {
	$('#pwreset-button').click(pwresetButtonClicked);
	$('pwreset-input-newpwd').keyup(clearMessages);
	$('pwreset-input-newpwdagm').keyup(clearMessages);
});
