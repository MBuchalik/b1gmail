<fieldset>
	<legend>b1gMail</legend>

	<table width="100%">
		<tr>
			<td rowspan="2" width="40" align="center" valign="top"><img src="{$tpldir}images/about_logo.png" border="0" alt="" width="32" heigh="32" /></td>
			<td class="td1" width="24%">{lng p="version"}:</td>
			<td class="td2" width="26%">{$version}</td>

			<td colspan="3" rowspan="2">&nbsp;</td>
		</tr>
	</table>
</fieldset>

{if $adminRow.type==0||$adminRow.privileges.overview}
<fieldset>
	<legend>{lng p="overview"}</legend>

	<table width="100%">
		<!-- user stuff -->
		<tr>
			<td rowspan="3" width="40" align="center" valign="top"><img src="{$tpldir}images/ico_users.png" border="0" alt="" width="32" heigh="32" /></td>
			<td class="td1" width="24%"><a href="users.php?sid={$sid}">{lng p="users"}</a>:</td>
			<td class="td2" width="26%">{$userCount}</td>

			<td rowspan="3" width="40" align="center" valign="top"><img src="{$tpldir}images/ico_system.png" border="0" alt="" width="32" heigh="32" /></td>
			<td class="td1" width="24%">{lng p="phpversion"}:</td>
			<td class="td2" width="26%">{$phpVersion}</td>
		</tr>
		<tr>
			<td class="td1"><a href="users.php?filter=true&statusNotActivated=true&allGroups=true&sid={$sid}">{lng p="notactivated"}</a>:</td>
			<td class="td2">{$notActivatedUserCount}</td>

			<td class="td1">{lng p="webserver"}:</td>
			<td class="td2">{$webserver}</td>
		</tr>
		<tr>
			<td class="td1"><a href="users.php?filter=true&statusLocked=true&allGroups=true&sid={$sid}">{lng p="locked"}</a>:</td>
			<td class="td2">{$lockedUserCount}</td>

			<td class="td1">{lng p="load"}:</td>
			<td class="td2">{$loadAvg}</td>
		</tr>

		<!-- mail stuff -->
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<td rowspan="3" width="40" align="center" valign="top"><img src="{$tpldir}images/ico_email.png" border="0" alt="" width="32" heigh="32" /></td>
			<td class="td1">{lng p="emailsize"}:</td>
			<td class="td2">{if $emailSize!==false}{size bytes=$emailSize}{else}-{/if}</td>

			<td rowspan="3" width="40" align="center" valign="top"><img src="{$tpldir}images/ico_data.png" border="0" alt="" width="32" heigh="32" /></td>
			<td class="td1">{lng p="mysqlversion"}:</td>
			<td class="td2">{$mysqlVersion}</td>
		</tr>
		<tr>
			<td class="td1">{lng p="emails"}:</td>
			<td class="td2">{$emailCount}</td>

			<td class="td1">{lng p="tables"}:</td>
			<td class="td2">{$tableCount}</td>
		</tr>
		<tr>
			<td class="td1">{lng p="folders"}:</td>
			<td class="td2">{$folderCount}</td>

			<td class="td1">{lng p="dbsize"}:</td>
			<td class="td2">{size bytes=$dbSize}</td>
		</tr>
	</table>
</fieldset>

<fieldset>
	<legend>{lng p="notes"}</legend>
	<form action="welcome.php?sid={$sid}&do=saveNotes" method="post" onsubmit="spin(this)">
		<textarea style="width:100%;height:94px;" name="notes">{text value=$notes allowEmpty=true}</textarea>
		<p align="right"><input type="submit" value=" {lng p="save"} " class="button" /></p>
	</form>
</fieldset>
{/if}

{if $adminRow.type==0}
<fieldset>
	<legend>{lng p="notices"}</legend>

	<table width="100%" id="noticeTable">
	{foreach from=$notices item=notice}
		<tr>
			<td width="20" valign="top"><img src="{$tpldir}images/{$notice.type}.png" width="16" height="16" border="0" alt="" align="absmiddle" /></td>
			<td valign="top">{$notice.text}</td>
			<td align="right" valign="top" width="20">{if isset($notice.link)}<a href="{$notice.link}sid={$sid}"><img src="{$tpldir}images/go.png" border="0" alt="" width="16" height="16" /></a>{else}&nbsp;{/if}</td>
		</tr>
	{/foreach}
	</table>
</fieldset>
{/if}
