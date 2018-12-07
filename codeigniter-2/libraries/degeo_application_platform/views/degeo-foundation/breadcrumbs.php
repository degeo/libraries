<?php if( !empty( $breadcrumbs ) ): ?>
<div class="row">
	<div class="small-12 columns">
		<nav class="breadcrumbs">
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
					$class .= 'current ';
			
				$class = rtrim( $class, ' ' );
		
				echo "<a class='{$class}' href='{$href}'>{$crumb['label']}</a>";
			endforeach;
			?>
		</nav>
	</div>
</div>
<? endif; ?>