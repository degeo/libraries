<? if( !empty( $dataset ) ): ?>
<ul class="feeds no-bullet">
<? foreach( $dataset as $data ): ?>
<li class="feed"><a href="<?=site_url("user/{$data['update_user_url']}")?>"><?=$data['update_username']?></a> <?=$data['update']?> to <a href="<?=site_url("directory/{$data['rank_url']}/{$data['taxon_url']}")?>"><?=$data['taxon_name']?></a> on <?=date('M d, Y',strtotime($data['update_timestamp']))?></li>
<? endforeach; ?>
</ul>
<? endif; ?>