<?
if ( !defined('MLKLC') ) die('Lucky Duck');

/* ---------- MEDIA -------------------------//
$m =& new FILE();
if ($m->isMedia($file)) $m->media();

// ---------- POP-UP IMAGE ------------------------------ //
$m =& new FILE();
if ($m->isMedia('medias/actualite/medium/'.$A->V[$i]['visuel'])) {
	$m->css = 'verdana11 gris2';
	$m->popImage();
}

// ---------- LIEN --------------------------------//	
$m =& new FILE();
if ($m->isUrl($A->V[$i]['lien'])) {
	$m->target = $A->V[$i]['cible'];
	$m->texte = $A->V[$i]['titre_lien'];
	$m->lien();
}

// ---------- IMAGE + CUSTOM LIEN --------------------------------//	
$m =& new FILE();
if ($m->isMedia('imgs/partners/'.$row['cli_pic'])) {
	$m->texte =  $m->image(FALSE);
	$m->isUrl('index.php?RID='.$this->app->crid.'&reference_id='.$row['cli_idt']);
	$m->target = 'none';
	$tpl_content_row->addValue('cli_pic', $m->lien(FALSE));
}

// ---------- INFO FICHIER -------------------------//
$m =& new FILE();
if ($m->isMedia('medias/actualite/'.$A->V[$i]['document'])) {
	$m->texte = $A->V[$i]['titre_doc'];
	$m->css = 'verdana11 gris2';
	$m->info();
}

// ---------- UPLOAD FILE -------------------------//
if ($blog_id > 0) $visuel = fetchValues('visuel', 'mod_membres_blogs', 'id', $blog_id);
else $visuel = '';
if (!empty($_FILES['blog_visuel']['name'])) {
	$m =& new FILE();
	$m->uploadFile('blog_visuel', $wwwRoot.'medias/membres/', array('mini'=>'120x80', 'medium'=>'300x170', 'grand'=>'600x350', 'pop'=>'800x600'), 'IMAGE');
if ($m->error) {
		$info .= $m->error;
	break;
	}
	else {
		$d =& new FILE();
		$d->delFiles($visuel, $wwwRoot.'medias/membres/');
		$visuel = $m->name;
	}
}

// ---------- THUMBALIZE -------------------------//
$m =& new FILE();
$m->makeThumbs('./medias/galeries/070220152107_arbre.jpg','./medias/galeries/',$R2['sizeimg']);
if ($m->error) {
	return 'Unable to make Mini / Medium / Grand';
}

// ---------- SIMPLE THUMB -------------------------//
$avatar_new = makeName($avatar_file['name']);
$m =& new FILE();
$m->makeThumb($avatar_file['tmp_name'], 'forum/images/avatars/'.$avatar_new, 74, 74, '', 'XY'); // Cropped
if ($m->error) $action_errors .= "<br />- ".$m->error;
else {
	$d =& new FILE(); // Del EX file
	$d->delFiles($avatar,'forum/images/avatars/');
	$avatar = $avatar_new;
}

// ----------DEL FILES -------------------------//
$m =& new FILE();
$m->delFiles('070220152107_arbre.jpg','./medias/galeries/');

//----------- FLASH OBJ -----------------------//
$m =& new FILE();
$m->_path = $video_url;
$m->id = generateId();
$m->width = '300';
$m->height = '270';
$m->version = '8.0.0';
$m->flashvars = $video_params;
$m->flashparam = array('wmode'=>'transparent','allowFullScreen'=>'true');
$m->flashObj();


// ---------- CUSTOM JS POP-UP -------------------------//
$m =& new FILE();
if ($m->isMedia($R1['rep'].$mini.$M->V[0]['media'])) {
	$m->css = 'divImg';
	$m->texte = $m->image(FALSE);
	$m->title = '['.$m->width.'x'.$m->height.' pixels] Click : Pop-Up / Right-Click : Enregistrer-sous';
	$m->onClick = 'return printPopUrl(\''.$R1['rep'].$M->V[0]['media'].'\',\'img\');';
	$m->css = '';
	$m->lien();
	echo '<br />'.affCleanName($M->V[0]['media']);
}
else echo '<img src="images/nav/nopreview.png" width="120" height="80" class="divImg" />';

= <a href="medias/galeries/mini/070220152107_arbre.jpg" title="[120x80 pixels] Click : Pop-Up / Right-Click : Enregistrer-sous" onclick="return printPopUrl('medias/galeries/','img');"><img src="medias/galeries/mini/070220152107_arbre.jpg" id="img_Arbre" name="img_Arbre" alt="Arbre" class="divImg" border="0" height="80" width="120"></a><br />Arbre.jpg

//-------------------------------------------------- */
	
class FILE {

	/* PUBLIC // User entry */
	var $id						= '';
	var $css					= '';
	var $style					= '';
	var $onClick				= '';
	var $onMouseOver			= '';
	var $onMouseOut				= '';
	var $attribute				= ''; 		// Custom attribute(s) (Var/Array)
	var $texte					= '';		// Texte (d'un lien) � afficher
	var $lien					= '';		// Lien()
	var $title					= '';
	var $target					= '';		// 'toto' | '' | 'none' >>>> par defaut : '_blank'
	var $align					= '';		// Image adjust...
	
	/* PRIVATE // Php response */
	var $_html					= '';		// HTML 
	var $_script				= '';		// Script embed plug
	var $_path					= ''; 		// isMedia($path) // Ex. $path = "medias/programmes/184837_stroumf.jpg" ...
	
	var $rep					= ''; 		// ./medias/programmes/
	var $name					= '';		// 184837_stroumf.jpg || La buseette.jpg
	var $cname					= '';		// Stroumf || la_buseette.jpg
	var $size					= '0 Ko';	// 42 Ko
	var $ext					= '';		// jpg
	var $type					= '';		// image/jpg
	var $width					= 0;		// Largeur pixels
	var $height					= 0;		// Hauteur pixels
	var $isImage				= FALSE;	// Image
	var $isFile					= FALSE;	// Fichier
	var $isDir					= FALSE;	// Directory
	var $error					= '';		// Message d'erreur
	
	
	/* CONSTRUCTEUR ------------------------------------------- */
	function FILE() {
		global $debug;			
		$this->debug 			= $debug; 	// Print error ?
	}

	/* BUILD HTML ATTRIBUTES ------------------------------------------- */
	function _setAttributes($attributeName,$attributeValue='') {
		if ($attributeName == 'css') $attributeNameN = 'class'; // Cas particulier :-/
		else $attributeNameN = $attributeName;

		if (empty($attributeValue) && isset($this->$attributeName)) $this->_html .= ' '.$attributeNameN.'="'.$this->$attributeName.'"';
		else $this->_html .= ' '.$attributeNameN.'="'.$attributeValue.'"';
	}
	
