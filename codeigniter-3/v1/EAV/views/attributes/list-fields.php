<?php
if( !empty( $record ) ):
	$content = '';

	if( array_key_exists( 'attribute_key', $record ) && !empty( $record['attribute_key'] ) ):
		$content .= '<label>Attribute Key:</label> ' . $record['attribute_key'];
	endif;

	if( array_key_exists( 'attribute_type', $record ) && !empty( $record['attribute_type'] ) ):
		$content .= '<br/><label>Attribute Type:</label> ' . $record['attribute_type'];
	endif;

	$media = array(
		'heading' => $record['attribute_label'],
		'content' => $content
	);

	$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
endif; ?>