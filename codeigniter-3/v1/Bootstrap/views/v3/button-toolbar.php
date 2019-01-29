<?php if( isset( $toolbars ) && !empty( $toolbars ) ): ?>
<div class="btn-toolbar" role="toolbar" aria-label="Toolbar">
	<?php foreach( $toolbars as $toolbar ): ?>
	<div class="btn-group" role="group" aria-label="Toolbar Group"><?php $this->load->view( 'Bootstrap/v3/button-group', array( 'buttons' => $toolbar ) ); ?></div>
	<?php endforeach; ?>
</div>
<?php endif; ?>