	/* OVERWRITE DEFAULT ATTRIBUTES WITH ARRAY OR STRING ------------------------------------------- */
	function _getCustomAttributes() {
		if ($this->noAttributes) return FALSE;
		if (is_array($this->attributes) && count($this->attributes)) {
			foreach ($this->attributes as $key=>$val) {
				$this->_html .= ' '.$key.'="'.$val.'"';
				if (isset($this->$key)) $this->$key = ''; // Overwrite default attribute
			}
		}
		elseif (!empty($this->attributes)) $this->_html .= ' '.$this->attributes;
		else return FALSE;
	}
	
	/* GENERATE UNIQUE ID ------------------------------------------- */
	function _getId() {
		if (!empty($this->cname)) {
			$prefix = substr($this->cname, 0, 20);
			if (is_numeric(substr($prefix, 0, 1))) $prefix = 'id'.$prefix;
			else $prefix = 'id';
		}
		else $prefix = 'id';
		return generateId($prefix.'_');
	}
	
	/* PRINT HTML FINAL ------------------------------------------- */
	function _printHtml($echo=TRUE) {
		if (!$echo) return $this->_html;
		else echo $this->_html;
	}
	
	/* GET INFOS SUR $path ------------------------------------------- */
	function isMedia($path) {
		global $extensionsImg,$extensionsFiles;
		$this->_path = $path;
		
		if (empty($this->_path)) {
			$this->error .= '[isMedia()] Chemin vide';
			return FALSE;
		}
		elseif(substr($this->_path,0,7) != 'http://' && ( !is_file($this->_path) ) ) { // || !@filesize($this->_path) 
			$this->error .= '[isMedia()] Fichier introuvable : '.$this->_path;
			return FALSE;
		}
		$ARR_pathinfo = pathinfo($this->_path); // Get Infos Path
		$this->rep = $ARR_pathinfo['dirname'].'/';
		$this->ext = $ARR_pathinfo['extension'];
		$this->name = $ARR_pathinfo['basename'];
		$this->cname =  affCleanName($this->name, 0); // Titre "Clean" sans ext. par defaut
		$this->size = cleanKo(@filesize($this->_path)); // Erreur si http://
		//if (!in_array($this->ext,$extensionsFiles)) { // UPLOAD CHECK TYPE
			//$this->error .= '[isMedia()] Extension non autoris�e : '.$this->ext;
			//return FALSE;
		//}
		$this->isFile = TRUE;
		if (in_array($this->ext, $extensionsImg)) { // Image ?
			$this->isImage = TRUE;
			list($this->width, $this->height, $this->type) = @getimagesize($this->_path);
		}
		return TRUE;
	}
	
	/* IS URL ------------------------------------------- */
	function isUrl($path) {
	
		$this->_path = $path;
		
		if ($fp) fclose($fp);

		if ($this->_path == '') {
			$this->error .= 'Le chemin sp&eacute;cifi&eacute; est vide';
			return FALSE;
		}
		elseif (strpos($this->_path,'javascript:') !== false || strpos($this->_path,'mailto:') !== false) { // javascript : RAS...
			return TRUE;
		}
		elseif (strpos($this->_path,'?') > 0) {
			list($this->_path, $query) = explode('?', $this->_path);
		}
		
		// Must be http
		if ($this->http) { 
			if (substr($this->_path,0,7) != 'http://') {
				$this->error .= 'Adresse http:// obligatoire : '.$this->_path;
				return FALSE;
			}
		}
		
		// if http Must be valide...
		if (substr($this->_path,0,3) == 'htt') { // EXTERNE ?
			eregi("^([a-z]*)(://([^/]+))?/?(.*)$", $this->_path, $regs);
			$host = $regs[3];
			if (!$host) return FALSE;
			
			@ini_set('default_socket_timeout', 3);
			$fp = @fsockopen($host,80);
			if (!$fp) {
				$this->error .= 'Adresse http:// inatteignable : '.$this->_path;
				return FALSE;
			}
		}
		elseif (strpos($this->_path,'.html') === false && !@is_file($this->_path) && !@is_dir($this->_path)) { // LOCAL ? Becareful for rewriting.. no scan for html
			$this->error .= 'Chemin ou ficher local introuvable : '.$this->_path;
			return FALSE;
		}
		
		$this->isDir = TRUE;
		
		if (!empty($query)) $this->_path = $this->_path.'?'.$query;
		
		$this->rep = ( @is_file($this->_path) ? dirname($this->_path) : $this->_path );
		
		return TRUE;
	}

	/* CREATION D'UN OBJECT MEDIA ------------------------------------------- */
	function media($echo=TRUE) {
		switch(getFileType($this->ext)) {
			case 'document' : 	return $this->info($echo); break;
			case 'image' : 		return $this->image($echo); break;
			case 'flash' : 		return $this->flashObj($echo); break;
			case 'video' : 		die('Il faut faire la class : class_video.php'); //return $this->video($echo); break;
			case 'musique' : 	die('Il faut faire la class : class_video.php'); //return $this->musique($echo); break;
			default : 
				$this->error .= 'Ce type de fichier n\'est pas reconnu : '.$this->_path;
				return FALSE;
			break;
		}
	}

	/* CREATION D'UN LIEN DYNAMIQUE ------------------------------------------- */
	function lien($echo=TRUE) {

		if ($this->lien == 'no') $this->lien = 'javascript:void(0);';
		elseif (empty($this->lien) && !empty($this->_path)) $this->lien = $this->_path;
		elseif (!$this->isUrl($this->lien)) {
			$this->error .= 'Ce lien semble ne pas fonctionner : '.$this->lien;
			return FALSE;
		}
		
		$this->_html = '<a';
		
		if (!isset($this->attributes['onfocus'])) $this->attributes['onfocus'] = 'this.blur()';
		
		$this->_getCustomAttributes();
		
		$this->_html .= ' href="'.$this->lien.'"';
		
		if (!$this->noTarget && $this->target != 'none') {	
			if ($this->target == '' && $this->onClick == '') $this->target = '_blank'; // _blank par defaut
			$this->_setAttributes('target');
		}

		if ($this->id != '' && !$this->noId) 		$this->_setAttributes('id');
		if ($this->onClick != '' && !$this->noClick) 	$this->_setAttributes('onClick');
		if ($this->title != '' && !$this->noTitle) 		$this->_setAttributes('title');
		if ($this->css != '' && !$this->noStyle) 		$this->_setAttributes('css');
		if ($this->style != '' && !$this->noStyle) 		$this->_setAttributes('style');
		
		if (empty($this->texte)) 						$this->texte = $this->lien; // Lien s'affiche en titre par defaut
		
		$this->_html .= '>'.$this->texte.'</a>';

		return $this->_printHtml($echo);
	}
	
