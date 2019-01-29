<?php if( !empty( $record ) ): ?>
<div class="row">
	<div class="col-xs-12">

		<form method="post" action="<?php echo site_url( strtolower( $this->router->fetch_class() ) . '/delete' ); ?>" >

			<?php $this->load->view( 'EAV/' . strtolower( $this->router->fetch_class() ) . '/form-fields', array( 'record' => $record ) ); ?>

			<div class="row">
				<div class="col-xs-12 text-center">
					<input type="button" value="Cancel" class="btn btn-primary" onclick="return window.history.back();" />
					<input type="submit" value="Delete <?php echo ucwords( singular( $this->router->fetch_class() ) ); ?>" class="btn btn-danger"/>
					<input type="hidden" name="confirm" value="true"/>
				</div>
			</div>

		</form>

	</div>
</div>
<?php endif; ?>