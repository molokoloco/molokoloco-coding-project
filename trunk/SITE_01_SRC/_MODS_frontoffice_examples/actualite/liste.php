<?

$G =& new Q(" SELECT count(id) as total FROM mod_actualites WHERE actif=1 AND une=0 "); // Total resultats
$total = $G->V[0]['total'];

// Pagination
$pagination = 3; //($page < 1 ? 3 : 4);
$pageArr = makePage($_GET['page'], $total, $pagination, thisPage('','','page'), 'premier', '', '', '<div class="pagination">Pages&nbsp;', '</div>', 'page');
$pageHtml = $pageArr['pageHtml'];
$page = $pageArr['page'];
$offset = $pageArr['offset'];
$debut = $pageArr['debut'];


$A =& new Q(" SELECT * FROM mod_actualites WHERE une=1 LIMIT 1 ");
$V = $A->V[0];

?>
<div id="actualite">
	<h1 class="t_page">Actualités</h1>

	<?=$pageHtml;?>
	
	<?
	if ($page == 1) {
		?>
		<div class="une">
			<div class="b_une">
				<div class="f_une">
					<h2>A la une</h2>
					<h3><strong><?=printDateTime($V['date'], 1);?></strong> - <?=aff($V['titre']);?></h3>
					<?
					$m =& new FILE();
					if ($m->isMedia('imgs/actualites/medium/'.$V['visuel'])) {
						$m->css = 'gauche';
						$m->popImage();
					} ?>
					<?=quote(aff($V['introduction']));?>
					<a href="index2.php?goto=actualite&actualite_id=<?=aff($V['id']);?>&page=<?=$page ;?>" class="bt_jaune"><span>Lire la suite</span></a>
				</div>
			</div>
		</div>
		<?
	}

	// Boucle paginée	
	$G =& new Q(" SELECT * FROM mod_actualites WHERE actif='1' AND une='0' ORDER BY date DESC LIMIT $debut,$offset ");
	foreach ($G->V as $V) {
		?>
		<div class="actu"><a name="A<?=aff($V['id']);?>" id="A<?=aff($V['id']);?>"></a>
			<h3><strong><?=printDateTime($V['date'], 1);?></strong> - <?=aff($V['titre']);?></h3>
			<?
			$m =& new FILE();
			if ($m->isMedia('imgs/actualites/medium/'.$V['visuel'])) {
				$m->css = 'gauche';
				$m->popImage();
			} ?>
			<?=quote(aff($V['introduction']));?>
			<ul class="liens">
				<? if (!empty($V['description'])) {
					?><li><a href="index2.php?goto=actualite&actualite_id=<?=aff($V['id']);?>&page=<?=$page ;?>"><img src="images/fr/bt_lire.gif" alt="Lire la suite" class="rollover" /></a></li><?
				}
				$m =& new FILE();
				if ($m->isUrl($V['lien'])) {
					?><li class="lien"><?
					$m->target = $V['cible'];
					$m->texte = $V['titre_lien'];
					$m->lien();
					?></li><?
				}
				$m =& new FILE();
				if ($m->isMedia('imgs/actualites/'.$V['document'])) {
					?><li class="doc"><?
					$m->texte = $V['titre_document'];
					$m->info();
					?></li><?
				}
				?>
			</ul>
		</div>
		<?
	}
	?>

	<?=$pageHtml;?>
	
</div>