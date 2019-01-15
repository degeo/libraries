<?php if( !empty( $record ) ): ?>
	<?php foreach( $record as $field => $value ): ?>
		<div class="row form-group">
			<div class="col-xs-4">
				<label for="<?php echo $field; ?>" class="control-label"><?php echo humanize( $field ); ?></label>
			</div>
			<div class="col-xs-8">
				<input type="text" name="<?php echo $field; ?>" class="form-control" value="<?php echo ( is_array( $value ) )? set_value( $field, $value['value'] ) : $value; ?>"/>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>