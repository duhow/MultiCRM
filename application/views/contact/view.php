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
				<button class="btn btn-outline-secondary" @click="loadContact(-1)">
					<i class="fas fa-caret-left"></i>
				</button>
				<button class="btn btn-outline-secondary" @click="loadContact(0)">
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
				<div v-show="person.contact" id="contact-profile" class="card-body" style="display: none">
					<h5 class="card-title" data-type="name">{{ person.contact.first_name + ' ' + person.contact.last_name }}</h5>
					<ul class="card-text list-unstyled">
						<li v-show="( person.contact.gender || person.contact.birthdate )">
							<i class="fa fa-user"></i>
							<span data-type="gender">{{ resolveGender }}</span><span v-if="person.contact.gender && person.contact.birthdate">, </span>
							<span v-show="person.contact.birthdate">
								<span data-type="birthday">{{ person.contact.birthdate | moment(true) }}</span>
								<span data-type="birthdate">({{ person.contact.birthdate | moment_format("l") }})</span>
							</span>
						</li>
						<li v-show="person.contact.country">
							<i class="fa fa-map-marker-alt"></i>
							<span data-type="country">{{ person.contact.country | countryName }}</span>
							<span data-type="country-time" data-timezone="">({{ person.contact.country | countryTZ | moment_tz("HH:mm") }})</span>
						</li>
						<li v-show="person.contact.id_card">
							<i class="fa fa-id-card"></i>
							<span data-type="id_card">{{ person.contact.id_card }}</span>
						</li>
						<li>
							<i class="fa fa-calendar-alt"></i>
							<span data-type="date_add">{{ person.contact.date_add | moment }}</span>
						</li>
					</ul>
				
				</div>
				<ul id="contact-sources" class="list-group list-group-flush list-unstyled">
					<li class="list-group-item" id="contact-tags" data-source-type="tags" @dblclick="tagAddToggle">
						<div id="contact-tags-list">
							<span v-if="person.tags.length == 0">No hay etiquetas.</span>
							<a v-for="(tag, idx) in person.tags" href="#" class="badge" :class="tagColor(idx)" @dblclick="tagRemove">{{ tag }}</a>
						</div>
						<div id="contact-tags-add" class="input-group input-group-sm mt-2" v-show="form.tag.inputvisible">
							<input type="text" class="form-control" placeholder="Etiqueta" maxlength="100" v-model="form.tag.inputadd" @keyup.enter="tagAdd">
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" @click="tagAdd">
									<i class="fa fa-plus"></i>
								</button>
							</div>
						</div>
					</li>
					<li class="list-group-item contact-source" v-if="person.sources.email" v-for="source in person.sources.email">
						<i class="fa fa-envelope"></i>
						<a target="_blank" :href="'mailto:' + source.email">{{ source.email }}</a>
						<i class="float-right fa" :class="[source.verified ? 'fa-check' : 'fa-question']"></i>
					</li>
					<li class="list-group-item contact-source" v-if="person.sources.phone" v-for="source in person.sources.phone">
						<i class="fa" :class="renderPhone(source.type)"></i>
						<a target="_blank" :href="'sip:' + source.phone">{{ source.phone | phoneResolve(source.country) | phoneFormat }}</a>
						<i class="float-right fa" :class="[source.verified ? 'fa-check' : 'fa-question']"></i>
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
				<div class="card-body">
					<div v-if="person.actions.length == 0" class="media">
						<i class="fa fa-question fa-lg mr-3"></i>
						<div class="media-body">
							<h6>No hay acciones recientes.</h6>
						</div>
					</div>
					<div v-for="action in person.actions" class="media">
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
					<span v-if="person.tasks.length == 0">
						No hay tareas pendientes.
					</span>
					<div class="form-check" v-for="task in person.tasks">
						<input class="form-check-input" type="checkbox" :checked="task.date_completed != null" :id="'task-' + task.id" :value="task.id" @change="taskComplete">
						<label class="form-check-label" :for="'task-' + task.id">
							{{ task.task }}
						</label>
						<span v-if="task.date_completed != null" class="text-muted d-block small">Completado {{ task.date_completed | moment }}</span>
						<span v-else class="text-muted d-block small">Desde {{ task.date_pending | moment }}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<script>

moment.locale("es");

