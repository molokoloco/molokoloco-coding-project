/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

					ATTENTION NE PASSE PAS DANS LE MOTEUR DE COMPRESSION  :-/

/////////////////////////////////////////////////////////////////////////////////////////////////////// */

var cleanString = function(string) { // common office char to html char + debug php global GPC with char �
	if (!isSet(string)) return '';
	string = string.replace(/&ldquo;/gi, "'"); string = string.replace(/&rdquo;/gi, "'"); string = string.replace(/&acute;/gi, "'"); string = string.replace(/&lsquo;/gi, "'"); string = string.replace(/&rsquo;/gi, "'"); string = string.replace(/&hellip;/gi, '...'); string = string.replace(/�/gi, "AE"); string = string.replace(/�/gi, "ae"); string = string.replace(/�/gi, 'OE'); string = string.replace(/�/gi, 'oe'); string = string.replace(/�/gi, '"'); string = string.replace(/�/gi, '"'); string = string.replace(/�/gi, '"'); string = string.replace(/�/gi, '"'); string = string.replace(/�/gi, 'Euros'); string = string.replace(/�/gi, "'"); string = string.replace(/�/gi, "'"); string = string.replace(/�/gi, "'"); string = string.replace(/�/gi, "'"); string = string.replace(/�/gi, '...'); string = string.replace(/�/gi, ' ');
	return string;
};