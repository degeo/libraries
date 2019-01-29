<?php if( !empty( $records ) ): ?>
<div class="media">
<?php foreach( $records as $record ): ?>
	<a class="list-group-item" href="<?php echo site_url( strtolower( $this->router->fetch_class() ) . '/read/' . current($record) ); ?>">
	<?php $this->load->view( 'EAV/eav/list-fields', array( 'record' => $record ) );	?>
	</a>
<?php endforeach; ?>
</div>
<?php endif; ?>