<? if( !empty( $taxon['taxonomy'] ) ): ?>
<? array_reverse( $taxon['taxonomy'] ); foreach( $taxon['taxonomy'] as $r => $t ): if( empty($t) ) continue; ?>
<li><a href="#<?=$r?>">About the <?=ucwords($t['taxon_name'])?> <?=ucwords($r)?></a></li>
<? endforeach; ?>
<? endif; ?>