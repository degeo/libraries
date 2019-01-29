<?php
if( !empty( $record ) ):
	$content = '';

	if( array_key_exists( 'classification_key', $record ) && !empty( $record['classification_key'] ) ):
		$content .= '<label>Key:</label> ' . $record['classification_key'];
	endif;

	$media = array(
		'heading' => $record['classification_label'],
		'content' => $content
	);

	$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
endif; ?>