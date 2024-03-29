<!DOCTYPE html>
<html>
<head>
  <title>{if $title}{$title} - {/if}b1gMail {lng p="acp"}</title>

	<meta http-equiv="content-type" content="text/html; charset={$charset}" />

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link href="{$tpldir}style/common.css?{fileDateSig file="style/common.css"}" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}clientlib/fontawesome/css/font-awesome.min.css?{fileDateSig file="clientlib/fontawesome/css/font-awesome.min.css"}" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}clientlib/fontawesome/css/font-awesome-animation.min.css?{fileDateSig file="clientlib/fontawesome/css/font-awesome-animation.min.css"}" rel="stylesheet" type="text/css" />

	{foreach from=$_cssFiles.admin item=_file}	
		<link rel="stylesheet" type="text/css" href="{$_file}" />
	{/foreach}

	<script>
		const currentSID = '{$sid}';
	</script>
	<script src="../clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}js/common.js?{fileDateSig file="js/common.js"}"></script>

	{foreach from=$_jsFiles.admin item=_file}	
		<script src="{$_file}"></script>
	{/foreach}

	<link href="{$tpldir}style/print.css?{fileDateSig file="style/print.css"}" rel="stylesheet" type="text/css" media="print" />
</head>

<body onload="documentLoader();preloadImages();" topmargin="0">

	<div id="navbar">
		<div id="navbar-first">
			<div id="navbar-logo">
				<div id="logo-right">
					{if $adminRow.type==0}
					<a href="welcome.php?action=phpinfo&sid={$sid}"><img src="{$tpldir}images/phpinfo.png" border="0" alt="" /> {lng p="phpinfo"}</a>
					{/if}
					<a href="admins.php?sid={$sid}"><img src="{$tpldir}images/user_active.png" border="0" alt="" /> {text value=$adminRow.username}</a>
					<a href="index.php?sid={$sid}&action=logout" onclick="return confirm('{lng p="logoutquestion"}');"><img src="{$tpldir}images/logout.png" border="0" alt="" /> {lng p="logout"}</a>
				</div>
				<a href="welcome.php?sid={$sid}">
					<img src="./templates/images/logo_letter.png" border="0" alt="" id="logo" /><img src="./templates/images/logo_text{if !$isGerman}_en{/if}.png" border="0" alt="" />
				</a>
			</div>
		</div>
		<div id="navbar-second">
			<div id="navbar-nav">
				<ul id="nav">
					<li id="welcome-menu"><a href="#welcome-menu"><img src="./templates/images/ico_license.png" />{lng p="welcome"}</a>
						<ul>
							<li><a href="welcome.php?sid={$sid}"><img src="./templates/images/ico_license.png" />{lng p="welcome"}</a></li>
							<li><a href="admins.php?sid={$sid}"><img src="./templates/images/ico_users.png" />{lng p="admins"}</a></li>
						</ul>
					</li>
					{if $adminRow.type==0}
					<li id="prefs-menu"><a href="#prefs-menu"><img src="./templates/images/ico_prefs_misc.png" />{lng p="prefs"}</a>
						<ul>
							<li><a href="prefs.common.php?sid={$sid}"><img src="./templates/images/ico_prefs_common.png" />{lng p="common"}</a></li>
							<li><a href="prefs.email.php?sid={$sid}"><img src="./templates/images/ico_prefs_email.png" />{lng p="email"}</a></li>
							<li><a href="prefs.recvrules.php?sid={$sid}"><img src="./templates/images/rule32.png" />{lng p="recvrules"}</a></li>
							<li><a href="prefs.profilefields.php?sid={$sid}"><img src="./templates/images/field32.png" />{lng p="profilefields"}</a></li>
							<li><a href="prefs.languages.php?sid={$sid}"><img src="./templates/images/lang32.png" />{lng p="languages"}</a></li>
							<li><a href="prefs.templates.php?sid={$sid}"><img src="./templates/images/template.png" />{lng p="templates"}</a></li>
							<li><a href="prefs.ads.php?sid={$sid}"><img src="./templates/images/ad32.png" />{lng p="ads"}</a></li>
							<li><a href="prefs.faq.php?sid={$sid}"><img src="./templates/images/faq32.png" />{lng p="faq"}</a></li>
						</ul>
					</li>
					{/if}
					{if $adminRow.type==0||$adminRow.privileges.users||$adminRow.privileges.groups||$adminRow.privileges.activity||$adminRow.privileges.newsletter}
					<li id="users-menu"><a href="#users-menu"><img src="./templates/images/ico_users.png" />{lng p="usersgroups"}</a>
						<ul>
							{if $adminRow.type==0||$adminRow.privileges.users}<li><a href="users.php?sid={$sid}"><img src="./templates/images/user_action.png" />{lng p="users"}</a></li>{/if}
							{if $adminRow.type==0||$adminRow.privileges.groups}<li><a href="groups.php?sid={$sid}"><img src="./templates/images/ico_group.png" />{lng p="groups"}</a></li>{/if}
							{if $adminRow.type==0||$adminRow.privileges.activity}<li><a href="activity.php?sid={$sid}"><img src="./templates/images/activity.png" />{lng p="activity"}</a></li>{/if}
							{if $adminRow.type==0||$adminRow.privileges.newsletter}<li><a href="newsletter.php?sid={$sid}"><img src="./templates/images/newsletter.png" />{lng p="newsletter"}</a></li>{/if}
						</ul>
					</li>
					{/if}
					{if $adminRow.type==0||$adminRow.privileges.optimize||$adminRow.privileges.maintenance||$adminRow.privileges.stats||$adminRow.privileges.logs}
					<li id="tools-menu"><a href="#tools-menu"><img src="./templates/images/toolbox.png" />{lng p="tools"}</a>
						<ul>
							{if $adminRow.type==0||$adminRow.privileges.optimize}<li><a href="optimize.php?sid={$sid}"><img src="./templates/images/db_optimize.png" />{lng p="optimize"}</a></li>{/if}
							{if $adminRow.type==0||$adminRow.privileges.maintenance}<li><a href="maintenance.php?sid={$sid}"><img src="./templates/images/orphans32.png" />{lng p="maintenance"}</a></li>{/if}
							{if $adminRow.type==0||$adminRow.privileges.stats}<li><a href="stats.php?sid={$sid}"><img src="./templates/images/stats.png" />{lng p="stats"}</a></li>{/if}
							{if $adminRow.type==0||$adminRow.privileges.logs}<li><a href="logs.php?sid={$sid}"><img src="./templates/images/logs.png" />{lng p="logs"}</a></li>{/if}
						</ul>
					</li>
					{/if}
					{if $adminRow.type==0||$adminRow.privileges.plugins}
					<li id="plugins-menu"><a href="#plugins-menu"><img src="./templates/images/plugin.png" />{lng p="plugins"}</a>
						<ul>
							{if $adminRow.type==0}<li><a href="plugins.php?sid={$sid}"><img src="./templates/images/plugin.png" />{lng p="plugins"}</a></li>{/if}
							{foreach from=$pluginMenuItems item=pluginInfo key=plugin}
							{if $adminRow.type==0||$adminRow.privileges.plugins.$plugin}
							<li><a href="plugin.page.php?sid={$sid}&plugin={$plugin}" ><img src="{if $pluginInfo.icon}../plugins/templates/images/{$pluginInfo.icon}{else}./templates/images/wlayout_add.png{/if}" />{$pluginInfo.title}</a></li>
							{/if}
							{/foreach}
						</ul>
					</li>
					{/if}
				</ul>
			</div>
		</div>
	</div>

	{if $title}<div id="breadcrumb">
		{$title}
	</div>{/if}

	<div id="content">
		<div id="pageTabs">
			<ul>
				{foreach from=$tabs item=tab}
				<li{if $tab.active} class="active"{/if}>
					<a href="{$tab.link}sid={$sid}">
						<img src="{if isset($tab.relIcon) && $tab.relIcon}./templates/images/{$tab.relIcon}{elseif $tab.icon}{$tab.icon}{else}./templates/images/ico_prefs_misc.png{/if}" border="0" alt="" />
						{$tab.title}
					</a>
				</li>
				{/foreach}
			</ul>
		</div>
		<div id="pageContent">
			{include file="$page"}
			<br class="clear" />
		</div>
	</div>

	<div id="footer">
		<a href="welcome.php?action=about&sid={$sid}">b1gMail {$bmver}</a>
	</div>

</body>
</html>