	/* CREATION D'UNE IMAGE DYNAMIQUE ------------------------------------------- */
	function image($echo=TRUE) {
	
		if (!$this->isImage) {
			$this->error .= 'Ce fichier n\'est pas une image';
			return;
		}
		
		$this->_html = '<img';
		
		$this->_getCustomAttributes();
		
		// Attributes Obligatoires
		if (empty($this->id)) $this->id = $this->_getId();
		if (empty($this->alt)) $this->alt = $this->cname;
		$this->_html .= ' src="'.$this->_path.'" id="'.$this->id.'" name="'.$this->id.'" alt="'.$this->alt.'" border="0"';

		$this->_setAttributes('width');
		$this->_setAttributes('height');
		
		if ($this->onClick != '' && !$this->noClick) 		$this->_setAttributes('onClick');
		if ($this->title != '' && !$this->noTitle) 			$this->_setAttributes('title');
		if ($this->align != '' && !$this->noAlign) 			$this->_setAttributes('align');
		if ($this->css != '' && !$this->noStyle) 				$this->_setAttributes('css');
		if ($this->style != ''&& !$this->noStyle) 			$this->_setAttributes('style');

		$this->_html .= ' />';

		return $this->_printHtml($echo);
	}


	/* CREATION D'UN POP-UP DYNAMIQUE ------------------------------------------- */
	function popImage($echo=TRUE) {

		if (!$this->isImage) {
			$this->error .= 'Le path n\'est pas une image';
			return;
		}

		// ZOOM : Si images dans ./xxx/mini/ ou ./xxx/medium/ cherche dans ./xxx/pop/ ou ./xxx/grand/ ou ./xxx/
		if (empty($this->_pathzoom)) {
			$this->_pathzoom = str_replace(array('mini/', 'medium/', 'grand/'), array(), $this->rep);
			if (@is_file($this->_pathzoom.'pop/'.$this->name)) $this->_pathzoom .= 'pop/'.$this->name;
			elseif (@is_file($this->_pathzoom.'grand/'.$this->name)) $this->_pathzoom .= 'grand/'.$this->name;
			elseif (@is_file($this->_pathzoom.$this->name)) $this->_pathzoom .= $this->name;
			else $this->_pathzoom = $this->_path; // No Zoom ?
		}
		elseif (!@is_file($this->_pathzoom)) {
			$this->error .= 'Le chemin vers le fichier zoom n\'est pas correcte';
			return;
		}

		if (empty($this->title) && !$this->noTitle) $this->title = 'Afficher &quot;'.$this->cname.'&quot;';// ('.$this->width.'x'.$this->height.' pixels, '.$this->size.')';
		
		if (!empty($this->rel)) { // PuT rel attribut on the image without put it on the link
			$this->attributes['rel'] = $this->rel;
			if ($this->texte == 'no') $this->texte = $this->cname;
			else if (empty($this->texte)) $this->texte = $this->image(FALSE); // Get Image
			$this->attributes = array(); // !
		}
		else {
			if ($this->texte == 'no') $this->texte = $this->cname;
			else if (empty($this->texte)) $this->texte = $this->image(FALSE); // Get Image
		}
		// Get LINK
		$this->lien = $this->_pathzoom;
		$this->id .= '_link';
		$this->noStyle = TRUE;
		$this->noTitle = TRUE;
		$this->noTarget = TRUE;

		//$this->onClick = "javascript:myLightWindow.activateWindow({href: '".$this->_pathzoom."', title: 'Waiting', author: 'Jazzmatt', caption: 'Mmmmmm', left: 300});return false;";
		//$this->onClick = 'popImg(\''.$pathzoom.'\',\'Zoom\'); return false;';
		if (empty($this->attributes)) { // Do not forget to include "220_lightbox.js" :)
			// FULL : $this->attributes = array('class'=>'lightwindow', 'onfocus'=>'blur();', 'title'=>'Tata', 'author'=>'Toto', 'caption'=>'Tutu');
			// params="lightwindow_width=640,lightwindow_height=288"
			//$this->attributes = array('class'=>'lightwindow', 'onfocus'=>'blur();', 'title'=>'Tata', 'author'=>'Toto', 'caption'=>'Tutu');
			$this->attributes = array('class'=>'lightwindow', 'onfocus'=>'blur();', 'title'=>$this->title); 
		}
		if (!empty($this->galRel)) $this->attributes['rel'] = $this->galRel.'['.$this->catRel.']';  // PuT rel attribut on the link without put it on the image

		$this->_html = $this->lien(FALSE);
		return $this->_printHtml($echo);
	}
	
	/* CREATION D'UN LIEN VERS UN FICHIER A TELECHARGER DYNAMIQUE ------------------------------------------- */
	function info($echo=TRUE) {

		$this->lien =  $this->_path;

		if ($this->texte == '') $this->texte = $this->cname; //.'.'.$this->ext;
		if ($this->texte != $this->name) $this->title = $this->name;

		$this->_html = $this->lien(FALSE);

		if (!$this->isSimple) {
			if ($this->css != '' && !$this->noStyle) $this->_html .= '<span class="'.$this->css.'">';
			if ($this->style != '' && !$this->noStyle) $this->_html .= '<span style="'.$this->style.'">';
			$this->_html .= ' (.'.$this->ext.', '.$this->size;
			if ($this->isImage) $this->_html .= ', '.$this->width.'x'.$this->height.'px';
			$this->_html .= ')';
			if ($this->css != '' && !$this->noStyle) $this->_html .= '</span>';
			if ($this->style != '' && !$this->noStyle) $this->_html .= '</span>';
		}
		return $this->_printHtml($echo);
	}

