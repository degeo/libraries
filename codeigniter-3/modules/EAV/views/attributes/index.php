<?php
$object = 'attributes';
if( !empty( $records ) ): ?>
<div class="media">
<?php foreach( $records as $record ): ?>
	<a class="list-group-item" href="<?php echo site_url( $object . '/view/' . current($record) ); ?>">
	<?php
		$content = '';

		if( array_key_exists( 'attribute_key', $record ) && !empty( $record['attribute_key'] ) ):
			$content .= '<label>Key:</label> ' . $record['attribute_key'];
		endif;

		$media = array(
			'heading' => $record['attribute_label'],
			'content' => $content
		);

		$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
	?>
	</a>
<?php endforeach; ?>
</div>
<?php endif; ?>