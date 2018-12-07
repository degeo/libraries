<? if( !empty( $dataset ) ): ?>
<ul>
<? foreach( $dataset as $data ): ?>
<li><a href="<?=site_url("recipe/{$data['recipe_url']}")?>"><?=$data['recipe_name']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>