<? 

/* BLOCS ID 

	1  	Se connecter 
	2 	Newsletter
	3 	Actualité du mois
	4 	Interview du mois
	6 	Boite à outil
	7 	Repères
	8 	Agenda
	9 	[MENU] Sous-rubriques
	10 	Actualités liste articles
	11 	Interview liste
	
*/


// ------------------------- TEMPLATE BLOCS PAR DEFAUT ----------------------------------//
// Fetch all blocs RELATED to a rub
function getColonneBloc($table_bloc, $table_encart, $rid) {
	$C =& new Q("SELECT prod_id AS bloc_id FROM $table_bloc WHERE cat_id='$rid' ORDER BY ordre ASC");
	foreach($C->V as $V) {
		switch($V['bloc_id']) {
			case 1: getBlocLogin(); break; // Se connecter
			case 2: getBlocNewsletter();  break;// Newsletter
			case 3: getBlocActu(); break; // Actu à la une
			case 4: getBlocInterview(); break; // Interview à la une
			case 6: getBlocBoiteOutil(); break; // Boite à outil
			case 7: getBlocReperesListe(); break; // Repères
			case 8: break; // Agenda
			case 9: getBlocMenu(); break;
			case 10: getBlocActuListe(); break; // Actu à la une
			case 11: getBlocInterviewListe(); break; // Interview à la une
			default: break;
		}
	}
	
	$C =& new Q("SELECT id FROM $table_encart WHERE cat_id='$rid' ORDER BY ordre ASC");
	foreach($C->V as $V) getBlocPub($C['id']);
}
// Fetch info for this bloc (title, ...)
function getBlocInfo($bloc_id) {
	$R =& new Q("SELECT * FROM blocs_de_colonne WHERE id='$bloc_id' LIMIT 1");
	return (array)$R->V[0];
}

// Template bloc header
function getBlocHead($box_titre='Menu sans nom', $box_color='1', $padding='10') {
	?><table width="237" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td height="34" valign="bottom" class="boxHeadBg<?=$box_color;?>"><table width="220" border="0" align="right" cellpadding="0" cellspacing="0">
	<tr>
	<td height="23"><strong class="boxHead"><?=$box_titre;?></strong></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td><table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td width="1" height="100%"><img src="images/images/box1_main_g.png" alt="" width="11" height="100%"></td>
	<td valign="top" background="images/images/box1_main_c.png"><table width="100%" border="0" align="center" cellpadding="<?=$padding?>" cellspacing="0">
	<tr>
	<td class="box1_texte"><?
}
// Template bloc foot
function getBlocFoot() { 
	?></td>
	</tr>
	</table></td><td width="1" height="100%"><img src="images/images/box1_main_d.png" alt="" width="11" height="100%"></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td height="38"><img src="images/images/box1_foot.png" alt="" width="237" height="38"></td>
	</tr>
	</table><?
}

// ------------------------- TEMPLATES BLOCS COLONNE ----------------------------------//


// SOUS-MENU des RUBRIQUE
function getBlocMenu() {
	
	global $S;

	$childMenuHtml = '';
	if (!empty($S->arbo[$S->rid]['childs'])) {
		$childMenuHtml = $S->getChildMenuHtml($S->rid);
		getBlocHead($S->arbo[$S->rid]['titre'], $S->arbo[$S->rid]['couleur'], '10'); 
	}
	else if (!empty($S->arbo[$S->prid]['childs'])) {
		$childMenuHtml = $S->getChildMenuHtml($S->prid);
		getBlocHead($S->arbo[$S->prid]['titre'], $S->arbo[$S->prid]['couleur'], '10'); 
	}
	else if (!empty($S->arbo[$S->rrid]['childs'])) {
		$childMenuHtml = $S->getChildMenuHtml($S->rrid);
		getBlocHead($S->arbo[$S->rrid]['titre'], $S->arbo[$S->rrid]['couleur'], '10'); 
	}
	else if (!empty($S->arbo[$S->arid]['childs'])) {
		$childMenuHtml = $S->getChildMenuHtml($S->arid);
		getBlocHead($S->arbo[$S->arid]['titre'], $S->arbo[$S->arid]['couleur'], '10'); 
	}
	echo $childMenuHtml;

	getBlocFoot(); 
}

