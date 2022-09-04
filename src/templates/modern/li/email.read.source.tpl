<!DOCTYPE html>

<html>

<head>
  <title>{lng p="showsource"}</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" type="image/png" href="res/favicon.png" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />
	
	<script>
		const tplDir = '{$tpldir}';
	</script>
	<script src="clientlang.php"></script>
	<script src="{$tpldir}js/common.js"></script>
	<script src="{$tpldir}js/loggedin.js"></script>
	<script src="{$tpldir}js/dialog.js"></script>
</head>

<body onload="documentLoader()">

	<table width="100%">
		<tr>
			<td align="center">
				<div class="sourceDiv"><div style="padding:5px;">{$source}</div></div>
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" onclick="parent.hideOverlay()" value="{lng p="close"}" />
			</td>
		</tr>
	</table>
	
</body>

</html>
