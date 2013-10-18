$(function() {
	$('#content_wrap').hide();
	$('#unlock_button').button().click(function() {
		$( "#password_dlg" ).dialog( "open" );
		$( "#password_dlg" ).show();
	});

	$( "#password_dlg" ).dialog({
		autoOpen: false,
		height: 240,
		width: 350,
		modal: true,
		buttons: {
		"Unlock": function() {
			var password = $('#password').val();
			var cipherParams = CryptoJS.lib.CipherParams.create({
					ciphertext: CryptoJS.enc.Base64.parse(ct)
				});
			cipherParams.iv = CryptoJS.enc.Hex.parse(iv);
			cipherParams.salt = CryptoJS.enc.Hex.parse(s);

			var rand_passwd = document.location.hash;
			rand_passwd = rand_passwd.substr(1, rand_passwd.length-1)
			if(password != null && password.trim() != "") {
				rand_passwd += password;
			}

			var decrypted = CryptoJS.AES.decrypt(cipherParams, rand_passwd);

			decrypted = decrypted.toString(CryptoJS.enc.Utf8);
			$('#content').val(decrypted);

			$('#lock_msg_wrap').hide();
			$('#content_wrap').show();
			$( this ).dialog( "close" );
		},
		Cancel: function() {
			$( this ).dialog( "close" );
		}
	  },
	  close: function() {
		
	  }
	});




	// $('#unlock_button').click(function( event ) {
	// 	//Get password value
	// 	var password = $('#password').val();
	
	// });
});