<? if( !empty( $dataset ) ): ?>
<ul>
<? foreach( $dataset as $data ): ?>
<li><a href="<?=site_url("directory/{$data['rank_url']}/{$data['taxon_url']}")?>"><?=$data['taxon_name']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>