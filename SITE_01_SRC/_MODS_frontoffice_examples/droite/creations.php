<?
$A =& new Q(" SELECT * FROM mod_creations WHERE une='1' LIMIT 1 ");
$V = $A->V[0];
?>
<div class="creation">
	<h1><a href="index2.php?goto=creations"><img src="images/fr/droite/t_creations.gif" alt="Les cr&eacute;ations" /></a></h1>
	<div class="f_creation">
		<div class="b_creation">
			<div class="m_creation">
				<a href="index2.php?goto=creations&creation_id=<?=aff($V['id']);?>"><?
				$m =& new FILE();
				if ($m->isMedia('imgs/creations/mini/'.$V['visuel'])) {
					$m->alt = html(aff($V['titre']));
					$m->image();
				} ?></a>
				<p><strong class="rose"><?=aff($V['titre']);?></strong></p>
				<?=quote(aff($V['introduction']));?>
			</div>
		</div>
	</div>
</div>