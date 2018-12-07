<? if( !empty( $dataset ) ): ?>
<ul class="inline-list images">
	<? 
	foreach( $dataset as $image ): ?>
	<li class="image image-<?=$image['image_id']?>" style="padding:5px;">
		<div class="of-hidden" style="height:110px;width:128px">
		<a class="th radius" href="http://img.scientificearth.net/u/<?=substr( $image['image_filename'], 0, -strlen( strrchr( $image['image_filename'], '.' ) ) )?>/<?=$image['image_filename']?>" target="_blank">
			<img width="100%" height="100%" src="http://img.scientificearth.net/u/<?=substr( $image['image_filename'], 0, -strlen( strrchr( $image['image_filename'], '.' ) ) )?>/<?=$image['image_thumbnail_filename']?>" />
		</a>
		</div>
	</li>
	<? endforeach; ?>
	<? if( user_has_role( 'contributor' ) ): ?>
	<li style="padding:5px;"><div class="panel"><a>Add an Image</a></div></li>
	<? endif; ?>
</ul>
<? endif; ?>