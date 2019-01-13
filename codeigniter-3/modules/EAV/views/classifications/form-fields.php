<?php if( isset( $record ) ): ?>
<div class="row form-group">
	<div class="col-xs-4">
		<label for="classification_id" class="control-label">Classification ID</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="classification_id" readonly="readonly" class="form-control" value="<?php echo set_value_data( 'classification_id', $record ); ?>"/>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-4">
		<label for="classification_key" class="control-label">Classification Key</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="classification_key" class="form-control" value="<?php echo set_value_data( 'classification_key', $record ); ?>"/>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-4">
		<label for="classification_label" class="control-label">Classification Label</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="classification_label" class="form-control" value="<?php echo set_value_data( 'classification_label', $record ); ?>"/>
	</div>
</div>
<?php endif; ?>