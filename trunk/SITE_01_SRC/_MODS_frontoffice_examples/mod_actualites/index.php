<div id="index_actualites">
	<?php include('./print.php');?>
	<div id="switchfont">
		<?
		
		if ($article_cat_id < 1) { // First cat ?
			$R =& new Q("SELECT id FROM mod_actualites_cat WHERE actif='1' ORDER BY ordre DESC LIMIT 1");
			$article_cat_id = $R->V[0]['id'];
		}
		
		// Selected article
		if ($article_id > 0) $R =& new Q(" SELECT * FROM mod_actualites WHERE id='$article_id' AND actif='1' LIMIT 1");
		else $R =& new Q(" SELECT * FROM mod_actualites WHERE categorie_id='$article_cat_id' AND actif='1' ORDER BY date DESC LIMIT 1");
		
		$firstId = $R->V[0]['id'];

		if ($firstId < 1) { // Pas d'articles ?
			?><h1><?=aff(_PAS_D_ARTICLE_CORRESPONDANTS_);?></h1><? 
		}
		else {
			?>
			<h3><?=printDateTime($R->V[0]['date'], 1, $lg);?></h3>
			<h1><?=html(aff($R->V[0]['titre_'.$lg]));?></h1>
			<div class="actu">
				<?
				$m =& new FILE();
				if ($m->isMedia('imgs/actualites/medium/'.$R->V[0]['visuel'])) {
					echo '<div class="img">';
					$m->popImage();
					if (!empty($R->V[0]['titre_visuel_'.$lg])) echo '<cite class="imgLeg">'.html(aff($R->V[0]['titre_visuel_'.$lg])).'</cite>';
					echo '</div>';
				}
				?>
				<div class="wg">
					<?=quote(aff($R->V[0]['texte_'.$lg]));?>
				</div>
				<div class="breaker">&nbsp;</div>
				<ul class="liens">
					<?
					$m =& new FILE();
					if ($m->isUrl($R->V[0]['lien_'.$lg])) {
						?><li><?
						$m->css = 'lien';
						$m->target = $R->V[0]['cible'];
						$m->texte =html(aff( $R->V[0]['titre_lien_'.$lg]));
						$m->lien();
						?></li><?
					}
					$m =& new FILE();
					if ($m->isMedia('imgs/actualites/'.$R->V[0]['document_'.$lg])) {
						?><li><?
						$m->css = 'doc';
						$m->texte = html(aff($R->V[0]['titre_document_'.$lg]));
						$m->info();
						?></li><?
					}
					?>
				</ul>
			</div>
			
			<h4><?=aff(_ARCHIVES_);?></h4>
			<?
			
			// Liste articles of this cat
			$R =& new Q(" SELECT * FROM mod_actualites WHERE categorie_id='$article_cat_id' AND actif='1' AND id!='$firstId' ORDER BY date DESC");
			
			if (count($R->V) < 1) {
				?><div class="archive">
					<h5><?=aff(_PAS_D_ARTICLE_CORRESPONDANTS_);?></h5>
				</div><?
			}
			else {
				foreach($R->V as $i=>$V) {
					?>
					<div class="archive">
						<h5><?=printDateTime($V['date'], 1, $lg);?></h5>
						<a href="<?=urlRewrite($S->arbo[$S->rid]['titre_'.$lg].'-'.$V['titre_'.$lg], 'r'.$S->rid.'-ac'.$article_cat_id.'-a'.$V['id']);?>"><?=html(aff($V['titre_'.$lg]));?></a>
					</div>
					<?
				}
			}
			?>
			
		<? } ?>
		
	</div>
</div>