<? if( !empty( $synonyms ) ): ?>
<ul>
<? foreach( $synonyms as $synonym ): ?>
<li><?=ucwords($synonym['taxon_name'])?></li>
<? endforeach; ?>
</ul>
<? endif; ?>