function getBlocLogin() { 
	
	?><table width="237" height="164" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td background="" id="t_login"><?=form('login_form', '_actions.php?action=LOGIN', false);?>
	<script language="javascript" type="text/javascript">
	// <![CDATA[
	loginSubmit = function() {
		
		return printInfo(TRAVAUX);
		
		if ($('login_pseudo').value == 'IDENTIFIANT') $('login_pseudo').value = '';
		if ($('login_password').value == 'MOTDEPASSE') $('login_password').value = '';
		param_login = { mep: 'alerte', autoScroll: false, action: 'submit'};
		champs_login = {
			login_pseudo: {alerte:'Le champ <strong>identifiant</strong> est obligatoire'},
			login_password: {alerte:'Le champ <strong>mot de passe</strong> est obligatoire'}
		};
		formVerif('login_form', champs_login, param_login);
	}
	// ]]>
	</script>
	
	<input name="login_pseudo" id="login_pseudo" type="text" value="<?=($_SESSION[SITE_CONFIG]['LOGIN']['pseudo']!=''? $_SESSION[SITE_CONFIG]['LOGIN']['pseudo']:'IDENTIFIANT');?>" onfocus="if(this.value==defaultValue)this.value='';" onBlur="if(this.value=='')this.value=defaultValue;" maxlength="100"/>
	<br />
	<input name="login_password" id="login_password" type="password" value="<?=($_SESSION[SITE_CONFIG]['LOGIN']['mdp']!='' ? $_SESSION[SITE_CONFIG]['LOGIN']['mdp']:'MOTDEPASSE');?>" onfocus="if(this.value=='MOTDEPASSE')this.value='';enterEvent('login_password',loginSubmit);" onBlur="if(this.value=='')this.value='MOTDEPASSE';"  maxlength="30"/>
	<br />
	<a href="javascript:void(0);">Mot de passe oubli&eacute; ?</a><br />
	<a href="javascript:void(0);">Nouvel utilisateur</a>
	<?=formE();?></td>
	</tr>
	</table><?
	
}

function getBlocNewsletter() { 
	
	$bloc = getBlocInfo(2);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10'); 
	
	?><?=form('newsletter_form', 'index.php?action=NEWSLETTER', false);?>
	<script language="javascript" type="text/javascript">
	// <![CDATA[
	loginSubmit = function() {
		if ($('newsletter_email').value == 'email@email.com') $('newsletter_email').value = '';
		param_login = { mep: 'alerte', autoScroll: false, action: 'submit'};
		champs_login = {
			newsletter_email: {type:'mail',alerte:'Le champ <strong>e-mail</strong> est obligatoire'}
		};
		formVerif('newsletter_form', champs_login, param_login);
	}
	// ]]>
	</script>
	<input name="newsletter_email" id="newsletter_email" type="text" value="<?=($_SESSION[SITE_CONFIG]['LOGIN']['pseudo']!=''? $_SESSION[SITE_CONFIG]['NEWSLETTER']['newsletter_email']:'email@email.com');?>" onfocus="if(this.value==defaultValue)this.value='';enterEvent('newsletter_email',loginSubmit);" onBlur="if(this.value=='')this.value=defaultValue;" maxlength="100"/><br />
	Inscrivez vous &agrave; notre newsletter !
	<?=formE();?><?
	
	getBlocFoot(); 
	
}

function getBlocActu() {
	
	global $S;
	
	$A =& new Q("SELECT * FROM mod_actus WHERE une='1' LIMIT 1");

	$actuRubRid = $S->getRidByModule(4);
	$articleUrl = urlRewrite($S->arbo[$actuRubRid]['titre'].'-'.$A->V[0]['titre'], 'r'.$actuRubRid.'ac'.$A->V[0]['id']);
	
	$bloc = getBlocInfo(3);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10'); 

	?><h3><?=aff($A->V[0]['titre']);?></h3>
	<p class="c1"><?=cs(strip_tags(aff($A->V[0]['texte'])), 220);?><br />
	<a href="<?=$articleUrl;?>" class="lire">--> Lire la suite</a></p>
	<p align="center"><a href="<?=$articleUrl;?>"><img src="images/images/btn_voir-actu.png" width="148" height="22" border="0" alt=""/></a></p><? 
	getBlocFoot(); 
}

