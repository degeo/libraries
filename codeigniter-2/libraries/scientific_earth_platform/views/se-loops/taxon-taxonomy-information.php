<? if( !empty( $taxon['taxonomy'] ) ): ?>
<div class="taxon-information">
<? $taxon['taxonomy'] = array_reverse( $taxon['taxonomy'] ); foreach( $taxon['taxonomy'] as $r => $t ): if( empty($t) ) continue; ?>
<h4 id="<?=$r?>">About the <?=ucwords($t['taxon_name'])?> <?=ucwords($r)?></h4>
<? loop_paragraph_content( $t['brief_description'] ); ?>
<p><a href="<?=site_url("directory/{$t['rank_url']}/{$t['taxon_url']}")?>">Learn more about the <?=ucwords($t['taxon_name'])?> <?=ucwords($r)?></a></p>
<? endforeach; ?>
</div>
<? endif; ?>