	/* CREATION D'UN FLASH DYNAMIQUE ------------------------------------------- */
	function flash($echo=TRUE) {
		global $extensionsFlash;	
		if (!in_array($this->ext,$extensionsFlash)) {
			$this->error .= 'Ce fichier n\'est pas un flash';
			return;
		}
		
		if (empty($this->id)) $this->id = $this->_getId();
		
		$makeSize = FALSE;
		if (empty($this->width) || empty($this->height)) {
			$this->width = 360;
			$this->height = 280;
			$makeSize = TRUE;
		}
		$this->_html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" id="'.$this->id.'" name="'.$this->id.'" width="'.$this->width.'" height="'.$this->height.'"';
		
		if ($this->css != '') $this->_setAttributes('css');
		if ($this->style != '') $this->_setAttributes('style');
		
		$this->_html .= '>';
		
		$this->_html .= '<param name="movie" value="'.$this->_path.'">'.chr(13);
		$this->_html .= '<param name="allowScriptAccess" value="sameDomain" />'.chr(13);
		
		if ($this->flashvars != '') { // $m->flashvars = array('titre'=>'Soubi douf');
			$this->_html .= '<param name="flashvars" value="';
			foreach($this->flashvars as $var=>$varValue) $this->_html .= '&'.$var.'='.htmlentities(unhtmlentities(aff($varValue)));
			$this->_html .= '">'.chr(13);
		}

		if (!empty($this->attributes)&& count($this->attributes))   { // Default attribute(s)
			$this->_html .= '<param name="scale" value="exactfit">'.chr(13);
			$this->_html .= '<param name="quality" value="high">'.chr(13);
			if ($this->transp != 'no') $this->_html .= '<param name="wmode" value="transparent">'.chr(13);
			$this->_html .= '<param name="swliveconnect" value="TRUE">'.chr(13);
			// <param name="bgcolor" value="#000000">';
		}
		
		$this->_html .= '<embed src="'.$this->_path.'" id="emb_'.$this->id.'" name="emb_'.$this->id.'" width="'.$this->width.'" height="'.$this->height.'" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowScriptAccess="sameDomain"';
		
		if ($this->css != '') $this->_setAttributes('css');
		if ($this->style != '') $this->_setAttributes('style');
		
		if ($this->flashvars != '') {
			$this->_html .= ' flashvars="';
			foreach($this->flashvars as $var=>$varValue) $this->_html .= '&'.$var.'='.htmlentities(unhtmlentities(aff($varValue)));
			$this->_html .= '"';
		}

		if (!$this->_getCustomAttributes()) { // Default attribute(s)
			$this->_html .= ' scale="exactfit" quality="high" swliveconnect="TRUE"';
			if ($this->transp != 'no') $this->_html .= ' wmode="transparent"';
		}
		
		$this->_html .= '></embed>'.chr(13);
		if (!$this->simple) {
			$this->_html .= '<noembed>Votre navigateur ne supporte pas les flash...<br /><a href="'.$this->_path.'" target="_blank">'.$this->_path.'</a>
			<div align="center"><br />
			<b>Vous n\'avez pas le lecteur flash,<br />
			voulez-vous le t&eacute;l&eacute;charger ?</b><br />
			<br /><a href="http://www.macromedia.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash&Lang=French&P5_Language=French" target="_blank"><img src="images/nav/no_flash_plugin.png" alt="no_flash_plugin" width="160" height="113" border="0"></a></div></noembed>';
		}
		$this->_html .= '</object>'.chr(13);
		
		if ($makeSize) { // AutoDetect Flash SIZE !
			if(navDetect() == 'gecko') $medId = 'emb_'.$this->id; // Resize sur l'ID de l'embed et non pas de l'oBject..
			$this->_script .= '<script type="text/javascript" language="javascript">
			setTimeout("setSizeFlash(\''.$medId.'\');",1000);
			</script>';
		}
		
		return $this->_printHtml($echo);
	}
	
	function flashObj($echo=TRUE) { // Cas particulier // Ne passe pas par isFile() : variable $this->_path a pr�ciser...
		//global $extensionsFlash;	
		//if (!in_array($this->ext,$extensionsFlash)) {
			//$this->error .= 'Ce fichier n\'est pas un flash';
			//return;
		//}
		if (empty($this->_path)) {
			$this->error .= 'flashObj() : $this->_path est vide';
			return;
		}
		
		if (empty($this->id)) $this->id = $this->_getId();
		
		$makeSize = FALSE;
		if ($this->width < 1 || $this->height < 1) {
			$this->width = 360;
			$this->height = 280;
			$makeSize = TRUE;
		}
		
		if ($this->version < 1) $this->version = 7; // Flash player version
		
		$this->_html = '<div id="'.$this->id.'"';
		if ($this->css != '') $this->_setAttributes('css');
		if ($this->style != '') $this->_setAttributes('style');
		$this->_html .= '>';
		if ($this->texte) $this->_html .= $this->texte;
		else $this->_html .= '<p style="text-align:center;"><br /><br /><br />
		Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a><br />&nbsp;</p>';
		$this->_html .= '</div>';
		
		// V2.0 ---
		$script = "
var flashvars = {};
var params = {".(!isset($this->flashparam['wmode']) ? "wmode: 'transparent'," : "")." allowScriptAccess: 'always'};
var attributes = {id: 'swf_".$this->id."'};
";
		if ($this->flashvars != '' && is_array($this->flashvars))  // $m->flashvars = array('titre'=>'Soubi douf');
			foreach($this->flashvars as $var=>$varValue) $script .=  "flashvars.".$var." = '".squote($varValue)."';".chr(13);

		if ($this->flashparam != '' && is_array($this->flashparam)) // $m->flashvars = array('titre'=>'Soubi douf');
			foreach($this->flashparam as $var=>$varValue) $script .=  "params.".$var." = '".squote($varValue)."';".chr(13);

$script .= "swfobject.embedSWF('".$this->_path."', '".$this->id."', '".$this->width."', '".$this->height."', '".$this->version."', '', flashvars, params, attributes);
";
		// V1.0 ---
		/*$script = "
var so = new SWFObject('".$this->_path."', 'swf_".$this->id."', '".$this->width."', '".$this->height."', '".$this->version."', '');
so.addParam('wmode', 'transparent');
so.addParam('allowScriptAccess', 'sameDomain');".chr(13);
		if ($this->flashvars != '' && is_array($this->flashvars)) { // $m->flashvars = array('titre'=>'Soubi douf');
			foreach($this->flashvars as $var=>$varValue) $script .=  "so.addVariable('".$var."', '".htmlentities(unhtmlentities(aff($varValue)))."');".chr(13);
		}
		if ($this->flashparam != '' && is_array($this->flashparam)) { // $m->flashvars = array('titre'=>'Soubi douf');
			foreach($this->flashparam as $var=>$varValue) $script .=  "so.addParam('".$var."', '".htmlentities(unhtmlentities(aff($varValue)))."');".chr(13);
		}
$script .= "so.write('".$this->id."');
		";*/
		
		$script = js($script,FALSE);
		$this->_html .= $script;
		
		if (!$echo) return $this->_html;
		else {
			echo $this->_html;
			return TRUE;
		}
	}

