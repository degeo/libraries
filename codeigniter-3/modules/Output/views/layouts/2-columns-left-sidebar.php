<?php
$css_column_content = $this->config->item('css_column_content');
$css_column_sidebar = $this->config->item('css_column_sidebar');
?>
<div class="body container-fluid">
	<div class="<?php echo $this->Layout->row(); ?>">
		<div class="sidebar sidebar-left <?php echo ( !empty( $css_column_sidebar ) ) ? $css_column_sidebar : 'col-sm-12 col-md-3'; ?>">
			<?php $this->Layout->view_sidebar( 'left' ); ?>
		</div>
		<div class="content <?php echo ( !empty( $css_column_content ) ) ? $css_column_content : 'col-sm-12 col-md-9'; ?>">
			<?php $this->Layout->view_contents(); ?>
		</div>
	</div>
</div>