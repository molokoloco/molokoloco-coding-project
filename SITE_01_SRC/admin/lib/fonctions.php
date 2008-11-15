<?
if ( !defined('MLKLC') ) die('Lucky Duck');

/* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PHP FONCTIONS INDEX /////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

FONCTIONS :

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */

// ---------- EXEC COMMANDE -------------------------//
function cmd($cmd, $escape=FALSE) {
	if ($escape) $cmd = escapeshellarg($cmd); // to check
	if (!isLocal()) $cmd = str_replace( array('(', ')'), array('\\(', '\\)'), $cmd);
	exec($cmd, $erreur, $exit);
	if ($exit != 0) return implode('. ', $erreur);
	else return FALSE; // :)
}

// NAVIGATORS -------------------------//
function navDetect() { // A finir...
	$browser = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (preg_match('/(gecko\/)/i',$browser)) return 'gecko';
	if (preg_match('/(msie)|(microsoft internet explorer)/i',$browser)) return 'msie';
	return NULL;
}
function getNav() {
	static $nav = NULL; 
	if ($nav === NULL) $nav = @get_browser();
	if (!$nav) {
		$nav->cookies = true;
		$nav->javascript = true;
	}
	return $nav;
}

// COOKIES -------------------------//
function getMyCookie($name) {
	if (!isset($_COOKIE[$name]) || empty($_COOKIE[$name])) return false;
	else return rawurldecode($_COOKIE[$name]);
}
function setMyCookie($name, $value, $day=7) {
	if (isLocal()) $domaine = false;
	else {
		global $WWW;
		$domaine = str_replace('http://','',$WWW);
		$domaine = str_replace('www.','',$domaine);
		$domaine = '.'.$domaine;
	}
	// setrawcookie
	setcookie($name, rawurlencode($value), (time() + (60 * 60 * 24 * $day)), '/', $domain, false); // invalid characters : ,;<space>\t\r\n\013\014'
}
function delMyCookie($name) {
	if (navDetect() == 'msie') setcookie($name, '', time()+20000);
	else {
		if (isLocal()) $domaine = false;
		else {
			global $WWW;
			$domaine = str_replace('http://','', $domaine);
			$domaine = str_replace('www.','', $domaine);
			$domaine = '.'.$domaine;
		}
		setcookie($name, '', time()-20000, '/', $domaine);
	}
}
function delAllMyCookie() {
	$cookiesSet = array_keys($_COOKIE);
	for ($i=0; $i<count($cookiesSet); $i++) delMyCook($cookiesSet[$i]);
}

// FORCE ISO - UTF8 -------------------------//
function setIsoHeader() {
	if (!headers_sent()) header("Content-Type:text/html; charset=iso-8859-1");
	else mb_http_output('iso-8859-1');
}
function setUtf8Header() {
	if (!headers_sent()) header("Content-Type:text/html; charset=utf-8");
	else mb_http_output('utf-8');
}

// GPC.. get var -------------------------//
function gpc($name, $method='g') { // Default : GET
	switch($method) {
		case 'p' : $value = ( isset($_POST[$name])  ? $_POST[$name] : '' );
		case 'c' : $value = ( isset($_COOKIE[$name]) ? getMyCookie($name) : '' );
		case 's' : $value = ( isset($_SESSION[$name]) ? $_SESSION[$name] : '' );
		case 'r' : $value = ( isset($_SERVER[$name]) ? $_SERVER[$name] : '' );
		case 'e' : $value = ( isset($_ENV[$name]) ? $_ENV[$name] : '' );
		case 'f' : $value = ( isset($_FILES[$name]) ? $_FILES[$name] : '' );
		case 'g' :
		default : $value = ( isset($_GET[$name]) ? $_GET[$name] : '' );
	}
	return clean($value);
}

// Standard script balise -------------------------//
function js($script, $echo=TRUE) {
	global $JS,$JSE;
	$js = $JS.chr(13).chr(10).$script.chr(13).chr(10).$JSE;
	if ($echo) echo $js;
	else return $js;
}

// GOTO URL AND EXIT -------------------------//
function goto($url,$head=NULL) { // header redir <-> body redir 
	if (empty($url)) $url = $_SERVER['HTTP_REFERER'];
	if (!headers_sent()) {
		switch($head) {
			case '100' : header("HTTP/1.1 100 Continue"); break;
			case '301' : header("HTTP/1.1 301 Moved Permanently"); break; // Google like
			case '302' : header("HTTP/1.1 302 Moved Temporarily"); break;
			case '401' : header("HTTP/1.1 401 Unauthorized"); break;
			case '403' : header("HTTP/1.1 403 Forbidden"); break;
			case '404' : header("HTTP/1.0 404 Not Found"); break;
			case '405' : header("HTTP/1.1 405 Method Not Allowed"); break;
			case '500' : headparseArrToRsser("HTTP/1.1 500 Internal Server Error"); break;
			default : header("HTTP/1.1 100 Continue"); break; // let's go ?
		}
		header("Location: $url");
	}
	else echo '<meta http-equiv="refresh" content="0;URL='.$url.'">'.chr(13).chr(10);
	js(' window.location.href="'.$url.'"; ');
	die('<font face="Arial" size="2">La redirection vers la page : <a href="'.$url.'">'.$url.'</a></font> a &eacute;chou&eacute;');
}

