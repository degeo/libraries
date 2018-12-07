<? if( !empty( $dataset ) ): ?>
<ul class="inline-list row">
<? foreach( $dataset as $data ): ?>
<li class="large-3 columns" style="height:160px;margin-left:0">
	<? if( !empty( $data['image_filename'] ) ): ?>
	<div style="height:110px;overflow:hidden;">
		<img class="left th" src="<?=base_url("uploads/{$data['image_filename']}")?>" width="100%" />
	</div>
	<? else: ?>
	<div style="height:110px;overflow:hidden;">
		<img class="left th" src="<?=base_url("assets/img/defaults/no-image-330x137.jpg")?>" height="110px" width="100%" />
	</div>
	<? endif; ?>
	<h6><a href="<?=site_url("directory/{$data['rank_url']}/{$data['taxon_url']}")?>"><?=$data['taxon_name']?></a></h6>
</li>
<? endforeach; ?>
</ul>

<? if( !empty( $pagination_html ) ): ?>
<div class="top-margin">
<? $this->load->view('degeo-foundation/pagination') ?>
</div>
<? endif; ?>

<? endif; ?>