<div class="previewMailHeader">
	<div class="left">
		<h1>{text value=$subject}</h1>
		
		<a href="javascript:advancedOptions('mailHeaders', 'right', 'bottom', '{$tpldir}');" style="float:left;margin-right:0.5em;">
			<img src="{$tpldir}images/li/mini_arrow_{if $narrow}bottom{else}right{/if}.png" width="13" height="13" align="absmiddle" border="0" alt="" id="advanced_mailHeaders_arrow" />
		</a>
		
		<div id="advanced_mailHeaders_body2" style="display:{if $narrow}none{/if};">
			{lng p="from2"}
				{addressList list=$fromAddresses short=true}
			{lng p="to2"}
				{addressList list=$toAddresses short=true}
			<span class="date">
				{date timestamp=$date nice=true}
			</span>
			
			{if $attachments}
			<a href="javascript:advancedOptions('mailHeaders', 'right', 'bottom', '{$tpldir}');"><i class="fa fa-paperclip" aria-hidden="true"></i></a>
			{/if}
		</div>
		
		<div id="advanced_mailHeaders_body" style="display:{if !$narrow}none{/if};">
			<table class="lightTable">
				<tr>
					<th>{lng p="from"}:</th>
					<td>{addressList list=$fromAddresses}</td>
				</tr>
				<tr>
					<th>{lng p="to"}:</th>
					<td>{addressList list=$toAddresses}</td>
				</tr>
				{if $ccAddresses}
				<tr>
					<th>{lng p="cc"}:</th>
					<td>{addressList list=$ccAddresses}</td>
				</tr>
				{/if}
				{if $replyToAddresses}
				<tr>
					<th>{lng p="replyto"}:</th>
					<td>
						{addressList list=$replyToAddresses}
					</td>
				</tr>
				{/if}
				{if $priority!=0}
				<tr>
					<th>{lng p="priority"}:</th>
					<td>
						{if $priority==1}<i class="fa fa-exclamation" aria-hidden="true"></i>{/if}
						{lng p="prio_$priority"}
					</td>
				</tr>
				{/if}
				<tr>
					<th>{lng p="date"}:</th>
					<td>{date timestamp=$date elapsed=true}</td>
				</tr>

				

				{if $attachments}
				<tr>
					<th>{lng p="attachments"}:</th>
					<td>
						{foreach from=$attachments item=attachment key=attID}
						<i class="fa fa-file-o"></i>
						{if $attachment.mimetype=='message/rfc822'||$attachment.filetype=='.eml'}
						<a href="javascript:showAttachedMail({$mailID}, '{$attID}', '{text value=$attachment.filename cut=45 escape=true}');">
						{elseif $attachment.mimetype=='application/zip'||$attachment.filetype=='.zip'}
						<a href="javascript:showAttachedZIP({$mailID}, '{$attID}', '{text value=$attachment.filename cut=45 escape=true}');">
						{else}
						<a href="email.read.php?id={$mailID}&action=downloadAttachment&attachment={$attID}{if $attachment.viewable}&view=true{/if}&sid={$sid}" target="_blank">
						{/if}
							{text value=$attachment.filename cut=45}
							({size bytes=$attachment.size})</a>
						&nbsp;
						{/foreach}
					</td>
				</tr>
				{/if}

				{if $notes}
				<tr>
					<th>{lng p="notes"}:</th>
					<td>
						<textarea style="width:100%;height:60px;" readonly="readonly">{text value=$notes allowEmpty=true}</textarea>
					</td>
				</tr>
				{/if}

			
			</table>
		</div>
	</div>
	<div class="right">
		<button onclick="currentID={$mailID};showMailMenu(event,this);">
			<i class="fa fa-gears"></i>
			{lng p="actions"}
			<img src="{$tpldir}images/li/ico_btn_dropdown.png" border="0" alt="" align="absmiddle" />
		</button>
	</div>
</div>

