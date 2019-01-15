<?php if( !empty( $record ) ):  print_r($record); ?>
	<div class="row">
		<div class="col-xs-12">

			<form name="<?php echo strtolower( $this->router->fetch_class() ); ?>" method="post" action="<?php echo site_url( strtolower( $this->router->fetch_class() ) . '/update' ); ?>" >

				<div class="panel panel-default">
					  <div class="panel-heading">
					    <h3 class="panel-title"><?php echo ucwords( singular( $this->router->fetch_class() ) ); ?> Details</h3>
					  </div>
					<div class="panel-body">

						<div class="row">
							<div class="col-xs-12 col-md-6">

								<?php $this->load->view( 'EAV/eav/form-fields', array( 'record' => $record )  ); ?>

							</div>
						</div>

						<div class="row">
							<div class="col-xs-12 text-center">
								<input type="submit" class="btn btn-primary" value="Save <?php echo ucwords( singular( $this->router->fetch_class() ) ); ?>"/>
								<a class="btn btn-danger" href="javascript:void(0);" onclick="$('form[name=<?php echo strtolower( $this->router->fetch_class() ); ?>]').attr('action','<?php echo site_url( strtolower( $this->router->fetch_class() ) . '/delete'); ?>').submit();">
									Delete <?php echo ucwords( singular( $this->router->fetch_class() ) ); ?>
								</a>
							</div>
						</div>

					</div>
				</div>

			</form>

		</div>
	</div>

<?php else: ?>
	<?php $this->load->view( 'System/norecords' ); ?>
<?php endif; ?>