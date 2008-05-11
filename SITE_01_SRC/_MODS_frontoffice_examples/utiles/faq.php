<div id="utiles">
	<h1 class="t_page">Foire aux questions</h1>
	<?
	$A =& new Q(" SELECT * FROM mod_faq WHERE actif='1' ORDER BY ordre DESC ");
	foreach ($A->V as $i=>$V) {
		?>
		<div class="faq">
			<a href="javascript:void(0);" onclick="javascript:accordeonEffect('<?=$i;?>', 'div.faq');" id="a<?=$i;?>" class="off"><?=strip_tags(quote(aff($V['question'])));?></a>
			<div id="div<?=$i;?>" class="h_faq" style="display:none;">
				<div class="dg_faq">
					<div class="b_faq">
						<div class="m_faq">
							<?=quote(aff($V['reponse']));?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<? } ?>
	<ul class="liens_faq">
		<li><a href="index2.php?goto=adherer"><img class="rollover" alt="Adhérez à la Charte Qualité Fleur" src="images/fr/bt_adherer.gif"/></a></li>
		<li><a href="index2.php?goto=contact"><img class="rollover" alt="Contactez-nous" src="images/fr/bt_contact.gif"/></a></li>
	</ul>
</div>