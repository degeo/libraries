<? if( !empty($dataset) ): ?>
<ul>
	<? foreach( $dataset as $data ): ?>
	<li>
		<? if( $taxon['taxon_id'] === $data['x_taxon'] ): ?>
		<?=$data['x_taxon']['taxon_name']?>
		<? else: ?>
		<a href="<?=site_url( "directory/{$data['x_taxon']['rank_url']}/{$data['x_taxon']['taxon_url']}" )?>" title="<?=$data['x_taxon']['taxon_name']?>"><?=$data['x_taxon']['taxon_name']?></a>
		<? endif; ?>
		&nbsp;[<?=$data['effect_on_x']?> | <?=$data['effect_on_y']?>]&nbsp;
		<? if( $taxon['taxon_id'] === $data['y_taxon'] ): ?>
		<?=$data['y_taxon']['taxon_name']?>
		<? else: ?>
		<a href="<?=site_url( "directory/{$data['y_taxon']['rank_url']}/{$data['y_taxon']['taxon_url']}" )?>" title="<?=$data['y_taxon']['taxon_name']?>"><?=$data['y_taxon']['taxon_name']?></a>
		<? endif; ?>
	</li>
	<? endforeach; ?>
</ul>
<? endif; ?>