<div id="bigFormToolbar">
	
	<button type="button" onclick="mailReply({$mailID},false);">
		<i class="fa fa-mail-reply"></i>
		{lng p="reply"}
	</button>
	
	<button type="button" onclick="mailReply({$mailID},true);">
		<i class="fa fa-mail-reply-all"></i>
		{lng p="replyall"}
	</button>
	
	<button type="button" onclick="document.location.href='email.compose.php?sid={$sid}&forward={$mailID}';">
		<i class="fa fa-mail-forward"></i>
		{lng p="forward"}
	</button>
	
	<button type="button" onclick="printMail({$mailID},'{$sid}');">
		<i class="fa fa-print"></i>
		{lng p="print"}
	</button>
	
	{if !isset($folderInfo.readonly) || !$folderInfo.readonly}<button type="button" onclick="{if $folderID==-5}if(confirm('{lng p="realdel"}')) {/if} deleteMail({$mailID});">
		<i class="fa fa-remove"></i>
		{lng p="delete"}
	</button>{/if}

	{if $folderID!=-4}
		<button type="button" onclick="javascript:setMailSpamStatus({$mailID}, true, true)">
			<i class="fa fa-frown-o"></i>
			{lng p="spam"}!
		</button>
	{/if}
</div>

{if $folderID==-3}
<div class="mailNote preview">
	&nbsp;
	<i class="fa fa-envelope" aria-hidden="true"></i>
	{lng p="thisisadraft"}
	<a href="email.compose.php?redirect={$mailID}&sid={$sid}">{lng p="editsend"}</a>
</div>
{/if}
{if $flags&128}
<div class="mailWarning preview">
	&nbsp;
	<img align="absmiddle" border="0" alt="" src="{$tpldir}images/li/infected.png" width="16" height="16" />
	{lng p="infectedtext"}: {$infection}
</div>
{/if}
{if $flags&256}
<div class="mailNote preview" id="spamQuestionDiv" style="display:;">
	&nbsp;
    <i class="fa fa-ban" aria-hidden="true"></i>    
	{lng p="spamtext"}
	<a href="javascript:setMailSpamStatus({$mailID}, false, true)">{lng p="isnotspam"}</a>
</div>
{/if}
{if $flags&512}
<div class="mailNote preview">
	&nbsp;
	<i class="fa fa-comment-o" aria-hidden="true"></i>
	{lng p="certmailinfo"}
</div>
{/if}
{if $htmlAvailable}
<div class="mailNote preview">
	&nbsp;
	<i class="fa fa-comment-o" aria-hidden="true"></i>
	{lng p="htmlavailable"}
	<a href="email.read.php?sid={$sid}&id={$mailID}&htmlView=true">{lng p="view"} &raquo;</a>
</div>
{/if}
{if $noExternal}
<div class="mailNote preview" id="noExternalDiv" style="display:;">
	&nbsp;
	<i class="fa fa-comment-o" aria-hidden="true"></i>
	{lng p="noexternal"}
	<a href="email.read.php?action=inlineHTML&mode={$textMode}&id={$mailID}&sid={$sid}&enableExternal=true" target="textArea" onclick="document.getElementById('noExternalDiv').style.display='none';">{lng p="showexternal"} &raquo;</a>
</div>
{/if}
{if $confirmationTo}
<div class="mailNote preview" id="confirmationDiv" style="display:;">
	&nbsp;
	<i class="fa fa-sign-language" aria-hidden="true"></i>
	{lng p="senderconfirmto"}
	<b>{text value=$confirmationTo}</b>.
	<a href="javascript:sendMailConfirmation({$mailID});">{lng p="sendconfirmation"} &raquo;</a>
</div>
{elseif $flags&16384}
<div class="mailNote preview" id="confirmationDiv" style="display:;">
	&nbsp;
	<i class="fa fa-sign-language" aria-hidden="true"></i>
	{lng p="confirmationsent"}
</div>
{/if}

<iframe width="100%" style="height:200px;" id="textArea" name="textArea" src="about:blank" frameborder="no"></iframe>
<textarea id="textArea_raw" style="display:none;">{text allowEmpty=true value=$text allowDoubleEnc=true}</textarea>

<form id="quoteForm" action="email.compose.php?sid={$sid}&reply={$mailID}" method="post">
	<input type="hidden" name="text" id="quoteText" value="" />
</form>

