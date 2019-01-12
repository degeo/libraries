<?php
/**
 * @param Array $media
 * Array Keys:
 * + link - href url for anchor tag
 * + image - Image to display
 * + alt - alt attribute for image tag
 * + heading - Heading text to display
 * + content (required) - Content to display
 */
?>
<?php if( isset( $media ) && !empty( $media ) ): ?>
<div class="media">
	<?php if( array_key_exists( 'image', $media ) && !empty( $media['image'] ) ): ?>
	<div class="media-left media-middle">

			<?php if( array_key_exists( 'link', $media ) && !empty( $media['link'] ) ): ?>
			<a href="<?php echo $media['link']; ?>">
			<?php endif; ?>
			<img class="media-object" src="<?php echo $media['image']; ?>" alt="<?php echo ( array_key_exists( 'alt', $media ) && !empty( $media['alt'] ) ) ? $media['alt'] : ''; ?>">
			<?php if( array_key_exists( 'link', $media ) && !empty( $media['link'] ) ): ?>
			</a>
			<?php endif; ?>

	</div>
	<?php endif; ?>
	<div class="media-body">
		<?php if( array_key_exists( 'heading', $media ) && !empty( $media['heading'] ) ): ?>
		<h4 class="media-heading">
			<?php if( array_key_exists( 'link', $media ) && !empty( $media['link'] ) ): ?>
			<a href="<?php echo $media['link']; ?>">
			<?php endif; ?>
				<?php echo $media['heading']; ?>
			<?php if( array_key_exists( 'link', $media ) && !empty( $media['link'] ) ): ?>
			</a>
			<?php endif; ?>
		</h4>
		<?php endif; ?>
		<?php if( array_key_exists( 'content', $media ) && !empty( $media['content'] ) ): ?>
			<?php echo $media['content']; ?>
		<?php endif; ?>
	</div>
</div>
<?php endif; ?>