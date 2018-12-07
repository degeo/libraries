<? if( !empty( $dataset ) ): ?>
<ul class="journals">
<? foreach( $dataset as $data ): ?>
<li class="journals-content"><a href="<?=site_url("journal/{$data['journal_url']}")?>" title="<?=$data['journal_name']?>"><?=$data['journal_name']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>