var app = new Vue({
	data: {
		myInterval: null,
		person: [],
		form: {
			tag: {
				inputvisible: false,
				inputadd: null
			}
		},
		language: 'es',
		languageAlt: 'spa'
	},
	methods: {
		loadContact: function(id){
			var cid = location.pathname.match(/contact\/view\/(\d+)/);
			if(cid){ cid = parseInt(cid[1]); }

			if(id === -1 || id < -1){
				id = Math.max(1, (cid - 1));
			}else if(id === 0){
				id = parseInt(cid + 1);
			}

			axios.get(CRM.API.URL() + 'contact/' + id)
			.then(function(r){
				if(r.data.status == "OK"){
					app.person = r.data.data;
					window.history.pushState('contact-' + id, null, './' + id);
				}
			})
			.catch(function(r){

			});
		},
		taskComplete: function(e){
			var id = e.target.value;
			var idx = this.person.tasks.findIndex(function(j){ return j.id == id; });
			if(e.target.checked){
				this.person.tasks[idx].date_completed = moment().format("YYYY-MM-DD HH:mm:ss");
			}else{
				this.person.tasks[idx].date_completed = null;
			}
			axios.post(CRM.API.URL() + 'contact/' + this.person.contact.id + '/task/' + id);
		},
		renderPhone: function(type){
			if(type == "work"){
				return ["fa-briefcase", "fa-phone"];
			}else if(type == "mobile"){
				return ["fa-mobile-alt"];
			}else{
				return ["fa-phone"];
			}
		},
		tagAddToggle: function(e){
			if(e.target.tagName !== "DIV" && e.target.tagName !== "LI"){ return; }
			this.form.tag.inputvisible = !this.form.tag.inputvisible;
			this.$nextTick(function(){
				document.querySelector("#contact-tags-add input").focus();
			});
			return this.form.tag.inputvisible;
		},
		tagAdd: function(e){
			this.form.tag.inputadd = this.form.tag.inputadd.trim();
			if(this.form.tag.inputadd.length < 1){ return; }
			tags = new Array(this.form.tag.inputadd);
			axios.post(CRM.API.URL() + 'contact/' + this.person.contact.id + '/tag', tags)
			.then(function(d){
				app.person.tags.push(app.form.tag.inputadd);
				app.form.tag.inputadd = null;
			})
			.catch(function(d){

			});
		},
		tagRemove: function(e){
			var tags = [e.target.innerText];
			axios.delete(CRM.API.URL() + 'contact/' + this.person.contact.id + '/tag', { data: tags })
			.then(function(d){
				var idx = app.person.tags.indexOf(e.target.innerText);
				app.person.tags.splice(idx, 1);
				this.form.tag.inputadd = e.target.innerText;
			})
			.catch(function(d){

			});
		},
		tagColor: function(idx){
			var colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];
			var t = this.person.tags[idx];
			var color = (t.trim().length % colors.length);
			var classes = ["badge-" + colors[color]];
			if(color != 4 && color != 6){
				classes.push("text-white");
			}
			return classes;
		}
	},
	filters: {
		moment: function(date, short) {
			if(typeof short === 'undefined'){ short = false; }
			return moment(date).fromNow(short);
		},
		moment_format: function(date, fmt){
			return moment(date).format(fmt);
		},
		moment_tz: function(tz, fmt){
			return moment().tz(tz).format(fmt);
		},
		countryTZ: function(ct){
			return window.countrytz[ct];
		},
		countryName: function(ct, lang){
			if(typeof window.countries === 'undefined'){ return ct; }
			var idx = window.countries.findIndex(function(j){ return j.cca2 == ct; });

			if(typeof lang === 'undefined'){ lang = app.languageAlt; }

			if(typeof window.countries[idx].translations[lang] !== 'undefined'){
				return window.countries[idx].translations[lang].common;
			}
			return window.countries[idx].name.common;
		},
		phoneResolve: function(phone, ct){
			var x = libphonenumber.parse(phone, ct);
			return (x.phone ? x : phone);
		},
		phoneFormat: function(phone, fmt){
			if(typeof fmt === 'undefined'){ fmt = 'International'; }
			var x = libphonenumber.format(phone, fmt);
			return x;
		}
	},
	computed: {
		resolveGender(){
			if(this.person.contact.gender == "M"){ return "Hombre"; }
			else if(this.person.contact.gender == "F"){ return "Mujer"; }
		}
	},
	mounted: function(){
		axios.get('https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json')
		.then(function(r){
			window.countries = r.data;
		});
	}
});

<?php if(isset($contact)){
	echo 'app.person = ' .json_encode($contact) .";\n";
} ?>
app.$mount("main");

</script>
