<!DOCTYPE html>

<html>

<head>
  <title>{$service_title}</title>

	<meta http-equiv="content-type" content="text/html; charset={$charset}" />

	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />
	<link href="{$tpldir}style/loggedin.css" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}style/dtree.css" rel="stylesheet" type="text/css" />

	<script>
		const currentSID = '{$sid}';
		const tplDir = '{$tpldir}';
		const serverTZ = {$serverTZ};
	</script>
	<script src="clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}js/common.js"></script>
	<script src="{$tpldir}js/loggedin.js"></script>
	<script src="{$tpldir}clientlib/dtree.js"></script>
	<script src="{$tpldir}clientlib/overlay.js"></script>
	<script src="{$tpldir}clientlib/autocomplete.js"></script>

	<base target="_top" />
</head>

<body style="background: #FFFFFF;">

	<table class="listTable" width="100%" style="border:0;table-layout:fixed;border-radius:0;">
		<colgroup>
			<col style="" />
			<col style="width:25%;" />
			<col style="width:130px;" />
		</colgroup>

		{foreach from=$thread item=mail}
		{cycle values="listTableTD,listTableTD2" assign="class"}
		<tr>
			{if !$mail.id}
			<td class="{if $mailID==$mail.id}listTableTDActiveH{else}{$class}{/if}" colspan="3">
				<img src="{$tpldir}res/dummy.gif" border="0" width="{$mail.level*16}" height="1" alt="" />
				<img src="{$tpldir}images/li/mail_markunread.png" width="16" height="16" border="0" alt="" align="absmiddle" />
				<span style="color:#666666;">{lng p="unknownmessage"}</font>
			</td>
			{else}
			<td class="{if $mailID==$mail.id}listTableTDActiveH{else}{$class}{/if}" nowrap="nowrap" style="text-overflow:ellipsis;overflow:hidden;">
				<img src="{$tpldir}res/dummy.gif" border="0" width="{$mail.level*16}" height="1" alt="" />
				{if $mail.id!=$mailID}<a href="email.read.php?id={$mail.id}&sid={$sid}&openConversationView=true">{/if}
					<img src="{$tpldir}images/li/mail_mark{if $mail.id!=$mailID}un{/if}read.png" width="16" height="16" border="0" alt="" align="absmiddle" />
					{text value=$mail.subject}
				{if $mail.id!=$mailID}</a>{/if}
			</td>
			<td class="{if $mailID==$mail.id}listTableTDActiveH{else}{$class}{/if}" width="25%"{if $mail.id==$mailID} id="activeMail"{/if} nowrap="nowrap" style="text-overflow:ellipsis;overflow:hidden;">
				<a href="email.compose.php?to={email value=$mail.from_mail}&sid={$sid}">
					&nbsp;{if $mail.from_name}{text value=$mail.from_name}{else}{email value=$mail.from_mail}{/if}
				</a>
			</td>
			<td class="{if $mailID==$mail.id}listTableTDActiveH{else}{$class}{/if}" width="130">
				{date timestamp=$mail.date nice=true}
			</td>
			{/if}
		</tr>
		{/foreach}
	</table>

	<script>
		{literal}
		var h = parseInt(document.body.scrollHeight);

		if(h > 120)
		{
			var activeMail = document.getElementById('activeMail');
			if(activeMail)
			{
				var mailTop = activeMail.offsetTop;
				window.scrollBy(0, mailTop-120/2+activeMail.offsetHeight/2);
			}
		}
		{/literal}
	</script>

</body>

</html>
