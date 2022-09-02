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
			<td align="center">
				<img src="{$tpldir}images/{if $language.disabled}no{else}yes{/if}.png" border="0" alt="" width="16" height="16" />
			</td>
			<td {if $language.disabled}style="text-decoration: line-through;"{/if}>
				{text value=$language.title}
				<br />
				<small>{text value=$language.locale}</small>
			</td>
			<td>
				<a href="prefs.languages.php?action=texts&lang={$langID}&sid={$sid}">
					<img src="{$tpldir}images/phrases.png" border="0" alt="{lng p="customtexts"}" width="16" height="16" />
				</a>
				{if !$language.default}
					{if $language.disabled}
						<a href="prefs.languages.php?enable={$langID}&sid={$sid}">
							<img src="{$tpldir}images/unlock.png" border="0" width="16" height="16" />
						</a>
					{else}
						<a href="prefs.languages.php?disable={$langID}&sid={$sid}">
							<img src="{$tpldir}images/unlock.png" border="0" width="16" height="16" />
						</a>
					{/if}
				{/if}
			</td>
		</tr>
		{/foreach}
	</table>
</fieldset>
