<? if( !empty( $taxon['taxonomy'] ) ): ?>
<ul class="breadcrumbs">
<? foreach( $taxon['taxonomy'] as $r => $t ): if( empty($t) ) continue; ?>
<li><div style="display:inline-block;"><a href="<?=site_url("directory/{$t['rank_url']}/{$t['taxon_url']}")?>"><strong><?=ucwords($t['taxon_name'])?></strong><br/><?=ucwords($r)?></a></div></li>
<? endforeach; ?>
</ul>
<? endif; ?>