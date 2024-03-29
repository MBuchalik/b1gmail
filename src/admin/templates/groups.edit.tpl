<form method="post" action="groups.php?{if isset($create) && $create}action=create&create=true{else}do=edit&id={$group.id}&save=true{/if}&sid={$sid}" onsubmit="spin(this)">
<table width="100%" cellspacing="2" cellpadding="0">
	<tr>
		<td valign="top" width="50%">
			<fieldset>
				<legend>{lng p="common"}</legend>
				
				<table width="100%">
					<tr>
						<td class="td1" width="160">{lng p="title"}:</td>
						<td class="td2"><input type="text" name="titel" value="{text value=$group.titel allowEmpty=true}" style="width:85%;" /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="htmlview"}?</td>
						<td class="td2"><input type="checkbox" name="soforthtml"{if $group.soforthtml=='yes'} checked="checked"{/if} /></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend>{lng p="storage"}</legend>
				
				<table width="100%">
					<tr>
						<td class="td1" width="160">{lng p="email"}:</td>
						<td class="td2"><input type="text" name="storage" value="{$group.storage/1024/1024}" size="8" /> MB</td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend>{lng p="limits"}</legend>
				
				<table width="100%">
					<tr>
						<td class="td1" width="160">{lng p="emailin"}:</td>
						<td class="td2"><input type="text" name="maxsize" value="{$group.maxsize/1024}" size="8" /> KB</td>
					</tr>
					<tr>
						<td class="td1">{lng p="emailout"}:</td>
						<td class="td2"><input type="text" name="anlagen" value="{$group.anlagen/1024}" size="8" /> KB</td>
					</tr>
					<tr>
						<td class="td1" width="220">{lng p="maxrecps"}:</td>
						<td class="td2"><input type="text" name="max_recps" value="{$group.max_recps}" size="8" /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="sendlimit"}:</td>
						<td class="td2"><input type="text" name="send_limit_count" value="{$group.send_limit_count}" size="8" />
										{lng p="emailsin"}
										<input type="text" name="send_limit_time" value="{$group.send_limit_time}" size="8" />
										{lng p="minutes"}</td>
					</tr>
					<tr>
						<td class="td1">{lng p="aliases"}:</td>
						<td class="td2"><input type="text" name="aliase" value="{$group.aliase}" size="8" /></td>
					</tr>					
					<tr>
						<td class="td1">{lng p="allownewsoptout"}?</td>
						<td class="td2"><input name="allow_newsletter_optout"{if $group.allow_newsletter_optout=='yes'} checked="checked"{/if} type="checkbox" /></td>
					</tr>
				</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset>
				<legend>{lng p="services"}</legend>
				
				<table width="100%">
					<tr>
						<td class="td1" width="150">{lng p="autoresponder"}?</td>
						<td class="td2"><input type="checkbox" name="responder"{if $group.responder=='yes'} checked="checked"{/if} /></td>
						<td class="td1" width="150">{lng p="forward"}?</td>
						<td class="td2"><input type="checkbox" name="forward"{if $group.forward=='yes'} checked="checked"{/if} /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="ads"}?</td>
						<td class="td2"><input type="checkbox" name="ads"{if $group.ads=='yes'} checked="checked"{/if} /></td>
						<td class="td2" colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td class="td1">{lng p="mobileaccess"}?</td>
						<td class="td2"><input type="checkbox" name="wap"{if $group.wap=='yes'} checked="checked"{/if} /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="smtp"}?</td>
						<td class="td2"><input type="checkbox" name="smtp"{if $group.smtp=='yes'} checked="checked"{/if} /></td>
						<td class="td1">{lng p="pop3"}?</td>
						<td class="td2"><input type="checkbox" name="pop3"{if $group.pop3=='yes'} checked="checked"{/if} /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="imap"}?</td>
						<td class="td2"><input type="checkbox" name="imap"{if $group.imap=='yes'} checked="checked"{/if} /></td>
						<td colspan="2">&nbsp;</td>
					</tr>					
					<tr>
						<td class="td1">{lng p="sender_aliases"}?</td>
						<td class="td2"><input type="checkbox" name="sender_aliases"{if $group.sender_aliases=='yes'} checked="checked"{/if} /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="ftsearch"}?</td>
						<td class="td2"><input type="checkbox" name="ftsearch"{if !$ftsSupport} disabled="disabled"{else}{if $group.ftsearch=='yes'} checked="checked"{/if}{/if} /></td>
					</tr>
					<tr>
						<td class="td1">{lng p="auto_save_drafts"}?</td>
						<td class="td2"><input type="checkbox" name="auto_save_drafts"{if $group.auto_save_drafts=='yes'} checked="checked"{/if} /></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend>{lng p="aliasdomains"}</legend>
				
				<textarea style="width:100%;height:80px;" name="saliase">{text value=$group.saliase allowEmpty=true}</textarea>
				<small>{lng p="sepby"}</small>
			</fieldset>
			
			<fieldset>
				<legend>{lng p="misc"}</legend>
				
				<table width="100%">
					<tr>
						<td class="td1">{lng p="mailsig"}:</td>
						<td class="td2"><textarea style="width:100%;height:80px;" name="signatur">{text value=$group.signatur allowEmpty=true}</textarea></td>
					</tr>
					
					{foreach from=$groupOptions key=fieldKey item=fieldInfo}
					<tr>
						<td class="td1">{$fieldInfo.desc}</td>
						<td class="td2">
							{if $fieldInfo.type==16}
								<textarea style="width:100%;height:80px;" name="{$fieldKey}">{text value=$fieldInfo.value allowEmpty=true}</textarea></td>
							{elseif $fieldInfo.type==8}
								{foreach from=$fieldInfo.options item=optionValue key=optionKey}
								<input type="radio" name="{$fieldKey}" id="{$fieldKey}_{$optionKey}" value="{$optionKey}"{if $fieldInfo.value==$optionKey} checked="checked"{/if} />
									<label for="{$fieldKey}_{$optionKey}">{text value=$optionValue}</label>
								{/foreach}
							{elseif $fieldInfo.type==4}
								<select name="{$fieldKey}">
								{foreach from=$fieldInfo.options item=optionValue key=optionKey}
									<option value="{$optionKey}"{if $fieldInfo.value==$optionKey} selected="selected"{/if}>{text value=$optionValue}</option>
								{/foreach}									
								</select>
							{elseif $fieldInfo.type==2}
								<input type="checkbox" name="{$fieldKey}" value="1"{if $fieldInfo.value} checked="checked"{/if} />
							{elseif $fieldInfo.type==1}
								<input type="text" style="width:85%;" name="{$fieldKey}" value="{text value=$fieldInfo.value allowEmpty=true}" />
							{/if}
					</tr>
					{/foreach}
				</table>
			</fieldset>
		</td>
	</tr>
</table>
<p>
	{if !isset($create) || !$create}<div style="float:left" class="buttons">
		&nbsp;{lng p="action"}:
		<select name="groupAction" id="groupAction">
			<optgroup label="{lng p="actions"}">
				<option value="newsletter.php?toGroup={$group.id}&sid={$sid}">{lng p="sendmail"}</option>
				<option value="groups.php?singleAction=delete&singleID={$group.id}&sid={$sid}">{lng p="delete"}</option>
			</optgroup>
		</select>
	</div>
	<div style="float:left">
		<input class="button" type="button" value=" {lng p="ok"} " onclick="executeAction('groupAction');" />
	</div>{/if}
	<div style="float:right" class="buttons">
		<input class="button" type="submit" value=" {lng p="save"} " />
	</div>
</p>
</form>
<br /><br />
