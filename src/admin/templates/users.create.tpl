<form method="post" action="users.php?action=create&create=true&sid={$sid}" onsubmit="spin(this)">
	<table width="100%">
		<tr>
			<td width="50%" valign="top">
				<fieldset>
					<legend>{lng p="profile"}</legend>
					
					<table width="100%">
						<tr>
							<td class="td1" width="115">{lng p="email"}:</td>
							<td class="td2"><input type="text" name="email" style="width:40%;" />
								<select name="emailDomain">
									{foreach from=$domainList item=domain}
										<option value="{$domain}">@{domain value=$domain}</option>
									{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="salutation"}:</td>
							<td class="td2"><select name="anrede">
									<option value="">&nbsp;</option>
									<option value="herr">{lng p="mr"}</option>
									<option value="frau">{lng p="mrs"}</option>
								</select></td>
						</tr>
						<tr>
							<td class="td1">{lng p="firstname"}:</td>
							<td class="td2"><input type="text" name="vorname" style="width:85%;" /></td>
						</tr>
						<tr>
							<td class="td1">{lng p="lastname"}:</td>
							<td class="td2"><input type="text" name="nachname" style="width:85%;" /></td>
						</tr>
						<tr>
							<td class="td1">{lng p="streetno"}:</td>
							<td class="td2"><input type="text" name="strasse" style="width:55%;" />
								<input type="text" name="hnr" style="width:15%;" />
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="zipcity"}:</td>
							<td class="td2"><input type="text" name="plz" style="width:20%;" />
								<input type="text" name="ort" style="width:50%;" />
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="country"}:</td>
							<td class="td2">
								<select name="land">
									{foreach from=$countries item=countryName key=countryID}
										<option value="{$countryID}"{if $countryID==$defaultCountry} selected="selected"{/if}>{text value=$countryName}</option>
									{/foreach}
								</select>
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="tel"}:</td>
							<td class="td2">
								<input type="text" name="tel" style="width:85%;" />
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="fax"}:</td>
							<td class="td2">
								<input type="text" name="fax" style="width:85%;" />
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="altmail"}:</td>
							<td class="td2">
								<input type="text" name="altmail" style="width:85%;" />
							</td>
						</tr>
						
						{foreach from=$profileFields item=profileField}
						{assign var=fieldID value=$profileField.id}
						<tr>
							<td class="td1">{$profileField.title}:</td>
							<td class="td2">
								{if $profileField.type==1}
									<input type="text" name="field_{$profileField.id}" value="{text value=$profileField.value allowEmpty=true}" style="width:85%;" />
								{elseif $profileField.type==2}
									<input type="checkbox" name="field_{$profileField.id}"{if $profileField.value} checked="checked"{/if} />
								{elseif $profileField.type==4}
									<select name="field_{$profileField.id}">
									{foreach from=$profileField.extra item=item}
										<option value="{text value=$item allowEmpty=true}"{if $profileField.value==$item} selected="selected"{/if}>{text value=$item allowEmpty=true}</option>
									{/foreach}
									</select>
								{elseif $profileField.type==8}
									{foreach from=$profileField.extra item=item}
										<input type="radio" id="field_{$profileField.id}_{$item}" name="field_{$profileField.id}" value="{text value=$item allowEmpty=true}"{if $profileField.value==$item} checked="checked"{/if} />
										<label for="field_{$profileField.id}_{$item}"><b>{$item}</b></label> &nbsp;
									{/foreach}
								{elseif $profileField.type==32}
									{html_select_date time="---" year_empty="---" day_empty="---" month_empty="---" start_year="-120" end_year="+0" prefix="field_$fieldID" field_order="DMY"}
								{/if}
							</td>
						</tr>
						{/foreach}
					</table>
				</fieldset>
			</td>
			
			<td width="50%" valign="top">
				<fieldset>
					<legend>{lng p="common"}</legend>
					
					<table width="100%">
						<tr>
							<td class="td1" width="115">{lng p="group"}:</td>
							<td class="td2"><select name="gruppe">
							{foreach from=$groups item=groupItem}
								<option value="{$groupItem.id}"{if $groupItem.id==$defaultGroup} selected="selected"{/if}>{text value=$groupItem.title}</option>
							{/foreach}
							</select></td>
						</tr>
						<tr>
							<td class="td1">{lng p="status"}:</td>
							<td class="td2">
								<select name="gesperrt">
									<option value="no" selected="selected">{lng p="active"}</option>
									<option value="yes">{lng p="locked"}</option>
									<option value="locked">{lng p="notactivated"}</option>
									<option value="delete">{lng p="deleted"}</option>
								</select>
							</td>
						</tr>
						<tr>
							<td class="td1">{lng p="password"}:</td>
							<td class="td2"><input type="password" name="passwort" autocomplete="new-password" style="width:85%;" /></td>
						</tr>
					</table>
				</fieldset>
				
				<fieldset>
					<legend>{lng p="notes"}</legend>
					<textarea style="width:100%;height:80px;" name="notes"></textarea>
				</fieldset>
			</td>
		</tr>
	</table>
				
	<p>
		<div style="float:right;" class="buttons">
			<input class="button" type="submit" value=" {lng p="create"} " />
		</div>
	</p>
</form>
