$(function() {

	$('#content').on("keyup", action);
	$('#result_wrap').hide();

	function action() {
		if($('#content').val().length > 0) {
			$('#save_button').button("enable");
		} else {
			$('#save_button').button("disable");
		}
	}

	$('#save_button').button().click(function( event ) {

		var rand_passwd = CryptoJS.lib.WordArray.random(128/8).toString();
		var url_passwd = rand_passwd;
		var password = $('#password').val();
		if(password != null && password.trim() != "") {
			rand_passwd += password;
		}

		var content = $('#content').val();
		var encrypted = CryptoJS.AES.encrypt(content, rand_passwd);
		var ct = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
		var iv = null;
		var s = null;
		if (encrypted.iv) {
			iv = encrypted.iv.toString();
		}
		if (encrypted.salt) {
			s = encrypted.salt.toString();
		}
		
		$.post('process.php', {'ct': ct, 'iv' : iv, 's' : s},  function (data, status) {
			//ID will be returned
			var link = "http://ruchith.net/pl/d.php?id=" + data + "#" + url_passwd;

			$('#result_wrap').html("<a href='" + link + "'>" + link + "</a>");
			$('#result_wrap').show();

		});
	});

	$('#save_button').button("disable");
});
