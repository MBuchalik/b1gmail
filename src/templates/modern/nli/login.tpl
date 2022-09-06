<div class="jumbotron splash">
	<div class="container">
		<div class="panel panel-primary login">
			<div class="panel-heading">
				{lng p="welcome"}
			</div>
			<div class="panel-body">
				<form action="{if $ssl_login_enable}{$ssl_url}{/if}index.php?action=login" method="post" id="loginFormMain">
					<input type="hidden" name="do" value="login" />
					<input type="hidden" name="timezone" value="{$timezone}" />

					<div class="alert alert-danger" style="display:none;"></div>

					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							{if $domain_combobox}
							<label class="sr-only" for="email_local">{lng p="email"}</label>
							<input type="text" name="email_local" id="email_local" class="form-control" placeholder="{lng p="email"}" required="true" />
							<div class="input-group-btn">
								<input type="hidden" name="email_domain" data-bind="email-domain" value="{domain value=$domainList[0]}" />
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span data-bind="label">@{domain value=$domainList[0]}</span> <span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-right domainMenu" role="menu">
									{foreach from=$domainList item=domain key=key}<li{if $key==0} class="active"{/if}><a href="#">@{domain value=$domain}</a></li>{/foreach}
								</ul>
							</div>
							{else}
							<label class="sr-only" for="email_full">{lng p="email"}</label>
							<input type="email" name="email_full" id="email_full" class="form-control" placeholder="{lng p="email"}" required="true" />
							{/if}
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<label class="sr-only" for="password">{lng p="password"}</label>
							<input type="password" name="password" id="password" class="form-control" placeholder="{lng p="password"}" required="true" />
						</div>
					</div>
					
					<div class="lost-pw">
						<a href="#" data-toggle="modal" data-target="#lostPW">{lng p="lostpw"}?</a>
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-success btn-block">{lng p="login"}</button>
					</div>
				</form>
			</div>			
		</div>		
	</div>
</div>
