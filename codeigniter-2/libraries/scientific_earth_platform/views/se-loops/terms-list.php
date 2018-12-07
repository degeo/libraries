<? if( !empty( $dataset ) ): ?>
<ul>
<? foreach( $dataset as $data ): ?>
<li><a href="<?=site_url("term/{$data['term_url']}")?>"><?=$data['term_name']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>