// HEADER NO CACHE -------------------------//
function noCache() {
	header("Expires: ".gmdate("D, d M Y H:i:s", time()-315360000)." GMT");
	header("Last-Modified: ".gmdate("D, d M Y H:i:s", time()-315360000)." GMT");
	header("Cache-Control: private, no-cache='set-cookie'");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
}
function setCache() {
	header("Expires: ".gmdate("D, d M Y H:i:s", time()+315360000)." GMT");
	header("Cache-Control: max-age=315360000");
}

// SCRIPT ALERT -------------------------//
function alert($message, $redir=NULL, $type='printInfo') { // $redir = NULL | 'back' | url
	js(' '.$type.'("'.str_replace('"','&quot;',aff($message,3)).'"); '.($redir == 'back' ? ' history.back(); ' :'') );
	if ($redir && $redir != 'back') goto($redir);
	die();
}

////////////////////////////////////////////////////// TEMPLATES //////////////////////////////////////////////////////////////


// HTML Attributes... -------------------------//
function getAtt($name, $value) {
	return ' '.$name.'="'.$value.'"';
}

// TAGS TEMPLATES -------------------------//
// Lazy coding ;) Genere les attributs par d�faut pour chaque tag, overwritable
// echo getTag('img', array('src'=>'toto.jpg'));
function getTag($tag, $options=array()) {
	$tpl = '';
	$customAttributes = '';
	$defaultId = generateId($tag.'_');
	$defaultAttributes =  array(
		'id'=> 		getAtt('id', ( $options['id'] ? $options['id'] : $defaultId )),
		'alt'=> 	( ($options['alt'] || $options['src']) ? getAtt('alt', ( $options['alt'] ? $options['alt'] : affCleanName($options['src']))) : '' ),
		'name'=> 	getAtt('name', ( $options['name'] ? $options['name'] : $defaultId )),
		'href'=> 	getAtt('href', ( $options['href'] ? $options['href'] : 'javascript:void(0);' )),
		'border'=> 	getAtt('border', ( $options['border'] | 0 )),
		'type'=> 	getAtt('type', ( $options['type'] ? $options['type'] : 'text' )),
		
		'src'=> 				getAtt('src', ( $options['src'] ? $options['src'] : 'about:blank' )),
		'allowtransparency'=>	getAtt('allowtransparency', ( $options['allowtransparency'] ? $options['allowtransparency'] : '1' )),
		'frameborder'=> 		getAtt('frameborder', ( $options['frameborder'] ? $options['frameborder'] : '0' )),
		'scrolling'=> 			getAtt('scrolling', ( $options['scrolling'] ? $options['scrolling'] : 'auto' )),
		'width'=> 				getAtt('width', ( $options['width'] ? $options['width'] : '100%' )),
		'height'=> 				getAtt('height', ( $options['height'] ? $options['height'] : '350' )),
	);
	foreach($options as $key=>$val) {
		if (!array_key_exists($key, $defaultAttributes) && $key != 'inner') $customAttributes .= '{#'.$key.'}';
		if ($key == 'inner') $defaultAttributes[$key] = $val;
		else $defaultAttributes[$key] = getAtt($key, $val);
	}
	switch($tag) {
		case 'a' : 			$tpl = '<a{#id}{#href}'.$customAttributes.'>{#inner}</a>'; break;
		case 'img' : 		$tpl = '<img{#src}{#id}{#alt}{#name}{#border}'.$customAttributes.'/>'; break;
		case 'div' : 		$tpl = '<div{#id}'.$customAttributes.'>{#inner}</div>'; break;
		case 'input' : 		$tpl = '<input{#id}{#type}{#name}'.$customAttributes.'/>'; break;
		case 'area' :
		case 'textarea' : 	$tpl = '<textarea{#id}{#name}'.$customAttributes.'>{#inner}</textarea>'; break;
		case 'select' : 	$tpl = '<select{#id}{#name}'.$customAttributes.'>{#inner}</select>'; break;
		case 'iframe' : 	$tpl = '<iframe{#id}{#name}{#src}{#allowtransparency}{#frameborder}{#scrolling}{#width}{#height}'.$customAttributes.'>{#inner}</iframe>'; break;
		default : 			$tpl = '<'.$tag.'{#id}'.$customAttributes.'>{#inner}</'.$tag.'>'; break;
	}
	foreach($defaultAttributes as $key=>$val) {
		$tpl = str_replace('{#'.$key.'}', $val, $tpl);
	}
	return $tpl;	
}

