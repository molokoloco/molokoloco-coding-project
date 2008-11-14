/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
///////////////////////////////////////////////////////////////////////////////////////////////////////
001_admin.js, 002_flashObject, 003_cookie, 004_fade, 005_color, 006_mousewheel, 100_prototype,
102_slider, 104_sound, 110_effects, 111_effects.extend, 112_dragdrop, 113_controls, 114_prototip, 150_scroller,
200_tools, 201_client, 210_functions, 211_form, 212_diaporama, 213_horloge, 214_modal-dialogue,
215_menu,215_accordeon, 220_lightbox, 230_tab-view, 300_specifique,999_regexp
////////////////////////////////////////////////////////////////////////////////////////////////////*/
var compressed = false; // Cf. http://alex.dojotoolkit.org/shrinksafe/
var toLoad = '002_flashObject,100_prototype,110_effects,111_effects.extend,200_tools,201_client,210_functions,211_form,214_modal-dialogue,114_prototip,212_diaporama,215_menu,215_accordeon,300_specifique,999_regexp';
var Scriptaculous = {Version: '1.8.1'};
var preloader = {
    require: function(libraryName) {
        document.write('<script type="text/javascript" src="'+libraryName+'" onerror="alert(\'Error: failed to load \'+this.src)"></script>');
    }, 
    init: function() {
		if (compressed) this.require('./js/proto/functions.compressed.js');
		else {
			var files = toLoad.split(',');
			for (var i=0; i<files.length; i++) this.require('./js/proto/optional/'+files[i]+'.js');
		}
    }
};
preloader.init();
