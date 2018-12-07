<div><!-- class="fixed" -->
	
	<nav class="sena-toolbar" role="navigation">
		<div class="row small-collapse">
			<div class="small-12 medium-12 large-12 columns">
			
				<ul class="nav-list inline-list no-bullet">
					<li class="left"><h1 style="margin-bottom:0"><a href="<?=base_url()?>" title="<?php $this->Degeo_application->name(); ?> - <? $this->Degeo_application->description(); ?>"><?php $this->Degeo_application->name(); ?></a></h1></li>
					<? if( !empty( $network_app_menu ) ): ?>
					<ul class="left inline-list" style="margin-left:0;">
					<? foreach( $network_app_menu as $item ): ?>
					<li<?=( strpos( $_SERVER['REQUEST_URI'], $item['url'] ) > 0 ) ? ' class="active"' : '' ?>><a href="<?=$item['url']?>" title="<?=$item['title']?>"><?=$item['label']?></a></li>
					<? endforeach; ?>
					</ul>
					<? endif; ?>
					<? $this->load->view('se-templates/toolbar-menu-right'); ?>
				</ul>
			
			</div>
		</div>
	</nav>
	
</div>