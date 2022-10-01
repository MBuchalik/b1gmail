<!DOCTYPE html>
<html lang="{lng p="langCode"}">

<head>
    <title>{if $pageTitle}{text value=$pageTitle} - {/if}{$service_title}</title>

	<meta http-equiv="content-type" content="text/html; charset={$charset}" />

	<link rel="shortcut icon" type="image/png" href="{$tpldir}res/favicon.png" />
	<link href="{$tpldir}style/loggedin.css?{fileDateSig file="style/loggedin.css"}" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}style/dtree.css?{fileDateSig file="style/dtree.css"}" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}clientlib/fontawesome/css/font-awesome.min.css?{fileDateSig file="clientlib/fontawesome/css/font-awesome.min.css"}" rel="stylesheet" type="text/css" />
	<link href="{$tpldir}clientlib/fontawesome/css/font-awesome-animation.min.css?{fileDateSig file="clientlib/fontawesome/css/font-awesome-animation.min.css"}" rel="stylesheet" type="text/css" />
{foreach from=$_cssFiles.li item=_file}	<link rel="stylesheet" type="text/css" href="{$_file}" />
{/foreach}

	<script>
		const currentSID = '{$sid}';
		const tplDir = '{$tpldir}';
		const serverTZ = {$serverTZ};
		const ftsBGIndexing = {if $ftsBGIndexing}true{else}false{/if};
		{if $bmNotifyInterval}
			const	notifyInterval = {$bmNotifyInterval}; 
			const notifySound = {if $bmNotifySound}true{else}false{/if};
		{/if}
	</script>
	<script src="clientlang.php?sid={$sid}"></script>
	<script src="{$tpldir}js/common.js?{fileDateSig file="js/common.js"}"></script>
	<script src="{$tpldir}js/loggedin.js?{fileDateSig file="js/loggedin.js"}"></script>
	<script src="{$tpldir}clientlib/dtree.js?{fileDateSig file="clientlib/dtree.js"}"></script>
	<script src="{$tpldir}clientlib/overlay.js?{fileDateSig file="clientlib/overlay.js"}"></script>
	<script src="{$tpldir}clientlib/autocomplete.js?{fileDateSig file="clientlib/autocomplete.js"}"></script>

	{foreach from=$_jsFiles.li item=_file}
		<script src="{$_file}"></script>
	{/foreach}

	{hook id="li:index.tpl:head"}
</head>

