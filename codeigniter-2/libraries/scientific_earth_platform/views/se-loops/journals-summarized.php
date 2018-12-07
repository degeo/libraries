<? if( !empty( $dataset ) ): ?>
<div class="journals">
<? foreach( $dataset as $data ): ?>
<div class="journals-content">
	<h4><a href="<?=site_url("journal/{$data['journal_url']}")?>" title="<?=$data['journal_name']?>"><?=$data['journal_name']?></a></h4>
	<h6 class="subheader">By <?=$data['username']?></h6>
	<p><?=substr( $data['journal_entry'], 0, 255 )?></p>
	<? if( strlen($data['journal_entry']) >= 254 ): ?>
	<p><a href="<?=site_url("journal/{$data['journal_url']}")?>">Read more...</a></p>
	<? endif; ?>
</div>
<? endforeach; ?>
</div>
<? endif; ?>