	/* DELETE FROM MINI / MEDIUM / GRAND ------------------------------------------- */
	function delFiles($file_name,$file_dir) {
		global $mini,$medium,$grand;
		$this->rep = $file_dir;
		$this->name = $file_name;
		if (empty($file_name)) {
			$this->error .= 'delFiles() : Le nom du fichier est vide';
			return;
		}
		if (!@is_dir($this->rep)) {
			$this->error .= 'delFiles() : Le repertoire indiqu&eacute; n\'est pas correcte';
			return;
		}
		if (@is_file($this->rep.$mini.$this->name)) @unlink($this->rep.$mini.$this->name);
		if (@is_file($this->rep.$medium.$this->name)) @unlink($this->rep.$medium.$this->name);
		if (@is_file($this->rep.$grand.$this->name)) @unlink($this->rep.$grand.$this->name);
		if (@is_file($this->rep.'pop/'.$this->name)) @unlink($this->rep.'pop/'.$this->name);
		if (@is_file($this->rep.$this->name)) @unlink($this->rep.$this->name);
	}

	// RETAILLE IMAGE >>>>>>>>> GD GRAPHICS ---------------------------------------------
	// Attention ne doit pas �craser les propri�t�s de la class makeThumbs() (avec un S) !!!!!!!!!!!!!!!!
	function makeThumb($file, $fileDest, $maxWidth, $maxHeight, $quality='', $resize='') { 
		global $convert;
		require_once('class_image_Transform.php'); // LOAD MODULES IMAGE !
		
		if (!is_file($file) || (!empty($fileDest) && !is_dir(dirname($fileDest)))) {
			$this->error .= 'Probl�me avec le fichier ('.$file.') ou son r&eacute;pertoire destination ('.dirname($fileDest).')';
			return;
		}

		// Infos
		if (empty($quality)) {
			global $jpgquality;
			$quality = $jpgquality;
		}

		$ext = getExt($fileDest);
		list($width, $height, $type, $attr) = @getimagesize($file); 
		if ($width < 1 || $height < 1) {
			$this->error .= 'Ce fichier image semble �tre corrompu ! : '.$file;
			return;
		}

		// Auto Detect GD/IM
		if ($this->imageModule == '' && !empty($convert)) $this->imageModule = 'IM';
		elseif ($this->imageModule == '' && @function_exists('imagecreatefromjpeg')) $this->imageModule = 'GD';
		
		// Check witch Module
		if ($this->imageModule == 'IM') {
			if (empty($convert)) { //  || !@is_dir($convert) IMAGE MAGICK
				$this->error .= 'Convert est introuvable (IMAGE MAGICK : '.$convert.') !';
				return FALSE;
			}
			$IMG =& Image_Transform::factory($this->imageModule); // INIT
		}
		elseif ($this->imageModule == 'GD') {
			if (!function_exists('imagecreatefromjpeg')) { // GD GRAPHICS
				$this->error .= 'La fonction "imagecreatefromjpeg" semble introuvable (GD GRAPHICS) !';
				return FALSE;
			}
			$IMG =& Image_Transform::factory($this->imageModule); // INIT
		}
		else { // NO MODULES ?
			$this->error .= 'Aucun module de traitement d\'image ne semblent �tre d&eacute;fini';
			return FALSE;
		}

		$IMG->load($file);
		$IMG->setOption('quality', $quality);

		//if (intval($maxWidth) <= 120 || intval($maxHeight) <= 120) //$IMG->setOption('contrast',''); !! Bad class TODO !

		if ($resize == 'X') { // STRETCH TO X
			$IMG->scaleByX($maxWidth);
		}
		elseif ($resize == 'Y') { // STRETCH TO Y
			$IMG->scaleByY($maxHeight);
		}
		elseif ($resize == 'XY') { // SCALE AND CENTER CROP TO X AND Y
			$srcRatio = $width/$height;
			$destRatio = $maxWidth/$maxHeight; 
			
			if ($destRatio > $srcRatio) $IMG->scaleByX($maxWidth);
			else $IMG->scaleByY($maxHeight);
			
			if ($this->imageModule != 'IM') $IMG->crop($maxWidth, $maxHeight); // TODO !!!!
			else $IMG->cropCenter($maxWidth, $maxHeight);
		}
		elseif (intval($resize) === $resize) { // SCALE POURCENTAGE

			$IMG->scaleByPercentage($resize);
		}
		elseif ($resize == 'WM') { // WATER MARK // TODO
			$maxWidth = $width < $maxWidth ? $width : $maxWidth;
			$maxHeight = $height < $maxHeight ? $height : $maxHeight;
			$taille = $maxWidth.'x'.$maxHeight;
			
			$rep = dirname($fileDest).'/';
			$filetemp1 = $rep.'temp1.png'; // Temp file png to work shadow
			
			$watermark = '../../medias/mark_png24.png'; // To change :)
			
			// Crop it
			$cmd = $convert."convert $file $option -resize $taille -quality 85 -gravity center $filetemp1 ";
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;
			
			// Composite
			$cmd = $convert."composite -compose over -gravity SouthEast $watermark $filetemp1 $fileDest "; // Paste img for margin
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;
		}
		elseif ($resize == 'ROTA') { // ROTA BLUP // ATTENTION SEULEMENT POUR MINI CAR FORCE LES AUTRES EN PNG !!!!!! CF MAKETHUMBS()
		
			if ($this->imageModule == 'GD') die('D&eacute;sol&eacute; le mode POLARO:D n\'est impl&eacute;ment&eacute; que pour IM...');

			$maxWidth = $width < $maxWidth ? $width : $maxWidth;
			$maxHeight = $height < $maxHeight ? $height : $maxHeight;
			
			$srcRatio = $width/$height;
			$destRatio = $maxWidth/$maxHeight; 
			
			$taille = ( $destRatio > $srcRatio ? $maxWidth.'x' : 'x'.$maxHeight );
			$crop = $maxWidth.'x'.$maxHeight;
			
			$rep = dirname($fileDest).'/';
			$filetemp1 = $rep.'temp1.png'; // Temp file png to work shadow
			$filetemp2 = $rep.'temp2.png';
			
			// Crop it
			$cmd = $convert."convert $file $option -resize $taille -quality 85 -gravity center -crop $crop+0+0 $filetemp1 ";
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;
			
			echo('<img src='.$filetemp1 .' />');
			
			// Shadow normale
			$cmd = $convert."convert $filetemp1 -background none ( +clone -shadow 40x6+4+4 ) +swap -background none -mosaic -rotate ".rand(-7,7)." PNG32:$fileDest ";
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;

			echo('<img src='.$fileDest .' />');
			
			//die();
			
			if ($this->debug) die('<img src='.$fileDest .' />');
			
			// Clean
			@unlink($filetemp1);
			@unlink($filetemp2);
			$IMG->free();
			
			return TRUE;
		}
		elseif ($resize == 'POLA') { // POLARO:D
		
			if ($this->imageModule == 'GD') die('D&eacute;sol&eacute; le mode POLARO:D n\'est impl&eacute;ment&eacute; que pour IM...');

			$maxWidth = $width < $maxWidth ? $width : $maxWidth;
			$maxHeight = $height < $maxHeight ? $height : $maxHeight;
			
			$srcRatio = $width/$height;
			$destRatio = $maxWidth/$maxHeight; 
			
			$taille = ( $destRatio > $srcRatio ? $maxWidth.'x' : 'x'.$maxHeight );
			$crop = $maxWidth.'x'.$maxHeight;
			
			$rep = dirname($fileDest).'/';
			$filetemp1 = $rep.'temp1.png'; // Temp file png to work shadow
			$filetemp2 = $rep.'temp2.png';
			
			// Crop it
			$cmd = $convert."convert $file $option -resize $taille -quality 85 -gravity center -crop $crop+0+0 $filetemp1 ";
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;

			// Shadow normale
			$cmd = $convert."convert $filetemp1 -background black ( +clone -shadow 40x6+4+4 ) +swap -background none -mosaic -rotate ".rand(-7,7)." $filetemp1 ";
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;
			
			// Black shadow rotate
			$cmd = $convert."convert $filetemp1 -matte -bordercolor none -border 8 -channel A -virtual-pixel transparent -evaluate multiply .40 +channel -fill black -colorize 100% -repage -8-8! -background rgb(100,100,102) -rotate ".rand(-7,7)." +repage +swap -flatten $filetemp2 ";
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;

			// Composite images
			$cmd = $convert."composite -compose over -gravity center -geometry +".rand(0,10)."+".rand(0,10)." -repage +10+10! -quality ".$quality." $filetemp1 $filetemp2 $fileDest "; // Paste img for margin
			$this->error .= cmd($cmd);
			if ($this->error) return FALSE;
			
			if ($this->debug) die('<img src='.$fileDest .' />');
			
			// Clean
			@unlink($filetemp1);
			@unlink($filetemp2);
			$IMG->free();
			
			return TRUE;
		}
		else { // PAR DEFAUT !!! N'agrandit pas...
			//$maxWidth = $this->width < $maxWidth ? $this->width : $maxWidth;
			//$maxHeight = $this->height < $maxHeight ? $this->height : $maxHeight;	
			$IMG->fit($maxWidth, $maxHeight);
		}
		
		// SAVE OR DISPLAY
		if (!empty($fileDest)) $IMG->save($fileDest,$ext);
		else $IMG->display();
		
		//if ($this->debug) die('<img src='.$fileDest.' />');

		$IMG->free();

		if ($IMG->error) {
			$this->error .= $IMG->error;
			die("Error in FILE::makeThumb() : ".$this->error." !"); // DIE TO HELP DEV <:-)
			return FALSE;
		}
		
		return TRUE;
		
		/*
		
		// Add border : -bordercolor white -border 5 -bordercolor rgb(100,100,100)
		// Add Background : -background rgb(100,100,102) // #646466
		// Resize canvas : -geometry +5+5
		// rotate :  -rotate ".rand(-7,7)."
		// Cmd magique : +repage (Autoresize ?)
		// Shadow : ( +clone -shadow 80x8+4+4 ) +swap -background none 
		
		
		// "This works for me (direct display rather than save to file)"
		convert floor.jpg ( wall.png -modulate 100,100,60 ) -composite ( ( 22.jpg -geometry 155x155+0-160 -gravity center ) ( +clone -background black -shadow 70x10+20+20 ) +swap -background none -mosaic ) -gravity center  -composite miff:- | display -
		
		// RANDOM ROTATE // exec(" convert $file -thumbnail $taille -bordercolor white -border 4 -bordercolor grey60 -border 0.5 -background  none -rotate ".rand(-7,7)." -background black ( +clone -shadow 30x4+4+4 ) +swap -background none -flatten -depth 8 -quality 85 $file_new");

		// exec(" $convert convert {$fileDir}temp.jpg -bordercolor white -border 5 -bordercolor rgb(100,100,100) -background  rgb(100,100,102) -rotate ".rand(-7,7)." -background rgb(100,100,102) ( +clone -shadow 30x4+2+2 ) +swap -background none -flatten -depth 8 -quality 85 $file_new");

		// exec(" $convert convert {$fileDir}temp.jpg  -background  rgb(100,100,102) -rotate ".rand(-7,7)." ( +clone -shadow 80x8+0+0 ) +swap -background none -flatten -depth 8 -quality 85 $file_new");
		
		// exec(" $convert convert {$fileDir}temp.jpg  -background  rgb(100,100,102) -rotate ".rand(-7,7)." ( +clone -shadow 80x8+0+0 ) +swap -background none -flatten -depth 8 -quality 85 $file_new");
		
		// EN COURS // exec(" convert $file -thumbnail $taille -bordercolor white -border 5 -bordercolor rgb(100,100,100) -background  none -rotate ".rand(-7,7)." -background black ( +clone -shadow 30x4+2+2 ) +swap -background none -flatten -depth 8 -quality 85 $file_new");
		
		// REGULAR SHADOW // exec(" $convert convert $filetemp -matte -bordercolor none -border 8 -channel A -virtual-pixel transparent -blur 8x1 -evaluate multiply .40 +channel -fill black -colorize 100% -repage +5+5! -repage -8-8! -background rgb\\(100,100,102\\) -rotate ".rand(-7,7)." +repage +swap -flatten -depth 8 -quality 85 $filetemp2 ");
		
		// SIMPLE THUMBAIL // exec(" convert $file  -thumbnail 160x90  -background black +polaroid $file_new ");	
			

		if ($resize == 'XY') {
			// Calculate Min size to keep all surface before crop
			$srcRatio = $width/$height;
			$destRatio = $maxWidth/$maxHeight; 
			if ($destRatio > $srcRatio) $taille = $maxWidth.'x';
			else $taille = 'x'.$maxHeight;
			$crop = $maxWidth.'x'.$maxHeight;
			exec(" $convert convert $option -resize $taille -quality 85 -gravity center -crop $crop+0+0 $file $file_new ");
		}
		elseif (intval($resize) === $resize) { // Reduce with PCT Normale SIZE
			$inputVal = bornes(floatval($_POST['resize']),0.2,1);
			$maxWidth = $maxWidth*$inputVal;
			$maxHeight = $maxHeight*$inputVal;
			$maxWidth = $width < $maxWidth ? $width : $maxWidth;
			$maxHeight = $height < $maxHeight ? $height : $maxHeight;
			$taille = $maxWidth.'x'.$maxHeight;
			// Convert Jpg for flash
			$file_nom = $file_new_nom.'.jpg';
			$filedir = $path.$file_nom;
			exec(" $convert convert $option -resize $taille -quality 85 $file $file_new ");
		}
		elseif ($resize == 'O') { // OMBRES
			$file_nom = date(ymdHis).'_'.cleanName(substr(preg_replace('|.'.$ext.'|si','',trim($file_nom)),0,20)).'.jpg'; // Force .jpg pour flash
			$file_new = $fileDir.$file_new_nom;
			$size = $width.'x'.$height;
			$marge = intval(($width+$height)/16);
			$l = $width+$marge;
			$h = $height+$marge;
			$canvasSize = $l.'x'.$h;
			exec($convert." convert -size $canvasSize xc:transparent canvas2.png "); // CREATE CANVAS (Cropping BUG)
			exec($composite." -compose over -gravity  Center $file canvas2.png canvas2.png "); // Paste img for margin
			exec($convert." convert canvas2.png -threshold 65535 -resize ".$width."x".($height/2)."! temp_ombre.png "); // IMG to BLACK //
			exec($convert." convert -size $canvasSize xc:white canvas.png "); // CREATE CANVAS (work)
			exec($composite." -compose over -gravity South temp_ombre.png canvas.png canvas.png "); // PASTE BLACK to CANVAS
			exec($convert." convert canvas.png -splice 40x40 -fill white -colorize 85% -gaussian 12x8 -gravity center -crop +0+60 canvas.png "); // SHADOWING CANVAS
			exec($composite." -compose over -gravity Center $file canvas.png canvas.png "); // IMG to CANVAS
			exec($convert." convert $option -resize $taille -quality 85 canvas.png $file_new "); // Resize final
			//echo "<img src='$filedir' />"; die();
		}
		elseif ($resize == 'WM') { // WATER MARK
			$maxWidth = $width < $maxWidth ? $width : $maxWidth;
			$maxHeight = $height < $maxHeight ? $height : $maxHeight;
			$taille = $maxWidth.'x'.$maxHeight;
			exec(" $convert convert $option -resize $taille -quality 85 $file canvas.png ");
			// watermark_dacomex_'.$maxWidth.'_png24.png
			exec($composite." -compose over -gravity SouthEast ../../medias/watermark_dacomex_png24.png canvas.png $file_new "); // IMG to CANVAS
		}
		elseif ($resize == 'THUMB') { // THUMBALIZE
	
			$maxWidth = $width < $maxWidth ? $width : $maxWidth;
			$maxHeight = $height < $maxHeight ? $height : $maxHeight;
			$taille = $maxWidth.'x'.$maxHeight;
			$tailleDouble = ($maxWidth*2).'x'.($maxHeight*2);
	
			// ROUNDED Shadow
			// Resize to thumb
			exec(" $convert convert -size $tailleDouble $file $option -auto-orient -thumbnail $taille $file_new ");
			list($width,$height) = getimagesize($file_new); 
			
			// Chimie...
			exec(" $convert convert $file_new -border 2 -draw \" roundrectangle 1,1 $width,$height 16,16 \" {$fileDir}rounded_corner.mvg ");
			exec(" $convert convert $file_new -border 2 -matte -channel RGBA -threshold -1 -background none -fill none -stroke \"rgba(0,0,0,0.6)\" -strokewidth 1 -draw \"@{$fileDir}rounded_corner.mvg\" {$fileDir}rounded_corner_overlay.png ");
			exec(" $convert convert $file_new -border 2 -matte -channel RGBA -threshold -1 -background none -fill white -stroke black -strokewidth 1 -draw \"@{$fileDir}rounded_corner.mvg\" {$fileDir}rounded_corner_mask.png ");
			exec(" $convert convert $file_new -matte -bordercolor none -border 2 $composite {$fileDir}rounded_corner_mask.png -compose DstIn -composite {$fileDir}rounded_corner_overlay.png -compose Over -composite -depth 8 -quality 99 $file_new ");
			// Shadow
			exec(" $convert convert -page +4+4 $file_new ( +clone -background black -shadow 36x4+4+4 ) +swap -background none -mosaic -depth 8 -quality 85 $file_new ");
		}
		elseif ($resize == 'PHOTOMATON') {
			if ($resize == 'X') $taille = $maxWidth.'x';
			elseif ($resize == 'Y') $taille = 'x'.$maxHeight;
			else { // N'agrandit pas
				$maxWidth = $width < $maxWidth ? $width : $maxWidth;
				$maxHeight = $height < $maxHeight ? $height : $maxHeight;
				$taille = $maxWidth.'x'.$maxHeight;
			}
			if (is_file($file_new)) unlink($file_new);
			exec(" $convert convert $option -resize $taille  $file test.jpg ");
			echo "<img src='test.jpg' />";
			// -despeckle -sharpen -enhance -blur -gaussian  -unsharp -noise  -spread -displace 
			exec(" $convert convert $option -resize $taille $file -colorspace gray -normalize layer1.png "); // Full Gray
			// Copy to make the 2nd layer
			exec(" $convert convert -sharpen layer1.png layer2.png "); // Copy
			//exec(" $composite composite  -blend 0x50  NULL:  white: -matte layer2.png "); // Fade
			// Make the first layer : blur
			exec(" $convert convert layer1.png -gaussian 3x3 -gaussian 2x2 layer1.png "); // Gaussian
			// Composite / mode linear burn / 
			exec(" $composite -compose Color_Dodge layer2.png layer1.png $file_new "); // Hard_Light OU Color_Dodge
			echo "<img src='$file_new' />"; die();
		}
		else {
		
			if ($resize == 'X') $taille = $maxWidth.'x';
			elseif ($resize == 'Y') $taille = 'x'.$maxHeight;
			else { // N'agrandit pas
				$maxWidth = $width < $maxWidth ? $width : $maxWidth;
				$maxHeight = $height < $maxHeight ? $height : $maxHeight;
				$taille = $maxWidth.'x'.$maxHeight;
			}
			exec(" $convert convert $option -resize $taille  $file $file_new ");
		} */
	}
	
