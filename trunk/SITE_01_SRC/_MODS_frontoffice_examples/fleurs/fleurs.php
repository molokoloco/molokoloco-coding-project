<div id="fleurs">
	<h1 class="t_page">Les fleurs Charte Qualit&eacute; Fleurs</h1>
	<div class="menu_g">
		<div class="f_menu_g">
			<select name="fleur_id" id="fleur_id" onchange="window.location='index2.php?goto=fleurs&fleur_id='+this.options[this.selectedIndex].value;" >
				<option value="">S&eacute;lectionnez une vari&eacute;t&eacute;</option>
				<?
				$A =& new Q(" SELECT id,titre FROM mod_fleurs WHERE actif='1' ORDER BY titre ASC ");
				foreach ($A->V as $i=>$V) {
					?><option value="<?=aff($V['id']);?>" <?=($fleur_id == $V['id'] ? 'selected="selected"' : '');?>><?=aff($V['titre']);?></option><?
				}
				?>
			</select>
			<a href="javascript:void(0);" onclick="javascript:myLightWindow.activateWindow({href: 'fleurs/lightwindow.php', title:'', width:'600px', height:'420px'});"><img src="images/fr/bt_voir_fleur.gif" alt="Voir toutes les fleurs" class="rollover" /></a>
			<div class="ligne">
				<?
				$js = '';
				$A =& new Q(" SELECT * FROM mod_fleurs WHERE actif='1' ORDER BY ordre DESC ");
				foreach ($A->V as $i=>$V) {
					if ($i > 0 && $i%2 == 0) {
						?>
						</div>
						<div class="ligne">
						<?
					}
					?>
					<div class="fleur <?=($fleur_id == $V['id'] ? 'on' : '');?>" id="tip<?=aff($V['id']);?>"><a href="index2.php?goto=fleurs&fleur_id=<?=aff($V['id']);?>"><?
						$m =& new FILE();
						$m->alt = html(aff($V['titre']));
						if ($m->isMedia('imgs/fleurs/mini/'.$V['visuel'])) $m->image();
						?></a> <a href="index2.php?goto=fleurs&fleur_id=<?=aff($V['id']);?>"><?=aff($V['titre']);?></a>
					</div>
					<?
					//$js .= " new Tip('tip".$V['id']."', decor1+'".aff($V['titre']." - <em>".$V['titre_latin'])."</em>'+decor2, {effect:'appear',offset: {x:-20, y:-60}}); ";
				}
				//js($js);
				?>
			</div>
		</div>
	</div>
	<?

	$A =& new Q(" SELECT * FROM mod_fleurs WHERE id='$fleur_id' LIMIT 1 ");
	$V = $A->V[0];

	?>
	<div class="page">
		<h2><?=aff($V['titre']);?> / <em><?=aff($V['titre_latin']);?></em></h2>
		
		<div class="fiche">
			<div class="visuel">
				<?
				$m =& new FILE();
				if ($m->isMedia('imgs/fleurs/grand/'.$V['visuel'])) {
					?><a href="<?='imgs/fleurs/pop/'.$V['visuel'];?>" title="Zoom" onfocus="blur();" class="lightwindow" style="padding:0;margin:0;"><?
					$m->style = 'margin:0 0 10px 0;padding:0;';
					$m->alt = html(aff($V['titre']));
					$m->image();
					?></a><?
				} ?>
				<h3><img src="images/fr/t_principale_qualite.gif" alt="Principale qualité" /></h3>
				<? if (!empty($V['qualite'])) { ?>
				<div class="liste">
					<ul>
						<li><?
							echo implode('</li><li>', explode("\n", aff($V['qualite'])));
						?></li>
					</ul>
				</div>
				<? } ?>
				<? if (!empty($V['naimepas'])) { ?>
				<h3><img src="images/fr/t_aime_pas.gif" alt="Ce qu'elle n'aime pas" /></h3>
				<div class="liste">
					<ul>
						<li><?
							echo implode('</li><li>', explode("\n", aff($V['naimepas'])));
						?></li>
					</ul>
				</div>
				<? } ?>
			</div>
			<div class="description">
				<?=quote(aff($V['description']));?>
				<p class="recap"><? if (!empty($V['disponibilite'])) { ?><strong>Disponibilit&eacute; :</strong> <?=aff($V['disponibilite']);?><br /><? } ?>
				<? if (!empty($V['tenue'])) { ?><strong>Tenue en vase :</strong> <?=aff($V['tenue']);?><br /><? } ?>
				<? if (!empty($V['symbolique'])) { ?><strong>Symbolique :</strong> <?=aff($V['symbolique']);?><br /><? } ?>
				<? if (!empty($V['utilisation'])) { ?><strong>Usage :</strong> <?=aff($V['utilisation']);?><? } ?></p>

				<?
				$F =& new Q("
					SELECT mod_creations.titre, mod_creations.id
					FROM mod_creations, mod_fleurs_rel_mod_creations AS mfrmc
					WHERE mfrmc.cat_id='{$fleur_id}' AND mfrmc.prod_id=mod_creations.id AND mod_creations.actif='1'
					ORDER BY mfrmc.ordre DESC
				");
				if (count($F->V) > 0) {
					?><p>D&eacute;couvrez les cr&eacute;ations &agrave; base de <?=aff($V['titre']);?> :<br><?
					foreach($F->V as $R) {
						?><a href="index2.php?goto=creations&creation_id=<?=aff($R['id']);?>" class="doc"><?=aff($R['titre']);?></a><?
					}
					?></p><?
				}
				?>
			</div>
		</div>		
		
		<?
		$B =& new Q(" SELECT * FROM mod_fleurs_blocs WHERE fleur_id='$fleur_id' AND actif='1' ORDER BY ordre DESC ");
		if (count($B->V) > 0) {
			?>
			<div class="gestes">
				<h3><?=aff($V['titre_gestes']);?></h3>
				<div class="f_gestes">
					<?
					foreach($B->V as $i=>$R) {
						?>
						<div class="geste<?=($i+1 == count($B->V) ? ' dernier' : '');?>">
							<? $m =& new FILE();
							if ($m->isMedia('imgs/fleurs/medium/'.$R['visuel'])) {
								$m->alt = html(aff($V['titre']));
								$m->popImage();
							} ?>
							<h4><?=($i+1);?>. <?=aff($R['titre']);?></h4>
							<?=quote(aff($R['texte']));?>
						</div>
					<? } ?>
				</div>
			</div>
			<?
		}
		if (!empty($V['asavoir'])) { 
			?>
			<div class="savoir">
				<div class="b_savoir">
					<h3>Bon &agrave; savoir</h3>
					<?=quote(aff($V['asavoir']));?>
				</div>
			</div>
			<?
		}
		?>
	</div>
</div>