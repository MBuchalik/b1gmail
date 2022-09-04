<!DOCTYPE html>

<html>

<head>
  <title>Password</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" type="image/png" href="{$selfurl}{$_tpldir}images/li/webdisk_folder.png" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />

	<link href="{$selfurl}{$_tpldir}clientlib/fontawesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="{$selfurl}{$_tpldir}clientlib/fontawesome/css/font-awesome-animation.min.css" rel="stylesheet" type="text/css" />
	
	<script src="../clientlang.php"></script>
</head>

<body onload="document.getElementById('pw').focus()">

		<table width="100%" cellspacing="0">
			<tr>
				<td width="42" valign="top"><i class="fa fa-cloud-download fa-3x" aria-hidden="true"></i></td>
				<td>
					{lng p="protected_desc"}
					
					<form action="index.php?action=passwordSubmit&user={$user}&folder={$folder}" method="post">
						<p align="center">
							{lng p="password"}:
							<input type="password" name="pw" id="pw" size="26" />
						</p>
						
						<p align="right">
							<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
							<input type="submit" value="{lng p="ok"}" />
						</p>
					</form>
				</td>
			</tr>
		</table>
	
</body>

</html>
