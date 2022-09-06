<!DOCTYPE html>

<html>

<head>
	<title>{$service_title} - {lng p="maintenance"}</title>
	
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />

	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />
	<link rel="stylesheet" type="text/css" href="{$tpldir}style/notloggedin.css" />
</head>

<body>

	<center>
		<br /><br /><br /><br />
		
		<p>
			<img src="{$tpldir}images/main/maintenance.png" border="0" alt="" width="128" height="128" />
			<br /><br />
		</p>
			
		<div class="maintenanceBox">
			<h2>{lng p="maintenance"}</h2>
			
			<div style="text-align: left; padding: 8px;">
				{$text}
			</div>
		</div>
	</center>

</body>

</html>
