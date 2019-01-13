<?php
$object = 'values';
if( !empty( $records ) ): ?>
<div class="media">
<?php foreach( $records as $record ): ?>
	<a class="list-group-item" href="<?php echo site_url( $object . '/view/' . current($record) ); ?>">
	<?php
		$media = array(
			'heading' => $record['value']
		);

		$this->load->view( 'Bootstrap/v3/media', array( 'media' => $media ));
	?>
	</a>
<?php endforeach; ?>
</div>
<?php endif; ?>