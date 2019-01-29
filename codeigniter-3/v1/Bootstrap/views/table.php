<?php if( !empty( $table ) ): ?>
<table class="table">
	<thead>
		<?php if( !empty( $table['columns'] ) ): ?>
		<tr>
			<?php foreach( $table['columns'] as $column ): ?>
			<th><?php echo $column; ?></th>
			<?php endforeach; ?>
		</tr>
		<?php endif; ?>
	</thead>
	<tbody>
		<?php if( !empty( $table['rows'] ) ): ?>
		<?php foreach( $table['rows'] as $row ): ?>
		<tr>
			<?php foreach( $row as $column ): ?>
			<td><?php echo $column ?></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>
<?php endif; ?>