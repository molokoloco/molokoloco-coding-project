/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/

var compressed = false; // Cf. http://alex.dojotoolkit.org/shrinksafe/
var toLoad = '002_flashObject,100_prototype,110_effects,111_effects.extend,112_dragdrop,200_tools,201_client,210_functions,211_form,001_admin,214_modal-dialogue,216_tabs,220_lightbox,240_cms,999_regexp';

// Default : '002_flashObject,100_prototype,110_effects,111_effects.extend,200_tools,201_client,210_functions,211_form,999_regexp';
// Light : '100_prototype,200_tools';
// Tous : '002_flashObject,003_cookie,004_fade,005_color,100_prototype,102_slider,104_sound,110_effects,111_effects.extend,112_dragdrop,113_controls,200_tools,201_client,210_functions,211_form,212_diaporama,213_horloge,220_lightbox,230_tab-view,999_regexp';

var preloader = {
    require: function(libraryName) {
        document.write('<script type="text/javascript" src="'+libraryName+'"></script>');
    },
    init: function() {							
		if (compressed) var path = '../../js/proto/optional.compressed/';
		else var path = '../../js/proto/optional/';
		var reg = new RegExp("[,;]+", "g");
		var files = toLoad.split(reg);
		for (var i=0; i<files.length; i++) this.require(path+files[i]+(compressed?'.compressed':'')+'.js');
    }
};
preloader.init();