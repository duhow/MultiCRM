<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<main class="col-xs-12 col-md-4 col-lg-3 col-sm-6 mx-auto animated" :class="!this.loginbtn.logged ? 'bounceInDown' : 'bounceOutUp'" :style="{ top: startHeight + 'px' }">
	<div class="col-xs-12 mx-auto">
		<img src="<?= base_url("img/logo.png"); ?>" class="img-fluid">
	</div>
	<form method="post" action="<?= base_url("login"); ?>" class="card" @submit.prevent="doLogin">
		<div class="card-header">
			<i class="fas fa-user"></i> Iniciar sesión
		</div>
		<div class="card-body">
			<div class="form-group">
				<input class="form-control" type="text" name="user" v-model="login.user" @change="isFilledForm" placeholder="Usuario">
			</div>

			<div class="form-group">
				<input class="form-control" type="password" name="pass" v-model="login.pass" @change="isFilledForm" placeholder="Contraseña">
			</div>

			<div class="form-group" id="recaptcha" :data-sitekey="captcha.key" v-show="captcha.enabled && captcha.active"></div>

			<b-button :variant="loginbtn.variant" :block="true" :disabled="loginbtn.disabled || loginbtn.login" type="submit">
				<i :class="loadingIcon"></i> Entrar
			</b-button>
		</div>
	</form>
</main>

<script>
function grecaptchaEnable(){
	app.captcha.enabled = true;
	grecaptcha.render('recaptcha', {'callback': 'grecaptchaFill'});
}

function grecaptchaFill(token){
	app.captcha.value = token;
}

var app = new Vue({
	el: 'main',
	data: {
		login: {
			user: null,
			pass: null,
			'g-recaptcha-response': null
		},
		loginbtn: {
			disabled: true,
			variant: 'primary',
			login: false,
			logged: false
		},
		startHeight: 0,
		captcha: {
			enabled: false,
			active: <?= (isset($captcha) ? 'true' : "false"); ?>,
			key: '<?= ($this->config->item('recaptcha_key_public') ?: ''); ?>',
			value: null
		}
	},
	computed: {
		isFilledForm: function(){
			var r = !(
				this.login.user && this.login.user.length > 3 &&
				this.login.pass && this.login.pass.length > 3
			);
			if(this.captcha.enabled && this.captcha.active && !r){
				r = !(this.captcha.value && this.captcha.value.length > 1);
			}
			this.loginbtn.disabled = r;
			if(!r && this.loginbtn.variant === 'danger'){
				this.loginbtn.variant = 'primary';
			}
			return !r;
		},
		loadingIcon: function(){
			if(this.loginbtn.variant == 'default'){
				return ['fas', 'fa-sync-alt', 'fa-spin'];
			}else if(this.loginbtn.variant == 'success'){
				return ['fas', 'fa-check'];
			}else{
				return ['fas', 'fa-sign-in-alt'];
			}
		}
	},
	mounted() {
		this.$nextTick(function() {
			window.addEventListener('resize', this.centerLogin);
			this.centerLogin();
		});
	},
	beforeDestroy() {
		window.removeEventListener('resize', this.centerLogin);
	},
	methods: {
		centerLogin: function(){
			var h = document.querySelector("main").clientHeight;
			var wh = window.innerHeight;

			this.startHeight = parseInt((wh - h) / 2) - 20;
		},
		doLogin: function(e){
			if(!this.login.user || !this.login.pass){ return false; }
			this.loginbtn.login = true;
			this.loginbtn.variant = 'default';
			this.login['g-recaptcha-response'] = this.captcha.value;

			axios.post(e.target.action, Qs.stringify(this.login))
			.then(function(r){
				setTimeout(function(){
					app.loginbtn.variant = 'success';
				}, 600);

				setTimeout(function(){
					app.loginbtn.logged = true;
				}, 1500);

				setTimeout(function(){
					location.reload();
				}, 2500);
			})
			.catch(function(r){
				setTimeout(function(){
					app.login.pass = '';
					app.loginbtn.variant = 'danger';
					app.loginbtn.login = false;
					if(app.captcha.enabled){
						app.captcha.active = true;
						app.captcha.value = null;
						grecaptcha.reset();
					}
				}, 1000);
			});
		}
	}
});
</script>
