<? if( user_has_role( 'contributor' ) ): ?>
<div class="large-12 columns">
	<div class="panel">
	<a>Add an Image</a>
	</div>
</div>
<? endif; ?>

<? if( !empty( $dataset ) ): ?>
<div class="large-12 columns images bottom-margin">
	<? 
	$col_split = abs(12 / count($dataset));
	foreach( $dataset as $image ): ?>
	<div class="small-<?=$col_split?> columns image of-hidden" style="padding:5px;">
		<a class="th radius" href="http://img.scientificearth.net/u/<?=substr( $image['image_filename'], 0, -strlen( strrchr( $image['image_filename'], '.' ) ) )?>/<?=$image['image_filename']?>" target="_blank">
			<img width="100%" height="100%" src="http://img.scientificearth.net/u/<?=substr( $image['image_filename'], 0, -strlen( strrchr( $image['image_filename'], '.' ) ) )?>/<?=$image['image_thumbnail_filename']?>" />
		</a>
	</div>
	<? endforeach; ?>
</div>
<? endif; ?>