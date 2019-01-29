<?php if( !empty( $breadcrumbs ) ): ?>
<div class="<?php echo $this->Layout->row(); ?>">
	<div class="<?php echo $this->Layout->column( 'xs', 12 ); ?>">
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
					$href = '';
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