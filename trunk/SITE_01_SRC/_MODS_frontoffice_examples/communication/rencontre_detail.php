<?

$filtre_date = gpc('filtre_date');


$A =& new Q(" SELECT * FROM mod_page_intro WHERE id='3' LIMIT 1 ");
$A = $A->V[0];

?>
<div id="rencontre_detail">
	<h1 class="t_page"><?=aff($A['titre']);?></h1>
	<? include('communication/smenu.php');?>
	<div class="page">
	
	<div class="retour">
		<a href="javascript:history.back();">Retour à la liste</a>
	</div>
	<div class="archives">
	<div class="h_archives">
		<div class="b_archives">
	<? $G =& new Q(" SELECT * FROM mod_rencontres WHERE actif='1' AND id='$rencontre_id' LIMIT 1 "); ?>
	<div class="h_archive">
		<div class="b_archive">
		<h2><?=aff($G->V[0]['titre']);?></h2>
		<?
		$B =& new Q(" SELECT * FROM mod_rencontres_blocs WHERE rencontre_id='$rencontre_id' AND actif='1' ORDER BY ordre DESC ");
		if (count($B->V) > 0) {
		?>
			<?
			foreach($B->V as $i=>$R) {
				?>
					<h3><?=($i+1);?>. <?=aff($R['titre']);?></h3>
					<? $m =& new FILE();
					if ($m->isMedia('imgs/rencontres/medium/'.$R['visuel'])) {
						$m->css = 'visuel';
						$m->alt = html(aff($V['titre']));
						$m->popImage();
					} ?>
					<?=quote(aff($R['texte']));?>
			<? } ?>
		<? } ?>
		</div>
	</div>
	
	</div>
	</div>
	</div>
	
	</div>

</div>