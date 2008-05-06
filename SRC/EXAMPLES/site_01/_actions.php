<?
// Astuce : ce fichier peut etre inclus en "require" ou appellé directement par ajax
if (!isset($WWW)) {
	require_once 'admin/lib/racine.php';
	setIsoHeader();
}

switch($action) {

	case 'NOLOGIN' : // DESESSIONNISATION --------------------------------------------------------- //
		
		$_SESSION[SITE_CONFIG]['CLIENT'] = NULL; // Reset
		delMyCookie('CLIENTIDS'); // Del COOKIE
		goto('index.php');
		
	break;

	case 'LOGIN' : // AJAX -LOGIN CLIENT --------------------------------------------------------- //
		
		// Get Values
		$_SESSION[SITE_CONFIG]['LOGIN']['pseudo'] = $pseudo = cleanAjax($_POST['pseudo']);
		$_SESSION[SITE_CONFIG]['LOGIN']['mdp'] = $mdp = cleanAjax($_POST['mdp']);
		
		// Check values
		//if (!checkRef()) $info .= "<strong>Nous ne parvenons pas a v&eacute;rifier votre url d\'acc&eacute;s</strong><br />";
		if (empty($pseudo)) $info .= "Le champ <strong>identifiant</strong> est obligatoire<br />";
		if (empty($mdp)) $info .= "Le champ <strong>mot de passe</strong> est obligatoire<br />";

		// Si erreur
		if ($info != '') {
			require('_login.php');
			die(js(' printInfo("'.str_replace('"','\"',$info).'"); '));
		}
		
		// Check Client exist
		$A = new Q(" SELECT id,ids FROM mod_ec_auteurs WHERE pseudo='$pseudo' AND mdp='$mdp' AND actif=1 LIMIT 1 ");
		if ($A->V[0]['id'] > 0) { // FOUND
			$client_id = $A->V[0]['id'];
			initClientSession($client_id);
			
			// If cookie
			if (isset($_POST['cook'])) { // Présence case a cocher "COOK" sur le formulaire ???
				$ids = genPass().genPass().genPass();// Regenerate IDS...
				$U = new SQL('mod_ec_auteurs');
				$U->updateSql(array(array('ids',$ids))," id='$client_id' LIMIT 1 ");
				$cookie_val = serialize(array(
					'client_id' => $client_id,
					'ids' => $ids
				));
				setMyCookie('CLIENTIDS', $cookie_val);
			}

			// Fill & Reset Vars
			$_SESSION[SITE_CONFIG]['LOGIN'] = NULL;
			$fromActionSuccess = true;
			
			// Print Result
			require('_logge.php');
			die(js('redir();'));
			
		}
		else { // NO FOUND
			$A = new Q(" SELECT id FROM mod_ec_auteurs WHERE pseudo='$pseudo' AND mdp='$mdp' LIMIT 1 ");
			if ($A->nb == 1) $info .= "<strong>D&eacute;sol&eacute;, votre compte n\'est pas activ&eacute;, v&eacute;rifiez vos emails</strong><br />";
			else {
				$A = new Q(" SELECT pseudo,mdp FROM mod_ec_auteurs WHERE pseudo='$pseudo' OR mdp='$mdp' LIMIT 1 ");
				if ($A->V[0]['pseudo'] == $pseudo) $info .= "Votre  <strong>mot de passe</strong> ne correspond pas &agrave; votre <strong>identifiant</strong> !<br />";
				else if ($A->V[0]['mdp'] == $mdp) $info .= "Votre <strong>identifiant</strong> ne correspond pas &agrave; votre <strong>mot de passe</strong> !<br />";
				else $info .= "<strong>D&eacute;sol&eacute;, vos informations ne correspondent  &agrave; aucun compte</strong><br />";
			}
			require('_login.php');
			die(js(' printInfo("'.str_replace('"','\"',$info).'"); '));
		}
	break;

	case 'INSCRIPTION' :
	
		// Get Values
		$_SESSION[SITE_CONFIG]['INSCRIPTION']['pseudo'] = $pseudo = cleanAjax($_POST['pseudo']);
		$_SESSION[SITE_CONFIG]['INSCRIPTION']['email'] = $email = cleanAjax($_POST['email']);
		$_SESSION[SITE_CONFIG]['INSCRIPTION']['annee'] = $annee = intval(cleanAjax($_POST['annee']));
		$_SESSION[SITE_CONFIG]['INSCRIPTION']['mdp'] = $mdp = cleanAjax($_POST['mdp']);
		$_SESSION[SITE_CONFIG]['INSCRIPTION']['mdp_2'] = $mdp_2 = cleanAjax($_POST['mdp_2']);
		
		// Check values
		//if (!checkRef()) $info .= "<strong>Nous ne parvenons pas a v&eacute;rifier votre url d\'acc&eacute;s</strong><br />";
		if (empty($pseudo)) $info .= "Le champ <strong>identifiant</strong> est obligatoire<br />";
		if (!checkMail($email)) $info .= "Le champ <strong>e-mail</strong> est obligatoire et doit &ecirc;tre valide<br />";
		if (empty($annee) || $annee < 1) $info .= "Le champ <strong>ann&eacute;e de naissance</strong> est obligatoire<br />";
		if (empty($mdp)) $info .= "Le champ <strong>mot de passe</strong> est obligatoire<br />";
		elseif ($mdp != $mdp_2) $info .= "Les <strong>deux mots de passe</strong> ne correspondent pas<br />";
		
		$E =& new Q(" SELECT email FROM mod_ec_auteurs WHERE email='$email' LIMIT 1 ");
		if ($E->V[0]['email'] != '') $info .= "<strong>D&eacute;sol&eacute; cet e-mail existe d&eacute;j&agrave;</strong><br />";
		$E =& new Q(" SELECT pseudo FROM mod_ec_auteurs WHERE pseudo='$pseudo' LIMIT 1 ");
		if ($E->V[0]['pseudo'] != '') $info .= "<strong>D&eacute;sol&eacute; ce pseudo existe d&eacute;j&agrave;</strong><br />";
		
		// Si erreur
		if ($info != '') {
			require('_inscription.php');
			die(js(' printInfo("'.str_replace('"','\"',$info).'"); '));
		}
		
		// Fill default values
		$ids = '';
		$actif = 1;
		$membre_une = 0;
		$video_une = 0;
		$adulte = ( (date("Y") - $annee) <= 13 ? '0' : '1');
		//$pseudo = '';
		//$email = '';
		//$mdp = '';
		$date = getDateTime();
		$nom = '';
		$prenom = '';
		$sexe = 'x';
		$profession = '';
		$pays_id = '';
		//$annee = '';
		$age_aff = '0';
		$description_fr = '';
		$avatar = '';
		$video = '';
		$nom_site = '';
		$url_site = '';
		$rss_feed = '';
		$description_blog_fr = '';
		$visuel_site = '';
		$top_blog = 0;
		
		// Insert BDD
		$champs = array(
			array('ids', 'actif', 'membre_une', 'video_une', 'adulte', 'pseudo', 'email', 'mdp', 'date', 'nom', 'prenom', 'sexe', 'profession', 'pays_id', 'annee', 'age_aff', 'description_fr', 'avatar', 'video', 'nom_site', 'url_site', 'rss_feed', 'description_blog_fr', 'visuel_site','top_blog'),
			array($ids, $actif, $membre_une, $video_une, $adulte, $pseudo, $email, $mdp, $date, $nom, $prenom, $sexe, $profession, $pays_id, $annee, $age_aff, $description_fr, $avatar, $video, $nom_site, $url_site, $rss_feed, $description_blog_fr, $visuel_site, $top_blog)
		);
		$A = new SQL('mod_ec_auteurs');
		$A->insertSql($champs,'1');
		$client_id = $A->id;

		initClientSession($client_id);

		// Stats...
		$A =& new Q(" UPDATE parametres SET nb_membres=(nb_membres+1) WHERE id=1 LIMIT 1 ");

		// Envoie du mail
		$email_admin = fetchValue('email');

		// Fetch Alerte Email
		list($suject_email, $texte_email) = fetchAlerte(2, array('#pseudo'=>$pseudo, '#mdp'=>$mdp), $lg);
		if (!empty($message)) $texte_email .= '<hr />'.$message;

		mailto($email, $email_admin, $suject_email, '', $texte_email, $email_admin);
		mailto($email_admin, $email, $suject_email, '', $texte_email, $email_admin);
		
		// Clean vars
		$_SESSION[SITE_CONFIG]['INSCRIPTION'] = NULL;
		$_SESSION[SITE_CONFIG]['categories'] = NULL;
		$fromActionSuccess = TRUE;
		
		require('_inscription.php');
	
	break;
	
	case 'OUBLIE' :
	
		// Get Values
		$_SESSION[SITE_CONFIG]['OUBLIE']['email'] = $email = cleanAjax($_POST['email4']);
		
		
		// Check values
		//if (!checkRef()) $info .= "<strong>Nous ne parvenons pas a v&eacute;rifier votre url d\'acc&eacute;s</strong><br />";
		if (!checkMail($email)) $info .= "Le champ <strong>e-mail</strong> est obligatoire et doit &ecirc;tre valide<br />";

		$E =& new Q(" SELECT id, pseudo, email, mdp FROM mod_ec_auteurs WHERE email='$email' LIMIT 1 ");
		if ($E->V[0]['email'] == '') $info .= "<strong>D&eacute;sol&eacute; cet e-mail n\'existe pas dans notre base</strong><br />";

		// Si erreur
		if ($info != '') {
			require('_popup_mdp.php');
			die(js(' printInfo("'.str_replace('"','\"',$info).'"); '));
		}
		
		// Parse param
		$id = $E->V[0]['id'];
		$pseudo = $E->V[0]['pseudo'];
		$mdp = $E->V[0]['mdp'];
		$email = $E->V[0]['email'];

		// Envoie du mail
		$email_admin = fetchValue('email');
		
		// Fetch Alerte Email
		list($suject_email, $texte_email) = fetchAlerte(3, array('#pseudo'=>$pseudo, '#email'=>$email, '#mdp'=>$mdp), $lg);
		if (!empty($message)) $texte_email .= '<hr />'.$message;

		mailto($email, $email_admin, $suject_email, '', $texte_email, $email_admin);
		
		// Clean vars
		$_SESSION[SITE_CONFIG]['OUBLIE'] = NULL;
		$fromActionSuccess = TRUE;
		
		require('_popup_mdp.php');
	
	break;

	case 'COMPTE' : // AJAX SUBMIT // EMAIL TOP BLOG TO DO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
		if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) alert('Vous n\'&ecirc;tes pas connect&eacute;', '', 'alert'); 
		
		// Get Values
		$mdp = cleanAjax($_POST['mdp']);
		$mdp_2 = cleanAjax($_POST['mdp_2']);
		$email = cleanAjax($_POST['email']);

		$_SESSION[SITE_CONFIG]['COMPTE']['nom'] = $nom = cleanAjax($_POST['nom']);
		$_SESSION[SITE_CONFIG]['COMPTE']['prenom'] = $prenom = cleanAjax($_POST['prenom']);
		
		$_SESSION[SITE_CONFIG]['COMPTE']['sexe'] = $sexe = cleanAjax($_POST['sexe']);
		$_SESSION[SITE_CONFIG]['COMPTE']['annee'] = $annee = intval(cleanAjax($_POST['annee']));
		$_SESSION[SITE_CONFIG]['COMPTE']['profession'] = $profession = cleanAjax($_POST['profession']);
		$_SESSION[SITE_CONFIG]['COMPTE']['description'] = $description = cleanAjax($_POST['description']);
		$_SESSION[SITE_CONFIG]['COMPTE']['pays_id'] = $pays_id = cleanAjax($_POST['pays_id']);

		$_SESSION[SITE_CONFIG]['COMPTE']['nom_site'] = $nom_site = cleanAjax($_POST['nom_site']);
		$_SESSION[SITE_CONFIG]['COMPTE']['url_site'] = $url_site = cleanAjax($_POST['url_site']);
		$_SESSION[SITE_CONFIG]['COMPTE']['rss_feed'] = $rss_feed = cleanAjax($_POST['rss_feed']);
		$_SESSION[SITE_CONFIG]['COMPTE']['description_blog'] = $description_blog = cleanAjax($_POST['description_blog']);
		$_SESSION[SITE_CONFIG]['COMPTE']['top_blog'] = $top_blog = cleanAjax($_POST['top_blog']);
		$_SESSION[SITE_CONFIG]['COMPTE']['visuel_site'] = $visuel_site = $_FILES['visuel_site'];
		
		// Check values
		//if (!checkRef()) $info .= "<strong>Nous ne parvenons pas a v&eacute;rifier votre url d\'acc&eacute;s</strong><br />";
		if (!checkMail($email)) $info .= "Le champ <strong>e-mail</strong> est obligatoire et doit &ecirc;tre valide<br />";
		if (empty($annee) || $annee < 1) $info .= "Le champ <strong>ann&eacute;e de naissance</strong> est obligatoire<br />";
		if (empty($mdp)) $info .= "Le champ <strong>mot de passe</strong> est obligatoire<br />";
		elseif ($mdp != $mdp_2) $info .= "<strong>Les deux mots de passe ne correspondent pas</strong><br />";

		$E =& new Q(" SELECT email FROM mod_ec_auteurs WHERE email='$email' AND id!={$_SESSION[SITE_CONFIG]['CLIENT']['id']} LIMIT 1 ");
		if ($E->V[0]['email'] != '') $info .= "D&eacute;sol&eacute; cet e-mail existe d&eacute;j&agrave;<br />";

		// Si erreur
		if ($info != '') {
			require('compte/_profil-update.php');
			die(js(' printInfo("'.str_replace('"','\"',$info).'"); '));
		}
		
		// Fill default values
		
		// Update BDD
		$champs = array(
			array('email', $email),
			array('mdp', $mdp),
			array('nom', $nom),
			array('prenom', $prenom),
			array('sexe', $sexe),
			array('profession', $profession),
			array('pays_id', $pays_id),
			array('annee', $annee),
			array('age_aff', $age_aff),
			array('description_'.$lg, $description),
			//array('avatar', $avatar),
			//array('video', $video),
			array('nom_site', $nom_site),
			array('url_site', $url_site),
			array('rss_feed', $rss_feed),
			array('description_blog_'.$lg, $description_blog),
			array('top_blog', $top_blog),
			//array('visuel_site', $visuel_site),
		);
		$A = new SQL('mod_ec_auteurs');
		$A->updateSql($champs," id='{$_SESSION[SITE_CONFIG]['CLIENT']['id']}' LIMIT 1 ");
		
		// MAJ
		initClientSession($_SESSION[SITE_CONFIG]['CLIENT']['id']);
		
		// Clean vars
		$_SESSION[SITE_CONFIG]['COMPTE'] = NULL;
		$fromActionSuccess = TRUE;
		
		require('compte/_profil-update.php');
		$info = 'Votre profil a bien &eacute;t&eacute; mis &agrave; jour';
		
		die(js(' printInfo("'.str_replace('"','\"',$info).'"); '));
		
	break;
	
	
	
	case 'SENDAMI': // Call From Ajax
		
		// Get Values
		$cat_id = intval(gpc('cat_id'));
		$_SESSION[SITE_CONFIG]['SENDAMI']['nom_prenom'] = 	$nom_prenom = cleanAjax($_POST['nom_prenom']);
		$_SESSION[SITE_CONFIG]['SENDAMI']['email'] = 		$email = cleanAjax($_POST['email']);
		$_SESSION[SITE_CONFIG]['SENDAMI']['email_dest'] = 	$email_dest = cleanAjax($_POST['email_dest']);
		$_SESSION[SITE_CONFIG]['SENDAMI']['message'] = 	$message = clean(htmlentities(html_entity_decode(make_iso(nl2br($_POST['message'])))));
		$_SESSION[SITE_CONFIG]['SENDAMI']['idee_id'] = 	$idee_id = cleanAjax($_POST['idee_id']);

		// Check values
		//if (!checkRef()) $info .= '<strong>Nous ne parvenons pas a v&eacute;rifier votre url d\'acc&eacute;s</strong><br />';
		if (time() - $_SESSION[SITE_CONFIG]['actiontime'] < 10) $info .= "<strong>Veuillez patienter avant de reposter</strong><br />";
		if (empty($nom_prenom )) $info .= 'Le champ <strong>nom et pr&eacute;nom</strong> est obligatoire<br />';
		if (!checkMail($email)) $info .= 'Le champ <strong>email</strong> est obligatoire et l\'email doit &ecirc;tre valide<br />';
		if (!checkMail($email_dest)) $info .= 'Le champ <strong>email destinataire</strong> est obligatoire et l\'email doit &ecirc;tre valide<br />';
		
		// Get more values
		for ($i=2; $i<5; $i++) {
			if (empty($_POST['email_dest'.$i])) continue;
			if (!checkMail($_POST['email_dest'.$i])) $info .= "Veuillez v&eacute;rifiez <strong>l'email votre ami(e)</strong> : ".aff(clean($_POST['email_dest'.$i]))."<br />";
			else $email_dest .= ';'.clean(urldecode(make_iso($_POST['email_dest'.$i])));
		}
		
		// Erreur ?
		if ($info != '') {
			require 'contribution/_envoyer_ami.php';
			die(js(' printInfo("'.str_replace('"','\"',$info).'"); ',false));
		}
		
		// Parse Params
		$url = $WWW.'index.php?goto=contribution_detail&idee_id='.$idee_id;
		$url = '<a href="'.$url.'" target="_blank">'.$url.'</a>';
		
		// Insert BDD
		/*$A = new SQL('mailinglist');
		$A->LireSql(array('id')," email='$email' LIMIT 1 ");
		if (count($A->V) < 1) {
			$champs = array(array('actif', 'email', 'nom'),array('1', $email, $nom));	
			$A = new SQL('mailinglist');
			$A->insertSql($champs,'1');
		}*/
		
		// Fetch Alerte Email
		list($suject_email, $texte_email) = fetchAlerte(1, array('#nomprenom'=>$nom_prenom, '#lien'=>$url), $lg);
		if (!empty($message)) $texte_email .= '<hr />'.$message;
		
		mailto($email, $email_dest, $suject_email, '', $texte_email);
		
		// Fill & Reset Vars
		$_SESSION[SITE_CONFIG]['actiontime'] = time();
		$_SESSION[SITE_CONFIG]['SENDAMI'] = NULL;
		$fromActionSuccess = true;
		
		// Print Result
		require 'contribution/_envoyer_ami.php';
		die(js(' 
			 setTimeout( function () {
				Effect.Fade(\'envoi_ami_'.$idee_id.'\', {duration:0.4, afterFinish:function(effect) { Effect.BlindUp(effect.element, {duration:0.4}); }});
			}, 2000);
		',false));

	break;

	case 'CONTACT' : // AJAX
	
		// Get Values
		$_SESSION[SITE_CONFIG]['CONTACT']['nom'] = $nom = clean($_POST['contact_nom']);
		$_SESSION[SITE_CONFIG]['CONTACT']['prenom'] = $prenom = clean($_POST['contact_prenom']);
		$_SESSION[SITE_CONFIG]['CONTACT']['tel'] = $tel = clean($_POST['contact_tel']);
		$_SESSION[SITE_CONFIG]['CONTACT']['email'] = $email = clean($_POST['contact_email']);
		$_SESSION[SITE_CONFIG]['CONTACT']['societe'] = $societe = clean($_POST['contact_societe']);
		$_SESSION[SITE_CONFIG]['CONTACT']['sujet'] = $sujet = clean($_POST['contact_sujet']);
		$_SESSION[SITE_CONFIG]['CONTACT']['message'] = $message = clean($_POST['contact_message']);


		// Check values
		//if (!checkRef()) $info .= "Nous ne parvenons pas a v&eacute;rifier votre url d\'acc&eacute;s<br />";
		if (empty($nom)) $info .= "Le champ <strong>nom</strong> est obligatoire<br />";
		if (empty($prenom)) $info .= "Le champ <strong>pr&eacute;nom</strong> est obligatoire<br />";
		if (!checkMail($email)) $info .= "Le champ <strong>email</strong> est obligatoire et doit &ecirc;tre valide<br />";
		if (empty($message)) $info .= "Le champ <strong>message</strong> est obligatoire<br />";

		// Si erreur
		if ($info != '') break;
		
		// Insert BDD
		$champs = array(
			array('nom', 'prenom', 'tel', 'email', 'societe', 'sujet', 'message'),
			array($nom, $prenom, $tel, $email, $societe, $sujet, $message)
		);
		$A = new SQL('mod_contact');
		$A->insertSql($champs,'1');
		$thisId = $A->id;
		
		// Envoie du mail
		$email_admin = fetchValue('email');
		$subject = 'Nouveau contact de '.aff($nom).' '.aff($prenom).' ['.$SITE.']';
		$innerBody = '<br /><a href='.$WWW.'admin/mod_contact/index.php?mode=fiche&id='.$thisId.'>Voir la fiche dans l\'admin</a>';
		
		$innerBody .= "
<br /><br />
<b>Nom</b> : $nom<br />
<b>Pr&eacute;nom</b> : $prenom<br />
<b>T&eacute;l</b> : $tel<br />
<b>E-mail</b> : $email<br />
<b>Soci&eacute;t&eacute;</b> : $societe<br />
<b>Sujet</b> : $sujet<br />
<b>Message</b> : ".nl2br($message)."<br />
<br />
--<br />
<br />
Julien Gu&eacute;zennec<br />
D&eacute;veloppeur Internet Ind&eacute;pendant<br />
http://www.borntobeweb.fr<br />
<br />
Adresse : 8, rue Boucry, app. 1711, 75018 Paris<br />
E-mail : ".$email_admin."<br />
Portable : 06 61 75 64 98<br />
T&eacute;l : 01 76 67 93 73<br />
<br />
--";
		
		mailto($email, $email_admin, $subject, '', $innerBody, $email_admin);
		mailto($email_admin, $email, $subject, '', $innerBody, $email_admin);
		
		// Clean vars
		$_SESSION[SITE_CONFIG]['CONTACT'] = NULL;
		$info = "Votre message a bien &eacute;t&eacute; envoy&eacute;, merci";

	break;

} // Fin Action


$_SESSION[SITE_CONFIG]['info'] = $info;

?>