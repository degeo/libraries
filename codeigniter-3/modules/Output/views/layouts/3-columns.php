<div class="body container-fluid">
	<div class="<?php echo $this->Layout->row(); ?>">
		<div class="sidebar sidebar-left small-12 medium-3 columns">
			<?php $this->Layout->view_sidebar( 'left' ); ?>
		</div>
		<div class="content small-12 medium-6 columns">
			<?php $this->Layout->view_contents(); ?>
		</div>
		<div class="sidebar sidebar-right small-12 medium-3 columns">
			<?php $this->Layout->view_sidebar( 'right' ); ?>
		</div>
	</div>
</div>