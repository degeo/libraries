<? if( !empty( $dataset ) ): ?>
<ul>
<? foreach( $dataset as $data ): ?>
<li><a href="<?=site_url("article/{$data['article_url']}")?>"><?=$data['article_title']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>