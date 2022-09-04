<!DOCTYPE html>
<html>
<head>
    <title>b1gMail - {lng p="acp"}</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link href="{$tpldir}style/common.css?{fileDateSig file="style/common.css"}" rel="stylesheet" type="text/css" />
	
	<script src="../clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}js/common.js?{fileDateSig file="js/common.js"}"></script>
</head>

<body id="loginBody">
	
	<form action="index.php?action=login" method="post" autocomplete="off">
		<input type="hidden" name="timezone" id="timezone" value="{$timezone}" />
		
		<div id="loginBox1">
			<div id="loginBox2">
				<div id="loginBox3">
					{if isset($error)}<div class="loginError">{$error}</div>{/if}
				
					<div id="loginLogo">
						<img src="templates/images/logo_letter.png" style="width:90px;height:53px;" border="0" alt="" />
					</div>
					
					<div id="loginForm">
						{lng p="username"}:<br />
						<input id="username" type="text" name="username" value="" autofocus style="width:200px;" />
						<br /><br />
						
						{lng p="password"}:<br />
						<input id="pw" type="password" name="password" value="" style="width:200px;" />
						<br /><br />
						
						<div style="float:right;">
						<input class="button" type="submit" value=" {lng p="login"} &raquo; " />
						</div>
					</div>
					
					<br class="clear" />
				</div>
			</div>
		</div>
	</form>
	
	<script>
		EBID('timezone').value = (new Date()).getTimezoneOffset() * (-60);
	</script>

</body>
</html>
