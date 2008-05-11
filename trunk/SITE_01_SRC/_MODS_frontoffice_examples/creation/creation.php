<div id="creations">
	<h1 class="t_page">Les cr&eacute;ations</h1>
	<div class="menu_g">
		<div class="f_menu_g">
			<div class="ligne">
				<?
				$js = '';
				$A =& new Q(" SELECT * FROM mod_creations WHERE actif='1' ORDER BY ordre DESC ");
				foreach ($A->V as $i=>$V) {
					if ($i > 0 && $i%2 == 0) {
						?>
						</div>
						<div class="ligne">
						<?
					}
					?>
					<div class="fleur <?=($creation_id == $V['id'] ? 'on' : '');?>" id="tip<?=aff($V['id']);?>"> <a href="index2.php?goto=creations&creation_id=<?=aff($V['id']);?>">
						<?
						$m =& new FILE();
						if ($m->isMedia('imgs/creations/mini/'.$V['visuel'])) {
							$m->alt = html(aff($V['titre']));
							$m->image();
						} ?></a> <a href="index2.php?goto=creations&creation_id=<?=aff($V['id']);?>"><?=aff($V['titre']);?></a>
					</div>
					<?
					//$js .= " new Tip('tip".$V['id']."', decor1+'".aff($V['sstitre'])."'+decor2, {effect:'appear',offset: {x:-20, y:-60}}); ";
				}
				//js($js);
				?>
			</div>
		</div>
	</div>
	<?

	$A =& new Q(" SELECT * FROM mod_creations WHERE id='$creation_id' LIMIT 1 ");
	$V = $A->V[0];

	?>
	<div class="page">
		<h2><?=aff($V['titre']);?> <span><?=aff($V['sstitre']);?></span></h2>
		<div class="fiche">
			<div class="visuel">
				<?
				$m =& new FILE();
				if ($m->isMedia('imgs/creations/grand/'.$V['visuel'])) {
					?><a href="<?='imgs/creations/pop/'.$V['visuel'];?>" title="Zoom" onfocus="blur();" class="lightwindow" style="padding:0;margin:0;"><?
					$m->style = 'margin:0;padding:0;';
					$m->alt = html(aff($V['titre']));
					$m->image();
					?></a><div class="agrandir"><a href="<?='imgs/creations/pop/'.$V['visuel'];?>" title="Zoom" onfocus="blur();" class="lightwindow">Agrandir</a></div><?
				} ?>
				<div class="liste">
					<h3>Fiche fleur CQF</h3>
					<ul>
						<?
						$F =& new Q("
							SELECT mod_fleurs.titre, mod_fleurs.id
							FROM mod_fleurs, mod_fleurs_rel_mod_creations AS mfrmc
							WHERE mfrmc.prod_id='{$creation_id}' AND mfrmc.cat_id=mod_fleurs.id AND mod_fleurs.actif='1'
							ORDER BY mfrmc.ordre DESC
						");
						foreach($F->V as $R) {
							?><li><a href="index2.php?goto=fleurs&fleur_id=<?=aff($R['id']);?>"><?=aff($R['titre']);?></a></li><?
						}
						?>
					</ul>
				</div>
			</div>
			<div class="description">
				<?=aff($V['introduction']);?>
				<div class="realisation">
					<h3>Temps de r&eacute;alisation : <span><?=aff($V['temps']);?></span></h3>
				</div>
				<div class="encart">
					<h3>V&eacute;g&eacute;taux utilis&eacute;s</h3>
					<ul>
						<li><?
							echo implode('</li><li>', explode("\n", aff($V['vegetaux'])));
						?></li>
					</ul>
				</div>
				<div class="encart">
					<h3>Accessoires n&eacute;cessaires</h3>
					<ul>
						<li><?
							echo implode('</li><li>', explode("\n", aff($V['accessoires'])));
						?></li>
					</ul>
				</div>
			</div>
		</div>
		
		<?
		$B =& new Q(" SELECT * FROM mod_creations_blocs WHERE creation_id='$creation_id' AND actif='1' ORDER BY ordre DESC ");
		if (count($B->V) > 0) {
			?>
			<div class="gestes">
				<h3><?=aff($V['titre_realisations']);?></h3>
				<div class="f_gestes">
					<?
					foreach($B->V as $i=>$R) {
						?>
						<div class="geste<?=($i+1 == count($B->V) ? ' dernier' : '');?>">
							<? $m =& new FILE();
							if ($m->isMedia('imgs/creations/medium/'.$R['visuel'])) {
								$m->alt = html(aff($V['titre']));
								$m->popImage();
							} ?>
							<h4><?=($i+1);?>. <?=aff($R['titre']);?></h4>
							<?=quote(aff($R['texte']));?>
							<div class="breaker"></div>
						</div>
					<? } ?>
				</div>
			</div>
			<?
		}
		if (!empty($V['conseils'])) { 
			?>
			<div class="savoir">
				<div class="b_savoir">
					<h3>Le conseil du professionel</h3>
					<p><?=quote(aff($V['conseils']));?></p>
				</div>
			</div>
			<?
		}
		?>

	</div>
</div>
