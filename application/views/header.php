<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1 user-scalable=no">
	<title>MultiCRM<?= (isset($title) ? ' >> ' .$title : ''); ?></title>
	<?php
	if(!isset($css_files)) { $css_files = array(); }
	$base = [
		'bootstrap.min.css',
		'bootstrap-vue.min.css',
		'animate.min.css',
		'fontawesome-all.min.css',
		// 'jquery.dataTables.min.css',
		// 'summernote-bs4.css',
		'placeholder-loading.css'
	];
	// Push custom CSS files
	foreach($css_files as $css){ $base[] = $css; }

	// Use full list
	$css_files = $base;
	foreach ($css_files as $css){
		if(file_exists("include/css/$css")){
			$css = base_url("include/css/$css");
		}
	?>
	<link rel="stylesheet" href="<?= $css; ?>">
	<?php }

	if(!isset($js_files)) { $js_files = array(); }
	$base = [
		'vue.js',
		'axios.min.js',
		'qs.min.js',
		'bootstrap-vue.min.js',
		// 'jquery.min.js',
		// 'bootstrap.min.js',
		// 'jquery.dataTables.min.js',
		'linkify.min.js',
		// 'linkify-jquery.min.js',
		// 'summernote-bs4.min.js',
		// 'summernote-es-ES.js',
		'libphonenumber-js.min.js',
		// 'vue-tel-input.min.js',
		// 'intlTelInput.utils.js',
		// -- 'intlTelInput.min.js',
		'moment-with-locales.min.js',
		'moment-timezone-with-data-2012-2022.min.js',
		'country.tz.js',
		'crm.js',
	];
	// Push custom JS files
	foreach($js_files as $js){ $base[] = $js; }

	// Use full list
	$js_files = $base;
	foreach ($js_files as $js){
		if(file_exists("include/js/$js")){
			$js = base_url("include/js/$js");
		}
	?>
	<script src="<?= $js; ?>"></script>
	<?php }

	// Add Google Recaptcha if enabled
	if($this->load->is_loaded('recaptcha')){ ?>
	<script src="https://www.google.com/recaptcha/api.js?render=explicit&onload=grecaptchaEnable" async defer></script>
	<script src="<?= base_url('include/js/vue-grecaptcha.min.js'); ?>"></script>
	<?php } ?>
</head>
<body class="container-fluid">
