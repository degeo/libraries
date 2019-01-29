<?php
if( !empty( $record ) ):
	$content = '';

	if( array_key_exists( 'value', $record ) && !empty( $record['value'] ) ):
		$content .= '<label>Value:</label> ' . $record['value'];
	endif;

	$media = array(
		'heading' => $record['value_id'],
		'content' => $content
	);

	$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
endif; ?>