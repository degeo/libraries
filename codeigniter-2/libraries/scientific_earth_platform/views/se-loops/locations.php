<? if( !empty( $dataset ) ): ?>
<div id="vmap" style="width: 970px; height: 600px"></div>

<script type="text/javascript">
jQuery(document).ready(function() {
	<?
	$map_arr = array();
	foreach( $dataset as $data ):
		if( !empty( $data['territory_abbreviation'] ) ):
			if( array_key_exists( $data['territory_abbreviation'], $map_arr ) ):
				$map_arr[strtolower($data['territory_abbreviation'])] += 1;
			else:
				$map_arr[strtolower($data['territory_abbreviation'])] = 1;
			endif;
		endif;
		
		if( !empty( $data['country_code'] ) ):
			if( array_key_exists( $data['country_code'], $map_arr ) ):
				$map_arr[strtolower($data['country_code'])] += 1;
			else:
				$map_arr[strtolower($data['country_code'])] = 1;
			endif;
		endif;
	endforeach;
	
	foreach( $map_arr as $k => $v ):
		$map_arr[$k] = "{$v}";
	endforeach;
	?>
	
	var map_data = <?=json_encode($map_arr)?>;
	
	$('#vmap').vectorMap({
	    map: 'world_en',
	    backgroundColor: '#F2F2F2',
	    color: '#eeeeee',
		borderColor: '#222222',
	    hoverOpacity: 0.7,
	    selectedColor: '#666666',
	    enableZoom: true,
	    showTooltip: true,
	    values: map_data,
	    scaleColors: ['#000000', '#5DA423'],
	    normalizeFunction: 'polynomial'
	});
});
</script>
<? endif; ?>