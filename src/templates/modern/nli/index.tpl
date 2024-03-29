<!DOCTYPE html>
<html lang="{lng p="langCode"}">

<head>
	<meta http-equiv="content-type" content="text/html; charset={$charset}" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	{if isset($robotsNoIndex) && $robotsNoIndex}<meta name="robots" content="noindex" />{/if}

	<title>{$service_title}{if isset($pageTitle)} - {text value=$pageTitle}{/if}</title>

	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />

	<link rel="stylesheet" href="{$tpldir}bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="{$tpldir}style/notloggedin.css?{fileDateSig file="style/notloggedin.css"}" />

	<script>
		const tplDir = '{$tpldir}';
		const sslURL = '{$ssl_url}';
		const serverTZ = {$serverTZ};
	</script>

	<script src="clientlang.php"></script>
	<script src="{$tpldir}clientlib/jquery/jquery-1.8.2.min.js"></script>
	<script src="{$tpldir}bootstrap/js/bootstrap.min.js"></script>
	<script src="{$tpldir}js/nli.main.js?{fileDateSig file="js/nli.main.js"}"></script>
	{hook id="nli:index.tpl:head"}
</head>

<body>
	{hook id="nli:index.tpl:beforeContent"}

	<div class="navbar {if $page!='nli/login.tpl'}navbar-inverse{/if} navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">{$service_title}</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li{if $smarty.request.action=='login'} class="active"{/if}><a href="index.php">{lng p="home"}</a></li>
				{foreach from=$pluginUserPages item=item}{if $item.top&&$item.after=='login'}
					<li{if $item.active} class="active"{/if}><a href="{$item.link}">{$item.text}</a></li>
				{/if}{/foreach}					
				{foreach from=$pluginUserPages item=item}{if $item.top&&$item.after=='signup'}
					<li{if $item.active} class="active"{/if}><a href="{$item.link}">{$item.text}</a></li>
				{/if}{/foreach}
					<li{if $smarty.request.action=='faq'} class="active"{/if}><a href="index.php?action=faq">{lng p="faq"}</a></li>
				{foreach from=$pluginUserPages item=item}{if $item.top&&$item.after=='faq'}
					<li{if $item.active} class="active"{/if}><a href="{$item.link}">{$item.text}</a></li>
				{/if}{/foreach}
					<li{if $smarty.request.action=='tos'} class="active"{/if}><a href="index.php?action=tos">{lng p="tos"}</a></li>
				{foreach from=$pluginUserPages item=item}{if $item.top&&(!$item.after||$item.after=='tos')}
					<li{if $item.active} class="active"{/if}><a href="{$item.link}">{$item.text}</a></li>
				{/if}{/foreach}
					<li{if $smarty.request.action=='privacy-policy'} class="active"{/if}><a href="index.php?action=privacy-policy">{lng p="privacy"}</a></li>
					<li{if $smarty.request.action=='imprint'} class="active"{/if}><a href="index.php?action=imprint">{lng p="imprint"}</a></li>
				</ul>
				<form action="{if $ssl_login_enable}{$ssl_url}{/if}index.php?action=login" method="post" id="loginFormPopover">
					<input type="hidden" name="do" value="login" />
					<input type="hidden" name="timezone" value="{$timezone}" />

					<ul class="nav navbar-nav navbar-right">
						{if $smarty.request.action!='login'}<li class="login-li hidden-xs">							
							<button type="button" class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="popover" data-placement="bottom">
								{lng p="login"} <span class="caret"></span>
							</button>
						</li>{/if}

						{if count($languageList) > 1}
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">{foreach from=$languageList key=langKey item=langInfo}{if $langInfo.active}{$langInfo.title}{/if}{/foreach} <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									{foreach from=$languageList key=langKey item=langInfo}
									<li{if $langInfo.active} class="active"{/if}><a href="index.php?action=switchLanguage&amp;lang={$langKey}{if isset($smarty.get.action)}&amp;target={text value=$smarty.get.action}{/if}">{$langInfo.title}</a></li>
									{/foreach}
								</ul>
							</li>
						{/if}
					</ul>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="lostPW" tabindex="-1" role="dialog" aria-labelledby="lostPWLabel" aria-hidden="true">
		<div class="modal-dialog">
			<form action="index.php?action=lostPassword" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{lng p="cancel"}</span></button>
					<h4 class="modal-title" id="lostPWLabel">{lng p="lostpw"}</h4>
				</div>
				<div class="modal-body">
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
					{if $domain_combobox}
						<label class="sr-only" for="email_local_lpw">{lng p="email"}</label>
						<input type="text" name="email_local" id="email_local_lpw" class="form-control" placeholder="{lng p="email"}" required="true" />
						<div class="input-group-btn">
							<input type="hidden" name="email_domain" data-bind="email-domain" value="{domain value=$domainList[0]}" />
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span data-bind="label">@{domain value=$domainList[0]}</span> <span class="caret"></span></button>
							<ul class="dropdown-menu dropdown-menu-right domainMenu" role="menu">
								{foreach from=$domainList item=domain key=_key}<li{if $_key==0} class="active"{/if}><a href="#">@{domain value=$domain}</a></li>{/foreach}
							</ul>
						</div>
					{else}
						<label class="sr-only" for="email_full_p">{lng p="email"}</label>
						<input type="email" name="email_full" id="email_full_lpw" class="form-control" placeholder="{lng p="email"}" required="true" />
					{/if}
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{lng p="cancel"}</button>
					<button type="submit" class="btn btn-success">{lng p="requestpw"}</button>
				</div>
			</div>
			</form>
		</div>
	</div>

	<div id="loginPopover" style="display: none;">
		<div class="alert alert-danger" style="display:none;"></div>

		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			{if $domain_combobox}
				<label class="sr-only" for="email_local_p">{lng p="email"}</label>
				<input type="text" name="email_local" id="email_local_p" class="form-control" placeholder="{lng p="email"}" required="true" />
				<div class="input-group-btn">
					<input type="hidden" name="email_domain" data-bind="email-domain" value="{domain value=$domainList[0]}" />
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span data-bind="label">@{domain value=$domainList[0]}</span> <span class="caret"></span></button>
					<ul class="dropdown-menu dropdown-menu-right domainMenu" role="menu">
						{foreach from=$domainList item=domain key=_key}<li{if $_key==0} class="active"{/if}><a href="#">@{domain value=$domain}</a></li>{/foreach}
					</ul>
				</div>
			{else}
				<label class="sr-only" for="email_full_p">{lng p="email"}</label>
				<input type="email" name="email_full" id="email_full_p" class="form-control" placeholder="{lng p="email"}" required="true" />
			{/if}
			</div>
		</div>
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
				<label class="sr-only" for="password_p">{lng p="password"}</label>
				<input type="password" name="password" id="password_p" class="form-control" placeholder="{lng p="password"}" required="true" />
			</div>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-success btn-block">{lng p="login"}</button>
		</div>

		<div class="login-lostpw">
			<a href="#" data-toggle="modal" data-target="#lostPW">{lng p="lostpw"}?</a>
		</div>
	</div>

	{include file="$page"}	

	{hook id="nli:index.tpl:afterContent"}
</body>

</html>