<body onload="documentLoader()">
	{hook id="li:index.tpl:beforeContent"}

	<div id="main" class="
		{if isset($disablePageMenu) && $disablePageMenu} no-pageMenu {/if}
		{if $pageContent === 'li/start.page.tpl'} page-start {/if}
	">
		<div class="dropdownNavbar">
			<button id="menu-activate-small" onclick="toggleDropdownNavMenu()">
				<i class="fa fa-bars"></i>
			</button>				
			<div id="menu-inline">
				{foreach from=$pageTabs key=tabID item=tab}
					{if $tabID=='organizer'}
						<a href="organizer.addressbook.php?sid={$sid}" title="{lng p="addressbook"}"{if $activeTab==$tabID && $organizerSection=='addressbook'} class="active"{/if}>
							<i class="fa fa-users"></i>
						</a>
					{else}
						{comment text="tab $tabID"}
						<a href="{$tab.link}{$sid}" title="{$tab.text}"{if $activeTab==$tabID} class="active"{/if}>
							<i class="fa {$tab.faIcon}"></i>
						</a>
					{/if}
				{/foreach}
			</div>

			<div class="toolbar right">
				{if $bmNotifyInterval>0}<a href="#" onclick="showNotifications(this)" title="{lng p="notifications"}" style="position:relative;"><i id="notifyIcon" class="fa fa-bell faa-ring"></i><div class="noBadge" id="notifyCount"{if $bmUnreadNotifications==0} style="display:none;"{/if}>{number value=$bmUnreadNotifications min=0 max=99}</div></a>{/if}
				<a href="#" onclick="showNewMenu(this)" title="{lng p="new"}"><i class="fa fa-plus-square fa-lg"></i> {lng p="new"}
							| <i class="fa fa-angle-down"></i></a>
				<a href="#" onclick="showSearchPopup(this)" title="{lng p="search"}"><i class="fa fa-search"></i></a>
				<a href="prefs.php?action=faq&sid={$sid}" title="{lng p="faq"}"><i class="fa fa-question fa-lg"></i></a>
				<a href="start.php?sid={$sid}&action=logout" onclick="return confirm('{lng p="logoutquestion"}');" title="{lng p="logout"}"><i class="fa fa-sign-out fa-lg"></i></a>
			</div>

			<div class="toolbar">
				{if isset($pageToolbarFile)}
				{comment text="including $pageToolbarFile"}
				{include file="$pageToolbarFile"}
				{elseif isset($pageToolbar)}
				{$pageToolbar}
				{else}
				&nbsp;
				{/if}
			</div>

			<div class="menu fade" id="dropdownNavMenu" style="display:none;">
				<div class="arrow"></div>
				{foreach from=$pageTabs key=tabID item=tab}
				{comment text="tab $tabID"}
				<a href="{$tab.link}{$sid}" title="{$tab.text}"{if $activeTab==$tabID} class="active"{/if}>
					<i class="fa {$tab.faIcon}"></i>
					{$tab.text}
				</a>
				{/foreach}
			</div>
		</div>

		{if !isset($disablePageMenu) || !$disablePageMenu}
		<div id="mainMenu" class="up">
			<div id="mainMenuContainer">
	            {if $pageMenuFile}
	            {comment text="including $pageMenuFile"}
	            {include file="$pageMenuFile"}
	            {else}
	            {foreach from=$pageMenu key=menuID item=menu}
	            {comment text="menuitem $menuID"}
	           	<a href="{$menu.link}">
		            <img src="{$tpldir}images/li/menu_ico_{$menu.icon}.png" width="16" height="16" border="0" alt="" align="absmiddle" />
		            {$menu.text}
	            </a>
	            {if $menu.addText}
	            <span class="menuAddText">{$menu.addText}</span>
	            {/if}
	            <br />
	        	{/foreach}
	            {/if}
            </div>
		</div>
		{/if}

		<div id="mainBanner" style="display:none;">
			{banner}
		</div>

		<div id="mainContent" class="up">
			{include file="$pageContent"}
		</div>

	  {comment text="search popup"}
	  <div class="headerBox" id="searchPopup" style="display:none">
			<div class="arrow"></div>
			<div class="inner">
				<table width="100%" cellspacing="0" cellpadding="0" class="up" onmouseover="disableHide=true;" onmouseout="disableHide=false;">
					<tr>
						<td>
							<div class="arrow"></div>
							<table cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<td width="22" height="26" align="right"><i id="searchSpinner" style="display:none;" class="fa fa-spinner fa-pulse fa-fw"></i></td>
									<td align="right" width="70">{lng p="search"}: &nbsp;</td>
									<td align="center">
										<input id="searchField" name="searchField" style="width:90%" onkeypress="searchFieldKeyPress(event,{if $searchDetailsDefault}true{else}false{/if})" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tbody id="searchResultBody" style="display:none">
					<tr>
						<td id="searchResults"></td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>

	  {comment text="new menu"}
		<div class="headerBox" id="newMenu" style="display:none;">
			<div class="arrow"></div>
			<div class="inner">
			{foreach from=$newMenu item=item}
				{if isset($item.sep) && $item.sep}
				<div class="mailMenuSep"></div>
				{else}
				<a class="mailMenuItem" href="{$item.link}{$sid}"><i class="fa {$item.faIcon}" aria-hidden="true"></i> {$item.text}...</a>
				{/if}
			{/foreach}
			</div>
		</div>

		{comment text="notifications"}
		<div class="headerBox" id="notifyBox" style="display:none;">
			<div class="arrow"></div>
			<div class="inner" id="notifyInner"></div>
		</div>

	</div>

	{hook id="li:index.tpl:afterContent"}
</body>

</html>