function getBlocActuListe() {
	
	global $S;
	
	$bloc = getBlocInfo(10);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10'); 

	$A =& new Q("SELECT id, titre, date FROM mod_actus WHERE statut='2' ORDER BY date DESC"); //AND une='0' 
	
	$actuRubRid = $S->getRidByModule(4);
	foreach($A->V as $V) {
		$articleUrl = urlRewrite($S->arbo[$actuRubRid]['titre'].'-'.$V['titre'], 'r'.$actuRubRid.'ac'.$V['id']);
		?><p><span class="c8"><?=printDateTime($V['date'],4);?><br /></span>
		<a href="<?=$articleUrl;?>" class="texte c9"><?=aff($V['titre']);?></a></p><?
		
	}
	getBlocFoot(); 
}

function getBlocInterview($id=0) {
	global $S;
	$where = ($id > 0 ? " id='$id' " : "une='1' ");
	$A =& new Q("SELECT * FROM mod_actus WHERE $where LIMIT 1");
	
	if (empty($A->V[0]['interview_texte'])) return '';
	
	$bloc = getBlocInfo(4);
	getBlocHead($A->V[0]['interview_titre'], $bloc['couleur'], '10'); 
	
	$m =& new FILE();
	$interview_visuel = ($m->isMedia('medias/actus/mini/'.$A->V[0]['interview_visuel']) ? $m->image(FALSE).'<br />' : '');
	
	$actuRubRid = $S->getRidByModule(4);
	$interviewUrl = urlRewrite($S->arbo[$actuRubRid]['titre'].'-'.$A->V[0]['titre'], 'r'.$actuRubRid.'ac'.$A->V[0]['id']);
	
	?><?=$interview_visuel;?>
	<div class="texte" style="color:#000000;"><br />
	<?=printDateTime($A->V[0]['interview_date'],4);?><br />
	<?=aff($A->V[0]['interview_nom']);?><br />
	<?=aff($A->V[0]['interview_fonction']);?></div>
	<p class="c1"><?=quote(aff($A->V[0]['interview_texte']));?><br /><? 
	
	getBlocFoot(); 
}

function getBlocInterviewListe() {
	
	global $S;

	$bloc = getBlocInfo(11);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10'); 

	$A =& new Q("SELECT id, interview_nom, interview_fonction, interview_date FROM mod_actus WHERE statut='2' AND interview_texte!='' ORDER BY date DESC");

	$actuRubRid = $S->getRidByModule(4);
	foreach($A->V as $V) {
		$articleUrl = urlRewrite($S->arbo[$actuRubRid]['titre'].'-'.$V['interview_nom'], 'r'.$actuRubRid.'ac'.$V['id']);
		?><p><span class="c8"><?=printDateTime($V['interview_date'],4);?><br /></span>
		<a href="<?=$articleUrl;?>" class="texte c9"><?=aff($V['interview_nom'].', '.$V['interview_fonction']);?></a></p><?
		
	}
	getBlocFoot(); 
}

/*function getBlocReperes() {
	
	$bloc = getBlocInfo(7);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10');
	
	?><h3>Le droit individuel &agrave; la formation</h3>
	<p class="c1">Ce droit permet &agrave; tout salari&eacute; en contrat &agrave; dur&eacute;e ind&eacute;termin&eacute;e, ayant au moins un an d'anciennet&eacute; dans l'entreprise, d'acqu&eacute;rir chaque ann&eacute;e un droit &agrave; la formation d'une dur&eacute;e cumulable sur 6 ans.<br />
	<a href="javascript:void(0);" class="lire">--> Lire la suite</a></p>
	<p><a href="http://www.opapl.com/reforme/mdif.html" target="_blank" class="lien">www.opapl.com/reforme/</a></p>
	<p align="center"><img src="images/images/btn_voir-actu.png" width="148" height="22"></p><? 
	
	getBlocFoot(); 
}*/

