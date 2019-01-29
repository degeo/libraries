<?php if( isset( $record ) ): ?>
<div class="row form-group">
	<div class="col-xs-4">
		<label for="attribute_id" class="control-label">Attribute ID</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="attribute_id" readonly="readonly" class="form-control" value="<?php echo set_value_data( 'attribute_id', $record ); ?>"/>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-4">
		<label for="attribute_key" class="control-label">Attribute Key</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="attribute_key" class="form-control" value="<?php echo set_value_data( 'attribute_key', $record ); ?>"/>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-4">
		<label for="attribute_label" class="control-label">Attribute Label</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="attribute_label" class="form-control" value="<?php echo set_value_data( 'attribute_label', $record ); ?>"/>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-4">
		<label for="attribute_type" class="control-label">Attribute Type</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="attribute_type" class="form-control" value="<?php echo set_value_data( 'attribute_type', $record ); ?>"/>
	</div>
</div>
<?php endif; ?>