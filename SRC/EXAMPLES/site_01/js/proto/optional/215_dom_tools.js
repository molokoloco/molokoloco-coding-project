///////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////// DOM TOOLS  ///////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////
/*
	FONCTIONS :
		- resetMenu(evt)
		- hideIfFar(evt)
		- makeMenu(evt)
		- accordeonEffect(id, all_search)		==> Fonction pour créer un effet d'accordéon au clic sur un lien
	
	CLASSES :
		- Lister(lst_contenuId, lst_btaddId, lst_btremoveId, lst_element, lst_struct_template, lst_position, lst_autoinsert, lst_opt_template, lst_limite)
		  ==> Classe qui permet que créer un template qu'on peut dupliquer ou supprimer (ex: liste de produits)

 
*/

// ------------------------- REQUIRE :) ---------------------------------- //
if (typeof Element == 'undefined') throw('215_dom_tools.js requires prototype.js library');
if (typeof Effect == 'undefined') throw("215_dom_tools.js requires 110_effects.js");

// ------------------------- CLASSE LISTER ---------------------------------- //
/*
Exemple d'utilisation : 

	Event.observe(window, 'load', lst_init, false);
	
	var lst_struct_template = '<div class="element" id="element#{cpt}" style="display:none;">';
	lst_struct_template += '<p><input type="text" name="produit[]" id="produit#{cpt}" class="produit" value="Ligne #{cpt}" /></p>';
	lst_struct_template += '<p><input type="text" name="categorie[]" id="categorie#{cpt}" class="categorie" /></p>';
	lst_struct_template += '<p><input type="text" name="quantite[]" id="quantite#{cpt}" class="quantite" /></p>';
	lst_struct_template += '<p><a href="javascript:void(0);" class="bt_remove" id="bt_remove#{cpt}">Supprimer un produit</a></p>';
	lst_struct_template += '</div>';
	
	function lst_init(){
	var lst_ligne = new Lister('contenu', 'bt_add', 'bt_remove', 'element', lst_struct_template, 'bottom', true, {}, 5,'Vous ne pouvez pas insérer plus de 5 lignes');
	}
*/

var Lister = Class.create();
Lister.prototype =
{
	initialize: function(lst_contenuId, lst_btaddId, lst_btremoveId, lst_element, lst_struct_template, lst_position, lst_autoinsert, lst_opt_template, lst_limite, lst_alerte) {
		// Récupération des variables
		this.lst_contenuId  = lst_contenuId; 				// Id du conteneur des lignes insérées
		this.lst_btaddId  = lst_btaddId;					// Id du bouton ajouter
		this.lst_btremoveId  = lst_btremoveId;				// Prefixe du bouton supprimer
		this.lst_element  = lst_element;					// Prefixe du conteneur de la ligne		
		this.lst_struct_template = lst_struct_template;		// Structure du template
		this.lst_position  = lst_position.toLowerCase();	// Position de l'insertion de la ligne (after, before, bottom, top)
		this.lst_autoinsert = lst_autoinsert;				// Insertion automatique de la première ligne
		this.lst_opt_template = lst_opt_template;			// Options du template entre accolades	
		this.lst_limite = lst_limite;						// Nombre de ligne maximum
		this.lst_alerte = lst_alerte;						// Message d'alerte
		
		// Initialisation des compteurs
		this.lst_opt_template.cpt = 0;
		this.lst_opt_template.nb = 0;
		
		// Création du template
		this.lst_new_template = new Template(this.lst_struct_template);
		
		// Ajout de la ligne
		if(this.lst_autoinsert) this.inserer(this);
		Event.observe(this.lst_btaddId, 'click', this.inserer.bindAsEventListener(this));		
	},
	
	inserer: function(e) {
		if(this.lst_limite=='' || this.lst_opt_template.nb < this.lst_limite) {
			// Mise à jour du template
			this.lst_template = this.lst_new_template.evaluate(this.lst_opt_template);
			
			// Traitement de l'insertion
			switch(this.lst_position) {
				case 'after' : 		new Insertion.After(this.lst_contenuId, this.lst_template);		break;
				case 'before' : 	new Insertion.Before(this.lst_contenuId, this.lst_template);	break;
				case 'bottom' : 	new Insertion.Bottom(this.lst_contenuId, this.lst_template);	break;
				case 'top' :		new Insertion.Top(this.lst_contenuId, this.lst_template);		break;
			}
			Effect.Appear(this.lst_element + this.lst_opt_template.cpt, {duration:0.3});
			
			// Mise en place de l'évènement de suppression
			Event.observe(this.lst_btremoveId + this.lst_opt_template.cpt, 'click', this.effacer.bindAsEventListener(this, this.lst_element, this.lst_opt_template.cpt));
			
			// Mise à jour des compteurs
			this.lst_opt_template.cpt ++;
			this.lst_opt_template.nb ++;
		} else {
			printInfo(this.lst_alerte);
		}
	},
	
	effacer: function(e, lst_element, cpt) {
		// Disparition de la ligne
		Effect.Fade(lst_element + cpt, {
			duration:0.3,
			afterFinish:function(e) {
				Element.remove(lst_element+cpt);
				}
			}
		);
		
		// Mise à jour du compteur
		this.lst_opt_template.nb --;
	}
};