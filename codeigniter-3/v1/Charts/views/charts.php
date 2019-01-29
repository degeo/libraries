<?php if( !empty( $charts ) ):  ?>
<?php foreach( $charts as $chart ): ?>
<?php #$this->load->view( $chart['view'], array( 'chart' => $this->Charts->chart($chart['id']) ) ); ?>
<?php endforeach;?>
<?php endif;?>