<?
set_time_limit(1800);
//include '../lib/pdfClasses/class.ezpdf.php';
//include_once '../lib/all.php';

$titre = 'titre_'.$langues[0];

// GET COMMANDE ////////////////////////////////////////////////////////////////////////////////////////////////
$req1 = mysql_query("SELECT * FROM commandes WHERE cmd_id='$cmd_id' LIMIT 1",$connexion) or die(mysql_error($connexion)); // $cmd_id
$Commandes = mysql_fetch_array($req1);
//$cmd_id = $Commandes['cmd_id'];
$ref = 'GB'.str_pad($cmd_id,6,'0',STR_PAD_LEFT);
$clt_id = $Commandes['clt_id'];

// Date crea
$datecrea = explode(' ',$Commandes['datecrea']); // 2005-01-04 16:44:10
list($a,$m,$j) = explode('-',$datecrea[0]);
$datecrea = $j.'/'.$m.'/'.$a; //.' @ '.$datecrea[1];

// Date cmd
$datecmd = explode(' ',$Commandes['datecmd']); // 2005-01-04 16:44:10
list($a,$m,$j) = explode('-',$datecmd[0]);
$datecmd = $j.'/'.$m.'/'.$a; //.' @ '.$datecmd[1];

// GET CLIENT
$req3 = mysql_query("SELECT * FROM clients WHERE id='$clt_id' ",$connexion) or die(mysql_error($connexion));
$Client = mysql_fetch_array($req3);
$civilite = $Client['civilite'];
$nom = $Client['nom'];
$prenom = $Client['prenom'];
$email = $Client['email'];

// Adresse FAC
list($Aadr_id,$Acivilite,$Aprenom,$Anom,$Aadr,$Acp,$Aville,$Apays,$Acommentaire) = explode('#!#',Aff($Commandes['adr_fac']));
$req2 = mysql_query("SELECT * FROM adresses WHERE id='$Aadr_id' LIMIT 1",$connexion) or die(); // $cmd_id
$Adresses = mysql_fetch_array($req2);

