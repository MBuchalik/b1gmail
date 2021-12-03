<fieldset>
	<legend>{lng p="modfax_gateways_advanced"}</legend>

	<form action="{$pageURL}&action=gateways&sid={$sid}" name="f1" method="post" onsubmit="spin(this)">
	<table class="list">
		<tr>
			<th width="20">&nbsp;</th>
			<th width="25" style="text-align:center;"><a href="javascript:invertSelection(document.forms.f1,'gateways[]');"><img src="{$tpldir}images/dot.png" border="0" alt="" width="10" height="8" /></a></th>
			<th>{lng p="title"}</th>
			<th width="120">{lng p="modfax_protocol"}</th>
			<th width="60">&nbsp;</th>
		</tr>

		{foreach from=$gateways item=gateway}
		{cycle name=class values="td1,td2" assign=class}
		<tr class="{$class}">
			<td align="center"><img src="../plugins/templates/images/modfax_gateway.png" border="0" alt="" width="16" height="16" /></td>
			<td align="center"><input type="checkbox"{if $gateway.default} disabled="disabled"{else} name="gateways[]" value="{$gateway.faxgateid}"{/if} /></td>
			<td>{text value=$gateway.title}</td>
			<td>{if $gateway.protocol==1}{lng p="modfax_email"}{else}{lng p="modfax_http"}{/if}</td>
			<td>
				<a href="{$pageURL}&action=gateways&do=edit&id={$gateway.faxgateid}&sid={$sid}"><img src="{$tpldir}images/edit.png" border="0" alt="{lng p="edit"}" width="16" height="16" /></a>
				{if !$gateway.default}<a href="{$pageURL}&action=gateways&delete={$gateway.faxgateid}&sid={$sid}" onclick="return confirm('{lng p="realdel"}');"><img src="{$tpldir}images/delete.png" border="0" alt="{lng p="delete"}" width="16" height="16" /></a>{/if}
			</td>
		</tr>
		{/foreach}

		<tr>
			<td class="footer" colspan="5">
				<div style="float:left;">
					{lng p="action"}: <select name="massAction" class="smallInput">
						<option value="-">------------</option>

						<optgroup label="{lng p="actions"}">
							<option value="delete">{lng p="delete"}</option>
							<option value="setdefault">{lng p="setdefault"}</option>
						</optgroup>
					</select>&nbsp;
				</div>
				<div style="float:left;">
					<input type="submit" name="executeMassAction" value=" {lng p="execute"} " class="smallInput" />
				</div>
			</td>
		</tr>
	</table>
	</form>
</fieldset>

