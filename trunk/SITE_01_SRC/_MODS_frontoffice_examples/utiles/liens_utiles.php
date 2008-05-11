<div id="utiles">
	<h1 class="t_page">Liens Utiles</h1>
	<?
	$G =& new Q(" SELECT * FROM mod_liens WHERE actif='1' ORDER BY ordre DESC ");
	foreach ($G->V as $V) {
	
		?><div class="liens_utiles">		
			<?
			$m =& new FILE();
			if ($m->isMedia('imgs/liens/medium/'.$V['visuel'])) {
				$m->css = 'gauche';
				$m->alt = html(aff($V['titre']));
				$m->image();
			}
			?>
			<div class="texte">
				<h3><?=aff($V['titre']);?></h3>
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
					if ($m->isMedia('imgs/liens/'.$V['document'])) {
						?><li class="doc"><?
						$m->texte = ( !empty($V['titre_document']) ? $V['titre_document'].' ('.$m->ext.', '.$m->size.')' : '' );
						$m->lien();
						?></li><?
					}
					?>
				</ul>
			</div>
		</div>
		<?
	}
	?>
</div>