<? if( !empty( $dataset ) ): ?>
<div class="articles">
<? foreach( $dataset as $data ): ?>
<div class="article-content">
	<h4><a title="Read <?=$data['article_title']?>" href="<?=site_url("article/{$data['article_url']}")?>"><?=$data['article_title']?></a></h4>
	<p><?=$data['article_summary']?></p>
	<p><a title="Read <?=$data['article_title']?>" href="<?=site_url("article/{$data['article_url']}")?>">Read More...</a></p>
</div>
<? endforeach; ?>

<? if( !empty( $pagination_html ) ): ?>
<? $this->load->view('degeo-foundation/pagination') ?>
<? endif; ?>
</div>
<? endif; ?>