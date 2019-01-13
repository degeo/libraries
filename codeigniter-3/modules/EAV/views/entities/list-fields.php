<?php
if( !empty( $record ) ):
	$content = '<ul class="list-unstyled">';

	foreach( $record as $field => $value ):
		if( !empty( $value ) ):
			$content .= '<li><label>' . humanize( $field ) . ':</label> ' . $value . '</li>';
		endif;
	endforeach;

	$content .= '</ul>';

	$media = array(
		'heading' => 'Entity',
		'content' => $content
	);

	$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
endif; ?>