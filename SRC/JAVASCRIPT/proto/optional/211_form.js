/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/

if (typeof db == 'undefined') throw("form.js requires tools.js");


// Form Submit By Ajax ///////////////////////////////////////////////////
/*
	Form.requestAction('frm_name', {
		evalScripts: true,
		onSuccess: function(transport) {
			if (updateElement != '') $(updateElement).update(transport.responseText);
		}
	});
*/
Form.Extended = {
	requestAction: function(form, options) {
		form = $(form);
        options = Object.clone(options || {});
        var params = options.parameters;
        options.parameters = form.serialize(true);
        if (params) {
            if (typeof params == 'string') params = params.toQueryParams();
            Object.extend(options.parameters, params)
        }
        if (form.hasAttribute('method') &&! options.method) options.method = form.method;
		var formAction = form.action;
		if (form.hasAttribute('alternateAction')) formAction = form.getAttribute('alternateAction');
        return new Ajax.Request(formAction, options)
    }
};
Object.extend(Form, Form.Extended);

// Form Submit Class ///////////////////////////////////////////////////
/*
	EXEMPLE :
		// CSS pour les inputs avec erreur
		form input.input_error, form textarea.area_error, form select.select_error{
			border: 1px solid #FF0000;
		}
		// CSS pour la div des messages d'erreur
		.divError{
			clear:both;
			display:block;
			font-size:11px;
			color:#00A7DC;
			font-weight:normal;
			padding:0 0 0 262px;
			background:#EBEBEB;
		}

		<scr + ipt language="javascript" type="text/javascript">
		updateProfil = function() {
			param_membre = { mep: 'alerte', autoScroll: false, action: 'submit' };
			champs_membre = {
				login: {defautValue:'Login', alerte:'Le login est obligatoire'},
				nom: {type:'', defaut:'Dupont', alerte:'Le nom est obligatoire'},
				prenom: {type:'', alerte:'Le prénom est obligatoire'},
				email: {type:'mel', alerte:'L\'email est obligatoire et doit &ecirc;tre valide'},
				naissance: {type:'date', alerte:'La date de naissance est obligatoire'},
				cv: {type:'doc|pdf|txt', alerte:'Le champ adresse est obligatoire'},
			};
			
			// Extension de la function (avant submit)
			if ($('password').value != '') champs_membre.password = {type:'', minchar:4, alerte:'Le mot de passe est obligatoire'};
			if ($('visuel').value != '') champs_membre.visuel: {type:'jpg|png|gif', alerte:'Le champ visuel est obligatoire est doit être au format JPG, GIF ou PNG'};
			
			// OU...
			if (checked) {
				var champs_membre2 = {
					inscription2_adresse2: {alerte:'Le champ <strong>adresse</strong> est obligatoire'},
					inscription2_cp2: {alerte:'Le champ <strong>code postal</strong> est obligatoire'},
					...
				};
				champs_membre = Object.extend(champs_membre, champs_membre2);
			}
			
			// Let's Go
			formVerif('frm_membre', champs_membre, param_membre);
		}

		// SI besoin extension de la function
		checkCheck = function() {
			myForm = document.frm_membre;
			info = '';
			if (myForm.doc.value == '' && myForm.lien.value == '') {
				 info += '<br />- Veuillez choisir une photo ou un lien Youtube';
			}
			if (info != '') {
				printInfo(info);
				return false;
			}
			else updateProfil();
		}

	Par defaut...
	 - mep (Mise en Page) = 'message' (apres input) | 'alerte' (utilise l'alerte "google like")
	 - autoScroll = true : Si erreur scroll formulaire jusqu'a input | false : pas de scroll !
	 - action = 'submit' | getUrl | action a définir... Si "getUrl" préciser "responseElement" si reponse voulu
	 - afterFinish = false | some action to perform
	
	En mode "message", il est possible de créer et placer une DIV "infos alerte" pour chaque champs (Cf. cas particulier radio) :
	<div id="div_error_MONCHAMPS" style="display:none;"></div>  (remplacez "MONCHAMPS" par le nom du champs)
	
	En mode "message" il est aussi possible de customiser la div de message d'erreur :
	annif: {type:'date', alerte:'La date est obligatoire', errorMessCss: 'myCustomClass'},
	
	Types de contraintes : tel_fr, tel, chiffre, mel, url, date et "extensions" (Ex : "jpg|jpeg|gif|png")
	*Cas particulier ne pas mettre les crochets [] des "name" pour les checkbox...

	TODO : add message error (advanced pre-checking)
*/


