<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<main>
	<div class="row mb-3">
		<div class="col text-right order-12">
			<div class="btn-group">
				<button class="btn btn-outline-secondary">
					<i class="fas fa-plus"></i>
				</button>
				<button class="btn btn-outline-secondary">
					<i class="fas fa-pencil-alt"></i>
				</button>
				<button class="btn btn-outline-secondary">
					<i class="fas fa-exchange-alt"></i>
				</button>
			</div>
		</div>
		<div class="col order-4">
			<div class="btn-group">
				<button class="btn btn-outline-secondary">
					<i class="fas fa-caret-left"></i>
				</button>
				<button class="btn btn-outline-secondary">
					<i class="fas fa-caret-right"></i>
				</button>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-md-4">
			<div class="card">
				<div class="card-header">
					Contacto
					<div class="float-right">
						<i class="fas fa-pencil-alt"></i>
					</div>
				</div>
				<div id="contact-profile" class="card-body">
					<h5 class="card-title">Nombre Apellido Tal</h5>
					<ul class="card-text list-unstyled">
						<li>
							<i class="fa fa-mars"></i>
							Hombre, 22 años (1996-09-14)
						</li>
						<li>
							<i class="fa fa-map-marker-alt"></i>
							España (16:04)
						</li>
						<li>
							<i class="fa fa-id-card"></i>
							44441234L
						</li>
						<li>
							<i class="fa fa-calendar-alt"></i>
							Creado 2018-04-01 16:40
						</li>
					</ul>
				
				</div>
				<ul id="contact-sources" class="list-group list-group-flush list-unstyled">
					<li id="contact-tags" class="list-group-item">
						<textarea class="form-control d-none" name="tags">Campus Espanyol, Scouting, No interesado, </textarea>
						<div id="contact-tags-list">
							No hay etiquetas.
						</div>
						<div id="contact-tags-add" class="input-group input-group-sm mt-2 d-none">
							<input type="text" class="form-control" placeholder="Etiqueta" maxlength="100">
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button">
									<i class="fa fa-plus"></i>
								</button>
							</div>
						</div>
					</li>
					<li class="list-group-item">
						<i class="fa fa-envelope"></i>
						<span>micorreo@ejemplo.com</span>
						<span class="badge badge-pill badge-primary float-right">5</span>
					</li>
					<li class="list-group-item">
						<i class="fa fa-mobile-alt"></i>
						<a href="#">+54 112 745 615</a>
						<span class="badge badge-pill badge-warning float-right">2</span>
					</li>
					<li class="list-group-item">
						<i class="fa fa-phone"></i>
						<a href="#">+34 901 247 621</a>
						<span class="badge badge-pill badge-success float-right">3</span>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-xs-12 col-md-4">
			<div class="card">
				<div class="card-header">
					Tareas
					<div class="float-right">
						<i class="fas fa-plus"></i>
					</div>
				</div>
				<div id="contact-tasks" class="card-body">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
						<label class="form-check-label" for="defaultCheck2">
							 Hacer la tarea esa.
						</label>
						<span class="text-muted small">Desde hace 2 semanas</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script>
function renderTags(){
	var tags = $("textarea[name=tags]").val().split(',');
	if(tags.length > 0){
		$("#contact-tags-list").empty();
		var colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
		tags.forEach(function(t){
			if(t.trim().length <= 1){ return; }
			var tag = $("<a></a>");
			var color = (t.trim().length % colors.length);
			tag.addClass("badge badge-" + colors[color]);
			// warning and dark
			if(color != 4 && color != 6){
				tag.addClass("text-white");
			}
			tag.text(t.trim());
			$("#contact-tags-list").append(tag);
			$("#contact-tags-list").append(" ");
		});
	}
};

$(function(){
	$("textarea[name=tags]").change( function(){ renderTags() } );
	renderTags();

	$("#contact-tags").on('dblclick', function(e){
		// Only on div, not tags.
		if(e.target.tagName === "A"){ return; }
		$("#contact-tags-add").toggleClass("d-none");
		$("#contact-tags-add input").focus();
	});

	$('body').on('dblclick', "#contact-tags-list a", function(e){
		var tag = $(this).text();
		$("#contact-tags-add input").val(tag);
		var tags = $("textarea[name=tags]").val();
		tags = tags.replace(tag + ", ", "");
		console.log(tags);
		$("textarea[name=tags]").val(tags);
		$("textarea[name=tags]").html(tags);
		renderTags();
	});

	$("#contact-tags-add input").on('keypress', function(e){
		// Si pulsa Enter, 
		// $("#contact-tags-add button").trigger('click');
	});
	$("#contact-tags-add button").on('click', function(e){
		var tag = $("#contact-tags-add input").val().trim();
		if(tag.length < 1){ return; }
		var tags = $("textarea[name=tags]").val();
		if(tags.toLowerCase().indexOf( tag.toLowerCase() ) >= 0){ return; }
		tags += tag + ", ";
		$("textarea[name=tags]").val(tags);
		$("textarea[name=tags]").html(tags);
		$("#contact-tags-add input").val("");
		renderTags();
	});
});
</script>
