<div id="parole">
	<h1 class="t_page">La parole &agrave; ...</h1>
	<div class="menu_g">
		<div class="f_menu_g">
			<div class="ligne">
				<?
				$js = '';
				$A =& new Q(" SELECT * FROM mod_paroles WHERE statut='1' AND photo!='' ORDER BY ordre DESC ");
				foreach ($A->V as $i=>$V) {
					if ($i > 0 && $i%2 == 0) {
						?>
						</div>
						<div class="ligne">
						<?
					}
					?>
					<div class="parole <?=($parole_id == $V['id'] ? 'on' : '');?>" id="tip<?=aff($V['id']);?>"> <a href="index2.php?goto=parole&parole_id=<?=aff($V['id']);?>">
						<?
						$m =& new FILE();
						if ($m->isMedia('imgs/paroles/mini/'.$V['photo'])) {
							$m->alt = html(aff($V['titre']));
							$m->image();
						} ?></a> <a href="index2.php?goto=parole&parole_id=<?=aff($V['id']);?>"><?=aff($V['titre']);?><br />
						<?=aff($V['fonction']);?></a>
					</div>
					<?
				}
				?>
			</div>
		</div>
	</div>
	<?
	
	$where = ( $parole_id > 0 ? " id='$parole_id' " : " une='1' " );
	$A =& new Q(" SELECT * FROM mod_paroles WHERE statut='1' AND $where LIMIT 1 ");
	$V = $A->V[0];
	
	?>
	<div class="page">
		<div class="intro">
			<div class="b_intro">
				<div class="f_intro">
					<div class="texte">
						<h2><?=aff($V['titre']);?>, <?=aff($V['fonction']);?></h2>
						<cite><span><?=aff($V['accroche']);?></span></cite>
					</div>
					<div class="visuel">
						<?
						$m =& new FILE();
						if ($m->isMedia('imgs/paroles/medium/'.$V['photo'])) {
							$m->alt = html(aff($V['titre']));
							$m->image();
						} ?>
					</div>
				</div>
			</div>
		</div>
		<div class="intro_interview">
			<?
			$m =& new FILE();
			if ($m->isMedia('imgs/paroles/grand/'.$V['visuel'])) {
				$m->alt = html(aff($V['titre']));
				$m->image();
			} ?>
			<?=quote(aff($V['introduction']));?>
		</div>
		<div class="interview">
			<?=quote(aff($V['texte']));?>
		</div>
	</div>
</div>
