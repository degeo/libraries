<? if( !empty( $dataset ) ): ?>
<ul>
<? foreach( $dataset as $data ): ?>
<li><?=( !empty($data['territory_name']) ) ? ucwords("{$data['territory_name']}, ") : '' ?><?=ucwords($data['country_name'])?></li>
<? endforeach; ?>
</ul>
<? endif; ?>