	/* COPY AND RESIZE TO MINI / MEDIUM / GRAND ------------------------------------------- */
	function makeThumbs($file, $file_dest, $arrSize) {
		global $jpgquality, $maxUploadSize;
		
		if (!@is_file($file) || !@is_dir($file_dest)) {
			$this->error .= '[makeThumbs()] Probleme avec le fichier ('.$file.') ou sa destination ('.$file_dest.')';
			return;
		}
		if (!is_array($arrSize) || !count($arrSize)) {
			$this->error .= "[makeThumbs()] Il faut pr&eacute;ciser les tailles : array('grand'=>'800x600xXY',...) ";
			return;
		}
		
		$this->_path = $file;
		$this->rep = $file_dest;
		$this->name = @basename($this->_path);
		$this->ext = getExt($this->name);

		// MAKE THUMBS
		$haveTgrand = FALSE;
		foreach($arrSize as $rep=>$size) {
			if ($rep == 'tgrand') { // Racine du rep... : tgrand /// Keep the file
				//$rep = '';
				$this->noUnlink = TRUE; 
			}
			else {
				list($width, $height, $resizeN) = explode('x',$size); // Resize g�n�ral de la data "table"
				if (!empty($resizeN)) $resizeSpe = $resizeN;
				else $resizeSpe = $this->resize; // Resize d'une image en particulier si data // ou appel de la class
				
				if (!is_dir($this->rep.$rep)) {
					$this->error .= '[makeThumbs()] Probl�me avec le repertoire '.$this->rep.$rep;
					return;
				}
				
				if ($resizeSpe == 'ROTA') { // FORCER UN FORMAT... PNG
					$this->name = substr($this->name, 0, -4).'.png';
					$_SESSION[SITE_CONFIG]['filedestSaveName'] = $this->name;
				}
				else if (!empty($_SESSION[SITE_CONFIG]['filedestSaveName'])) { // Si petit est sauv� au format PNG les autres doivent l'etre aussi..
					$this->name = $_SESSION[SITE_CONFIG]['filedestSaveName'];
				}
				$this->makeThumb($this->_path, $this->rep.$rep.'/'.$this->name, $width, $height, $jpgquality, $resizeSpe);
			}
		}
		
		if (!$this->noUnlink && is_file($this->_path)) @unlink($this->_path); // Efface fichier � la racine si tgrand n'est pas pr�sent (garde "mini" "medium" et "grand")

	}
	
