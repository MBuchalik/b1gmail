<fieldset>
	<legend>{lng p="searchprovider"}</legend>
	
	<form action="{$pageURL}&sid={$sid}&do=save" method="post" onsubmit="spin(this)">
	<table>
		<tr>
			<td align="left" rowspan="3" valign="top" width="40"><img src="../plugins/templates/images/search32.png" border="0" alt="" width="32" height="32" /></td>
			<td>
				{lng p="includeinsearch"}:
				<blockquote>
					<input type="checkbox" name="searchIn[mails]" id="searchMails"{if $searchIn.mails} checked="checked"{/if} />
						<label for="searchMails"><b>{lng p="emails"}</b></label><br />
					<input type="checkbox" name="searchIn[attachments]" id="searchAtt"{if $searchIn.attachments} checked="checked"{/if} />
						<label for="searchAtt"><b>{lng p="attachments"}</b></label><br />
					<input type="checkbox" name="searchIn[addressbook]" id="searchAddressbook"{if $searchIn.addressbook} checked="checked"{/if} />
						<label for="searchAddressbook"><b>{lng p="addressbook"}</b></label><br />
				</blockquote>
			</td>
		</tr>
	</table>
	<p>
		<div style="float:left;">
			<img src="{$tpldir}images/warning.png" border="0" alt="" width="16" height="16" align="absmiddle" />
			{lng p="mailsearchwarn"}
		</div>
		<div style="float:right;">
			<input class="button" type="submit" value=" {lng p="save"} " />
		</div>
	</p>
	</form>
</fieldset>
