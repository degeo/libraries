<? if( !empty( $dataset ) ): ?>
<div class="journals">
<? foreach( $dataset as $data ): ?>
<div class="journals-content">
	<h4><?=$data['journal_name']?></h4>
	<h6 class="subheader">By <?=$data['username']?></h6>
	<p><?=$data['journal_entry']?></p>
</div>
<? endforeach; ?>
</div>
<? endif; ?>