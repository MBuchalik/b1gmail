<!DOCTYPE html>

<html>

<head>
    <title>{lng p="prefs"}</title>
    
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

		<form action="{$widgetPrefsURL}" method="post">
			<input type="hidden" name="save" value="true" />
			
			<fieldset>
				<legend>{lng p="prefs"}</legend>
				
				<input type="checkbox" name="hideSystemFolders" id="hideSystemFolders"{if $hideSystemFolders} checked="checked"{/if} />
				<label for="hideSystemFolders">{lng p="hidesystemfolders"}</label><br />
				
				<input type="checkbox" name="hideCustomFolders" id="hideCustomFolders"{if $hideCustomFolders} checked="checked"{/if} />
				<label for="hideCustomFolders">{lng p="hidecustomfolders"}</label><br />
				
				<input type="checkbox" name="hideIntelliFolders" id="hideIntelliFolders"{if $hideIntelliFolders} checked="checked"{/if} />
				<label for="hideIntelliFolders">{lng p="hideintellifolders"}</label>
			</fieldset>
	
			<p align="right">
				<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
				<input type="submit" value="{lng p="ok"}" />
			</p>
		</form>
	
</body>

</html>
