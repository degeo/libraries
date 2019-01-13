<?php
$object = 'entities';
if( !empty( $records ) ): ?>
<div class="media">
<?php foreach( $records as $record ): ?>
	<a class="list-group-item" href="<?php echo site_url( $object . '/view/' . current($record) ); ?>">
	<?php
		$content = '<pre>';
		$content .= print_r($record,true);
		$content .= '</pre>';

		$media = array(
			'heading' => '<label>Entity ID:</label> ' . $record['entity_id'],
			'content' => $content
		);

		$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
	?>
	</a>
<?php endforeach; ?>
</div>
<?php endif; ?>