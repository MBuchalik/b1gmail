<!DOCTYPE html>

<html>

<head>
  <title>{$title}</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" type="image/png" href="res/favicon.png" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />
	
	<script src="clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}clientlib/overlay.js"></script>
	<script src="{$tpldir}js/common.js"></script>
	<script src="{$tpldir}js/loggedin.js"></script>
	<script src="{$tpldir}js/dialog.js"></script>
</head>

<body>

		{if $pkcs12Support}
		{lng p="addprivcert12text"}
		{else}
		{lng p="addprivcerttext"}
		{/if}
		
		<form action="prefs.php?action=keyring&do=uploadPrivateCertificate&sid={$sid}" enctype="multipart/form-data" method="post" autocomplete="off">
			<br /><br />
			<table width="100%" cellspacing="0" cellpadding="2">
			{if $pkcs12Support}	
				<tr>
					<td width="90">* {lng p="pkcs12file"}:</td>
					<td width="20"><i class="fa fa-file-o" aria-hidden="true"></i></td>
					<td>{fileSelector name="pkcs12File" size="18"}</td>
				</tr>
			{else}
				<tr>
					<td width="90">* {lng p="certificate"}:</td>
					<td width="20"><i class="fa fa-file-o" aria-hidden="true"></i></td>
					<td>{fileSelector name="certFile" size="18"}</td>
				</tr>
				<tr>
					<td>{lng p="chaincerts"}:</td>
					<td><i class="fa fa-file-o" aria-hidden="true"></i></td>
					<td>{fileSelector name="chainFile" size="18"}</td>
				</tr>
				<tr>
					<td>* {lng p="key"}:</td>
					<td><i class="fa fa-file-o" aria-hidden="true"></i></td>
					<td>{fileSelector name="pkeyFile" size="18"}</td>
				</tr>{/if}
				<tr>
					<td>{lng p="password"}:</td>
					<td>&nbsp;</td>
					<td><input type="password" name="pkeyPass" value="" size="28" style="width:86%;" /></td>
				</tr>
			</table>
			
			<p align="right">
				<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
				<input type="submit" value="{lng p="ok"}" />
			</p>
		</form>
	
</body>

</html>
