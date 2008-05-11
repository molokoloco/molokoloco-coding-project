<?

$filtre_date = gpc('filtre_date');


$A =& new Q(" SELECT * FROM mod_page_intro WHERE id='3' LIMIT 1 ");
$A = $A->V[0];

?>
<div id="rencontre">
	<h1 class="t_page"><?=aff($A['titre']);?></h1>
	<? include('communication/smenu.php');?>
	<div class="page">
		<div class="h_rencontre">
			<div class="b_rencontre">
				<div class="f_rencontre">
					<div class="visuel">
						<? $m =& new FILE();
						if ($m->isMedia('imgs/pages/medium/'.$A['visuel'])) {
							$m->css = 'visu_gauche';
							$m->_pathzoom = 'imgs/pages/pop/'.$A['visuel'];
							$m->alt = html(aff($V['titre']));
							$m->popImage();
						} ?>
					</div>
					<div class="description">
						<p><strong><?=aff($A['intro']);?></strong></p>
						<?=quote(aff($A['texte']));?>
						<? $m =& new FILE();
						if ($m->isMedia('imgs/pages/'.$A['document'])) {
							$m->css = 'telecharger';
							$m->texte = ( !empty($A['titre_document']) ? '<span>'.$A['titre_document'].' ('.$m->ext.', '.$m->size.')</span>' : '' );
							$m->lien();
						}
						?>
						<div class="contact">
							<div class="b_contact">
								<p>Pour participer, contactez directement votre fournisseur</p>
							</div>
						</div>
				  </div>
				</div>
			</div>
		</div>
		
		<div class="calendrier">
			<h2>Le calendrier des Rencontres</h2><br>
			<p><strong>Les prochaines Rencontres, organisées lors de journées dédiées à la Charte Qualité Fleurs ou à l'occasion de Journées Portes Ouvertes, auront lieu aux dates suivantes :</strong></p>
			<?
			$G =& new Q(" SELECT * FROM mod_rencontres WHERE actif='1' AND date>=CURRENT_DATE() ORDER BY date ASC ");
			foreach ($G->V as $V) {
			
				?><div class="rencontre">
					<h3><a href="mailto:<?=$V['email'];?>"><strong><?=printDateTime($V['date'], 5);?></strong> - <?=aff($V['titre']);?></a></h3>
					<?
					//if (!empty($V['email'])) {
						////$m = new emailcrypt($V['email'], 'Demande d\'infos', 'info', FALSE); // JS seem to break CSS
						//echo '<a href="mailto:'.$V['email'].'" class="info">Demande d\'infos</a>';
					//}
					?>
					<div class="breaker"></div>
					<?=quote(aff($V['description']));?>
					<ul class="liens">
							<?
							$m =& new FILE();
							if ($m->isUrl($V['lien'])) {
								?><li class="lien"><?
								$m->target = $V['cible'];
								$m->texte = $V['titre_lien'];
								$m->lien();
								?></li><?
							}
							$m =& new FILE();
							if ($m->isMedia('imgs/rencontres/'.$V['document'])) {
								?><li class="doc"><?
								$m->texte = $V['titre_document'];
								$m->info();
								?></li><?
							}
							?>
					</ul>
				</div><?
			}
			?>
		</div>

		<div class="archives">
			<h2>Archives</h2>
			<p><strong>Vous pouvez consulter ci-dessous le bilan des précédentes rencontres Charte Qualité Fleurs:</strong></p>
			<form name="frm_archives" id="frm_archives" action="#" method="post">
				<h3>Filtrer les archives par :</h3>
				<div class="filtres">
					<p>
						<select name="filtre_date" id="filtre_date"  onchange="window.location='index2.php?goto=rencontre&filtre_date='+this.options[this.selectedIndex].value;">
							<option value="0">Sélectionnez une date</option>
							<?
							$mois = array(1=>'Janvier', 2=>'F&eacute;vrier', 3=>'Mars', 4=>'Avril', 5=>'Mai', 06=>'Juin', 7=>'Juillet', 8=>'Ao&ucirc;t', 9=>'Septembre', 10=>'Octobre', 11=>'Novembre', 12=>'D&eacute;cembre');
							
							for ($month=1; $month<=12; $month++) {
								$dateSel = dateToArray(date("d/m/Y", mktime(0, 0, 0, (date("m")- $month), date("d"), date("Y"))));
								$y = $dateSel['y'];
								$m = $dateSel['m'];
								$ym = $y.$m;
								?><option value="<?=$ym;?>" <?=($ym == $filtre_date ? 'selected="selected"' : '');?>><?=$mois[($m < 10 ? $m{1} : $m)].' '.$y;?></option><?
							}
							?>
						</select>
					</p>
				</div>
			</form>
			
			<div class="h_archives">
				<div class="b_archives">
					<?
					if (empty($filtre_date)) $where = " date<CURRENT_DATE() ";
					else {
						$y = substr($filtre_date, 0 ,4);
						$m =  substr($filtre_date, -2);
						$where = " date LIKE '".$y."-".$m."%' ";
					}
					$G =& new Q(" SELECT * FROM mod_rencontres WHERE actif='1' AND $where ORDER BY date ASC ");

					if (count($G->V) < 1) {
						?>
						<div class="h_archive">
							<div class="b_archive">
								<div class="titre">
									<h4>Pas de r&eacute;sultat</h4>
								</div>
							</div>
						</div>
						<?
					}
					foreach ($G->V as $V) {
						?>
						<div class="h_archive">
							<div class="b_archive">
								<div class="titre">
									<h4><?
									list($dateSel) = explode(' ', $V['date']);
									$dateSel = dateToArray($dateSel);
									$y = $dateSel['d'];
									$m = $dateSel['m'];
									echo $mois[($m < 10 ? $m{1} : $m)].' '.$y;
									?> - <?=aff($V['titre']);?></h4>
									<?
									if (!empty($V['email'])) echo '<a href="mailto:'.$V['email'].'" class="info">Demande d\'infos</a>';
									?>
								</div>
								<? $m =& new FILE();
								if ($m->isMedia('imgs/rencontres/medium/'.$V['visuel'])) {
									?><div class="visuel"><?
									$m->css = 'visu_gauche';
									$m->alt = html(aff($V['titre']));
									$m->popImage();
									?></div><?
								}
								?>
							  	<div class="description">
									<h5>Bilan de la Rencontre</h5>
									<?=quote(aff($V['description']));?>
									<a href="index2.php?goto=rencontre&rencontre_id=<?=aff($V['id']);?>" class="detail">En savoir plus</a>
								</div>
							</div>
						</div>
						<?
					}
					?>
				</div>
			</div>
			
		</div>
	</div>
</div>