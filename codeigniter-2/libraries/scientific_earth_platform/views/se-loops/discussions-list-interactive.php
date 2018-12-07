<ul class="discussions no-bullet">
	<? if( !$this->Se_app_user->is_loggedin() ): ?>
	<li class="empty-results-placeholder"><p><a href="<?=site_url('account')?>">Create an Account</a> or <a href="<?=site_url('account')?>">Login</a> to contribute to the discussions.</p></li>
	<? else: ?>
	<li class="discussions-form-container<?=( $primary_id_name == 'discussion_id' ) ? ' hide' : '' ;?>">
		<form id="discussion-form" class="discussion-form" action="<?=site_url('account_ajax/add_discussion')?>" method="post">
			<div class="discussion-form-message" class="hidden alert-box"></div>
			<fieldset>
				<legend>Start a Discussion</legend>

				<table width="100%">
					<tr>
						<td><label>Subject</label></td>
						<td><input name="subject" type="text" /></td>
					</tr>
					<tr>
						<td colspan="2">
							<div class="right">
								<dl class="sub-nav">
									<dd><a style="display:none" class="discussion-reply-close-link">&times;</a></dd>
								</dl>
							</div>
							<label>Message</label>
						</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="message"></textarea></td>
					</tr>
					<tr>
						<td colspan="2"><input class="right" type="submit" /></td>
					</tr>
				</table>

			</fieldset>
			
			<? if( !empty( $section ) ): ?>
			<input name="section" value="<?=$section?>" type="hidden" />
			<? endif; ?>
			<? if( $primary_id_value === false ): ?>
			<input name="primary_id" value="0" type="hidden" />
			<? else: ?>
			<input name="primary_id" value="<?=$primary_id_value?>" type="hidden" />
			<? endif; ?>
			<input name="parent_discussion_id" value="0" type="hidden" />
		</form>
	</li>
	<? endif; ?>
	
<? if( !empty( $dataset ) ): ?>
	<? foreach( $dataset as $data ): ?>
	<li class="discussions-content">
		<li id="discussion-<?=$data['discussion_id']?>" class="discussion-item" data-discussion-id="<?=$data['discussion_id']?>">
			<? if( $this->Se_app_user->is_loggedin() ): ?>
			<div class="right">
				<dl class="sub-nav">
					<dd><a class="discussion-reply-link">Reply</a></dd>
					<? if( $data['account_id'] == $this->Se_app_user->get_id() ): ?>
					<dd><a>&times;</a></dd>
					<? endif; ?>
				</dl>
			</div>
			<? endif; ?>
			
			<? if( !empty( $data['discussion_subject'] ) ): ?>
			<span class="label">Subject:</span> <span class="discussion-subject"><?=$data['discussion_subject']?></span>
			<? endif; ?>
			<blockquote>
				<?=$data['discussion_text']?>
				<cite><?=$data['username']?></cite>
			</blockquote>
			
			<ul class="discussion-item-children no-bullet">
				<? if( !empty( $data['discussion_children'] ) ): ?>
				<? foreach( $data['discussion_children'] as $reply ): ?>
				<li class="discussion-item"  data-discussion-id="<?=$reply['discussion_id']?>">
					<blockquote style="border-bottom:1px solid #DDD">
						<?=$reply['discussion_text']?>
						<cite><?=$reply['username']?></cite>
					</blockquote>
				</li>
				<? endforeach; ?>
				<? else: ?>
				<li class="empty-results-placeholder hide"></li>
				<? endif; ?>
			</ul>
			
		</li>
	</li>
	<? endforeach; ?>
<? endif; ?>
</ul>
<? if( user_has_role('registered-user') ): ?>
<script type="text/javascript">
$(document).ready(function(){
	$("a.discussion-reply-link").click(function(e){
		e.preventDefault();
		var anchor = $(this);
		var li = anchor.parents('li.discussion-item');
		var move_form_to = li.children('ul.discussion-item-children');
		console.log(move_form_to);
		var discussion_id = li.data('discussion-id');

		$('.discussions-form-container').appendTo( move_form_to );
		anchor.hide();
		$('.discussion-reply-close-link').show();

		var form = li.find('form');
		
		form.find('legend').text('Join the Discussion');
		form.find('input[name=subject]').parents('tr').hide();
		form.find('input[name=parent_discussion_id]').val(discussion_id);
		form.find('textarea[name=message]').focus();
	});	

	$("a.discussion-reply-close-link").click(function(e){
		e.preventDefault();
		
		var anchor = $(this);
		var li = anchor.parents('li.discussion-item');
		var form = li.find('form');
		
		anchor.hide();
		$('.discussion-reply-link').show();
		
		form.find('legend').text('Start a Discussion');
		form.find('input[name=subject]').parents('tr').show();
		form.find('input[name=parent_discussion_id]').val('0');
		
		$('.discussions-form-container').prependTo( $('ul.discussions').first() );
	});

	$("#discussion-form").submit(function(e){
		e.preventDefault();

		var form = $(this);
		var li = form.parent('li');
		var ul = li.parent('ul');
		
		var primary_id = form.children('input[name=primary_id]').val();
		var parent_discussion_id = form.children('input[name=parent_discussion_id]').val();
		var subject = form.find('input[name=subject]').val();
		var message = form.find('textarea[name=message]').val();

		$.post( '<?=site_url('account_ajax/add_discussion')?>', {
			'section' : '<?=$section?>',
			'primary_id' : primary_id,
			'parent_discussion_id' : parent_discussion_id,
			'subject' : subject,
			'message' : message
		}, function(data) {
			if( data == '0' ) {
				form.children(".discussion-form-message").addClass('alert-box error alert').html("Your message could not be posted.").show();
			} else {
				form.children(".discussion-form-message").addClass('alert-box success').html("Your message was posted.").show();
				$(".empty-results-placeholder").remove();

				var new_message_html = '<li class="discussion-item">';

				if( subject ) {
					new_message_html += '<span class="label">Subject:</span> ' + subject;
				} // if

				new_message_html += '<blockquote style="border-bottom:1px solid #DDD">' + message + '</blockquote>';
				new_message_html += '</li>';

				if( ul.hasClass('discussion-item-children') ){
					$( new_message_html ).appendTo( ul );
				} else {
					$( new_message_html ).prependTo( ul );
				} // if, else
				
				form.find("input[type=text], textarea").val("");
				form.find('legend').text('Start a Discussion');
				form.find('input[name=subject]').parents('tr').show();
				form.find('input[name=parent_discussion_id]').val('0');
			} // if

			setTimeout( function(){ $(".discussion-form-message").fadeOut(); }, 3000 );
		}).done(function(){
			$('.discussions-form-container').prependTo( $('ul.discussions').first() );
		});
	});
	
});
</script>
<? endif; ?>