<div id="contentHeader">
	<div class="left">
		<i class="fa fa-id-card-o" aria-hidden="true"></i>
		{lng p="membership"}
	</div>
</div>

<div class="scrollContainer"><div class="pad">

<form action="prefs.php?action=membership&do=changePW&sid={$sid}" method="post">
<h2>{lng p="changepw"}</h2>
{if isset($errorStep)}
<div class="note">
	{$errorInfo}
</div>
<br />
{/if}
<table class="listTable">
	<tr>
		<th class="listTableHead" colspan="2"> {lng p="changepw"}</th>
	</tr>
	<tr>
		<td class="listTableLeft">{lng p="password"}:</td>
		<td class="listTableRight">
			<input type="password" name="pass1" value="" size="35" />
		</td>
	</tr>
	<tr>
		<td class="listTableLeft">{lng p="repeat"}:</td>
		<td class="listTableRight">
			<input type="password" name="pass2" value="" size="35" />
		</td>
	</tr>
	<tr>
		<td class="listTableLeft">&nbsp;</td>
		<td class="listTableRight">
			<input type="submit" class="primary" value=" {lng p="save"} " />
			<input type="reset" value=" {lng p="reset"} " />
		</td>
	</tr>
</table>
</form>

{if $regDate||$allowCancel}
<h2>{lng p="membership"}</h2>
<table class="listTable">
	<tr>
		<th class="listTableHead" colspan="2"> {lng p="membership"}</th>
	</tr>
	{if $regDate}
	<tr>
		<td class="listTableLeft">{lng p="membersince"}:</td>
		<td class="listTableRight">
			{date timestamp=$regDate dayonly=true}
		</td>
	</tr>
	{/if}
	{if $allowCancel}
	<tr>
		<td class="listTableLeft">&nbsp;</td>
		<td class="listTableRight">
			<input type="button" value=" {lng p="cancelmembership"} " onclick="document.location.href='prefs.php?action=membership&do=cancelAccount&sid={$sid}';" />
		</td>
	</tr>
	{/if}
</table>
{/if}

</div></div>
