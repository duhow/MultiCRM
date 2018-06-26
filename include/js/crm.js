var CRM = {
	API: {
		Get: function(action, cbSuccess, cbError){
			var wl = window.location;
			var nurl = wl.protocol + '//' + wl.hostname + '/api/' + CRM.API.Token + '/' + action;

			$.ajax({
				url: nurl,
				cache: false,
				dataType: 'json',
				success: cbSuccess,
				error: cbError
			});
		},
		Token: 'JzVuGfdeDArfNHSbKDWC3th1'
	},

	Language: 'es',
	LanguageAlt: 'spa',

	Countries: null,
	displayCountry: function(ct, lang){
		if(CRM.Countries == null){
			var json = $.getJSON("https://raw.githubusercontent.com/mledoze/countries/master/dist/countries.json").done(function(d){
				CRM.Countries = d;
			});
		}
		if(Number.isInteger(ct)){
			if(lang != null && CRM.Countries[ct].translations[lang]){
				return CRM.Countries[ct].translations[lang].common;
			}
			return CRM.Countries[ct].name.common;
		}
		for(var cid in CRM.Countries){
			var ctu = ct.toUpperCase();
			if(CRM.Countries[cid].cca2 == ctu){
				cid = parseInt(cid);
				return CRM.displayCountry(cid, lang);
			}
		}
		return null;
	},
	displayCountryTimezone: function(ct){
		var cts = {
			"AD":"Europe\/Andorra",
			"AM":"Asia\/Yerevan",
			"AR":"America\/Argentina\/Buenos_Aires",
			"AU":"Australia\/Adelaide",
			"AW":"Asia\/Riyadh",
			"BG":"Europe\/Sofia",
			"BO":"America\/La_Paz",
			"BR":"America\/Sao_Paulo",
			"CA":"Canada\/Central",
			"CN":"Asia\/Shanghai",
			"CO":"America\/Bogota",
			"CR":"America\/Costa_Rica",
			"CU":"America\/Havana",
			"DE":"Europe\/Berlin",
			"DK":"Europe\/Copenhagen",
			"DO":"America\/Santo_Domingo",
			"DZ":"Africa\/Algiers",
			"EC":"America\/Guayaquil",
			"EG":"Africa\/Cairo",
			"ES":"Europe\/Madrid",
			"FR":"Europe\/Paris",
			"GB":"Europe\/London",
			"GQ":"Africa\/Algiers",
			"GR":"Europe\/Athens",
			"GT":"America\/Guatemala",
			"HK":"Asia\/Hong_Kong",
			"HN":"America\/Tegucigalpa",
			"HT":"America\/Santo_Domingo",
			"ID":"Asia\/Jakarta",
			"IE":"Europe\/Dublin",
			"IN":"Asia\/Kolkata",
			"IT":"Europe\/Rome",
			"JP":"Asia\/Tokyo",
			"LB":"Asia\/Beirut",
			"MA":"Africa\/Casablanca",
			"ME":"Europe\/Podgorica",
			"MV":"Indian\/Maldives",
			"MX":"America\/Mexico_City",
			"NI":"America\/Managua",
			"NO":"Europe\/Oslo",
			"NZ":"Antarctica\/McMurdo",
			"PA":"America\/Panama",
			"PE":"America\/Lima",
			"PL":"Europe\/Warsaw",
			"PR":"America\/Puerto_Rico",
			"PT":"Europe\/Lisbon",
			"PY":"America\/Asuncion",
			"QA":"Asia\/Qatar",
			"RO":"Europe\/Bucharest",
			"SA":"Asia\/Riyadh",
			"SE":"Europe\/Stockholm",
			"SV":"America\/El_Salvador",
			"UA":"Europe\/Kiev",
			"US":"America\/Chicago",
			"UY":"America\/Montevideo",
			"VE":"America\/Caracas",
		};

		ct = ct.toUpperCase();
		if(ct in cts){ return cts[ct]; }
		return null;
	},

	Contact: {
		updateMoments: function(){
			var el, tz;
			el = $("#contact-profile li span[data-type=birthday]");
			if(el.data('moment-date')){
				el.text(moment(el.data('moment-date')).fromNow(true));
			}

			el = $("#contact-profile li span[data-type=country-time]");
			tz = el.data('timezone');
			if(!tz){ tz = 'Europe/Madrid'; }
			el.text(moment().tz(tz).format("HH:mm"));
			
			el = $("#contact-profile li span[data-type=date_add]");
			el.text(moment(el.data('moment-date')).fromNow());

			setTimeout(CRM.Contact.updateMoments, 1000);
		},

		renderContact: function(c, hide){
			var d = $("#contact-profile");
			$("#contact-profile h5").empty();
			$("#contact-profile ul li span").each(function(i){
				$(this).removeData('moment-date');
				$(this).empty();
			});
			$("#contact-profile ul li").addClass("d-none");
			// d.empty();
			$("#contact-sources li.contact-source").remove();
		
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
					$("#contact-profile li span[data-type=birthday]")
						.text(moment(c.contact.birthdate).fromNow(true))
						.data('moment-date', c.contact.birthdate);
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
				if(CRM.Countries){
					s.text(CRM.displayCountry(c.contact.country, CRM.LanguageAlt));
				}else{
					s.text(c.contact.country);
					setTimeout(function(){
						s.text(CRM.displayCountry(c.contact.country, CRM.LanguageAlt));
					}, 100);
				}
				/* if($.fn.intlTelInput){
					$.each($.fn.intlTelInput.getCountryData(), function(i, e){
						if(e.iso2 == c.contact.country.toLowerCase()){
							s.text(e.name);
							return false;
						}
					});
				}else{
					s.text(c.contact.country);
				} */
		
				s = $("#contact-profile li span[data-type=country-time]");
				if(CRM.displayCountryTimezone(c.contact.country)){
					s.data('timezone', CRM.displayCountryTimezone(c.contact.country));
				}
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
			s.data('moment-date', c.contact.date_add);
			s.text(moment(c.contact.date_add).fromNow());
		
			if(c.tags){
				var tags = c.tags.join(', ') + ', ';
				$("#contact-tags textarea").text(tags).val(tags);
				CRM.Contact.renderTags();
			}else{
				$("#contact-tags textarea").text("").val("");
				CRM.Contact.renderTags();
				$("#contact-tags-list").text("No hay etiquetas.");
			}
			// ------------
		
			// $("#contact-sources li.contact-source").remove();
			if(c.sources){
				for(var sType in c.sources){
					if(c.sources[sType]){
						for(var sIdx in c.sources[sType]){
							var sr = CRM.Contact.renderSource(c.sources[sType][sIdx], sType);
							// HACK Hide only if asked
							if(typeof hide === 'undefined' || hide){
								sr.addClass("d-none");
							}
							$("#contact-sources").append(sr);
						};
					}
				};
			}
		
			setTimeout(function(){
				$("#contact-profile").removeClass("d-none");
				$(".contact-profile.loading").addClass("d-none");
		
				$("#contact-tags-list").removeClass("d-none");
				$(".contact-tags-list.loading").addClass("d-none");
				
				$("#contact-sources li.contact-source").removeClass("d-none");
			}, 100);
		},

		renderTags: function(){
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
		},

		renderSource: function(s, type){
			var icons = {
				'email': 'envelope',
				'mobile': 'mobile-alt',
				'phone': 'phone'
			};
		
			var t = $('<li class="list-group-item contact-source"></li>');
			t.data('source-type', type);
		
			var x = $('<i class="fa"></i>');
			x.addClass("fa-" + icons[type]);
			t.append(x);
		
			x = $('<a></a>');
			if(type == 'email'){
				x.attr('href', 'mailto:' + s[type]);
				x.attr('target', '_blank');
				x.text(s[type]);
			}else if(type == 'phone' || type == 'mobile'){
				var z = $('<input type="tel"/>');
				z.val(s[type]);
		
				// Start intl-tel-input
				z.intlTelInput();
				if(s.country){
					z.intlTelInput("setCountry", s.country);
				}
		
				// Display formated number
				x.text(z.intlTelInput("getNumber", intlTelInputUtils.numberFormat.INTERNATIONAL));

				// If not valid number
				if(!z.intlTelInput("isValidNumber") && !s.verified){
					t.addClass("bg-warning");
				}
		
				x.attr('href', 'tel:' + z.intlTelInput("getNumber"));
				// x.attr('href', 'sip:' + z.intlTelInput("getNumber"));
			}
			t.append(x);
		
			return t;
		},
		
		load: function(id, hide){
			if(typeof hide === 'undefined' || hide){
				CRM.Contact.sectionsHide();
				$("#contact-sources li.contact-source").remove();
			}

			CRM.API.Get('contact/' + id, function(d){
				if(d.status == "OK"){ return CRM.Contact.renderContact(d.data, hide); }
			});
		},

		refreshTimeout: null,
		refresh: function(){
			var id = CRM.Contact.getCurrentURL();
			return CRM.Contact.load(id, false);
		},

		autoRefresh: function(enable, secs){
			clearTimeout(CRM.Contact.refreshTimeout);
			// Disable autorefresh
			if(typeof enable !== 'undefined' && !enable){
				return false;
			}
			// If no fixed seconds specified
			var secspass = true;
			if(typeof secs === 'undefined'){
				// 7-15s
				secs = Math.floor(Math.random() * 8) + 7;
				secspass = false;
			}
			CRM.Contact.refreshTimeout = setTimeout(function(){
				CRM.Contact.refresh();
				if(secspass){
					CRM.Contact.autoRefresh(enable, secs);
				}else{
					CRM.Contact.autoRefresh(enable);
				}
			}, secs * 1000);
		},

		sectionsHide: function(){
			$("#contact-profile").addClass("d-none");
			$(".contact-profile.loading").removeClass("d-none");
		
			$("#contact-tags-list").addClass("d-none");
			$(".contact-tags-list.loading").removeClass("d-none");
		},
		
		getCurrentURL: function(){
			var id = window.location.pathname.match(/contact\/view\/(\d+)\/?/);
			if(id){
				return parseInt(id[1]);
			}
			return false;
		}
	}
};
