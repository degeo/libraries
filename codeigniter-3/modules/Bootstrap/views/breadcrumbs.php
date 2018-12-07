<?php if( !empty( $breadcrumbs ) ): ?>
<ol class="breadcrumb">
	<?php
	$total = count($breadcrumbs);
	$count = 0;
	foreach( $breadcrumbs as $crumb ):
		$count++;

		$class = '';
		$href = '';

		if( empty( $crumb['url'] ) ):
			$class .= 'unavailable ';
			$href = '#';
		else:
			$href = $crumb['url'];
		endif;

		if( $count == $total )
			$class .= 'active ';

		$class = rtrim( $class, ' ' );

		echo "<li><a class='{$class}' href='{$href}'>{$crumb['label']}</a></li>";
	endforeach;
	?>
</ol>
<? endif; ?>