<div class="fixed">
<nav class="top-bar sena-toolbar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name">
			<h1><a href="<?=base_url()?>" title="<?php $this->Degeo_application->name(); ?> - <? $this->Degeo_application->description(); ?>"><?php $this->Degeo_application->name(); ?></a></h1>
		</li>
	</ul>

	<section class="top-bar-section">
		<? $this->load->view('se-templates/toolbar-menu-right'); ?>
		<? if( !empty( $network_app_menu ) ): ?>
		<ul class="left">
			<? foreach( $network_app_menu as $item ): ?>
			<li<?=( strpos( $_SERVER['REQUEST_URI'], $item['url'] ) > 0 ) ? ' class="active"' : '' ?>><a href="<?=$item['url']?>" title="<?=$item['title']?>"><?=$item['label']?></a></li>
			<? endforeach; ?>
		</ul>
		<? endif; ?>
	</section>
</nav>
</div>