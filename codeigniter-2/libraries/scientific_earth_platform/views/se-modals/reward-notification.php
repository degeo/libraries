<div id="reward-notification-modal" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
	<div class="text-center">
		<h2 id="modalTitle" class="text-center">Awesome!</h2>
	
		<span class="fi-trophy" style="font-size:750%;color:#ffa500"></span>
	
		<h3>You earned <?=$reward_notification['points']?> points!</h3>
	
		<p>Keep it up and you might make it on the leaderboard!</p>
		
		<a class="success button alt-close-reveal-modal" aria-label="Close">Continue</a>
		
		<a class="close-reveal-modal" aria-label="Close">&#215;</a>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#reward-notification-modal').foundation('reveal','open');
	
	$('a.alt-close-reveal-modal').on('click', function() {
	  $('#reward-notification-modal').foundation('reveal', 'close');
	});
});
</script>