// SPECIFIQUE STYLE FOR EACH PAGE IN HEADER -------------------------//
// getCss('styles.css');
function getCss($myCss, $path='css/') {
	if (is_array($myCss))
		foreach($myCss as $css)
			echo '<link href="'.$path.$css.'" rel="stylesheet" type="text/css" '.($css=='print.css'?'media="print"':'').'/>'.chr(13).chr(10);
	else echo '<link href="'.$path.$css.'" rel="stylesheet" type="text/css" '.($css=='print.css'?'media="print"':'').'/>'.chr(13).chr(10);
}

// GET FORMULAIRE -------------------------//
// form('frm_login', '_actions.php?action=LOGIN', false);
function form($name, $action, $submit=true, $method='post', $target='') {
	echo '<form action="'.$action.'" method="'.$method.'" enctype="multipart/form-data" name="'.$name.'" id="'.$name.'" '.($submit?'':'onsubmit="return false;"').' '.(!empty($target) ? 'target="'.$target.'"':'').'>';
}
function formE() {
	echo '</form>';
}

// STANDARD FORM ROW WITH LABEL AND INPUT -------------------------//
// getFormRow('text', 'Nom', 'connexion_nom', $_SESSION[SITE_CONFIG]['DEMANDE']['nom'], '')
function getFormRow($type, $titre, $name, $value, $options='') {
	$htm = getTag('label', array('for'=>$name, 'inner'=>aff($titre)));
	switch($type) {
		case 'textarea' :
			$customAtt = array('name'=>$name, 'id'=>$name, 'inner'=>aff($value));
			if (isSetArray($options)) $customAtt = array_merge($customAtt, $options);
			$htm .= getTag('textarea', $customAtt);
		break;
		default :
			$customAtt = array('type'=>$type, 'name'=>$name, 'id'=>$name, 'value'=>aff($value));
			if (isSetArray($options)) $customAtt = array_merge($customAtt, $options);
			$htm .= getTag('input', $customAtt);
		break;
	}
	return $htm;
}


////////////////////////////////////////////////////// NAVIGATION VERIFICATION //////////////////////////////////////////////////////////////
		
// CURRRENT URL/REP -------------------------//
function thisUrl($type='www',$query=1) { // www <-> current rep
	if ($type == 'www') {
		$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']; // $_SERVER['REQUEST_URI']
		if ($query == 1) $url .= '?'.$_SERVER['QUERY_STRING'];
		return $url;
	}
	else {
		$path = dirname($_SERVER['PHP_SELF']);
		$position = strrpos($path,'/') + 1; 
		return substr($path,$position); 
	}
}

// THIS PAGE WITH REWRITING -------------------------//
function thisRedir() {
	$request_uri = $_SERVER['REQUEST_URI'];
	$request_uri = 'http://'.$_SERVER['HTTP_HOST'].$request_uri;
	return $request_uri;
}

// THIS PAGE -------------------------//
function thisPage($action='',$replace='',$nameActionToClean='') {
	$myPage = thisRedir();
	list($selfPageName, $query) = explode('?', $myPage);
	$selfPageName = basename($selfPageName); // $_SERVER['PHP_SELF']
	if ('/'.$selfPageName.'/' == $_SERVER['REQUEST_URI'] || $_SERVER['REQUEST_URI'] == '/') $selfPageName = 'index.php'; // No page, page by default
	
	$query = str_replace($replace, '', $query); // $_SERVER['QUERY_STRING']
	$query = explode('&', $query);
	$query_str = '';
	if (is_scalar($nameActionToClean)) $clean[] = $nameActionToClean;
	else $clean = $nameActionToClean;

	foreach($query as $value) {
		if (empty($value)) continue;
		list($key) = explode('=', $value);
		if (!in_array($key, $clean)) $query_str .= '&amp;'.$value;
	}
	if (!empty($query_str)) $query_str = substr($query_str, 5);
	
	if (!empty($action) || !empty($query_str)) $page = $selfPageName.'?';
	else $page = $selfPageName;
	$page .= $query_str;
	if (!empty($action) && !empty($query_str)) $page .= '&amp;';
	$page .= $action;
	return $page;
}

// CHECK REFERER -------------------------//
function checkRef() {
    global $WWW;
	$ref = ( getenv("HTTP_REFERER") ? getenv("HTTP_REFERER") : $_SERVER["HTTP_REFERER"] );
	if (!$ref) return false;
	$shortRef = substr($ref, 0, strlen($WWW));
	if ($shortRef != $WWW) return false;
	else return true;
}

// MAX ACTION EXECUTION TIME -------------------------//
function checkAction($name,$max) {
	if (!isset($_SESSION[$name])) { session_register($name); $_SESSION[$name] = 1; }
	else $_SESSION[$name] = $_SESSION[$name] + 1;
	if ($_SESSION[$name] > $max)
		alert('D�sol� vous ne pouvez pas essayer cette action plus de '.$max.' fois', 'index.php');
}

