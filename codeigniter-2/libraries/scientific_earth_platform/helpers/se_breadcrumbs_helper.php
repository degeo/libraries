<?php

if( !function_exists( 'breadcrumbs_html' ) ) {
	function breadcrumbs_html( array $breadcrumbs ) {
		$html = "<nav class='breadcrumbs'>";
		
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
			
			$html .= "<a class='{$class}' href='{$href}'>{$crumb['label']}</a>";
		endforeach;
		
		$html .= "</nav>";
		
		return $html;
	} // function
} // if