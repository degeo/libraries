<div class="panel radius">
	
	<div class="row">
		<div class="large-12 columns">
			
			<h3 class="text-center" style="margin-bottom:15px">Social Login</h3>
			
			<div id="oa_social_login_container"></div>

			<script type="text/javascript"> 

				/* Replace #your_callback_uri# with the url to your own callback script */
				var your_callback_script = 'http://localhost/dgt/accounts.scientificearth.net/index.php/authorize/thirdparty_link?goto=<?= base_url() ?>';

				/* Embeds the buttons into the container oa_social_login_container */
				var _oneall = _oneall || [];
				_oneall.push(['social_login', 'set_providers', ['facebook', 'google', 'linkedin', 'windowslive', 'openid']]);
				_oneall.push(['social_login', 'set_callback_uri', your_callback_script]);
				_oneall.push(['social_login', 'do_render_ui', 'oa_social_login_container']);

			</script>
			
			<hr/>
			<p class="text-center" style="margin:0"><a href="http://accounts.scientificearth.net/authorize/login?goto=<?=base_url()?>"><span class="fi-shield"></span> Login or Create an Account</a></p>
			
		</div>
	</div>
	
</div>