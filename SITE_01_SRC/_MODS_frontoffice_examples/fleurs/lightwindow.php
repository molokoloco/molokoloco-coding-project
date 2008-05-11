<?
require '../admin/lib/racine.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Astredhor</title>
<link href="../css/fleurs.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/lib/prototype.js"></script>
<script type="text/javascript" src="../js/lib/scriptaculous.js?load=effects,swfobject"></script>
<script type="text/javascript" src="../js/tools.js"></script>
<script type="text/javascript" src="../js/scripts.js"></script>
</head>

<div class="lightwindow">
	
	<div class="ligne">
	<?
	$A =& new Q(" SELECT * FROM mod_fleurs WHERE actif='1' ORDER BY ordre DESC ");
	foreach ($A->V as $i=>$V) {
		if ($i > 0 && $i%6 == 0) {
			?>
			</div>
			<div class="ligne">
			<?
		}
		?>
		<div class="fleur"> <a href="<?=$WWW;?>index2.php?goto=fleurs&fleur_id=<?=aff($V['id']);?>" target="_top"><?
			$m =& new FILE();
			if ($m->isMedia('../imgs/fleurs/mini/'.$V['visuel'])) {
				$m->alt = html(aff($V['titre']));
				$m->image();
			} ?></a> <a href="<?=$WWW;?>index2.php?goto=fleurs&fleur_id=<?=aff($V['id']);?>" target="_top"><?=aff($V['titre']);?></a>
		</div>
		<?
	}
	?>
	</div>
	
</div>
</body>
</html>