<?

$A =& new Q(" SELECT * FROM mod_actualites WHERE id='$actualite_id' AND actif='1' LIMIT 1 ");
$V = $A->V[0];

if (empty($V['description'])) goto('index2.php?goto=actualite&page='.$page);

?><div id="actualite">
	<h1 class="t_page">Actualit&eacute;s</h1>
	<div class="pagination">
		<a href="index2.php?goto=actualite&page=<?=$page;?>" class="retour">Retour &agrave; la liste</a>
	</div>
	<div class="actu_detail">
		<h3><strong><?=printDateTime($V['date'], 1);?></strong> - <?=aff($V['titre']);?></h3>
		<?
		$m =& new FILE();
		if ($m->isMedia('imgs/actualites/medium/'.$V['visuel'])) {
			$m->css = 'gauche';
			$m->alt = html(aff($V['titre']));
			$m->popImage();
		} ?>
		<?=quote(aff($V['description']));?>
		
		<ul class="liens">
			<? 
			$m =& new FILE();
			if ($m->isUrl($V['lien'])) {
				?><li class="lien"><?
				$m->target = $A->V[$i]['cible'];
				$m->texte = $A->V[$i]['titre_lien'];
				$m->lien();
				?></li><?
			}
			$m =& new FILE();
			if ($m->isMedia('imgs/actualites/'.$V['document'])) {
				?><li class="doc"><?
				$m->texte = $A->V[$i]['titre_document'];
				$m->info();
				?></li><?
			}
			?>
		</ul>
	</div>
	<div class="article">
		<ul>
			<?
			$A =& new Q(" SELECT * FROM mod_actualites WHERE actif='1' AND id!='$actualite_id' AND description!='' AND date<'{$V['date']}' ORDER BY date DESC LIMIT 1 ");
			$V = $A->V[0];
			if ($V['id'] > 0) {
				?>
				<li class="prec"><a href="index2.php?goto=actualite&actualite_id=<?=aff($V['id']);?>&page=<?=$page ;?>">Article pr&eacute;c&eacute;dent</a></li>
				<?
			}
			$A =& new Q(" SELECT * FROM mod_actualites WHERE actif='1' AND id!='$actualite_id' AND description!='' AND date>'{$V['date']}' ORDER BY date DESC LIMIT 1 ");
			$V = $A->V[0];
			if ($V['id'] > 0) {
				?>
				<li class="suiv"><a href="index2.php?goto=actualite&actualite_id=<?=aff($V['id']);?>&page=<?=$page ;?>">Article suivant</a></li>
				<?
			}
			?>
		</ul>
	</div>
</div>