$adr_fac = unhtmlentities(Aff('Société : '.$Adresses['societe'].'
Civilité / Nom / Prénom  : '.$Acivilite.' '.$Anom.' '.$Aprenom.'
Adresse : '.$Aadr.'
Code postal : '.$Acp.'
Ville : '.$Aville.'
Pays : '.$Apays.'
Tél : '.$Adresses['tel'].'
Email : '.$email.'
TVA : '.$Adresses['tva']));

// Adresse LIV
list($Vadr_id,$Vcivilite,$Vprenom,$Vnom,$Aadr,$Vcp,$Vville,$Vpays,$Vcommentaire) = explode('#!#',Aff($Commandes['adr_liv']));
$req2 = mysql_query("SELECT * FROM adresses WHERE id='$Vadr_id' LIMIT 1",$connexion) or die(); // $cmd_id
$Adresses = mysql_fetch_array($req2);

$adr_liv = unhtmlentities(Aff('Société : '.$Adresses['societe'].'
Civilité / Nom / Prénom : '.$Vcivilite.' '.$Vnom.' '.$Vprenom.'
Adresse : '.$Vadr.'
Code postal : '.$Vcp.'
Ville : '.$Vville.'
Pays : '.$Vpays));

$villedestination = $Vville;

if ($mode == 'facture') {
	// ADRESSE POUR LA FACTURE
	$adr_facture = unhtmlentities(Aff('Référence Devis N°'.$ref.'
	Société : '.$Adresses['societe'].'
	Civilité / Nom / Prénom  : '.$Acivilite.' '.$Anom.' '.$Aprenom.'
	Adresse : '.$Aadr.'
	Code postal : '.$Acp.'
	Ville : '.$Aville.'
	Pays : '.$Apays.'
	TVA : '.$Adresses['tva']).'
	Lieu de livraison : '.Vadr.' '.$Vcp.' '.$Vville.' '.$Vpays.'
	Date d\'expédition : ???');
}

// Facture...........
if ($Commandes['prodqte1'] > 0) {
	$prodqte1 = $Commandes['prodqte1'];
	$prodballe1 = $prodqte1 * 25;
		$delai = 'delai_'.$langues[0];
		$C = new SQL('tarifs');
		$C->LireSql(array($delai)," titre <= '$prodballe1' AND a >= '$prodballe1' LIMIT 1 ");
		if ($C->nb < 1) $C->LireSql(array($delai)," titre <= '$prodballe1' ORDER BY titre DESC LIMIT 1 ");
		$$delai = Aff($C->V[0][$delai]);
	$prodprix1 = $Commandes['prodpx1'];
	$prodprixUnit1 = $prodprix1 / $prodballe1;
} else {
	$prodqte1 = 0;
	$prodballe1 = 0;
	$prodprix1 = 0;
	$prodprixUnit1 = 0;
}
if ($Commandes['prodqte2'] > 0) {
	$prodqte2 = $Commandes['prodqte2'];
	$prodballe2 = $prodqte2 * 25;
	if ($prodballe2 > $prodballe1) {
		$delai = 'delai_'.$langues[0];
		$C = new SQL('tarifs');
		$C->LireSql(array($delai)," titre <= '$prodballe2' AND a >= '$prodballe2' LIMIT 1 ");
		if ($C->nb < 1) $C->LireSql(array($delai)," titre <= '$prodballe2' ORDER BY titre DESC LIMIT 1 ");
		$$delai = Aff($C->V[0][$delai]);
	}
	$prodprix2 = MakeFloat($Commandes['prodpx2']);
	$prodprixUnit2 = MakeFloat($prodprix2 / $prodballe2);
} else {
	$prodqte2 = 0;
	$prodballe2 = 0;
	$prodprix2 = 0;
	$prodprixUnit2 = 0;
}
switch ($Commandes['tsp_mode']) {
	case 'aerien' : $tsp_mode = 'Aérien'; break;
	case 'camion' : $tsp_mode = 'Camion'; break;
	case 'maritime' : $tsp_mode = 'Maritime'; break;
}
$tsp_tarif = MakeFloat($Commandes['tsp_tarif']);

$total_ht = $prodprix1 + $prodprix2;
$total_ht = MakeFloat($total_ht + $tsp_tarif);

if ($Commandes['remise'] > 0) {
	$remise = $Commandes['remise'];
	$total_ht = $total_ht - $remise;
}
if ($Commandes['total_ttc'] == 1) {
	$tva = $total_ht * 0.1960;
	$solde = MakeFloat($total_ht + $tva);
	$tva = MakeFloat($tva);
} else {
	$solde = MakeFloat($total_ht);
}

$nbcolis = ceil($prodqte1 + $prodqte2); 
$poids = round((($prodballe1 + $prodballe2) * 40) / 1000,2);
$volume = round(($prodballe1 + $prodballe2) * 0.02 ,2);

// Admin E-mail
$M = new SQL('contact');
$M->LireSql(array('email')," id='1' LIMIT 1 ");
$AdminEmail = Aff($M->V[0]['email']);
if ($AdminEmail == '') $AdminEmail = 'contact@proxitek.com';

// PARAMETRES GENERAUX DU PDF ////////////////////////////////////////////////////////////////////////////////////////////////
$pdf = new Cezpdf('a4','portrait');
if (is_dir('../lib/pdfClasses/')) {
	$mainFont = '../lib/pdfClasses/fonts/Helvetica.afm';
	$codeFont = '../lib/pdfClasses/fonts/Courier.afm';
} else {
	$mainFont = './../admin/lib/pdfClasses/fonts/Helvetica.afm';
	$codeFont = './../admin/lib/pdfClasses/fonts/Courier.afm';
}
$pdf->selectFont($mainFont);
$pdf->ezSetMargins(50,70,50,50);
$pdf->openHere('Fit');

// PIED DE PAGE // addText(x,y,size,text,[angle=0],[adjust=0])
$all = $pdf->openObject();
$pdf->saveState();
$pdf->setStrokeColor(0,0,0,1);
$pdf->line(20,40,578,40);
//$pdf->line(20,822,578,822);

$pdf->addText(60,30,8,'<c:alink:'.$WWW.'>GOLFBOWL</c:alink>  -  24, avenue de Chavoye - 78124 MAREIL SUR MAULDRE FRANCE - Tél. : 01 30 90 83 65 - Email : <c:alink:mailto:'.$AdminEmail.'>'.$AdminEmail.'</c:alink>');
$pdf->addText(100,20,6,'N° TVA INTRACOMMUNAUTAIRE : FR 77482482031 -  APE 744B - SARL au capital de 30.000€ - Siret : 482 482 031 00010 -  R.C.S. Versailles');
$pdf->restoreState();
$pdf->closeObject();
$pdf->addObject($all,'all');

if (is_dir('../lib/pdfClasses/')) $pdf->addJpegFromFile('./logo.jpg',230,$pdf->y-90,100,100); // addJpegFromFile(imgFileName,x,y,w,[h])
else  $pdf->addJpegFromFile('../admin/commandes/logo.jpg',230,$pdf->y-90,100,100);
$pdf->ezSetDy(-130);

//$pdf->ezSetDy(-100);
if ($mode != 'facture') $pdf->ezText('DEVIS / PRO FORMA N°'.$ref.' ('.$datecrea.')',20,array('justification'=>'centre'));
else $pdf->ezText('FACTURE & LISTE DE COLISAGE N°'.$ref.' ('.$datecmd.')',20,array('justification'=>'centre'));
$pdf->ezSetDy(-16);

// ADRESSES //////////////////////////////////////////////////////////////////////////
if ($mode != 'facture') {
	$row_data1 = array(array('des'=>$adr_fac,'qte'=>$adr_liv));
	$pdf->ezTable(
		$row_data1,
		array(
			'des'=>'<b>Adresse de facturation :</b>',
			'qte'=>'<b>Adresse de livraison :</b>',
		),
		'',
		array(
			'showLines'=>0,
			'showHeadings'=>1,
			'shaded'=>0,
			'xPos'=>0,
			'xOrientation'=>'right',
			'width'=>500,
			'fontSize'=>10,
			'titleFontSize'=>12,
			'cols'=>array('des'=>'','qte'=>'')
		)
	); 
} else { 
$row_data1 = array(array('des'=>$adr_facture));
	$pdf->ezTable(
		$row_data1,
		array(
			'des'=>'<b>Adresse de facturation :</b>',
		),
		'',
		array(
			'showLines'=>0,
			'showHeadings'=>0,
			'shaded'=>0,
			'xPos'=>0,
			'xOrientation'=>'right',
			'width'=>500,
			'fontSize'=>10,
			'titleFontSize'=>12,
			'cols'=>array('des'=>'')
		)
	);
}
$pdf->ezSetDy(-30);

// FACTURE //////////////////////////////////////////////////////////////////////////
if ($mode != 'facture') {
		$facture .= '<td><b>DESIGNATION</b></td>
		<td><b>QUANTITE</b></td>
		<td><b>PRIX UNIT. EURO H.T.</b></td>
		<td><b>TOTAL EURO H.T.</b></td>';
	}
	else {
		$facture .= '<td><b>DESIGNATION</b><br />
		Objet promotionnel : Balle de golf en plastique : 3926400000<br />
		Origine : Made in France</td>
		<td><b>QUANTITE</b></td>
		<td><b>PRIX UNIT. EURO H.T.</b></td>
		<td><b>TOTAL EURO H.T.</b></td>';
	}
	
	
$row_data = array();
$row_data[] = array('des'=>'Balle GOLFBOWL avec un logo tampographié sur chaque demi-sphère','qte'=>$prodballe1,'pu'=>$prodprixUnit1,'tot'=>$prodprix1);
$row_data[] = array('des'=>'Balle GOLFBOWL avec 2 logos, chacun tampographié sur une demi-sphère','qte'=>$prodballe2,'pu'=>$prodprixUnit2,'tot'=>$prodprix2);
$row_data[] = array('des'=>'Transport (Assurance incluse)','qte'=>'','pu'=>$tsp_mode,'tot'=>$tsp_tarif);
$row_data[] = array('des'=>'Incoterm 2000 : DDU livraison','qte'=>'','pu'=>'','tot'=>$villedestination);
$row_data[] = array('des'=>'Colisage','qte'=>'NB colis : '.$nbcolis,'pu'=>'Poids : '.$poids.'Kg','tot'=>'Volume : '.$volume.'m3');
if ($Commandes['remise'] > 0) {
	$row_data[] = array('des'=>'Remise','qte'=>'','pu'=>'','tot'=>'-'.$remise);
}
$row_data[] = array('des'=>'TOTAL H.T.','qte'=>'','pu'=>'','tot'=>$total_ht);
if ($Commandes['total_ttc'] == 1) {
	$row_data[] = array('des'=>'TVA 19.60% (France)','qte'=>'','pu'=>'','tot'=>$tva);
	$row_data[] = array('des'=>'TOTAL T.T.C. (France)','qte'=>'','pu'=>'','tot'=>$solde);
}

$pdf->ezTable(
	$row_data,
	array(
		'des'=>'DESIGNATION'.($mode != 'facture' ? '' : '
		Objet promotionnel : Balle de golf en plastique : 3926400000
		Origine : Made in France'),
		'qte'=>'QUANTITE',
		'pu'=>'PRIX UNIT. EURO H.T.',
		'tot'=>'TOTAL EURO H.T.'
	),
	'',
	array(
		'showHeadings'=>1,
		'shaded'=>1,
		'xPos'=>0,
		'xOrientation'=>'right',
		'width'=>500,
		'cols'=>array('des'=>'','qte'=>array('width'=>100,'justification'=>'right'),'pu'=>array('width'=>100,'justification'=>'right'),'tot'=>array('width'=>100,'justification'=>'right'))
	)
);
 
// CONDITIONS //////////////////////////////////////////////////////////////////////////
$row_data1 = array();
$row_data[] = array('des'=>'PAIEMENT : 40% à la commande, solde à la livraison avant expédition par transfert SWIFT');
$row_data[] = array('des'=>'Délai de fabrication : '.$$delai);
$row_data[] = array('des'=>'Nos coordonnées banquaire :
	BPVF ST GERMAIN EN LAYE FRANCE
	IBAN : FR76 1870 7000 2409 0212 3401 942
	SWIFT : CCBPFRPPVER');

if ($mode == 'facture') {
	$row_data[] = array('Livraison en UE : <b>LIVRAISON EXONERE DE TVA, article 262 ter 1 du CGI</b>');
}

$row_data[] = array('des'=>'L\'ACCEPTATION DU DEVIS CONSTITUERA L\'ACCEPTATION DU CLIENT DE NOS CONDITIONS DE VENTES OU LE CAS ECHEANT DES CONDITIONS SPECIALES ACCORDEES. NOS CONDITIONS DE VENTES PREVALENT SUR TOUTES CONDITIONS D\'ACHAT');

if ($mode == 'facture') {
	$row_data[] = array('Le transfert de propriété des marchandises, nonobstant la livraison, qu\'au paiement complet du prix.');
}

$pdf->ezTable(
	$row_data1,
	array(
		'des'=>'',
	),
	'',
	array(
		'showLines'=>1,
		'showHeadings'=>0,
		'shaded'=>1,
		'xPos'=>0,
		'xOrientation'=>'right',
		'width'=>500,
		'fontSize'=>10,
		'titleFontSize'=>12,
		'cols'=>array('des'=>'')
	)
);

//$pdf->ezNewPage();
//$pdf->ezStartPageNumbers(500,28,10,'','',1);
//$pdf->ezStopPageNumbers(1,1);

if ($mode != 'facture') {
	// Prepare le nom
	$pdfFile = 'devis_clt-'.$clt_id.'_cmd-'.$cmd_id.'_'.date(ymdHis).'.pdf';
	if (is_dir('./../medias_files/')) $pdfFileDir = './../medias_files/'.$pdfFile;
	else $pdfFileDir = './../admin/medias_files/'.$pdfFile;
	
	// Efface ex-devis et update BDD
	$F = new SQL('commandes');
	$F->LireSql(array('devis')," cmd_id='$cmd_id' LIMIT 1 ");
	$Exdevis = $F->V[0]['devis'];
	if ($Exdevis!='' && file_exists('../medias_files/'.$Exdevis)) unlink('../medias_files/'.$Exdevis);
	mysql_query("UPDATE commandes SET devis='$pdfFile',total_ht='$total_ht',solde='$solde' WHERE cmd_id='$cmd_id' LIMIT 1 ",$connexion) or die(); // UPDATE SOLDE ET PDF
} else {
	// Prepare le nom
	$pdfFile = 'facture_clt-'.$clt_id.'_cmd-'.$cmd_id.'_'.date(ymdHis).'.pdf';
	if (is_dir('./../medias_files/')) $pdfFileDir = './../medias_files/'.$pdfFile;
	else $pdfFileDir = './../admin/medias_files/'.$pdfFile;
	
	// Efface ex-devis et update BDD
	$F = new SQL('commandes');
	$F->LireSql(array('facture')," cmd_id='$cmd_id' LIMIT 1 ");
	$Exfacture = $F->V[0]['facture'];
	if ($Exfacture!='' && file_exists('../medias_files/'.$Exfacture)) unlink('../medias_files/'.$Exfacture);
	mysql_query("UPDATE commandes SET facture='$pdfFile',total_ht='$total_ht',solde='$solde' WHERE cmd_id='$cmd_id' LIMIT 1 ",$connexion) or die(); // UPDATE SOLDE ET PDF
}
// Create
$pdfcode = $pdf->ezOutput(); // $pdf->ezStream();
$fp = fopen($pdfFileDir,'wb');
fwrite($fp,$pdfcode);
fclose($fp);

?>