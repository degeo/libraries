<?php if( !empty( $modals ) ):  ?>
<?php foreach( $modals as $modal ): ?>
<?php $this->load->view( $modal['view'], array( 'modal' => $this->Modals->get($modal['id']) ) ); ?>
<?php endforeach;?>
<?php endif;?>