<?php if( isset( $record ) ): ?>
<div class="row form-group">
	<div class="col-xs-4">
		<label for="entity_id" class="control-label">Entity ID</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="entity_id" readonly="readonly" class="form-control" value="<?php echo set_value_data( 'entity_id', $record ); ?>"/>
	</div>
</div>

<div class="row form-group">
	<div class="col-xs-4">
		<label for="classification_id" class="control-label">Classification ID</label>
	</div>
	<div class="col-xs-8">
		<input type="text" name="classification_id" class="form-control" value="<?php echo set_value_data( 'classification_id', $record ); ?>"/>
	</div>
</div>
<?php endif; ?>