<fieldset>
	<legend>{lng p="addgateway"}</legend>

	<form action="{$pageURL}&action=gateways&add=true&sid={$sid}" method="post" onsubmit="spin(this)">
		<table width="100%">
			<tr>
				<td width="40" valign="top" rowspan="4"><img src="../plugins/templates/images/modfax_gateway.png" border="0" alt="" width="32" height="32" /></td>
				<td class="td1" width="150">{lng p="title"}:</td>
				<td class="td2"><input type="text" style="width:85%;" id="title" name="title" value="" /></td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_numberformat"}:</td>
				<td class="td2">
					<select name="number_format" id="number_format">
						<option value="1">{lng p="modfax_number_internat_00"}</option>
						<option value="2">{lng p="modfax_number_internat_plus"}</option>
						<option value="3">{lng p="modfax_number_internat_none"}</option>
						<option value="4">{lng p="modfax_number_nat"}</option>
					</select>
				</td>
			</tr>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_protocol"}:</td>
				<td class="td2">
					<input type="radio" name="protocol" value="1" id="protocol1" checked="checked" onchange="toggleFaxGatePrefsForm()" />
					<label for="protocol1">{lng p="modfax_email"}</label>
					<input type="radio" name="protocol" value="2" id="protocol2" onchange="toggleFaxGatePrefsForm()" />
					<label for="protocol2">{lng p="modfax_http"}</label>
				</td>
			</tr>

			<tbody id="protocol1_prefs" style="display:;">
			<tr>
				<td width="40" rowspan="5">&nbsp;</td>
				<td class="td1">{lng p="modfax_emailfrom"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="email_from" name="email_from" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_emailto"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="email_to" name="email_to" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_emailsubject"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="email_subject" name="email_subject" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_emailtext"}:</td>
				<td class="td2">
					<textarea name="email_text" id="email_text" style="width:55%;height:120px;"></textarea>
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_emailpdffile"}:</td>
				<td class="td2">
					<input type="text" style="width:35%;" id="email_pdffile" name="email_pdffile" value="fax.pdf" />
				</td>
			</tr>
			</tbody>

			<tbody id="protocol2_prefs" style="display:none;">
			<tr>
				<td width="40" rowspan="3">&nbsp;</td>
				<td class="td1">{lng p="modfax_httpurl"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="http_url" name="http_url" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_httprequest"}:</td>
				<td class="td2">
					<textarea name="http_request" id="http_request" style="width:55%;height:120px;"></textarea>
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="returnvalue"}:</td>
				<td class="td2">
					<input type="text" style="width:35%;" id="http_returnvalue" name="http_returnvalue" value="100" />
				</td>
			</tr>
			</tbody>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td width="40">&nbsp;</td>
				<td class="td1">{lng p="modfax_supportsstatus"}?</td>
				<td class="td2">
					<input type="radio" name="status_mode" value="0" id="status0" checked="checked" onchange="toggleFaxGatePrefsForm2()" />
					<label for="status0">{lng p="no"}</label>
					<input type="radio" name="status_mode" value="1" id="status1" onchange="toggleFaxGatePrefsForm2()" />
					<label for="status1">{lng p="modfax_status1"}</label>
					<input type="radio" name="status_mode" value="2" id="status2" onchange="toggleFaxGatePrefsForm2()" />
					<label for="status2">{lng p="modfax_status2"}</label>
				</td>
			</tr>

			<tbody id="status1_prefs" style="display:none;">
			<tr>
				<td width="40" rowspan="5">&nbsp;</td>
				<td class="td1">{lng p="modfax_emailfrom"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="status_emailfrom" name="status_emailfrom" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_emailto"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="status_emailto" name="status_emailto" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_emailsubject"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="status_emailsubject" name="status_emailsubject" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_statuscode_from"}:</td>
				<td class="td2">
					<select name="status_code_field" id="status_code_field">
						<option value="subject">{lng p="subject"}</fieldset>
						<option value="text">{lng p="text"}</fieldset>
					</select>
					<input type="text" size="32" id="status_code_regex" name="status_code_regex" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_success_from"}:</td>
				<td class="td2">
					<select name="status_success_field" id="status_success_field">
						<option value="subject">{lng p="subject"}</fieldset>
						<option value="text" selected="selected">{lng p="text"}</fieldset>
					</select>
					<input type="text" size="32" id="status_success_regex" name="status_success_regex" value="" />
				</td>
			</tr>
			</tbody>

			<tbody id="status2_prefs" style="display:none;">
			<tr>
				<td width="40" rowspan="3">&nbsp;</td>
				<td class="td1">{lng p="modfax_statuscode_param"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="status_code_param" name="status_code_param" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_result_param"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="status_result_param" name="status_result_param" value="" />
				</td>
			</tr>
			<tr>
				<td class="td1">{lng p="modfax_success_from"}:</td>
				<td class="td2">
					<input type="text" style="width:60%;" id="status_result_regex" name="status_result_regex" value="" />
				</td>
			</tr>
			</tbody>

			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td width="40" rowspan="2">&nbsp;</td>
				<td class="td1">{lng p="user"}:</td>
				<td class="td2"><input type="text" size="36" id="user" name="user" value="" /></td>
			</tr>
			<tr>
				<td class="td1">{lng p="password"}:</td>
				<td class="td2"><input type="password" autocomplete="off" size="36" id="pass" name="pass" value="" /></td>
			</tr>
		</table>

		<p align="right">
			<input class="button" type="submit" value=" {lng p="add"} " />
		</p>
	</form>
</fieldset>

<fieldset>
	<legend>{lng p="defaults"}</legend>

	<script src="https://service.b1gmail.org/fax/gateways/?lang={$lang}"></script>
</fieldset>

<script>
<!--
	{literal}function toggleFaxGatePrefsForm()
	{
		var protocol = EBID('protocol1').checked ? 1 : 2;
		EBID('protocol1_prefs').style.display = protocol == 1 ? '' : 'none';
		EBID('protocol2_prefs').style.display = protocol == 2 ? '' : 'none';
	}

	function toggleFaxGatePrefsForm2()
	{
		var status = EBID('status1').checked ? 1 : (EBID('status2').checked ? 2 : 0);
		EBID('status1_prefs').style.display = status == 1 ? '' : 'none';
		EBID('status2_prefs').style.display = status == 2 ? '' : 'none';
	}{/literal}
//-->
</script>