/* ------------------------- VERIF Formulaire ---------------------------------- */

var formVerif = function(frm_name,arr_control,arr_param) {

    if (!document.forms[frm_name]) {
        alert('Vérifiez l\'ID du formulaire');
        die();
    }

	var myForm = document.forms[frm_name];
	
    var divErrorCss = 'divError'; // Class error applicable a la div affichant l'alerte
    var oneError = false;
    var focusinput = false;
    var errorMessage = '';
	
	// Array parametres (extensibles!...)
    var mep = arr_param['mep'] == 'alerte' ? 'alerte' : 'message';
    var autoScroll = arr_param['autoScroll'] == true ? true : false;
    var action = arr_param['action'] ? arr_param['action'] : 'submit';
	var afterFinish = arr_param['afterFinish'] ? arr_param['afterFinish'] : false;
   
    // Class error applicable aux champs
    var inputCss = {
        input:       'input_error',
        textarea:    'area_error',
        select:      'select_error'
    }

	// Par defaut, tous les champs a vérifier ?
	if (isWhat(arr_control) != 'object') {
		printInfo('toDO // Scan champs par defaut... !!!');
		inputCollection = Form.serialize(myForm, true);
		db(inputCollection);
		die();
		// Serialize form data to a string suitable for Ajax requests (default behavior) or,
		// if optional getHash evaluates to true, an object hash where keys are form control names and values are data
	}
	
	// Clean word bad string
	if (typeof cleanString == 'undefined') db("form.js requires 999_regexp.js");
	else {
		$$('#'+frm_name+' input[type="text"]').each( function(e) { $(e).value = cleanString($F(e)); });
		$$('#'+frm_name+' textarea').each( function(e) { $(e).value = cleanString($F(e)); });
	}
	
    for (var property in arr_control) {
		var nom_champ = property;
		var type = arr_control[property]['type'];
		var minchar = ( arr_control[property]['minchar'] > 0 ? arr_control[property]['minchar'] : false );
		if (minchar) var mincharAlerte = 'Le champ <strong>'+nom_champ+'</strong> doit faire '+minchar+' charact&egrave;res minimum';
		var alerte = ( arr_control[property]['alerte'] ? arr_control[property]['alerte'].stripScripts() : 'Le champ <strong>'+nom_champ+'</strong> est obligatoire');
		var divErrorCssCustom = arr_control[property]['errorMessCss'] ? arr_control[property]['errorMessCss'] : divErrorCss;
		//var specifiqueInput = arr_control[property]['input'] ? arr_control[property]['input'] : '';
		var defautValue = arr_control[property]['defaut'] ? arr_control[property]['defaut'] : '';
		
		var reg_expression;
		var matched = false;
		var alerte_sup = ''; // Alerte spécifique pour 2nd email
		//var toshort = false;
		
		if (!myForm[nom_champ] && !myForm[nom_champ+'[]']) {
			alert('Champ input absent : "'+nom_champ+'"');
			die();
		}
		var input_element = ( myForm[nom_champ] ? myForm[nom_champ] : myForm[nom_champ+'[]'] );
		
		var input_element_p = input_element;
		var input_element_d = input_element;
		if (input_element[0] && input_element[0].nodeName.toLowerCase() != 'option') { // Si input type radio|check > array
			input_element_p = input_element[0]; // Premier element
			input_element_d = input_element[(input_element.length-1)]; // Dernier element
		}
		
		var input_element_tag = input_element_p.nodeName.toLowerCase(); // 'textarea' | 'input' | ...
		var input_type_area = '';
		
		switch(input_element_tag) {
            case 'textarea': input_type_area = 'text'; // Astuce > fait passer textarea pour input "text"
            case 'input':
              var input_type = (input_type_area ? input_type_area : input_element_p.getAttribute('type'));
              input_type = input_type.toLowerCase();

              switch(input_type) {
                  case 'password':
						if (input_element.value == '') break;
						else if (minchar && input_element.value.length < minchar) {
							 alerte = mincharAlerte;
							 break;
						}
						else if (defautValue && defautValue == input_element.value) {
							myForm[nom_champ].value = '';
							break;
						}
						else matched = true;

						if (matched && myForm[nom_champ+'_2']) {
                            if (myForm[nom_champ+'_2'].value != myForm[nom_champ].value) alerte_sup = 'second_m2p_erreur';
                            else alerte_sup = 'second_m2p_ok';
                        }

                  case 'hidden':
                  case 'text':
						if ($F(input_element) == '') break;
						else if (defautValue && defautValue == input_element.value) {
							myForm[nom_champ].value = '';
							break;
						}
						else if (minchar && input_element.value.length < minchar) {
							 alerte = mincharAlerte;
							 break;
						}
                        switch(type) {
                            case 'tel_fr' :      reg_expression = /^0([1-6]|8|9)([. -\/]?)\d{2}(\2\d{2}) {3}$/; break;
                            case 'tel' :         reg_expression = /^[0-9]{10}$/; break;
                            case 'chiffre' :     reg_expression = /^[0-9]{1,}$/; break;
                            case 'mel' :         reg_expression = /^[A-Za-z0-9._-]+@[A-Za-z0-9.\-]{2,}[.][A-Za-z]{2,4}$/; break;
                            case 'url' :         reg_expression = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/; break;
                            case 'date' : break;
                            default : reg_expression = /^(.+)[\r\n]|$/; break; // Valide text non vide
                        }
						
                        if (type == 'date' && checkDate(input_element.value)) matched = true; // Valide jj/mm/aa(aa)
                        else if (input_element.value.match(reg_expression)) matched = true;
                        else if (type == 'mel') alerte = alerte; //'L\'email ne semble pas correcte';
                        // Si type "mel" check if "mel_2" exist et si identique
                        if (matched && type == 'mel' && myForm[nom_champ+'_2']) {
                            if (myForm[nom_champ+'_2'].value != myForm[nom_champ].value) alerte_sup = 'second_email_erreur';
                            else alerte_sup = 'second_email_ok';
                        }
                  break;
                  case 'file':
                        if (input_element.value != '') {
                            var fichier = baseName(input_element.value);
                            if (type) {
                                exts = type.split('|');
                                for (i=0; i<exts.length; i++) if (fichier.match(exts[i])) matched = true;
                            }
                            else if (fichier) matched = true;
                        }
                  break;
                  case 'checkbox':
                  case 'radio':
                     input_element_tag = 'radio';
                     if (input_element.length) {
                         for (var j=0; j<input_element.length; j++) { if (input_element[j].checked) matched = true; }
                     }
                     else if (input_element.checked) matched = true;
                  break;
              }
          break;
          case 'select':     
              if (input_element.options[input_element.selectedIndex].value != '') matched = true;
          break;
        }
        if (!matched) {
            oneError = true;
            if (mep == 'message') {
				// todo : setError(nom_champ)
				alerte = '<img src="images/form_error.png" class="form_error_ico" title="'+alerte+'" alt="'+alerte+'" />';
                if ($('div_error_'+nom_champ)) {
                    $('div_error_'+nom_champ).update(alerte);
                    $('div_error_'+nom_champ).addClassName('divError');
                    $('div_error_'+nom_champ).show();
                }
                else {
                    if ($(nom_champ+'_erreur')) $(nom_champ+'_erreur').show();
                    else new Insertion.After(input_element_d,'<div class="'+divErrorCssCustom+'" id="'+nom_champ+'_erreur">'+alerte+'</div>');
                }
            }
            else errorMessage += alerte+'<br />';

            if (input_type != 'checkbox' && input_type != 'radio') {
                Element.removeClassName(input_element_p, inputCss[input_element_tag]);
                Element.addClassName(input_element_p, inputCss[input_element_tag]);
            }
            if (!focusinput) { // Focus la première erreur
                focusinput = true;
                input_element_p.focus();
            }
        }
        else {
            if (input_type != 'checkbox' && input_type != 'radio') Element.removeClassName(input_element_p, inputCss[input_element_tag]);
            if ($('div_error_'+nom_champ)) $('div_error_'+nom_champ).hide();
            else if ($(nom_champ+'_erreur')) $(nom_champ+'_erreur').hide();
        }
		
        if (alerte_sup != '') {
            switch(alerte_sup) {
                case 'second_email_erreur' :
                    oneError = true;
                    alerte = 'Les deux e-mails  ne sont pas identiques';
                    nom_champ = nom_champ+'_2'; // Envois alert input "mel" sur "mel_2"
                    // todo : setError(nom_champ)
                    if (mep == 'message') {
							alerte = '<img src="images/form_error.png" class="form_error_ico" title="'+alerte+'" alt="'+alerte+'" />';
                        if ($(nom_champ+'_erreur')) $(nom_champ+'_erreur').show();
                        else new Insertion.After($(nom_champ),'<div class="'+divErrorCssCustom+'" id="'+nom_champ+'_erreur">'+alerte+'</div>');
                        Element.removeClassName($(nom_champ), inputCss[input_element_tag]);
                        Element.addClassName($(nom_champ), inputCss[input_element_tag]);
                    }
                    else errorMessage += alerte+'<br />';
                   
                   if (!focusinput) { // Focus la première erreur
                        focusinput = true;
                        $(nom_champ).focus();
                    }
                break;
                case 'second_email_ok' :
                    nom_champ = nom_champ+'_2'; // Envois alert input "mel" sur "mel_2"
                    if (mep == 'message') {
                        Element.removeClassName($(nom_champ), inputCss[input_element_tag])
                        if ($('div_error_'+nom_champ)) $('div_error_'+nom_champ).hide();
                        else if ($(nom_champ+'_erreur')) $(nom_champ+'_erreur').hide();
                    }
                break;
				
				case 'second_m2p_erreur' :
					oneError = true;
                    alerte = 'Les deux mots de passe ne sont pas identiques';
					nom_champ = nom_champ+'_2'; // Envois alert input "mel" sur "mel_2"
                    if (mep == 'message') {
							alerte = '<img src="images/form_error.png" class="form_error_ico" title="'+alerte+'" alt="'+alerte+'" />';
                        if ($(nom_champ+'_erreur')) $(nom_champ+'_erreur').show();
                        else new Insertion.After($(nom_champ),'<div class="'+divErrorCssCustom+'" id="'+nom_champ+'_erreur">'+alerte+'</div>');
                        Element.removeClassName($(nom_champ), inputCss[input_element_tag]);
                        Element.addClassName($(nom_champ), inputCss[input_element_tag]);
                    }
                    else errorMessage += alerte+'<br />';
                   
                   if (!focusinput) { // Focus la première erreur
                        focusinput = true;
                        $(nom_champ).focus();
                    }
                break;
                case 'second_m2p_ok' :
                    nom_champ = nom_champ+'_2'; // Envois alert input "mel" sur "mel_2"
                    if (mep == 'message') {
                        Element.removeClassName($(nom_champ), inputCss[input_element_tag])
                        if ($('div_error_'+nom_champ)) $('div_error_'+nom_champ).hide();
                        else if ($(nom_champ+'_erreur')) $(nom_champ+'_erreur').hide();
                    }
                break;
            }
        }
    }

    // SUBMIT
    if (!oneError) {
        if (action == 'submit') {
			if (Element.hasAttribute(myForm, 'onsubmit')) $(myForm).setAttribute('onsubmit', '');
			myForm.submit();
		}
		else if (action == 'getUrl') {
			var responseElement = arr_param['responseElement'];
			var responseMessage = arr_param['responseMessage'];
			var updateElement = arr_param['updateElement'];
			// Submit the form by Ajax
			var callAction = Form.requestAction(document.forms[frm_name], {
				evalScripts: true,
				onSuccess: function(transport) {
					if (isId(updateElement)) $(updateElement).update(transport.responseText);
					else return alert('Verifiez votre element a updater dans formVerif()');
				}
			});
		}
      	else if (isSet(action) && !exec(action)) return alert('Probleme avec votre action dans formVerif()');
		else return alert('Ajoutez une action dans le JS');
		if (afterFinish) exec(afterFinish); // !!!
		return true;
    }
    else {
        if (autoScroll) new Effect.ScrollTo(myForm, {offset: -16});
        if (autoScroll && mep != 'message') setTimeout("printInfo('"+strRep(errorMessage,'\'','\\\'')+"');", 1000); // Wait scroll
        else if (mep != 'message') printInfo(strRep(errorMessage,'\'','\\\''));
		return false;
    }
};