// GET IP -------------------------//
function getIp() {
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	elseif(isset($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
	else $ip = $_SERVER['REMOTE_ADDR'];
	return $ip;
}

// CHECK IP -------------------------//
function checkIp() {
	global $allowedIp;
	if (!in_array(getIp(),$allowedIp)) die('Lucky Duck');
}

// LOG IP -------------------------//
function stockIp($max=5,$dir='stock_ip.txt') {
	if (!is_file($dir)) writeFile($dir,''); // Create file...
	$error = 0;
	$ip = getIp();
	list($firstnumber) = explode('.',$ip);
	if ($firstnumber < 1) return $error; // N'attrape pas l'ip ?
	$fp = fopen($dir, 'rb');
	$file = fread($fp,filesize($dir));
	$ipArray = explode('#',$file);// #200.122.000.222_1#202.122.000.225_3
	$NewIpArray = array();
	$NewIpCountArray = array();
	foreach($ipArray as $ip_count) {
		list($ipSel,$ipCountSel) = explode('_',$ip_count);
		if ($ipSel != '') { // le premier commence par #...
			$NewIpArray[] = $ipSel;
			$NewIpCountArray[] = $ipCountSel;
		}
	}
	if (in_array($ip,$NewIpArray)) {
		$key = array_search($ip,$NewIpArray);
		$ipCountSel = $NewIpCountArray[$key];
		$ipCountSel++;
		$NewIpCountArray[$key] = $ipCountSel;
		if ($ipCountSel >= $max) $error = 1;
		if ($ipCountSel >= 20) {
			mailto('molokoloco@gmail.com','molokoloco@gmail.com', 'Spammeur...'.$WWW, $_SERVER['PHP_SELF']);
		}
	}
	else {
		$NewIpArray[] = $ip;
		$NewIpCountArray[] = 1;
	}
	$NewIpStock = '';
	foreach($NewIpArray as $key=>$stockip) {
		$NewIpStock .= '#'.$NewIpArray[$key].'_'.$NewIpCountArray[$key];
	}
	$fp = fopen($dir, 'wb'); // Ecrase
	fwrite($fp,trim($NewIpStock));
	return $error;
}

////////////////////////////////////////////////////// FETCH VALUES /////////////////////////////////////////////////////////////////////////

// GOT THE PASSWORD ? -------------------------//
function genPass() {
	$consonnes = 'bcdfgjklmnpqrstvxz';
	$voyelles = 'aeiouy';
	$chiffres = '0123456789';
	for ($i=0; $i<=2; $i++) {
		$consonne[$i] = substr($consonnes, rand(0, strlen($consonnes)-1), 1);
		$voyelle[$i] = substr($voyelles, rand(0, strlen($voyelles)-1), 1);
		$chiffre[$i] = substr($chiffres, rand(0, strlen($chiffres)-1), 1);
	} 
	return $consonne[0].$voyelle[0].$consonne[1].$voyelle[1].$consonne[2].$voyelle[2].$chiffre[0].$chiffre[2];
}

// GENERATE UNIQUE ID -------------------------//
function generateId($prefix='obj_') {
	static $idObjects = 0;
	$prefix = cleanName($prefix);	
	return $prefix.$idObjects++;
}

// GET NEXT SQL ID FOR A TABLE -------------------------//
function nextId($table) {
	$ID =& new Q("SELECT id FROM $table ORDER BY id DESC LIMIT 1");
	return (intval($ID->V[0]['id']) + 1);
}

// FETCH VALUE FROM TABLE -------------------------//
//$email_admin = fetchValue('email_admin');
function fetchValue($input='email', $table='admin_parametres', $id='1') {
	if ($input == 'email') {
		global $emailAdmin;
		if (!$emailAdmin) $emailAdmin = 'molokoloco@gmail.com';
		return $emailAdmin;
	}
	else {
		$A =& new Q(" SELECT $input FROM $table WHERE id='$id' LIMIT 1 ");
		$value = aff($A->V[0][$input]);
		return $value;
	}
}

// FETCH VALUES FROM TABLE -------------------------//
//$produit_titre = fetchValues('titre', 'mod_catalogue_produits', 'id', $V['produit_id']);
//$produit = fetchValues(array('id', 'titre'), 'mod_catalogue_produits', array('id'=>$V['produit_id'], 'actif'=>'1'));
function fetchValues($input='email', $table='admin_parametres', $champs='id', $champsVal='1') {
	if (is_array($input)) $inputSel = implode(',', $input);
	else $inputSel = $input;
	$where = '';
	if (is_array($champs)) {
		foreach($champs as $key=>$value) $where .= " $key='$champs' AND ";
		$where = substr($where, 0, -4);
	}
	else $where = " $champs='$champsVal' ";
	$A =& new Q("SELECT $inputSel FROM $table WHERE $where LIMIT 1");
	return ( is_array($input) ? $A->V[0] : $A->V[0][$input] );
}

// FETCH EMAIL ALERTE TEXTE -------------------------//
function fetchAlerte($id, $arr_replace='', $lg='', $wysiwyg=true) { // Fonctionne avec la table "alertes_email"
	if ($lg != '') $lg = '_'.$lg;
	$A =& new Q(" SELECT sujet{$lg}, texte{$lg} FROM dat_email_alertes WHERE id='$id' LIMIT 1 ");
	
	$suject_email = aff($A->V[0]['sujet'.$lg]);
	$texte_email = str_replace('&quot;','"',aff($A->V[0]['texte'.$lg]));
	if (!$wysiwyg) $texte_email = nl2br($texte_email);
	
	if (is_array($arr_replace)) { // Mini template
		$suject_email = str_replace(array_keys($arr_replace), array_values($arr_replace), $suject_email);
		$texte_email = str_replace(array_keys($arr_replace), array_values($arr_replace), $texte_email);
	}
	return array($suject_email,$texte_email);
}


// SEND SIMPLE E-MAIL... -------------------------//
function mailto($from, $to, $subject, $titreBody='', $innerBody='', $sender='', $files='' ) {
	global $WWW,$wwwRoot;
	
	if (empty($from) || empty($to) || empty($subject)) return false;
	
	$body = '<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>'.$WWW.'</title>
	</head>
	<body><span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #666666;">';

	if (!empty($titreBody))  $body .= '<center><font class="Arial" size="4" color="#CCCCCC">'.strtoupper($titreBody).'</font></center>
		<hr size="1" color="#CCCCCC">';
	
	$body .= '<br />';
	if (!empty($innerBody))  $body .= str_replace('&quot;','"',aff($innerBody));
	else {
		if(count($_POST)) {
			while (list($key,$val) = each($_POST)) { 
				if ( $key != 'Submit') $body .= '<b>'.str_replace('_',' ',aff($key)).' :</b> '.str_replace('&quot;','"',htmlentities(aff($val))).' <br />';
			}  
		}
	}
	$body .= '<br />&nbsp;<hr size="1" color="#666666"></span>
	</body>
	</html>';
	
	$body = str_replace('<p>', '<p style="margin:0;">', $body);

	// SEND MAIL
	
	$to = explode(';', $to);
	
	require_once dirname(__FILE__).'/class/class_mail2.php';
	
	foreach($to as $oneto) {

		$MyMail = new PHPMailer();
		$MyMail->Priority = 3;
		$MyMail->Encoding = '8bit';
		$MyMail->CharSet = 'iso-8859-1';
		$MyMail->IsHTML(true);
		$MyMail->From = $MyMail->FromName = $from;
		$MyMail->Sender = ( $sender != '' ? $sender : fetchValue('email')); // Anti-Blacklist si from = email visiteur
		$MyMail->AddAddress($oneto,$oneto);
		$MyMail->Subject = aff($subject);
		$MyMail->Body = $body;
		$MyMail->AltBody = strip_tags(str_replace('<br />',chr(10).chr(13),str_replace(chr(10).chr(13),' ',unhtmlentities($body))));
		$MyMail->WordWrap = 85;

		if (isLocal()) {
			
			$mailTxt = '<div style="border:1px dashed #000;padding:10px;"><font face=courier size=2><br />';
			$mailTxt .= '<b>From:</b> '.$MyMail->From.'<br />';
			$mailTxt .= '<b>To:</b> '.$MyMail->to[0][0].'<br />';
			$mailTxt .= '<b>Subject:</b> '.$MyMail->Subject.'<br /></font>';
			$mailTxt .= getDb($MyMail->Body);
			$mailTxt .= '</div><br /><br />';
			createFile($wwwRoot.'_mails.txt.htm', $mailTxt); //, 'append'
			if (headers_sent()) js("myPop('".$WWW."_mails.txt.htm','', 600, 480);");
			else echo '<h1 align="center" style="background:#FFF;color:#CCC;">'.getTag('a', array('href'=>$WWW.'_mails.txt.htm', 'target'=>'_blank', 'inner'=>'Voir le mail envoy�')).'</h1>';

		}
		else {
			if (!empty($files) && is_array($files)) { // Pieces jointe $files = array('./totot.jpg');
				foreach($files as $file) {
					if (is_file($file)) $MyMail->AddAttachment($file);
				}
			}
			else if (!empty($files) && is_file($files) ) $MyMail->AddAttachment($files);
			if (!$MyMail->Send()) return false;
		}
	}
	return true;
}


///////////////////////////////////////// EN COURS ////////////////////////////////////////////////////////////////////////////////

//  SCALE IMAGE -------------------------//
// list($w, $h) = scale($w, $h, 120);
function scale($w, $h, $max='120x90') {				
	if (strpos('%', $max) !== FALSE) {
		$max = str_replace('%', '', $max);
		$w = floor($w * ($max/100));
		$h = floor($h * ($max/100));
	}
	else {
		list($maxW, $maxH) = explode('x', $max);
		if (empty($maxH)) $maxH = $maxW;
		if ($w > $maxW || $h > $maxH) {
			if ($w > $h) {
				$h = floor(($h / $w) * $maxW);
				$w = $maxW;
			}
			else {
				$w = floor(($w / $h) * $maxH);
				$h = $maxH;
			}
		}
	}
	return array($w, $h);
}
function reduceImgInHtml($string, $max='120x90') {
	$pattern_img_src = '!<img.+src=("|\')([^\1]+)\1.+/?>!Ui';
	preg_match_all($pattern_img_src, $string, $links);
	if ($links[2][0]) {
		foreach($links[2] as $k=>$img) {
			list($w, $h) = @getimagesize($img);
			list($w, $h) = scale($w, $h, $max);
			$img = '<img src="'.$img.'" width="'.$w.'" height="'.$h.'" border="0" align="left" hspace="6" alt=""/>';
			$string = str_replace($links[0][$k], $img, $string);
		}
	}
	return $string;
}
function maskImgInHtml($string, $max='120x90') {
	list($maxW, $maxH) = explode('x', $max);
	if (empty($maxH)) $maxH = $maxW;
		
	$pattern_img_src = '!<img.+/?>!Ui';
	preg_match_all($pattern_img_src, $string, $links);
	if ($links[0][0]) {
		foreach($links[0] as $img) {
			$string = str_replace($img, '<div style="max-width:'.$maxW.'px;max-height:'.$maxH.'px;overflow:hidden;display:block;float:left;margin:2px;">'.$img.'</div>', $string);
		}
	}
	return $string;
}


// NO SPAM !!! GOT THE IMAGE CODE ? -------------------------// //
/*
	<?=genXCode();?> Recopier ici : <input name="code" type="text" class="input" size="4" maxlength="4">
	# _action.php
	$actif = 1;
	$code = clean($_POST['code']);
	if (!checkRef()) $actif = 0;
	if (strlen($code < 2 || strtolower($code) != $_SESSION['CODE']['code']) $info .= "<br />- Veuillez v&eacute;rifiez le code anti-spam !";
	if (time() - $_SESSION[SITE_CONFIG]['actiontime'] < 10) $info .= "<br />- Veuillez patienter avant de reposter";
	// ...
	$_SESSION[SITE_CONFIG]['actiontime'] = time();
*/
function genXCode() {
	global $WWW;
	/*$consonnes = 'bcdfgjklmnpqrstvxz';
	$voyelles = 'aeiouy';*/
	for ($i=0; $i<2; $i++) {
		/*$consonne[$i] = substr($consonnes, mt_rand(0, strlen($consonnes)-1), 1);
		$voyelle[$i] = substr($voyelles, mt_rand(0, strlen($voyelles)-1), 1);*/
		$chiffre[$i] = rand(0,9);
	}
	$_SESSION['CODE']['code'] = $chiffre[0].$chiffre[1]; // $consonne[0].$voyelle[0].$consonne[1].$voyelle[1].$chiffre[0].$chiffre[1];
	$_SESSION['CODE']['code_id'] = date("YmdHmi");
	$m =& new FILE();
	if (!$m->isMedia($WWW.'swf/code.swf')) die('&quot;'.$WWW.'swf/code.swf&quot; n\'existe pas');
	$m->width = '22';
	$m->height = '22';
	$m->style = 'display:inline;vertical-align:bottom;';
	$m->simple = TRUE;
	return $m->flashObj(false);
}

// MAKE SOME PAGINATION LINKS -------------------------//
/*
	Exemple :
		$G =& new Q(" SELECT id FROM pieces WHERE actif='1' ");
		$pageArr = makePage($_GET['page'], count($G->V), $_SESSION[SITE_CONFIG]['pagination'], thisPage('','','page'), 'bleue', 'rouge');
		$pageHtml = ( $pageArr['pageHtml'] ? '<span class="legende">'.$pageArr['pageHtml'].'</span><div class="spacer"></div>' : '');
		$page = $pageArr['page'];
		$offset = $pageArr['offset'];
		$debut = $pageArr['debut'];
*/

function makePage($currentPage, $total, $offset, $getUrl, $classOn, $classOff, $short=false, $startHtm='[&nbsp;', $endHtm='&nbsp;]', $getName='page') {
	if ($total < 1 || $offset < 1) return false;
	
	$currentPage = ($currentPage > 0 ? $currentPage : 1 );
	$nbpage = ceil($total/$offset);

	$debut = 0;
	if ($currentPage > 1) $debut = ($currentPage-1) * $offset;

	if ($currentPage > $nbpage)  { $debut = 0; $currentPage = 1; }

	if ($nbpage < 2) return array('pageHtml'=>'', $getName=>$currentPage, 'debut'=>$debut, 'offset'=>$offset);

	$pageHtml = $startpageHtml = $endpageHtml = '';
	if (strpos($getUrl, '?') === FALSE) $getUrl .= '?';
		
	if ($currentPage > 1)
		$startpageHtml = '<a href="'.$getUrl.'&amp;'.$getName.'=1" class="premier"><img src="images/common/picto_premier.gif" alt="Premi�re page" class="rollover" /></a>
		<a href="'.$getUrl.'&amp;'.$getName.'='.($currentPage-1).'"><img src="images/common/picto_precedent.gif" alt="Page pr&eacute;c&eacute;dante" class="rollover" /></a>'; // class="'.$classOff.'"

	if ($currentPage < $nbpage)
		$endpageHtml = '<a href="'.$getUrl.'&amp;'.$getName.'='.($currentPage+1).'"><img src="images/common/picto_suivant.gif" alt="Page suivante" class="rollover" /></a>
		<a href="'.$getUrl.'&amp;'.$getName.'='.$nbpage.'"><img src="images/common/picto_derniere.gif" alt="Derni�re page" class="rollover" /></a>';

	$pageHtml .= $startHtm;
	
	if (!$short) {
		$pageHtml .= $startpageHtml.'&nbsp;';
		for ($p=1; $p<=$nbpage; $p++) {
		 	//								$pageHtml .= '<a href="'.$getUrl.'&amp;'.$getName.'='.$p.'" class="'.($currentPage!=$p?$classOff:$classOn).'">'.$p.'</a>';
			if ($currentPage != $p) $pageHtml .= '<a href="'.$getUrl.'&amp;'.$getName.'='.$p.'" class="'.($currentPage==($p-1)?$classOn:$classOff).'">'.$p.'</a>';
			else $pageHtml .= '<strong>'.$p.'</strong>';
			if ($p<=$nbpage-1) $pageHtml .= ' ';
		}
		$pageHtml .= '&nbsp;'.$endpageHtml;
	}
	else {  // 1 page avant 2 pages apres...
		$p = 1; // Premiere page
		$pageHtml .= '<a href="'.$getUrl.'&amp;'.$getName.'='.$p.'" class="'.($currentPage!=$p?$classOff:$classOn).'">'.$p.'</a>';
		if ($p<$nbpage) $pageHtml .= ' ';
		$start =  $currentPage-1 > $p ? $currentPage-1 : $p + 1; // 1 page avant 2 pages apres...
		//if ($currentPage > 1) 
		$interval = $currentPage+1 >= $nbpage-1 ? $nbpage-1 : $currentPage+1;
		//else $interval = $currentPage+2 >= $nbpage-1 ? $nbpage-1 : $page+2;
		if ($p < $start-1) $pageHtml .= '<span>... </span>';
		for ($p=$start; $p<=$interval; $p++) {
			$pageHtml .= ' <a href="'.$getUrl.'&amp;'.$getName.'='.$p.'" class="'.($currentPage!=$p?$classOff:$classOn).'">'.$p.'</a>';
			if ($p<=$nbpage-1) $pageHtml .= ' ';
		}
		if ($p <= $nbpage-1) $pageHtml .= '<span>... </span>';
		$p = $nbpage; // Derniere page
		$pageHtml .= ' <a href="'.$getUrl.'&amp;'.$getName.'='.$p.'" class="'.($currentPage!=$p?$classOff:$classOn).'">'.$p.'</a>';
	}
	
	$pageHtml .= $endHtm;
	
	return array('pageHtml'=>$pageHtml, $getName=>$currentPage, 'debut'=>$debut, 'offset'=>$offset, 'precedante'=>$startpageHtml, 'suivante'=>$endpageHtml, 'nbpage'=>$nbpage);
}



// SEARCH IN DATABASE -------------------------//
/*
	$recherche = clean($_POST['recherche']);
	$auteur_id = clean($_POST['auteur_id']);
	if (!empty($recherche) || $auteur_id > 0) {
		$select = array(
			'S' => "art.id",
			'F' => "mod_articles AS art, cms_pages_relation_mod_articles AS cprma, admin_utilisateurs AS uti",
			'W' => "cprma.prod_id=art.id AND uti.id=art.utilisateur_id AND art.statut='2'",
		);
		$fields = array();
		$fields[1][] = array('art','titre', 'LIKE', $recherche);
		$fields[1][] = array('art','chapeau', 'LIKE', $recherche);
		$fields[1][] = array('art','texte', 'LIKE', $recherche);
		$fields[2][] = array('uti','id', '=', $auteur_id);

		$_SESSION[SITE_CONFIG]['R'] = searchDb($select, $fields);
		$_SESSION[SITE_CONFIG]['R']['recherche'] = make_iso($recherche);
		$_SESSION[SITE_CONFIG]['R']['auteur_id'] = $auteur_id;
	}

	// ALL RESULT + PREPARED REQUETE FOR PAGINATION
	$total = intval($_SESSION[SITE_CONFIG]['R']['total']);
	$from = $_SESSION[SITE_CONFIG]['R']['from'];
	$where = "cprma.prod_id=art.id AND uti.id=art.utilisateur_id AND art.statut='2' AND ".$_SESSION[SITE_CONFIG]['R']['where'];

*/
function searchDb($select, $fields, $max=200) {
	$searchArray = array();
	$arrayAnd = array();
	$one = 0;
	foreach((array)$fields as $k=>$criteres) {
		###db($criteres);
		foreach((array)$criteres as $field) {
			if ($field[3] !== 0 && $field[3] !== '0' && empty($field[3])) continue;
			$searchArray['keywords'][$field[1]] = $searchs = make_iso(aff($field[3])); // Base de donn�e ISO, pages UTF8......
			$searchs = explode(' ', $searchs);
			foreach($searchs as $i=>$search) {
				if ($search !== 0 && $search !== '0' && empty($search)) continue;
				else $one = 1;
				if ($field[2] == 'LIKE') $arrayAnd[$k][$i][] = $field[0].'.'.$field[1]." LIKE '%{$search}%'";
				else $arrayAnd[$k][$i][] .= $field[0].'.'.$field[1].$field[2]."'{$search}'";
		}
	}
	}
	###db($arrayAnd);
	
	if (!$one) { // Re-init
		$searchArray['keywords'] = '';
		$searchArray['where'] = '';
		$searchArray['total'] = 0;
		return $searchArray;
	}
	$where = '';

	foreach((array)$fields as $i=>$criteres) {
		if ($arrayAnd[$i] !== 0 && $arrayAnd[$i] !== '0' && empty($arrayAnd[$i])) continue;
		if (count($fields) > 1) $where .= '(';
		if (count($arrayAnd[$i]) > 1) {
			foreach($arrayAnd[$i] as $andOr) $where .= ' ('.implode(' OR ', $andOr).' ) AND ';
			$where = substr($where, 0, -5);
	}
		else if (count($arrayAnd[$i][0]) > 1) $where .= ' ('.implode(' OR ', $arrayAnd[$i][0]).' )';
		else $where .= $arrayAnd[$i][0][0];
		if (count($fields) > 1) $where .= ') AND ';
	}
	if (count($fields) > 1) $where = substr($where, 0, -5);

	$R =& new Q("
		SELECT DISTINCT ".$select['S']."
		FROM ".$select['F']."
		WHERE
			".(!empty($select['W']) ? $select['W'].' AND' : '')."
			$where
		LIMIT $max
	");
	### db($R);

	$searchArray['from'] = $select['F'];
	$searchArray['where'] = $where;
	$searchArray['total'] = count($R->V);

	return $searchArray;
   }

////////////////////////////////////////////////////// FONCTIONS ETENDUES CLARK TEAM //////////////////////////////////////////////

class sqlAccessor {
	function sqlAccessor() {}
	function query($requete='') {
		$R =& new Q($requete);
		return $R->V;
	}
}
function initSearch($keyword='') {
	global $wwwRoot;
	require(dirname(__FILE__).'/class/class_search.php');
	$APP =& new sqlAccessor();
	$mySearch =& new db_search($keyword , $APP);
	require($wwwRoot.'class_search_config.php');
	if (is_array($dataSearchObj)) {
		foreach($dataSearchObj as $profil) {
			if (is_object($profil)) $mySearch->s_query($profil);
		}
	}
	if (is_array($DataBaseSearch_Param)) {
		foreach($DataBaseSearch_Param as $param) {
			if (substr($param , 0 , 2 ) == '->') eval('$mySearch'.$param);
		}
	}
	return $mySearch ;
}


// TIME CALLED AT END OF PHP EXECUTION -------------------------//

// check_time(microtime()); // Caling our function once!
function check_time($time_str, $end = false){
    global $start;
    list($msec, $sec) = explode(' ', $time_str);
    $result = bcadd($msec, $sec, 10);
    if (!$end) {
        $start = $result;
        register_shutdown_function('check_time', microtime(), 'true'); // Function registrates itself only with some parametrs now
    }
	elseif ($end) {
        $end = $result;
        echo '<br />Page was generated in '.bcsub($end, $start, 10).' seconds...<br />';
    }
}

// getJsSwfFlv($V['video'], 'video_'.$V['id'], 640, 480); // DEPRECATED : See $m->flashObj();
function getJsSwfFlv($flashPath, $divId='', $width=640, $height=480) {
	if (empty($divId)) $divId = generateId('video_flv_');
	list($video_url, $video_params) = urlToJson($flashPath);
	$html = '<div id="'.$divId.'">&nbsp;</div>';
	$html .= js("
		var flashvars = {".$video_params."};
		var params = {wmode:'transparent',allowFullScreen:'true',allowScriptAccess:'always'};
		var attributes = {};
		swfobject.embedSWF('".$video_url."', '".$divId."', '".$width."', '".$height."', '8.0.0', '', flashvars, params, attributes);
	", FALSE);
	return $html;
}
?>