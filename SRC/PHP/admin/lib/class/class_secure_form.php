<?php
/*
	$SSL_form = new SecureForm();
	if($_POST && $SSL_form->check())
	{
		{ ... Code validé ... }
	}
	
	
	$generate = $SSL_form->generate();
	echo $generate[0]; // Champ <input>
	echo $generate[1]; // Balise <img>
	
	if($SSL_form->bad_code)
	{
		{ ... Code invalide ... }
	}
*/
/**
 * Formulaire donc la validation est soumise a un JS
 * Créé le 02-01-2007 Par Arnault SOIZEAU
 */
class SecureForm {
	/**
	 * Table contenant les chaines aléatoires
	 *
	 * @var string , Nom de table SQL
	 */
	var $stringList				= 'ssl_form' ;
	/**
	 * Nombre de caracteres a afficher dans le formulaire
	 *
	 * @var integer
	 */
	var $nb_char				= 4 ;
	/**
	 * Nombre de chaines a stocker dans la base de donnée
	 *
	 * @var integer
	 */
	var $nb_sql_string			= 200 ;
	/**
	 * Juste au cas ou, elle est une sécurité contre les boucles infinies
	 *
	 * @var integer
	 */
	var $debugLimit				= 500 ;
	/**
	 * Couleur de fond de l'image GD crée
	 *
	 * @var string
	 */
	var $img_bgcolor			= '#ECECEC' ;
	/**
	 * Couleur des caracteres dans l'image
	 *
	 * @var srting
	 */
	var $img_fontcolor			= '#000000' ;
	/**
	 * Largeur de l'image crée
	 *
	 * @var integer
	 */
	var $img_width				= 70 ;
	/**
	 * Hauteur de l'image créé
	 *
	 * @var integer
	 */
	var $img_height				= 30 ;
	/**
	 * Nom du champ HTML qui contiendra l'ID de l'image générée par GD
	 *
	 * @var string
	 */
	var $hidden_str_id			= 'str_id' ;
	/**
	 * Nom du champ HTML qui contiendra la valeur restituée par l'utilisateur
	 *
	 * @var string
	 */
	var $hidden_str_val			= 'str_val' ;
	/**
	 * Chemin vers le fichier chargé de créé l'image et comportant une instance de cet objet
	 *
	 * @var string
	 */
	var $img_file_template		= './imgs/image.php' ;
	/**
	 * Sera mise a vrai si ne match pas en passant dans la procédure de vérification
	 *
	 * @var boolean
	 */
	var $bad_code				= false ;
	/**
	 * Fichier TTF de police à utiliser
	 *
	 * @var string
	 */
	var $font_family			= 'comic.ttf' ;
	/**
	 * Constructeur
	 * Vérifie si la tables existent
	 * Si non la créé
	 *
	 * @return SecureForm
	 */
	function SecureForm()
	{
		// Verification que GD est installé
		// if(!function_exists('imagejpeg')){ die('GD not installed !') ; exit ; }
		// elseif(gd_version()<2){ die('GD version 2 required'); exit ; }
		// Vérification de l'existence de la table SQL
		$this->font_family = dirname(__FILE__).'/secure_form/'.$this->font_family ;
		$found = false ;
		$query = mysql_query('SHOW TABLES');
		while($tblName = mysql_fetch_array($query,MYSQL_NUM))
		{
			if($tblName[0]=='ssl_form'){ $found = true ; }
		}
		if(!$found){ $this->setup(); }
		else {
			// Si il y en a le nombre suffisant
			$nombre = mysql_query('SELECT COUNT(*) AS nb FROM `ssl_form`') ;
			$_nombre = mysql_fetch_array($nombre);
			if($_nombre['nb']<10)
			{
				// Nombre a rajouter
				$nb = $this->nb_sql_string - $_nombre['nb'] ;
				$this->setData($nb);
			}
		}
	}
	/**
	 * Création et remplissage automatique des tables SQL
	 *
	 */
	function setup()
	{
		$query = 'CREATE TABLE `ssl_form` (
			`str_id` INT( 3 ) NOT NULL AUTO_INCREMENT ,
			`str_val` VARCHAR( '.$this->nb_char.' ) NOT NULL ,
			UNIQUE (
			`str_id`
			)
		) ENGINE = MYISAM ;';
		mysql_query($query) ;
		$this->setData($this->nb_sql_string);
	}
	/**
	 * Insertion des données
	 *
	 */
	function setData($combien)
	{
		// Remplissage
		$alreadyDone = array() ;
		$compteur = 0 ;
		for($i=0;$i<$combien;$i++)
		{
			while(!in_array($newString = $this->GenPassword($this->nb_char),$alreadyDone))
			{
				// Sécurité de sortie de boucle pour éviter les plantages
				if(sizeof($alreadyDone)>=$this->nb_sql_string || $compteur>$this->debugLimit){ break ; }
				else
				{
					$alreadyDone[] = $newString ;
					mysql_query('INSERT INTO `ssl_form` (str_val) VALUES ("'.$newString.'")') ;
				}
				$compteur++;
			}
		}
	}
	/**
	 * Génération d'une chaine aléatoire de longueur $nb_car
	 *
	 * @param integer $nb_car
	 * @return string
	 */
	function GenPassword($nb_car)
	{
		$chaine  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$returnString = '' ;
		while($nb_car != 0) {
			$i = rand(0,strlen($chaine)-1);
			$returnString .= $chaine[$i];
			$nb_car--;
		}
		return $returnString ;
	}
	/**
	 * Création du formulaire
	 *
	 * @return array [0] => image PNG + champ caché [1] => champ HTML texte de rectitution
	 */
	function generate()
	{
		// On reprends la meme image que celle définie en postData apres une erreur de saisie de donnée
		if(!$this->bad_code)
		{
			$query = mysql_query('SELECT str_id FROM `ssl_form` ORDER BY rand() LIMIT 1') ;
			$get = mysql_fetch_array($query,MYSQL_NUM);
			$img_id = $get[0] ;
		} else $img_id = intval($_POST[$this->hidden_str_id]) ;
		$image = '<input type="hidden" name="'.$this->hidden_str_id.'" value="'.$img_id.'"><img align="absmiddle" border="0" src="'.$this->img_file_template.'?'.$this->hidden_str_id.'='.$img_id.'">' ;
		$inputText = '<input type="text" name="'.$this->hidden_str_val.'">' ;
		return array($image,$inputText) ;
	}

	function check()
	{
		$str_id		= $_POST[$this->hidden_str_id] ;
		$str_val	= $_POST[$this->hidden_str_val] ;
		// Verification SQL
		$check = mysql_query('SELECT * FROM `ssl_form` WHERE str_id="'.$str_id.'" AND str_val="'.$str_val.'" LIMIT 1') or die(mysql_error());
		if(mysql_num_rows($check)==1)
		{
			mysql_query('DELETE FROM `ssl_form` WHERE str_id="'.$str_id.'" LIMIT 1') ;
			return true ;
		}else { $this->bad_code = true ; return false ; }
	}
	/**
	 * Construction de l'image dont l'id est $id
	 *
	 * @param integer $id
	 */
	function image()
	{
		$id = $_GET[$this->hidden_str_id] ;
		// Choix d'une string aléatoire
		$getString = mysql_query('SELECT str_val FROM `ssl_form` WHERE str_id="'.$id.'" LIMIT 1') ;
		$_string = mysql_fetch_array($getString,MYSQL_NUM);
		$string = $_string[0] ;
		// Création de l'image
		$image = imagecreatetruecolor($this->img_width,$this->img_height) ;
		// Couleurs de fond
		$bg_color = $this->html2rgb($this->img_bgcolor);
		$bg_coloration = imagecolorallocate($image, $bg_color[0], $bg_color[1], $bg_color[2]);
		imagefilledrectangle($image,0,0,$this->img_width,$this->img_height,$bg_coloration) ;
		// Couleur de texte
		$font_color = $this->html2rgb($this->img_fontcolor);
		$font_coloration = imagecolorallocate($image, $font_color[0], $font_color[1], $font_color[2]);
		// Ecriture de la string
		imagestring($image,5,18,7,$string,$font_coloration);
		// Headers
		header('Content-type: image/png') ;
		imagepng($image) ;
		imagedestroy($image);
	}
	/**
	 * conversion des couleurs HTML en RGB
	 *
	 * @param string $color
	 * @return array
	 */
	function html2rgb($color) {
		if (substr($color,0,1) == '#') $color = substr($color,1,6); // gestion du #...
		$tablo[0] = hexdec(substr($color, 0, 2));
		$tablo[1] = hexdec(substr($color, 2, 2));
		$tablo[2] = hexdec(substr($color, 4, 2));
		return $tablo;
	}
	/**
	 * Création d'une image complexe
	 *
	 */
	function image_v2()
	{
		// Espacement entre les lettres
		$espacement = 40 ;
		// Ide de l'image a créer
		$id = $_GET[$this->hidden_str_id] ;
		// Choix d'une string aléatoire
		$getString = mysql_query('SELECT str_val FROM `ssl_form` WHERE str_id="'.$id.'" LIMIT 1') ;
		$_string = mysql_fetch_array($getString,MYSQL_ASSOC);
		$chaine = $_string['str_val'] ;
		// type de flood
		$name = $_GET['name'];
		// nb de caractères
		$strlen = strlen($chaine);
		// taille de l'image ( width )
		$width = $strlen * $espacement + 20;
		$height = 60;
		// taille de chaque zone de couleur
		$widthColor = $width / 4;
		#
		// création
		$img = imagecreatetruecolor( $width, $height );
		// antialising, c'est plus bô! :-)
		imageantialias( $img, 1 );
		// couleur de départ
		$c1 = array( mt_rand( 200, 255), mt_rand( 200, 255), mt_rand( 200, 255) );
		// couleur finale
		$c2 = array( mt_rand( 70, 180), mt_rand( 70, 180), mt_rand( 70, 180) );
		// pas pour chaque composante de couleur
		$diffsColor = array( ( $c1[0] - $c2[0] ) / $widthColor, ( $c1[1] - $c2[1] ) / $widthColor, ( $c1[2] - $c2[2] ) / $widthColor );
		#
		$start = 0;
		$end = $widthColor;
		#
		for( $j = 0; $j < 4; $j++ ) // boucle pour chacune des 4 zones
		{
			$r = $j % 2 == 0 ? $c1[0] : $c2[0]; // composante r de départ
			$v = $j % 2 == 0 ? $c1[1] : $c2[1]; // idem v
			$b = $j % 2 == 0 ? $c1[2] : $c2[2]; // idem b
			#
			// création des lignes
			for( $i = $start; $i < $end; $i++ )
			{
				if( $j % 2 == 0 )
				{
					$r -= $diffsColor[0];
					$v -= $diffsColor[1];
					$b -= $diffsColor[2];
				}
				else
				{
					$r += $diffsColor[0];
					$v += $diffsColor[1];
					$b += $diffsColor[2];
				}
				#
				$color = imagecolorallocate( $img, $r, $v, $b );
				#
				imageline( $img, $i, 0, $i, $height, $color );
			}
			#
			$start += $widthColor;
			$end += $widthColor;
		}
		#
		$colorsChar = array(); // on va mémoriser les couleurs des caractères
		// Chemin pour la liste des polices GD
		putenv('GDFONTPATH=' . realpath('.'));
		// caractères
		for( $i = 0; $i < $strlen; $i++ )
		{
			$colorsChar[$i] = imagecolorallocate( $img, mt_rand( 0, 120 ), mt_rand( 0, 120 ), mt_rand( 0, 120 ) );
			imagettftext( $img, mt_rand( 20, 25 ), mt_rand( -30, 30 ), 10 + $i * $espacement, 35, $colorsChar[$i], $this->font_family, $chaine[ $i ] );
		}
		#
		// quelques lignes qui embêtent
		/*for( $i = 0; $i < 10; $i++ )
		{
			imageline( $img, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $colorsChar[mt_rand( 0, $strlen - 1 )] );
		}*/
		#
		$noir = imagecolorallocate( $img, 0, 0, 0 );
		#
		// bordure
		imageline( $img, 0, 0, $width, 0, $noir );
		imageline( $img, 0, 0, 0, $height, $noir );
		imageline( $img, $width - 1, 0, $width - 1, $height, $noir );
		#
		// header: image
		header("Content-type: image/png");
		imagepng( $img );
		imagedestroy( $img );
	}
}
?>