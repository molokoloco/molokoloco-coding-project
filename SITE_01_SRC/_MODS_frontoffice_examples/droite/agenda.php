<?
$G =& new Q(" SELECT * FROM mod_rencontres WHERE actif='1' AND date>=CURRENT_DATE() ORDER BY date DESC LIMIT 1 ");
$V = $G->V[0];

?><div class="agenda">
	<h1><a href="index2.php?goto=rencontre"><img src="images/fr/droite/t_agenda.gif" alt="L'agenda des rencontres" /></a></h1>
	<div class="f_agenda">
		<div class="b_agenda">
			<div class="m_agenda">
				<p>Prochaine rencontre le <strong><?=printDateTime($V['date'], 5);?></strong><br />
				<br /><?=aff($V['titre']);?></p>
			</div>
		</div>
	</div>
</div>