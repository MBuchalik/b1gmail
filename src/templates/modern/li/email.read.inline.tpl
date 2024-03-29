<!DOCTYPE html>

<html>

<head>
    <title>{text value=$subject}</title>
    
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	
	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />
	<link href="{$tpldir}style/print.css" rel="stylesheet" type="text/css" />
</head>

<body>

	<table id="headerTable">
		<tr>
			<td class="headerField">{lng p="from"}:</td>
			<td>{addressList list=$fromAddresses simple=true}</td>
		</tr>
		<tr>
			<td class="headerField">{lng p="subject"}:</td>
			<td>{text value=$subject}</td>
		</tr>
		<tr>
			<td class="headerField">{lng p="date"}:</td>
			<td>{date timestamp=$date}</td>
		</tr>
		<tr>
			<td class="headerField">{lng p="to"}:</td>
			<td>{addressList list=$toAddresses simple=true}</td>
		</tr>
		{if $ccAddresses}<tr>
			<td class="headerField">{lng p="cc"}:</td>
			<td>{addressList list=$ccAddresses simple=true}</td>
		</tr>{/if}
		{if $replyToAddresses}<tr>
			<td class="headerField">{lng p="replyto"}:</td>
			<td>{addressList list=$replyToAddresses simple=true}</td>
		</tr>{/if}
		{if $priority!=0}<tr>
			<td class="headerField">{lng p="priority"}:</td>
			<td>{if $priority==1}<i class="fa fa-exclamation" aria-hidden="true"></i>{/if}
				{lng p="prio_$priority"}</td>
		</tr>{/if}
	</table>

	<div style="font-family:{if $plaintextCourier}courier{else}arial{/if};size:11px;padding-top:1em;">
		{$text}
	</div>
	
</body>

</html>
