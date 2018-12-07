<? if( !empty( $dataset ) ): ?>
<ul class="inline-list">
<? foreach( $dataset as $data ): ?>
<li><?=$data['term_name']?></li>
<? endforeach; ?>
<? if( user_has_role( 'contributor' ) ): ?>
<li><a>Add Term</a></li>
<? endif; ?>
</ul>
<? endif; ?>