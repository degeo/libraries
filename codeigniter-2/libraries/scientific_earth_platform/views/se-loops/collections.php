<? if( !empty( $dataset ) ): ?>
<div class="collections">
<? foreach( $dataset as $data ): ?>
<div class="collections-content">
<h4><a href="<?=site_url("collection/{$data['collection_url']}")?>"><?=$data['collection_name']?></a></h4>
</div>
<? endforeach; ?>
</div>
<? endif; ?>