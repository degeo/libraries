<? if( !empty( $dataset ) ): ?>
<div class="taxa">
<? foreach( $dataset as $data ): ?>
<div class="taxa-content" style="overflow:hidden;padding-bottom:10px">
	<h4><a href="<?=site_url("directory/{$data['rank_url']}/{$data['taxon_url']}")?>"><?=$data['taxon_name']?></a></h4>
	<p>
		<? if( !empty( $data['image_filename'] ) ): ?>
		<img class="left th" style="margin-right:15px" src="http://img.scientificearth.net/u/<?=substr( $data['image_filename'], 0, -strlen( strrchr( $data['image_filename'], '.' ) ) )?>/<?=$data['image_thumbnail_filename']?>" width="100px" />
		<? endif; ?>
		<?=( !empty($data['brief_description']) ) ? $data['brief_description'] : '' ?><br/>
		<a title="Learn more about <?=$data['taxon_name']?>" href="<?=site_url("directory/{$data['rank_url']}/{$data['taxon_url']}")?>">Learn More...</a>
	</p>
</div>
<? endforeach; ?>

<? if( !empty( $pagination_html ) ): ?>
<? $this->load->view('degeo-foundation/pagination') ?>
<? endif; ?>
</div>
<? endif; ?>