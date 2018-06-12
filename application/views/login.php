<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<main class="col-xs-12 col-md-4 col-lg-3 col-sm-6 mx-auto animated bounceInDown">
	<div class="col-xs-12 mx-auto">
		<img src="<?= base_url("img/logo.png"); ?>" class="img-fluid">
	</div>
	<form method="post" action="<?= base_url("main/login"); ?>" class="card">
		<div class="card-header">
			<i class="fas fa-user"></i> Iniciar sesión
		</div>
		<div class="card-body">
			<div class="form-group">
				<input class="form-control" type="text" name="user" value="" placeholder="Usuario">
			</div>

			<div class="form-group">
				<input class="form-control" type="password" name="pass" value="" placeholder="Contraseña">
			</div>

			<div class="form-group recaptcha">
				<div class="g-recaptcha" data-callback="captchaFill" data-sitekey="6Ldoj10UAAAAAA1UKl-0cYCvLEVxA4mIR6uc-LRd"></div>
			</div>

			<button class="btn btn-primary btn-block" disabled type="submit">
				<i class="fas fa-sign-in-alt"></i> Entrar
			</button>
		</div>
	</form>
</main>

<script>
	var captchaEnabled = <?= (isset($captcha) and $captcha ? 'true' : 'false'); ?>;
	function captchaFill(){ $("form input").trigger('keyup'); }
	function captchaShow(){
		var captchaDiv = $('<div>')
			.addClass("g-recaptcha")
			.data('callback', 'captchaFill')
			.data('sitekey', '<?= ($this->config->item('captcha_public_key') ?: ''); ?>');
		$("div.recaptcha").html(captchaDiv);
	}
	
	$(function() {
		$("form :input").keyup(function(){
			var dis = false;
			$("form :input:not(button)").each(function(){
				if($(this).val().length < 4){
					dis = true;
				}
			});
			$("form button").prop('disabled', dis).removeClass("btn-danger").addClass("btn-primary");
		});

		function centerLogin(){
			var h = $("main").height();
			var wh = $(window).innerHeight();

			$("main").css('top', parseInt((wh - h) / 2) - 20 + 'px');
		}

		$(window).resize(function(){ return centerLogin(); });
		centerLogin();

		if(captchaEnabled){ captchaShow(); }

		$("form").submit(function(e){
			e.preventDefault();
			$.ajax({
				type: 'POST',
				cache: false,
				url: $(this).attr('action'),
				data: $(this).serialize(),
				beforeSend: function(){
					$("form button").prop('disabled', true).removeClass("btn-primary btn-danger").addClass("btn-default");
					$("form button i.fas").toggleClass("fa-sign-in-alt fa-sync-alt fa-spin");
				},
				success: function(data){
					setTimeout(function(){
						$("form button").removeClass("btn-default").addClass("btn-success").prop('disabled', true);
						$("form button i.fas").toggleClass("fa-sync-alt fa-spin fa-check");
					}, 600);

					setTimeout(function(){
						$("main").removeClass("bounceInUp").addClass("bounceOutUp");
					}, 1500);

					setTimeout(function(){
						location.reload();
					}, 2500);
				},
				error: function(data){
					setTimeout(function(){
						$("form input[type=password]").val('');
						$("form button").removeClass("btn-default").addClass("btn-danger").prop('disabled', true);
						$("form button i.fas").toggleClass("fa-sign-in-alt fa-sync-alt fa-spin");
					}, 1000);
				}
			});
		});
	});
</script>
