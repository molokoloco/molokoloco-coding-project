<? 

require 'lib/racine.php';

$id_select = gpc('id');
$data = gpc('data');
$dir = gpc('dir');

require $dir.'/data.php';

$R = $$data;
$R_data = $data.'_data';
$R_data = $$R_data;
$table = $R['table'];

$C =& new Q("SELECT * FROM $table WHERE id='$id_select' LIMIT 1 ");

if (count($C->V) < 1) {
    alert('Désolé il manque un parametre', '');
    echo '<script>self.window.close();</script>';
    die();
}

$root = '../';

foreach($R_data as $arrChamps) {
	
	$champsValue = '';

	switch ($arrChamps['input']) { // SWITCH SPECIAL INPUT (checkbox....)
		case 'radio' :
			$key = array_search($C->V[0][$arrChamps['name']], $arrChamps['valeur']);   
			$champsValue .= $arrChamps['titrevaleur'][$key];
		break;

		case 'file' : 
			if ($C->V[0][$arrChamps['name']] != '') {
				$ext = getExt($C->V[0][$arrChamps['name']]);
				if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') {
					$big = false;

					if (!file_exists($root.$R['rep'].$mini.$C->V[0][$arrChamps['name']]))
					$champsValue .= '<img src="images/error.gif" border="0" align="absmiddle" title="ATTENTION, il semble que le fichier ne soit pas présent sur le serveur" /> '.wrap($C->V[0][$arrChamps['name']], 20);
					elseif (is_file($root.$R['rep'].$C->V[0][$arrChamps['name']]))
						$big = $root.$R['rep'].$C->V[0][$arrChamps['name']];
					elseif (is_file($root.$R['rep'].$grand.$C->V[0][$arrChamps['name']]))
						$big = $root.$R['rep'].$grand.$C->V[0][$arrChamps['name']];
					
					if ($big) $champsValue .= '<a href="javascript:void(0);" onClick="popImg(\''.$big.'\',\'View\');"><img src="'.$root.$R['rep'].$mini.$C->V[0][$arrChamps['name']].'" alt="" border="0" class="bor1"></a>';
				}
				else {
					if (!file_exists($root.$R['rep'].$C->V[0][$arrChamps['name']]))
					$champsValue .= '<img src="images/error.gif" border="0" align="absmiddle" title="ATTENTION, il semble que le fichier ne soit pas présent sur le serveur" /> '.wrap($C->V[0][$arrChamps['name']], 20);
					else $champsValue .= '&nbsp;<a href="'.$root.$R['rep'].$C->V[0][$arrChamps['name']].'" target="_blank">'.wrap(affCleanName($C->V[0][$arrChamps['name']],20)).'</a>';
				}
			}
			else $champsValue .= '[<i>vide</i>]';
		break;
		
		case 'select': // SELECT INCLUDE FROM OTHER TABLE // FETCH TITRE
			if (!empty($arrChamps['inc']) && $arrChamps['inc'] != '') {
				list($tableName,$idName,$champsName) = explode(':',$arrChamps['inc']);
				if (strpos($champsName,'-') !== false) {
					$TchampsName = explode('-',$champsName);
					$select = array($idName);
					foreach($TchampsName as $champsN) $select[] = $champsN;
				}
				else $select = array($idName,$champsName);
				$S = new SQL($tableName);
				$S->LireSql($select," $idName='".$C->V[0][$arrChamps['name']]."' LIMIT 1 ");
				if (count($S->V) > 0) {
					if (strpos($champsName,'-') !== false) {
						foreach($TchampsName as $champsN) $champsValue .= ' '.aff($S->V[0][$champsN]);
					}
					else $champsValue .= aff($S->V[0][$champsName]);
				}
				else $champsValue .= '[<i>vide</i>]';
			}
			elseif (!empty($arrChamps['valeur']) && $arrChamps['valeur'] != '') {
				$key = array_search($C->V[0][$arrChamps['name']], $arrChamps['valeur']);
				$champsValue .= aff($arrChamps['titrevaleur'][$key]);
			}
			elseif (!empty($arrChamps['relation']) && $arrChamps['relation'] == '1') {
				list($tableName,$idName,$champsName,$champRel) = explode(':',$R['relation']);
				if (strpos($champsName,'-') !== false) {
					$TchampsName = explode('-',$champsName);
					$select = array($idName);
					foreach($TchampsName as $champsN) $select[] = $champsN;
				}
				else $select = array($idName,$champsName);
				$S = new SQL($tableName);
				$S->LireSql($select," $idName='".$C->V[0][$arrChamps['name']]."' LIMIT 1 ");
				if (count($S->V) > 0) {
					if (strpos($champsName,'-') !== false) {
						foreach($TchampsName as $champsN) $champsValue .= ' '.aff($S->V[0][$champsN]);
					}
					else $champsValue .= aff($S->V[0][$champsName]);
				}
				else $champsValue .= '[<i>vide</i>]';
			}
		break;
		
		default :
			if ($arrChamps['htmDefaut'] == 'bibliotheque') {
				$champsValue .= '<img src="'.$root.'medias/bibliotheque/'.$mini.$C->V[0][$arrChamps['name']].'" alt="" border="0" class="bor1">';
			}
			elseif ($arrChamps['htmDefaut'] == 'date') 
				$champsValue .= rDate($C->V[0][$arrChamps['name']]);
			elseif ($arrChamps['htmDefaut'] == 'datetime') 
				$champsValue .= printDateTime($C->V[0][$arrChamps['name']]);
			elseif ($arrChamps['wysiwyg'] > 0) 
				$champsValue .= quote(aff($C->V[0][$arrChamps['name']]));
			else 
				$champsValue .= ($C->V[0][$arrChamps['name']] != '' ? html(aff($C->V[0][$arrChamps['name']])) : '[<i>vide</i>]');
		break;
	}
	
	$dataHTML .= '<strong>'.aff(($arrChamps['titre']?$arrChamps['titre']:ucfirst($arrChamps['name']))).' :</strong> '.$champsValue.'<br />';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
	<title>Administration : <?=$SITE;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta name="author" content="molokoloco@gmail.com for Borntobeweb.fr 2007"/>
	<link rel="icon" href="<?=$WWW;?>admin/favicon.ico"/>
	<link rel="shortcut icon" type="image/icon" href="<?=$WWW;?>admin/favicon.ico"/>
	<link href="style_admin.css.php" rel="stylesheet" type="text/css"/>
	<script type="text/javascript">
	function printIt() {
		//if (window.print) window.print();
		//else alert("Pour imprimer: Ctrl+P ou sélectionnez IMPRIMER dans le menu FICHIER");
	}
	</script>
	<style type="text/css">
	h1 { color : #CCC; }
	img { vertical-align:middle; margin:10px; }
	.texte { line-height:1.5em; }
	</style>
</head>
<body onload="self.focus();" style="background-color:#FFFFFF;">

<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="95%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="20%" height="23" nowrap class="table-titre"><?=($R['titres']!=''?aff($R['titres']):aff($R['titre']).'s');?></td>
<td class="table-titre2">&nbsp;&nbsp;<?=$WWW;?> - <?=getDateTime();?></td>
<td width="20%" align="center" class="table-titre2"><a href="javascript:void(0);" onclick="printIt();" class="whiteLink">Imprimer</a></td>
</tr>
<tr align="center">
<td colspan="3" class="bgTableauPcP"></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td class="texte">

		<?=$dataHTML;?>

</td>
</tr>
</table><table width="100%" border="0" cellspacing="0" cellpadding="0" id="insertProd" style="display:none;">
<tr>
<td><iframe src="javascript:void(0)" id="actionFrame" name="actionFrame" width="100%" height="100" frameborder="0" allowtransparency="1" scrolling="no"></iframe></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
</table>
</body>
</html>