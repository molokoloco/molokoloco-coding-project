<div id="plan">
	<h1 class="t_page">Plan du site</h1>
	
	<div class="ligne">
		<div class="colonne charte">
			<h1><img src="images/fr/t_plan_charte.gif" alt="La Charte" /></h1>
			<div class="f_colonne">
				<ul>
					<li><a href="index2.php?goto=charte">Présentation générale</a></li>
					<li><a href="index2.php?goto=engagement">Les 6 engagements</a>
						<ul>
							<?
							$A =& new Q(" SELECT id, titre FROM mod_charte ORDER BY ordre DESC ");
							foreach($A->V as $V) {
								?><li><a href="index2.php?goto=engagement&engagement_id=<?=$V['id'];?>"><?=cs(aff($V['titre']), 25);?></a></li><?
							}
							?>
						</ul>
					</li>
					<li><a href="#">Les 6 engagements en vidéo</a></li>
				</ul>
			</div>
		</div>
	
		<div class="colonne parole">
			<h1><img src="images/fr/t_plan_parole.gif" alt="La parole à..." /></h1>
			<div class="f_colonne">
				<ul>
					<?
					$A =& new Q(" SELECT id, titre FROM mod_paroles ORDER BY ordre DESC ");
					foreach($A->V as $V) {
						?><li><a href="index2.php?goto=parole&parole_id=<?=$V['id'];?>"><?=cs(aff($V['titre']), 25);?></a></li><?
					}
					?>
				</ul>
			</div>
		</div>
	
		<div class="colonne creations">
			<h1><img src="images/fr/t_plan_creations.gif" alt="Les créations" /></h1>
			<div class="f_colonne">
				<ul>
					<?
					$A =& new Q(" SELECT id, titre FROM mod_creations ORDER BY ordre DESC ");
					foreach($A->V as $V) {
						?><li><a href="index2.php?goto=creations&creation_id=<?=$V['id'];?>"><?=cs(aff($V['titre']), 25);?></a></li><?
					}
					?>
				</ul>
			</div>
		</div>
	
		<div class="colonne actualites">
			<h1><img src="images/fr/t_plan_actualite.gif" alt="Actualites" /></h1>
			<div class="f_colonne">
				<ul>
					<?
					$A =& new Q(" SELECT id, titre FROM mod_actualites ORDER BY date DESC ");
					foreach($A->V as $V) {
						?><li><a href="index2.php?goto=actualite&actualite_id=<?=$V['id'];?>"><?=cs(aff($V['titre']), 25);?></a></li><?
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	<div id="ligne">
		<div class="colonne fleurs">
			<h1><img src="images/fr/t_plan_fleurs.gif" alt="Les fleurs de la Charte" /></h1>
			<div class="f_colonne">
				<ul>
					<?
					$A =& new Q(" SELECT id, titre FROM mod_fleurs ORDER BY ordre DESC ");
					foreach($A->V as $V) {
						?><li><a href="index2.php?goto=fleurs&fleur_id=<?=$V['id'];?>"><?=cs(aff($V['titre']), 25);?></a></li><?
					}
					?>
				</ul>
			</div>
		</div>
	
		<div class="colonne communication">
			<h1><img src="images/fr/t_plan_communication.gif" alt="Communication" /></h1>
			<div class="f_colonne">
				<ul>
					<li><a href="index2.php?goto=communication">Pr&eacute;sentation</a></li>
					<li><a href="index2.php?goto=rencontre">Les rencontres CQF</a></li>
					<li><a href="index2.php?goto=journal">Le journal CQF</a></li>
					<li><a href="index2.php?goto=kit">Le kit CQF</a></li>
					<li><a href="index2.php?goto=presse">Presse</a></li>
				</ul>
			</div>
		</div>
		
		<div class="colonne utiles">
			<ul>
				<li><a href="index2.php?goto=adherer"><img src="images/fr/bt_plan_adherer.gif" alt="Devenir adh&eacute;rent" class="rollover" /></a></li>
				<li><a href="index2.php?goto=liens_utiles"><img src="images/fr/bt_plan_liens.gif" alt="Liens Utiles" class="rollover" /></a></li>
				<li><a href="index2.php?goto=faq"><abbr title="Foire aux questions"><img src="images/fr/bt_plan_faq.gif" alt="FAQ" class="rollover" /></abbr></a></li>
				<li><a href="index2.php?goto=lexique"><img src="images/fr/bt_plan_lexique.gif" alt="Lexique" class="rollover" /></a></li>
				<li class="texte"><a href="index2.php?goto=contact">Contact</a></li>
				<li class="texte2"><a href="index.php?goto=mentions">Mentions légales</a></li>
				<li class="texte"><a href="index.php?goto=credits">Crédits</a></li>
			</ul>
		</div>
	</div>
	
</div>