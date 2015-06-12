<?php

require_once('../_MyClasses/DBConnection.class.php');
require_once('_safeupperfolder/config.inc.php');


if(isset($_POST['btnSend'])) {

	$dbConnection = new DBConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

	$dbConnection->openConnection();

	$sender = str_replace(LAN_REMOTE_HOST, "", gethostbyaddr($_SERVER['REMOTE_ADDR']));

	$msg = $dbConnection->sqlInjectionStopper($_POST['message']);

	$sqlQuery = "INSERT messages VALUES (NULL, '$msg', '$sender', NULL)";

	$dbConnection->sendSqlQuery($sqlQuery);

	$dbConnection->closeConnection();
}

function readout()
{
	include('pullmsg.php');
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Chat</title>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<style type="text/css">
		@charset "utf-8";

		* {
			padding: 0;
			margin: 0;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}

		body {
			font: 14px Tahoma;
			/*color: white;*/
		}

		/* CLEAR FIX */
		.clearfix:after {
			content: ".";
			display: block;
			height: 0;
			clear: both;
			visibility: hidden;
		}
		.clearfix {display: inline-block;}

		/* Bereich nicht für IE-mac Anfang \*/
		* html .clearfix { height: 1%;}
		.clearfix {display: block;}
		/* Bereich nicht für IE-mac Ende */

		.txt_c {
			text-align: center;
		}
		.txt_r {
			text-align: right;
		}

		.fl_l {
			float: left;
		}
		.fl_r {
			float: right;
		}

		.margin_left {
			margin-left: 40%;
		}
		.margin_right {
			margin-right: 40%;
		}

		a {
			text-decoration: none;
			padding: 2px 0px;
		}
		a:hover {
			background: silver;
			border-radius: 3px;
			padding: 2px 0px;
		}
		a:visited {
			color: blue;
		}

		#wrap {
			margin: 0 auto;
			width: 800px;
			height: 100px;
			padding-top: 50px;
		}

		#new-message textarea {
			width: 100%;
		}
		#new-message p {
			/*margin-top: 15px;*/
			font-weight: bold;
			/*float: left;*/
			display: inline-block;
		}
		#new-message button {
			/*margin-top: 15px;*/
			/*float: right;*/
		}
		#new-message input {
			margin-left: 15px;
			width: 600px;
		}

		hr {
			margin: 25px 0px;
		}

		#old-message {
			height: 100px;
			padding: 10px 5px 5px 5px;
			/*border: 1px solid gray;*/
		}

		.msg {
			width: 60%;
			border: 1px solid silver;
			border-radius: 5px;
			padding: 10px 10px 10px 10px;
			box-shadow: 1px 1px 4px gray;
			margin-top: 10px;
			word-wrap: break-word;
		}
			.msg .sender {
				text-shadow: 1px 1px 4px gray;
			}

		.sender {
			display: inline-block;
		}
		.timestamp {
			display: inline-block;
			float: right;
			font-size: 12px;
		}
	</style>

	<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

	<script src="jquery.playSound.js" type="text/javascript"></script>

	<script type="text/javascript">
		$("message").keypress(function(event) {
		    if (event.which == 13) {
		        event.preventDefault();
		        $("form").submit();
		    }
		});
	</script>
	
</head>
<body>

<div id="wrap" class="clearfix">
	<div id="new-message" class="clearfix">
		<form action="index.php" method="post">
			<p><?php echo str_replace(LAN_REMOTE_HOST, "", gethostbyaddr($_SERVER['REMOTE_ADDR'])); ?> : </p>
			<input type="text" name="message" tabindex="1" autofocus>
			<button type="submit" name="btnSend">Absenden</button>
		</form>
	</div>
	<br>
	<a href="">MANUAL RELOAD</a>
	<hr>
	<div id="old-message" class="clearfix">
		<?php readout(); ?>		
	</div>
</div>



</body>

<script language="javascript" type="text/javascript">
		var timeout = setInterval(reloadChat, 5000); // 5000

		//var alterContent = $('#old-message').html();

		//console.log($.trim(alterContent));

		function reloadChat () {
		    $.ajax({
			    method: 'POST',
				url: 'pullmsg.php',
				success: function(data) {
					var alterContent = $('#old-message').html();

					$('#old-message').html(data);

					var neuerContent = $('#old-message').html();
					//console.log(neuerContent);

					if($.trim(alterContent) != $.trim(neuerContent))
					{
						$.playSound('chimes');
					}
				}
			});
		}
	</script>

</html>