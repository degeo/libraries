<?
if( !empty( $dataset ) ):

	$content_arr = explode( "\n", $dataset );
	$content_total = count( $content_arr );
	
	$total_ads = 2;
	$ad_every_n = abs($content_total / $total_ads);
	
	if( ( !$this->Se_app_user->is_loggedin() || !$this->Se_app_user->has_role('contributor') ) && $content_total > 1 ):
		$count = 0;
		
		foreach( $content_arr as $p ):
			if( empty($p) || $content_total < $ad_every_n )
				continue;

			$count++;
			if( $count == $ad_every_n ):
				echo '<p>' . $p . '<div class="advertisement" style="margin:10px 10px 10px 10px;">';
			#	$this->load->view('adsense/adsense-taxon-description-footer.php');
				echo '</div></p>';
				$count = 0;
			else:
				echo "<p>{$p}</p>";
			endif;
		endforeach;
		
	else:
		echo '<p>' . str_replace( "\n", '</p><p>', $dataset) . '</p>';
	endif;

endif;