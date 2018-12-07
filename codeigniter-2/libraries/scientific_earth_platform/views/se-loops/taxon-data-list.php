<? if( !empty( $taxon['data'] ) ): ?>
<ul class="inline-list" style="margin:0">
<? foreach( $taxon['data'] as $set ): ?>
<li>
<ul class="button-group round">
	<? $disabled = ' class="small disabled success button"'; ?>
	<li><a<?=( !empty( $set['key_article_url'] ) ) ? ' href="' . site_url("data/term/{$set['key_term_url']}") . '" class="small success button"' : $disabled ?>><?=$set['key_term_name']?></a></li>
	<? $disabled = ' class="small disabled secondary button"'; ?>
	<li><a class="small disabled secondary button"><?=$set['value_term_name']?></a></li>
	<!--<li><a<?=( !empty( $set['value_article_url'] ) ) ? ' href="' . site_url("data/term/{$set['value_term_url']}") . '" class="small secondary button"' : $disabled ?>><?=$set['value_term_name']?></a></li>-->
	<? if( !empty( $set['values'] ) ): ?>
	<? foreach( $set['values'] as $value ): ?>
	<li><a class="small disabled secondary button"><?=$value['value_term_name']?></a></li>
	<!--<li><a<?=( !empty( $value['value_article_url'] ) ) ? ' href="' . site_url("data/term/{$value['value_term_url']}") . '" class="small secondary button"' : $disabled ?>><?=$value['value_term_name']?></a></li>-->
	<? endforeach; ?>
	<? endif; ?>
</ul>
</li>
<? endforeach; ?>
</ul>
<? endif; ?>