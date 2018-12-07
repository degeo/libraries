<ul class="right inline-list">
	<? if( !empty( $network_app_menu_right ) ): ?>
		<? foreach( $network_app_menu_right as $item ): ?>
		<li<?=( strpos( $_SERVER['REQUEST_URI'], $item['url'] ) > 0 ) ? ' class="active"' : '' ?>><a href="<?=$item['url']?>" title="<?=$item['title']?>"><?=$item['label']?></a></li>
		<? endforeach; ?>
	<? endif; ?>
	
	<? if( $this->Se_app_user->is_loggedin() ): ?>
	<li>
		<a class="dropnavi" data-dropdown="networknavi" aria-controls="networknavi" aria-expanded="false">
			<span class="fi-list" style="font-size:1.75rem;line-height:1.8;"></span>
		</a>
		<ul id="networknavi" class="f-dropdown" data-dropdown-content aria-hidden="true" tabindex="-1">
			<li><a href="http://www.scientificearth.net/" target="_blank">Scientific Earth</a></li>
			<li><a href="http://www.mytankmate.com/" target="_blank">My Tankmate</a></li>
			<li><a href="http://www.speciesimages.com/" target="_blank">Species Images</a></li>
			<li><a href="http://www.binarygardener.com/">Binary Gardener</a></li>
			<li><a href="http://www.plantsgrowingnearby.com/">Plants Growing Nearby</a></li>
		</ul>
	</li>
	<li class="usernavi-wrapper">
		<a class="dropnavi" data-dropdown="usernavi" aria-controls="usernavi" aria-expanded="false">
			<span class="fi-torso"></span> <?= $this->Se_app_user->get_info('username'); ?><br/>
			<span class="fi-trophy" style="display:inline-block"></span> <?= $this->Se_app_user->get_points() ?> points
		</a>
		<ul id="usernavi" class="f-dropdown" data-dropdown-content aria-hidden="true" tabindex="-1">
			<li><a href="http://accounts.scientificearth.net/account" target="_blank"><span class="fi-layout"></span> Network Dashboard</a></li>
			<li><a href="http://accounts.scientificearth.net/account/feedback" target="_blank"><span class="fi-mail"></span> Send Feedback</a></li>
			<li><a href="http://accounts.scientificearth.net/account/settings" target="_blank"><span class="fi-wrench"></span> Account Settings</a></li>
			<li><a href="http://accounts.scientificearth.net/authorize/logout?goto=<?=base_url()?>"><span class="fi-shield"></span> Logout</a></li>
		</ul>
	</li>
	
	<? else: ?>
	<li class="has-form"><a class="success button" href="http://accounts.scientificearth.net/authorize/login?goto=<?=base_url()?>"><span class="fi-shield"></span> Login or Create an Account</a></li>
	<? endif; ?>
</ul>