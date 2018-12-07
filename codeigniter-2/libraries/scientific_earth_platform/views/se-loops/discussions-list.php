<? if( !empty( $dataset ) ): ?>
<ul class="discussions">
<? foreach( $dataset as $data ): ?>
<li class="discussion-item"><a href="<?=site_url("discussion/{$data['discussion_id']}")?>" title="<?=$data['discussion_subject']?>"><?=$data['discussion_subject']?></a></li>
<? endforeach; ?>
</ul>
<? endif; ?>