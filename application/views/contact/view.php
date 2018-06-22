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
						<textarea class="form-control d-none" name="tags">Campus Espanyol, Scouting, No interesado, </textarea>
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
			tag.prop('href', '#');
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

function renderContact(c){
	var d = $("#contact-profile");
	$("#contact-profile h5").empty();
	$("#contact-profile ul li span").each(function(i){
		$(this).empty();
	});
	$("#contact-profile ul li").addClass("d-none");
	// d.empty();

	if(c.contact.first_name){
		s = c.contact.first_name + " " + c.contact.last_name;
		$("#contact-profile [data-type=name]").text(s.trim());
	}

	if(c.contact.gender || c.contact.birthdate){
		var s = $("#contact-profile li span[data-type=gender]");
		s.parent("li").removeClass("d-none");
		// s.closest("i.fa").removeClass("fa-mars fa-venus");

		if(c.contact.gender == "M"){
			// s.closest("i").addClass("fa-mars");
			s.text("Hombre");
		}else if(c.contact.gender == "F"){
			// s.closest("i").addClass("fa-venus");
			s.text("Mujer");
		}

		var sep = Boolean(c.contact.gender && c.contact.birthdate);
		s.toggleClass("text-separator", sep);

		if(c.contact.birthdate){
			moment.locale('es');
			$("#contact-profile li span[data-type=birthday]").text(moment(c.contact.birthdate).fromNow(true));
			// moment(c.contact.birthdate).format("DD/MM/YYYY");
			$("#contact-profile li span[data-type=birthdate]").text(c.contact.birthdate).removeClass("d-none");
		}else{
			$("#contact-profile li span[data-type=birthdate]").addClass("d-none");
		}
	}

	// ------------
	if(c.contact.country){
		s = $("#contact-profile li span[data-type=country]");
		s.parent("li").removeClass("d-none");
		s.text(c.contact.country);

		s = $("#contact-profile li span[data-type=country-time]");
		s.data('timezone', "Europe/Madrid");
		s.text(moment().tz(s.data('timezone')).format("HH:mm"));
	}

	// ------------
	if(c.contact.id_card){
		s = $("#contact-profile li span[data-type=id_card]");
		s.parent("li").removeClass("d-none");
		s.text(c.contact.id_card);
	}

	// ------------
	s = $("#contact-profile li span[data-type=date_add]");
	s.parent("li").removeClass("d-none");
	s.text(moment(c.contact.date_add).fromNow());

	if(c.tags){
		$("#contact-tags textarea").text(c.tags.join(', ')).val(c.tags.join(', '));
		renderTags();
	}else{
		$("#contact-tags textarea").text("").val("");
		renderTags();
		$("#contact-tags-list").text("No hay etiquetas.");
	}
	// ------------

	setTimeout(function(){
		$("#contact-profile").removeClass("d-none");
		$(".contact-profile.loading").addClass("d-none");

		$("#contact-tags-list").removeClass("d-none");
		$(".contact-tags-list.loading").addClass("d-none");
	}, 100);
}

function renderSource(s, type){
	var icons = {
		'email': 'envelope',
		'mobile': 'mobile-alt',
		'phone': 'phone'
	};

	var t = $('<li class="list-group-item"></li>');
	t.data('source-type', type);

	var x = $('<i class="fa"></i>');
	x.addClass("fa-" + icons[type]);
	t.append(x);

	x = $('<a></a>');
	if(type == 'email'){
		x.attr('href', 'mailto:' + s[type]);
	}
	x.text(s[type]);
	t.append(x);

	return t;
}

function loadContact(id){
	$("#contact-profile").addClass("d-none");
	$(".contact-profile.loading").removeClass("d-none");

	$("#contact-tags-list").addClass("d-none");
	$(".contact-tags-list.loading").removeClass("d-none");

	$.ajax({
		url: 'https://' + window.location.hostname + '/api/JzVuGfdeDArfNHSbKDWC3th1/contact/' + id,
		cache: false,
		dataType: 'json',
		success: function(d,s,j){
			if(d.status == "OK"){ return renderContact(d.data); }
		}
	});
}

function getCurrentContactURL(){
	var id = window.location.pathname.match(/contact\/view\/(\d+)\/?/);
	if(id){
		return parseInt(id[1]);
	}
	return false;
}

window.onpopstate = function (event) {
	  if (event.state) {
		// history changed because of pushState/replaceState
		console.log("state poped!");
		loadContact(getCurrentContactURL());
	  } else {
		// history changed because of a page load
		console.log("time machine!");
	  }
}

$(function(){
	if(getCurrentContactURL()){
		loadContact(getCurrentContactURL());
	}

	$("textarea[name=tags]").change( function(){ renderTags() } );

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
		console.log(tags);
		$("textarea[name=tags]").val(tags);
		$("textarea[name=tags]").html(tags);
		renderTags();
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
		renderTags();
	});

	// Navigation buttons
	$("body").on('click', "button[data-action]", function(e){
		var id = getCurrentContactURL();
		if($(this).data('action') == "contact-prev"){
			id = Math.max(id - 1, 1);
		}else if($(this).data('action') == "contact-next"){
			id = (id + 1);
		}
		window.history.pushState('contact-' + id, null, './' + id);
		loadContact(id);
	});
});
</script>
