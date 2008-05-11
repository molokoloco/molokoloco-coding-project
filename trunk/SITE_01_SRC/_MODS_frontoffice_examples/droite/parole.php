<?
$A =& new Q(" SELECT * FROM mod_paroles WHERE une='1' LIMIT 1 ");
$V = $A->V[0];
?>
<div class="encart_jaune">
	<h1><a href="index2.php?goto=parole"><img src="images/fr/droite/t_parole.gif" alt="La parole &agrave;" /></a></h1>
	<div class="f_encart_jaune">
		<div class="b_encart_jaune">
			<div class="m_encart_jaune">
				<a href="index2.php?goto=parole&parole_id=<?=aff($V['id']);?>"><?
				$m =& new FILE();
				if ($m->isMedia('imgs/paroles/mini/'.$V['photo'])) {
					$m->alt = html(aff($V['titre']));
					$m->image();
				} ?></a>
				<h4><a href="index2.php?goto=parole&parole_id=<?=aff($V['id']);?>"><?=aff($V['titre']);?>, <?=aff($V['fonction']);?></a></h4>
				<div class="h_cit">
					<div class="b_cit">
						<blockquote><?=quote(aff($V['accroche']));?></blockquote>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>