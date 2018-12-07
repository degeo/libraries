<? if( !empty( $synonyms ) ): ?>
<ul class="inline-list">
<? foreach( $synonyms as $synonym ): ?>
<li><?=ucwords($synonym['taxon_name'])?></li>
<? endforeach; ?>
</ul>
<? endif; ?>