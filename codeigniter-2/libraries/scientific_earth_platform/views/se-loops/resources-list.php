<? if( !empty( $dataset ) ): ?>
<ul class="resources">
<? foreach( $dataset as $data ): ?>
<li><a href="<?=site_url("{$data['resource_url']}}")?>" target="_blank"><?=$data['resource_url']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>