	/* UPLOAD D'UN FICHIER ------------------------------------------- */
	function uploadFile($input, $file_dir, $arrSize=NULL, $media='ALL') {

		global $extensionsFiles,$allowedTypesFiles;
		global $extensionsImg,$allowedTypesImg;
		global $jpgquality,$maxUploadSize;
		global $mini,$medium,$grand;
		
		$inputFile = $_FILES[$input];

		switch ($inputFile['error']) {
			case 0 : break; // ok
			case 1 : $this->error .= '[uploadFile()] Le fichier que vous avez s&eacute;lectionn&eacute; est trop volumineux (php.ini)'; break;
			case 2 : $this->error .= '[uploadFile()] Le fichier que vous avez s&eacute;lectionn&eacute; est trop volumineux (Max File Size)'; break;
			case 3 : $this->error .= '[uploadFile()] Erreur lors du chargement FTP : Fichier partiellement mis en ligne'; break;
			case 4 : $this->error .= '[uploadFile()] Aucun fichier de s&eacute;lectionn&eacute;...'; break;
			default : $this->error .= '[uploadFile()] Probl�me inconnu lors de la mise en ligne du fichier'; break;
		}
		
		if ($this->error != '') {
			@unlink($this->_path);
			return;
		}

		$this->_path = $inputFile['tmp_name'];
		if (!is_file($this->_path)) {
			$this->error .= '[uploadFile()] Aucun fichier pr&eacute;sent';
			return;
		}

		if ($this->name) $forceName = $this->name; // Forcer a renommer selon un nom
		$this->name = $inputFile['name'];
		$this->titre = affCleanName($this->name,'0');
		$this->type = $inputFile['type'];
		$this->ext = getExt($this->name);
		$this->size = @filesize($this->_path);
		$this->rep = $file_dir;
		$this->isImage = FALSE;
		
		if ($forceName) $this->cname = $forceName.'.'.$this->ext; // Forcer a renommer selon un nom
		else $this->cname = makeName($this->name);
		
		$newPath = $this->rep.$this->cname;
		
		### db($this->type);
		
		if ($media == 'IMAGE' && (!in_array($this->ext,$extensionsImg) || !in_array($this->type,$allowedTypesImg))) {
			@unlink($this->_path);
			$this->error .= '[uploadFile()] Extension image non autoris&eacute;e : '.$this->name.' -> Extension autoris&eacute;es : '.implode(',',$extensionsImg);
			return;
		}
		elseif (!in_array($this->ext,$extensionsFiles) || !in_array($this->type,$allowedTypesFiles)) { // $media == 'ALL'
			@unlink($this->_path);
			$this->error .= '[uploadFile()] Extension fichier non autoris&eacute;e : '.$this->name.' -> Extension autoris&eacute;es : '.implode(',',$extensionsFiles);
			return;
		}
		
		if ($this->size > $maxUploadSize) { // MAX SIZE
			@unlink($this->_path);
			$this->error .= '[uploadFile()] Fichier de '.cleanKo($maxUploadSize).' Maximum ['.$this->name.' : '.cleanKo($this->size).']';
			return;
		}

		if (!@move_uploaded_file($this->_path,$newPath)) { // DEPLACE FILE TO DIR
			@unlink($this->_path);
			$this->error .= '[uploadFile()] Probl�me lors du placement du fichier : '.$this->_path.' &gt; '.$newPath.' (CHMOD ?)';
			return;
		}
		
		// MAJ
		$this->_path = $newPath;
		$this->name = $this->cname;
		$this->cname = $this->titre;
		
		if (in_array($this->ext, $extensionsImg) && in_array($this->type, $allowedTypesImg) && is_array($arrSize)) { // IMAGE ?...
			$this->isImage = TRUE;
			$this->makeThumbs($this->_path, $this->rep, $arrSize);
			if (!$this->noUnlink) @unlink($this->_path);		
		}	
		return $this->name;
	}
}
?>