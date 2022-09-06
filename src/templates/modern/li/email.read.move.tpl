<!DOCTYPE html>

<html>

<head>
    <title>{lng p="move"}</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />
	<link href="{$tpldir}style/dialog.css" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}style/dtree.css" rel="stylesheet" type="text/css" />
	
	<script>
		const tplDir = '{$tpldir}';
	</script>
	<script src="clientlang.php"></script>
	<script src="{$tpldir}clientlib/dtree.js"></script>
	<script src="{$tpldir}js/common.js"></script>
	<script src="{$tpldir}js/loggedin.js"></script>
	<script src="{$tpldir}js/dialog.js"></script>
</head>

<body onload="documentLoader()">

	<table width="100%">
		<tr>
			<td>{lng p="movemailto"}:</td>
		</tr>
		<tr>
			<td align="center">
				<div class="foldersDiv"><div style="padding:5px;">
					<script>
						var d = new dTree('d');
					{foreach from=$folderList item=folder}
						d.add({$folder.i}, {$folder.parent}, '{text value=$folder.text escape=true noentities=true}', 'email.read.php?action=move&id={$mailID}&dest={$folder.id}&sid={$sid}', '{text value=$folder.text escape=true noentities=true}', '', 'fa {if $folder.icon == 'inbox'}fa-inbox{elseif $folder.icon == 'outbox'}fa-inbox{elseif $folder.icon == 'drafts'}fa-envelope{elseif $folder.icon == 'spam'}fa-ban{elseif $folder.icon == 'trash'}fa-trash-o{elseif $folder.icon == 'intellifolder'}fa-folder{else}fa-folder-o{/if}', 'fa {if $folder.icon == 'inbox'}fa-inbox{elseif $folder.icon == 'outbox'}fa-inbox{elseif $folder.icon == 'drafts'}fa-envelope{elseif $folder.icon == 'spam'}fa-ban{elseif $folder.icon == 'trash'}fa-trash-o{elseif $folder.icon == 'intellifolder'}fa-folder{else}fa-folder-o{/if}', 'fa {if $folder.icon == 'inbox'}fa-inbox{elseif $folder.icon == 'outbox'}fa-inbox{elseif $folder.icon == 'drafts'}fa-envelope{elseif $folder.icon == 'spam'}fa-ban{elseif $folder.icon == 'trash'}fa-trash-o{elseif $folder.icon == 'intellifolder'}fa-folder{else}fa-folder-o{/if}', 'fa {if $folder.icon == 'inbox'}fa-inbox{elseif $folder.icon == 'outbox'}fa-inbox{elseif $folder.icon == 'drafts'}fa-envelope{elseif $folder.icon == 'spam'}fa-ban{elseif $folder.icon == 'trash'}fa-trash-o{elseif $folder.icon == 'intellifolder'}fa-folder{else}fa-folder-o{/if}');
					{/foreach}
						document.write(d);
					</script>
				</div></div>
			</td>
		</tr>
		<tr>
			<td align="right">
				<input type="button" onclick="parent.hideOverlay()" value="{lng p="cancel"}" />
			</td>
		</tr>
	</table>
	
</body>

</html>
