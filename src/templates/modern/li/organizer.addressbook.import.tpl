<!DOCTYPE html>

<html>

<head>
  <title>{lng p="import"}</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />
	
	<script src="clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}clientlib/overlay.js"></script>
	<script src="{$tpldir}js/common.js"></script>
	<script src="{$tpldir}js/loggedin.js"></script>
	<script src="{$tpldir}js/dialog.js"></script>
	<script src="{$tpldir}js/organizer.js"></script>
</head>

<body>

	<fieldset>
		<legend>{lng p="import"}</legend>
		
		<table>
			<tr>
				<td><label for="importType">{lng p="type"}:</label></td>
				<td>
					<select name="importType" id="importType">
						<option value="csv">{lng p="csvfile"}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="importEncoding">{lng p="encoding"}:</label></td>
				<td>
					<select name="importEncoding" id="importEncoding">
						<option value="UTF-8">UTF-8</option>
						<option value="ASCII">ASCII</option>
						<option value="ISO-8859-15" selected="selected">ISO-8859-15</option>
						<option value="ISO-8859-2">ISO-8859-2</option>
						<option value="ISO-8859-3">ISO-8859-3</option>
						<option value="ISO-8859-4">ISO-8859-4</option>
						<option value="ISO-8859-5">ISO-8859-5</option>
						<option value="ISO-8859-6">ISO-8859-6</option>
						<option value="ISO-8859-7">ISO-8859-7</option>
						<option value="ISO-8859-8">ISO-8859-8</option>
						<option value="ISO-8859-9">ISO-8859-9</option>
						<option value="ISO-8859-10">ISO-8859-10</option>
						<option value="Windows-1252">Windows-1252</option>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>

	<p align="right">
		<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
		<input type="button" onclick="addrImportDialog('{$sid}');" value="{lng p="ok"}" />
	</p>
	
</body>

</html>
