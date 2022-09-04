<!DOCTYPE html>

<html>

<head>
  <title>{lng p="export"}</title>
    
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

	<fieldset>
		<legend>{lng p="export"}</legend>
		
		<table>
			<tr>
				<td><label for="lineBreakChar">{lng p="linebreakchar"}:</label></td>
				<td>	
					<select name="lineBreakChar" id="lineBreakChar">
						<option value="lf">LF</option>
						<option value="cr">CR</option>
						<option value="crlf">CRLF</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="sepChar">{lng p="sepchar"}:</label></td>
				<td>	
					<select name="sepChar" id="sepChar">
						<option value="semicolon">{lng p="semicolon"} (;)</option>
						<option value="comma">{lng p="comma"} (,)</option>
						<option value="tab">{lng p="tab"}</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><label for="quoteChar">{lng p="quotechar"}:</label></td>
				<td>	
					<select name="quoteChar" id="quoteChar">
						<option value="double">{lng p="double"} (&quot;)</option>
						<option value="single">{lng p="single"} (')</option>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>

	<p align="right">
		<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
		<input type="button" onclick="parent.document.location.href='organizer.addressbook.php?action=exportAddressbook&sid={$sid}&lineBreakChar='+escape(EBID('lineBreakChar').value)+'&sepChar='+escape(EBID('sepChar').value)+'&quoteChar='+escape(EBID('quoteChar').value);parent.hideOverlay();" value="{lng p="ok"}" />
	</p>
	
</body>

</html>
