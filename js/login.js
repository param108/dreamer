$(document).ready(function () {
    $('#login-form').submit(function () {
	$.post('loginverify.php'+'?ajax=true&'+window.location.search.substr(1), 
		$('#login-form').serialize(), 
		function(data) {
			var pdata = JSON.parse(data);
			if (pdata.error != 0) {
				$('#loginmotd').show();
				return;
			}
		
			if (pdata.redirect == '') {
				window.location.assign('home.php');
			} else {
				window.location.assign(pdata.redirect);
			}
		});
	return false;
    });
});
