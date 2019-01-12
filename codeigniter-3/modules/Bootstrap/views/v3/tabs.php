<?php if( !empty( $tabs ) ): ?>
<!-- Nav tabs -->
<ul id="tabs" class="nav nav-tabs nav-pills">
	<?php foreach( $tabs as $key => $tab ): ?>
	<li><a href="#tab-<?php echo $key; ?>" data-toggle="tab"><?php echo $tab['label']; ?></a></li>
	<?php endforeach; ?>
</ul>

<!-- Tab panes -->
<div id="tab-content" class="tab-content">
	<?php foreach( $tabs as $key => $tab ): ?>
	<div class="tab-pane" id="tab-<?php echo $key; ?>">
		<?php foreach( $tab['content'] as $content_key => $content ): ?>
			<?php $this->load->view( $content['view'], $content['data'] ); ?>
		<?php endforeach; ?>
	</div>
	<?php endforeach; ?>
</div>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
	$('#tabs a:first').tab('show');
});
</script>