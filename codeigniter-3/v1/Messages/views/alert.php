<?php if( !empty( $messages ) ): ?>
<div class="container">
<?php foreach( $messages as $message ): ?>
<?php if( !empty( $message['content'] ) && !empty( $message['type'] ) ): ?>
<div class="alert alert-<?php echo $message['type']; ?>" role="alert"><?php echo $message['content']; ?></div>
<?php endif; ?>
<?php endforeach; ?>
</div>
<?php endif; ?>