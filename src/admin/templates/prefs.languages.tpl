<fieldset>
	<legend>{lng p="languages"}</legend>

	<table class="list">
		<tr>
			<th width="20">&nbsp;</th>
			<th>{lng p="language"}</th>
			<th width="60">&nbsp;</th>
		</tr>
		
		{foreach from=$languages item=language key=langID}
		{cycle name=class values="td1,td2" assign=class}
		<tr class="{$class}">
			<td align="center"><img src="{$tpldir}images/language.png" border="0" alt="" width="16" height="16" /></td>
			<td>{text value=$language.title}<br /><small>{text value=$language.locale}</small></td>
			<td>
				<a href="prefs.languages.php?action=texts&lang={$langID}&sid={$sid}"><img src="{$tpldir}images/phrases.png" border="0" alt="{lng p="customtexts"}" width="16" height="16" /></a>				
			</td>
		</tr>
		{/foreach}
	</table>
</fieldset>
