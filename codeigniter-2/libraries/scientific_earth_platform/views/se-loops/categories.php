<? if( !empty( $dataset ) ): ?>
<? foreach( $dataset as $data ): ?>
<!--<li><a href="<?=site_url("category/{$data['category_url']}")?>"><?=$data['category_name']?></a></li>-->
<li><?=$data['category_name']?></li>
<? endforeach; ?>
<? endif; ?>