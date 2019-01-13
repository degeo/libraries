<div class="row">
	<div class="col-xs-12">

		<form name="<?php echo strtolower( $this->router->fetch_class() ); ?>" method="post" action="<?php echo site_url( strtolower( $this->router->fetch_class() ) . '/create' ); ?>" >

			<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">New <?php echo ucwords( singular( $this->router->fetch_class() ) ); ?> Details</h3>
				  </div>
				<div class="panel-body">

					<div class="row">
						<div class="col-xs-12 col-md-6">

							<?php $this->load->view( 'EAV/' . strtolower( $this->router->fetch_class() ) . '/form-fields', array( 'record' => array() )  ); ?>

						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 text-center">
							<input type="submit" class="btn btn-primary" value="Create <?php echo ucwords( singular( $this->router->fetch_class() ) ); ?>"/>
						</div>
					</div>

				</div>
			</div>

		</form>

	</div>
</div>