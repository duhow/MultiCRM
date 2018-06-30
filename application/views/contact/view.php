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
		<div class="col-md-6 col-lg-4 col-xl-3 order-1 mb-3">
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
				</ul>
			</div>
		</div>
		<div class="col-sm-12 col-md-12 col-lg-8 col-xl-6 order-sm-3 order-2 mb-3">
			<div class="card">
				<div class="card-header">
					Registro
					<div class="float-right">
						<i class="fas fa-plus"></i>
					</div>
				</div>

				<ul class="card-body loading list-unstyled pl-0 pr-0">
					<?php for($i = 0; $i < 8; $i++){ ?>
					<li class="media ph-item">
						<div class="ph-col-12">
							<div class="ph-row">
								<div class="p-3 ph-col-2"></div>
								<div class="p-3 ph-col-10"></div>
							</div>
						</div>
					</li>
					<?php } ?>
				</ul>

				<div class="card-body">
					<div class="media">
						<i class="fa fa-phone fa-lg mr-3"></i>
						<div class="media-body">
							<h6>Hace 2 dias</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 order-3 order-md-2 order-lg-3 mb-3">
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
						<span class="text-muted d-block small">Desde hace 2 semanas</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script>

$(function(){
	// Load
	CRM.displayCountry("es");
	moment.locale(CRM.Language);

	if(CRM.Contact.getCurrentURL()){
		setTimeout(function(){
			CRM.Contact.load(CRM.Contact.getCurrentURL());
			CRM.Contact.updateMoments();
			CRM.Contact.autoRefresh(true);
		}, 150);
	}

	CRM.Contact.initBindings();
});
</script>