function getBlocReperesListe() {
	
	global $S;

	$bloc = getBlocInfo(7);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10'); 

	$A =& new Q("SELECT id, titre FROM mod_reperes WHERE actif='1' ORDER BY cat_id DESC, ordre DESC");

	$repRubRid = $S->getRidByModule(22);
	foreach($A->V as $V) {
		$repRubUrl = urlRewrite($S->arbo[$repRubRid]['titre'].'-'.$V['titre'], 'r'.$repRubRid.'rep'.$V['id']);
		?><p><a href="<?=$repRubUrl;?>" class="texte c9"><?=aff($V['titre']);?></a></p><?

	}
	getBlocFoot(); 
}


function getBlocAgenda() {
	
	$bloc = getBlocInfo(8);
	getBlocHead($bloc['titre'], $bloc['couleur'], '10');
	
	?><h3>Le droit individuel &agrave; la formation</h3>
	<p class="c1">Ce droit permet &agrave; tout salari&eacute; en contrat &agrave; dur&eacute;e ind&eacute;termin&eacute;e, ayant au moins un an d'anciennet&eacute; dans l'entreprise, d'acqu&eacute;rir chaque ann&eacute;e un droit &agrave; la formation d'une dur&eacute;e cumulable sur 6 ans.<br />
	<a href="javascript:void(0);" class="lire">--> Lire la suite</a></p>
	<p><a href="http://www.opapl.com/reforme/mdif.html" target="_blank" class="lien">www.opapl.com/reforme/</a></p>
	<p align="center"><img src="images/images/btn_voir-actu.png" alt="" width="148" height="22"></p><? 
	
	getBlocFoot(); 
}

function getBlocBoiteOutil() {
	global $S;
	
	$bloc = getBlocInfo(6);
	getBlocHead($bloc['titre'], $bloc['couleur'], '0');
	
	if (!empty($S->arbo[$S->getRidByModule(6)]['url'])) {
	?><br /><a href="<?=$S->arbo[$S->getRidByModule(6)]['url'];?>"><img src="images/images/btn_fiches.png" alt="" width="209" height="42" border="0"></a><br /><?
	} if (!empty($S->arbo[$S->getRidByModule(22)]['url'])) {
	?><a href="<?=$S->arbo[$S->getRidByModule(22)]['url'];?>"><img src="images/images/btn_reperes.png" alt="" width="209" height="45" border="0"></a><br /><?
	} if (!empty($S->arbo[$S->getRidByModule(7)]['url'])) {
	?><a href="<?=$S->arbo[$S->getRidByModule(7)]['url'];?>"><img src="images/images/btn_formation.png" alt="" width="209" height="43" border="0"></a><br /><?
	} if (!empty($S->arbo[$S->getRidByModule(14)]['url'])) {
	?><a href="<?=$S->arbo[$S->getRidByModule(14)]['url'];?>"><img src="images/images/btn_recrutement.png" alt="" width="209" height="44" border="0"></a><br /><?
	} if (!empty($S->arbo[$S->getRidByModule(2)]['url'])) {
	?><a href="<?=$S->arbo[$S->getRidByModule(2)]['url'];?>"><img src="images/images/btn_cv.png" alt="" width="209" height="49" border="0"></a><?
	}
	
	getBlocFoot();
}

function getBlocPub($pub_id) { 
	
	$bloc = getBlocInfo(2);
	//getBlocHead($bloc['titre'], $bloc['couleur'], '0');
// $image,$lien
	//'image','lien'

	?>&nbsp;<table width="237" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align="center"><a href="javascript:void(0);" target="_blank"><img src="medias/pubs/medium/pub1.jpg" alt="" width="216" height="330" border="0"></a></td>
	</tr>
	</table><?
}
?>