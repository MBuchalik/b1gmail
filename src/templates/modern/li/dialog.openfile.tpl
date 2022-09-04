<!DOCTYPE html>

<html>

<head>
    <title>{$title}</title>

	<meta http-equiv="content-type" content="text/html; charset={$charset}" />

	<link rel="shortcut icon" href="{$selfurl}favicon.ico" type="image/x-icon" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />
	{foreach from=$_cssFiles.li item=_file}	<link rel="stylesheet" type="text/css" href="{$_file}" /> {/foreach}

	<script src="{$selfurl}clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}clientlib/overlay.js"></script>
	<script src="{$tpldir}js/common.js"></script>
	<script src="{$tpldir}js/loggedin.js"></script>
	<script src="{$tpldir}js/dialog.js"></script>
	{foreach from=$_jsFiles.li item=_file}
		<script src="{$_file}"></script>
	{/foreach}
</head>

<body>

		{$text}

		<form action="{$formAction}" enctype="multipart/form-data" method="post">
			<br /><br />
			<table width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td width="20"><i class="fa fa-file-o" aria-hidden="true"></i></td>
					<td>{fileSelector name="$fieldName" multiple=$multiple}</td>
				</tr>
			</table>

			<p>
				<table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td align="left">
							{if $bar}
								{progressBar value=$bar.value max=$bar.max width=100}
							{/if}
						</td>
						<td align="right">
							<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
							<input type="submit" value="{lng p="ok"}" />
						</td>
					</tr>
				</table>
			</p>
		</form>

</body>

</html>
