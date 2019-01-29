<?php if( isset( $buttons ) && !empty( $buttons ) ): ?>
<div class="btn-group" role="group" aria-label="Buttons">
	<?php foreach( $buttons as $button ): ?>
		<?php if( array_key_exists( 'link', $button ) && !empty( $button['link'] ) ): ?>
		<a class="btn btn-default" href="<?php echo $button['link'] ?>"<?php echo ( array_key_exists( 'onclick', $button ) && !empty( $button['onclick'] ) )? 'onclick="' . $button['onclick'] . '"' : ''; ?>>
		<?php else: ?>
		<button type="button" class="btn btn-default">
		<?php endif; ?>
			<?php echo $button['label'] ?>
		<?php if( array_key_exists( 'link', $button ) && !empty( $button['link'] ) ): ?>
		</a>
		<?php else: ?>
		</button>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php endif; ?>