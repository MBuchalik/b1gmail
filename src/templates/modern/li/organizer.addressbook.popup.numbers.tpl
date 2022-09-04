<!DOCTYPE html>

<html>

<head>
  <title>{lng p="addressbook"}</title>
    
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
			<td colspan="2" height="127">
				<div class="addressDiv" style="height: 330px;" id="addresses"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
				<input type="button" onclick="submitNumberDialog()" value="{lng p="ok"}" />
			</td>
		</tr>
	</table>
	<script>
		registerLoadAction(initNumberDialog);
	
		var Addr = [];
				
		{literal}function initNumberDialog()
		{
			{/literal}{foreach from=$addresses item=address}
			Addr.push(["{text noentities=true escape=true value=$address.lastname}, {text noentities=true escape=true value=$address.firstname}",
										"{text noentities=true escape=true value=$address.handy}",
									  	"{text noentities=true escape=true value=$address.work_handy}"]);
			{/foreach}
			
			initNumbers(Addr);
			{literal}
		}{/literal}
	</script>
	
</body>

</html>
