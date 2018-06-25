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
				<button class="btn btn-outline-secondary" data-action="contact-prev">
					<i class="fas fa-caret-left"></i>
				</button>
				<button class="btn btn-outline-secondary" data-action="contact-next">
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
				<div class="card-body loading contact-profile">
					<div class="ph-item">
						<div class="ph-col-12">
							<div class="ph-row">
								<div class="ph-col-6 big"></div>
								<div class="ph-col-6 big empty"></div>
							</div>
							<div class="ph-row">
								<div class="ph-col-4"></div>
								<div class="ph-col-8 empty"></div>
							</div>
							<div class="ph-row">
								<div class="ph-col-2"></div>
								<div class="ph-col-10 empty"></div>
							</div>
							<div class="ph-row">
								<div class="ph-col-4"></div>
								<div class="ph-col-8 empty"></div>
							</div>
							<div class="ph-row">
								<div class="ph-col-4"></div>
								<div class="ph-col-8 empty"></div>
							</div>
						</div>
					</div>
				</div>
				<div id="contact-profile" class="card-body d-none">
					<h5 class="card-title" data-type="name"></h5>
					<ul class="card-text list-unstyled">
						<li>
							<i class="fa fa-user"></i>
							<span data-type="gender"></span>
							<span data-type="birthday"></span>
							<span class="text-enclosing" data-type="birthdate"></span>
						</li>
						<li>
							<i class="fa fa-map-marker-alt"></i>
							<span data-type="country"></span>
							<span class="text-enclosing" data-type="country-time" data-timezone=""></span>
						</li>
						<li>
							<i class="fa fa-id-card"></i>
							<span data-type="id_card"></span>
						</li>
						<li>
							<i class="fa fa-calendar-alt"></i>
							<span data-type="date_add"></span>
						</li>
					</ul>
				
				</div>
				<ul id="contact-sources" class="list-group list-group-flush list-unstyled">
					<li class="list-group-item" id="contact-tags" data-source-type="tags">
						<textarea class="form-control d-none" name="tags"></textarea>
						<div id="contact-tags-list"></div>
						<div class="loading contact-tags-list">
							<div class="ph-item">
								<div class="ph-col-12">
									<div class="ph-row">
										<div class="ph-col-12"></div>
									</div>
									<div class="ph-row">
										<div class="ph-col-8"></div>
										<div class="ph-col-4 empty"></div>
									</div>
								</div>
							</div>
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
					<!-- <li class="list-group-item contact-source">
						<i class="fa fa-phone"></i>
						<a href="#">+34 901 247 621</a>
						<span class="badge badge-pill badge-success float-right">3</span>
					</li> -->
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
window.onpopstate = function (event) {
	  if (event.state) {
		// history changed because of pushState/replaceState
		// console.log("state poped!");
		CRM.Contact.load(CRM.Contact.getCurrentURL());
	  } else {
		// history changed because of a page load
		// When anchor action (#)
		// console.log("time machine!");
	  }
}

$(function(){
	// Load
	CRM.displayCountry("es");
	moment.locale(CRM.Language);

	if(CRM.Contact.getCurrentURL()){
		setTimeout(function(){
			CRM.Contact.load(CRM.Contact.getCurrentURL());
			CRM.Contact.updateMoments();
		}, 150);
	}

	$("textarea[name=tags]").change( function(){ CRM.Contact.renderTags() } );

	// Display input new tag
	$("#contact-tags").on('dblclick', function(e){
		// Only on div, not tags.
		if(e.target.tagName === "A"){ return; }
		$("#contact-tags-add").toggleClass("d-none");
		$("#contact-tags-add input").focus();
	});

	// Delete tag
	$('body').on('dblclick', "#contact-tags-list a", function(e){
		var tag = $(this).text();
		$("#contact-tags-add input").val(tag);
		var tags = $("textarea[name=tags]").val();
		tags = tags.replace(tag + ", ", "");
		// console.log(tags);
		$("textarea[name=tags]").val(tags);
		$("textarea[name=tags]").html(tags);
		CRM.Contact.renderTags();
	});

	// If keypress ENTER on input tag
	$("#contact-tags-add input").on('keypress', function(e){
		if(e.which == 13){
			$("#contact-tags-add button").trigger('click');
		}
	});

	// Add new tag
	$("#contact-tags-add button").on('click', function(e){
		var tag = $("#contact-tags-add input").val().trim();
		if(tag.length < 1){ return; }
		var tags = $("textarea[name=tags]").val();
		if(tags.toLowerCase().indexOf( tag.toLowerCase() ) >= 0){ return; }
		tags += tag + ", ";
		$("textarea[name=tags]").val(tags);
		$("textarea[name=tags]").html(tags);
		$("#contact-tags-add input").val("");
		CRM.Contact.renderTags();
	});

	// Navigation buttons
	$("body").on('click', "button[data-action]", function(e){
		var id = CRM.Contact.getCurrentURL();
		if($(this).data('action') == "contact-prev"){
			id = Math.max(id - 1, 1);
		}else if($(this).data('action') == "contact-next"){
			id = (id + 1);
		}
		window.history.pushState('contact-' + id, null, './' + id);
		CRM.Contact.load(id);
	});
});
</script>
