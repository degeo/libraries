<? if( !empty( $dataset ) ): ?>
<? foreach( $dataset as $data ): ?>
<? interactive_discussions( array( $data ), 'discussion_id', $data['discussion_id'], $current_user ) ?>
<? endforeach; ?>
<? endif; ?>