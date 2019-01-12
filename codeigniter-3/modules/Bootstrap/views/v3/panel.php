<?php
/**
 * @param Array $panel
 * Array Keys:
 * + type - CSS class for styling, accepts: default, primary, success, info, warning, danger
 * + title - Text to display in the panel heading
 * + body - Content to display in the panel body
 * + table - Table to display in panel below body and above list-group
 * + list - List to display in panel below table
 */
?>
<?php if( isset( $panel ) && !empty( $panel ) ): ?>
<div class="panel panel-<?php echo ( array_key_exists( 'type', $panel ) && !empty( $panel['type'] )  ) ? $panel['type'] : 'default'; ?>">
	<?php if( array_key_exists( 'title', $panel ) && !empty( $panel['title'] ) ): ?>
	  <div class="panel-heading">
	    <h3 class="panel-title"><?php echo $panel['title']; ?></h3>
	  </div>
	<?php endif; ?>
	<?php if( array_key_exists( 'body', $panel ) && !empty( $panel['body'] ) ): ?>
	<div class="panel-body">
		<?php echo $panel['body']; ?>
	</div>
	<?php endif; ?>
	<?php if( array_key_exists( 'table', $panel ) && !empty( $panel['table'] ) ): ?>
		<?php echo $panel['table']; ?>
	<?php endif; ?>
	<?php if( array_key_exists( 'list', $panel ) && !empty( $panel['list'] ) ): ?>
		<ul class="list-group">
		<?php foreach( $panel['list'] as $key => $item ): ?>
			<li class="list-group-item"><?php echo $item; ?></li>
		<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
<?php endif; ?>