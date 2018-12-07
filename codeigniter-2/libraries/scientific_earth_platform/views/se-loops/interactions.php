<? if( !empty($dataset) ): ?>
<table width="100%">
	<? foreach( $dataset as $data ): ?>
	<tr>
		<? if( !empty($taxon) && $taxon['taxon_id'] === $data['x_taxon']['taxon_id'] ): ?>
		<td width="33%" class="text-center"><?=$data['x_taxon']['taxon_name']?></td>
		<? else: ?>
		<td width="33%" class="text-center"><a href="<?=site_url( "directory/{$data['x_taxon']['rank_url']}/{$data['x_taxon']['taxon_url']}" )?>" title="<?=$data['x_taxon']['taxon_name']?>"><?=$data['x_taxon']['taxon_name']?></a></td>
		<? endif; ?>
		<td width="33%">
			<table width="100%">
				<tr>
					<th colspan="2"><?=$data['interaction_name']?></th>
				</tr>
				<tr>
					<td class="text-center"><?=$data['effect_on_x']?></td>
					<td class="text-center"><?=$data['effect_on_y']?></td>
				</tr>
			</table>
		</td>
		<? if( !empty($taxon) && $taxon['taxon_id'] === $data['y_taxon']['taxon_id'] ): ?>
		<td width="33%" class="text-center"><?=$data['y_taxon']['taxon_name']?></td>
		<? else: ?>
		<td width="33%" class="text-center"><a href="<?=site_url( "directory/{$data['y_taxon']['rank_url']}/{$data['y_taxon']['taxon_url']}" )?>" title="<?=$data['y_taxon']['taxon_name']?>"><?=$data['y_taxon']['taxon_name']?></a></td>
		<? endif; ?>
	</tr>
	<? endforeach; ?>
</table>
<? endif; ?>