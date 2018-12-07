<? if( !empty( $dataset ) ): ?>

<table width="100%" class="taxa">

<thead>
	<tr>
		<th width="110px">&nbsp;</th>
		<th style="white-space:nowrap">Scientific Name</th>
		<th>Brief Description</th>
	</tr>
</thead>

<tbody>
<? foreach( $dataset as $data ): ?>
<tr class="taxa-content">
	<? if( !empty( $data['image_thumbnail_filename'] ) ): ?>
	<td valign="top"><img class="th" src="http://img.scientificearth.net/u/<?=$data['image_thumbnail_filename']?>" /></td>
	<? else: ?>
	<td valign="top"><img class="th" style="width:100px" src="http://assets.degeotechnologies.com/img/defaults/no-image-220x99.jpg" /></td>
	<? endif; ?>
	
	<td valign="top">
		<a href="http://www.scientificearth.net/directory/<?= $data['rank_url'] .'/'. $data['taxon_url'] ?>" target="_blank"><?=$data['taxon_name']?></a>
		<br/><small><a href="http://www.speciesimages.com/gallery/species/<?= $data['taxon_url'] ?>" title="View Images of <?=$data['taxon_name']?>" target="_blank">View Gallery</a></small>
	</td>
	
	<? if( !empty( $data['brief_description'] ) ): ?>
	<td valign="top"><?=$data['brief_description']?></td>
	<? else: ?>
	<td valign="top">&nbsp;</td>
	<? endif; ?>
</tr>
<? endforeach; ?>
</tbody>

</table>

<? if( !empty( $pagination_html ) ): ?>
<? $this->load->view('degeo-foundation/pagination') ?>
<? endif; ?>

<? endif; ?>