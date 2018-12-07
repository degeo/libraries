<? if( !empty( $dataset ) ): ?>
<ul>
<? foreach( $dataset as $data ): ?>
<li><a href="<?=site_url("collection/{$data['collection_url']}")?>"><?=$data['collection_name']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>