<?php
$id = $_GET['id'];
$link = mysqli_connect('localhost', 'root', 'testing', 'pl');

$values = array();
$values[] = $_SERVER['REMOTE_ADDR'];
$values[] = $id;

//record request info
$sql = "INSERT INTO Access (ip, req_id) VALUES ('" . implode("','", $values) . "')";
mysqli_query($link, $sql);

//Fetch data
$sql = "SELECT data FROM Content WHERE id = '$id'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$data = $row['data'];
$data = json_decode($data, true);
?>

<html>
<head>
	<title>PasteLink</title>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="css/disp.css" />
	<script type="text/javascript">
	var ct = "<?=$data['ct']?>";
	var iv = "<?=$data['iv']?>";
	var s = "<?=$data['s']?>";
	</script>

	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/aes.js"></script>
	<script src="js/disp.js"></script>

</head>
<body>

	<div id="lock_msg_wrap">
		<div id="lock_msg">
		Content Locked!
		</div>
		<div id="unlock_button_wrap">
			<button id="unlock_button">Unlock</button>
		</div>
	</div>
	
	<div id="password_dlg" title="Enter password to unlock content">
		
		<fieldset>
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" />
		</fieldset>
	
	</div>

	<div id="content_wrap">
		<textarea id="content"></textarea>
	</div>

</body>
</html>