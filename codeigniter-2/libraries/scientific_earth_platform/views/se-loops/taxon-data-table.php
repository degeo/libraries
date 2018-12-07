<? if( !empty( $taxon['data'] ) ): ?>
<table width="100%">
<? foreach( $taxon['data'] as $set ): ?>
<tr>
	<? $disabled = ' class="small disabled success button"'; ?>
	<td width="33%"><a<?=( !empty( $set['key_article_url'] ) ) ? ' href="' . site_url("data/term/{$set['key_term_url']}") . '" class="small success button"' : $disabled ?>><?=$set['key_term_name']?></a></td>
	<td>
	<a class="small disabled secondary button"><?=$set['value_term_name']?></a>
	<? if( !empty( $set['values'] ) ): ?>
	<? foreach( $set['values'] as $value ): ?>
	<a class="small disabled secondary button"><?=$value['value_term_name']?></a>
	<? endforeach; ?>
	<? endif; ?>
	</td>
</tr>
<? endforeach; ?>
</table>
<? endif; ?>