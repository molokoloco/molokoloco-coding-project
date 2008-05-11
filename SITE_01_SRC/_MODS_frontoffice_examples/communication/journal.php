<?

$A =& new Q(" SELECT * FROM mod_journal WHERE une=1 LIMIT 1 ");
$V = $A->V[0];

?><div id="journal">
	<h1 class="t_page">Le journal Charte Qualité Fleurs</h1>
	<? include('communication/smenu.php');?>
	<div class="page">
		<p>Comme son titre l’indique <b>« Objectif Qualité Fleurs »</b> a pour objet de rappeler les engagements pris dans la Charte mais aussi de faire valoir l’action de tous ceux qui y participent directement ou indirectement. C’est donc un espace de parole et de dialogue où interviennent producteurs, grossistes, fleuristes, livrant leurs expériences et faisant part de leurs projets. C’est aussi une source d’information pratique pour mieux appréhender les techniques de protection ou de préservation des fleurs et rappeler les gestes à connaître. Véritable lien entre les professionnels, <b>« Objectif Qualité Fleurs »</b> se veut en prise directe avec vos préoccupations et vos attentes.</p>
		
		<div class="h_journal">
			<div class="b_journal">
				<div class="f_journal">
					<div class="visuel">
						<?
						$m =& new FILE();
						if ($m->isMedia('imgs/journal/grand/'.$V['visuel'])) {
							$m->_pathzoom = 'imgs/journal/pop/'.$V['visuel'];
							$m->popImage();
						} ?>
					</div>
					<div class="description">
						<h2><?=aff($V['titre']);?></h2>
						<h3><?=aff($V['sstitre']);?></h3>
						<?=quote(aff($V['description']));?>
						
						<h4>Sommaire</h4>
						<div class="h_sommaire">
							<ul>
								<li><?
									echo implode('</li><li>', explode("\n", aff($V['sommaire'])));
								?></li>
							</ul>
						</div>
						<?
						$m =& new FILE();
						if ($m->isMedia('imgs/journal/'.$V['document'])) {
							$m->css = 'telecharger';
							$m->texte = ( !empty($V['titre_document']) ? '<span>'.$V['titre_document'].' ('.$m->ext.', '.$m->size.')</span>' : '' );
							$m->lien();
						}					
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="ligne">
			<?
			$A =& new Q(" SELECT * FROM mod_journal WHERE une='0' AND actif='1' ORDER BY ordre DESC ");
			foreach ($A->V as $i=>$V) {
				if ($i > 0 && $i%2 == 0) {
					?>
					</div>
					<div class="ligne">
					<?
				}
				?>
				<!--<div class="h_journal_<?=($i%2 != 1 ? 'g' : 'd');?>">-->
				<div class="h_journal">
					<div class="b_journal">
						<div class="f_journal">
							<div class="visuel">
								<?
								$m =& new FILE();
								if ($m->isMedia('imgs/journal/grand/'.$V['visuel'])) {
									$m->_pathzoom = 'imgs/journal/pop/'.$V['visuel'];
									$m->popImage();
								} ?>
							</div>
							<div class="description">
								<h2><?=aff($V['titre']);?></h2>
								<?=quote(aff($V['description']));?>
								<?
								$m =& new FILE();
								if ($m->isMedia('imgs/journal/'.$V['document'])) {
									$m->css = 'telecharger';
									$m->texte = ( !empty($V['titre_document']) ? '<span>'.$V['titre_document'].' ('.$m->ext.', '.$m->size.')</span>' : '' );
									$m->lien();
								}				
								?>
							</div>
						</div>
					</div>
				</div>		
				<?
			}
			?>
		</div>

	</div>
</div>