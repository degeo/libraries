<div class="container panel panel-default <?php echo $this->Layout->column( 'xs', 12 ); ?> <?php echo $this->Layout->column( 'md', 4 ); ?> col-md-offset-4">

	<h3 class="text-center">Login With Your Username</h3>

	<form action="<?=site_url('authorize/login')?>" method="post" accept-charset="utf-8">
		<div class="<?php echo $this->Layout->row(); ?>">
			<div class="<?php echo $this->Layout->column( 'xs', 12 ); ?>"><label>Username</label><br/>
				<input class="form-control" name="account_username" value="<?=set_value('account_username')?>" type="text" /></div>
		</div>
		<br/>
		<div class="<?php echo $this->Layout->row(); ?>">
			<div class="<?php echo $this->Layout->column( 'xs', 12 ); ?>"><label>Password</label><br/>
				<input class="form-control" name="account_password" type="password" style="margin-bottom:0" />
				<!--<br/><small><a href="<?=site_url('authorize/forgot-password')?>">Forgot Your Password?</a></small>-->
			</div>
		</div>
		<br/>
		<div class="<?php echo $this->Layout->row(); ?>">
			<div class="<?php echo $this->Layout->column( 'xs', 12 ); ?> text-center">
				<?=form_submit(array('name'=>'submit','class'=>'btn btn-primary','style'=>'margin-bottom:10px'),'Login')?>
<!--				<p><a href="<?=site_url('authorize/new-account')?>" title="Create a Scientific Earth Network Account">Create an Account</a></p> -->
			</div>
		</div>
	</form>

</div>