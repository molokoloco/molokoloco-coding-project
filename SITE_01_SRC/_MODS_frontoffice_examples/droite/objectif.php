<?

$A =& new Q(" SELECT * FROM mod_journal WHERE une=1 LIMIT 1 ");
$V = $A->V[0];

?>
<div class="encart_bleu">
	<h1><img src="images/fr/droite/t_objectif_qualite.gif" alt="Objectif qualité fleur" /></h1>
	<div class="b_encart_bleu">
		<div class="f_encart_bleu">
			<a href="index2.php?goto=journal" class="visuel"><img src="images/common/droite/v_objectif.gif" alt="" />
			<?
			// <!-- Info PHP != Gabarit ;)
			/*$m =& new FILE();
			if ($m->isMedia('imgs/journal/mini/'.$V['visuel'])) {
				$m->image();
			}*/
			?></a>
			<p>T&eacute;l&eacute;charger le journal de la Charte Qualit&eacute; Fleurs.</p>
			<a href="index2.php?goto=journal"><?=aff($V['titre']);?></a>
		</div>
	</div>
</div>