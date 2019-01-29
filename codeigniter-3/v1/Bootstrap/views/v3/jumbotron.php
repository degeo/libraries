<?php
/**
 * @param Array $jumbotron
 * Array Keys:
 * + heading - Heading text to display
 * + content (required) - Content to display
 * + link - href url for anchor tag
 * + link_label - Label for the button
 */
?>
<?php if( isset( $jumbotron ) && !empty( $jumbotron ) ): ?>
<div class="jumbotron">
	<?php if( array_key_exists( 'heading', $jumbotron ) && !empty( $jumbotron['heading'] ) ): ?>
	<h2><?php echo $jumbotron['heading']; ?></h2>
	<?php endif; ?>
	<?php if( array_key_exists( 'content', $jumbotron ) && !empty( $jumbotron['content'] ) ): ?>
	<p><?php echo $jumbotron['content']; ?></p>
	<?php endif; ?>
	<?php if( array_key_exists( 'link', $jumbotron ) && !empty( $jumbotron['link'] ) ): ?>
	<p>
		<a class="btn btn-primary btn-lg" href="<?php echo $jumbotron['link']; ?>" role="button">
			<?php if( array_key_exists( 'link_label', $jumbotron ) && !empty( $jumbotron['link_label'] ) ): ?>
				<?php echo $jumbotron['link_label']; ?>
			<?php endif; ?>
		</a>
	</p>
	<?php endif; ?>
</div>
<?php endif; ?>