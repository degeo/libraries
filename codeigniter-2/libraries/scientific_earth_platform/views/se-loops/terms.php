<? if( !empty( $dataset ) ): ?>
<dl>
<? foreach( $dataset as $data ): ?>
<? if( !empty( $data['article_summary'] ) ): ?>
	<dd><dfn><?=$data['term_name']?></dfn> - <?=$data['article_summary']?></dd>
<? endif; ?>
<? endforeach; ?>
</dl>
<? endif; ?>