<?

if (!isset($_SESSION[SITE_CONFIG]['CADDIE']['commande_ref'])) goto($commandes_url.'?etape=etape1');

?>
<div id="commande" class="etape3">
	<h1 class="t_rub">Commander</h1>
    <div class="nav_cmd">
    	<ul>
        	<li id="nc1"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc1.gif" alt="Ma s&eacute;lection" /></a></li>
            <li id="nc2"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc2.gif" alt="Mes coordonn&eacute;es" /></a></li>
            <li id="nc3"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc3.gif" alt="Moyens de paiement" /></a></li>
            <li id="nc4"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc4_on.gif" alt="Confirmation" /></a></li>
        </ul>
    </div>
    <div class="msg">
    	<p><strong>Merci d'avoir pass&eacute; votre commande (<?=html(aff($_SESSION[SITE_CONFIG]['CADDIE']['commande_ref']));?>)</strong> sur l'E-Atelier, d&egrave;s validation de votre r&egrave;glement, vous recevrez par mail vos acc&egrave;s &agrave; l'espace personnalis&eacute;. Vous pourrez ainsi voir l&nbsp;'&eacute;tat de traitement de votre commande en temps r&eacute;el, Merci de votre confiance.</p>
		<?
		if ($_SESSION[SITE_CONFIG]['CADDIE']['paiement'] == 2) {
			?>
			<p><strong>R&eacute;glement par virement bancaire :</strong></p>
			<?=quote(aff(fetchValue('info_virements_fr')));?>
			<?
		}
		elseif ($_SESSION[SITE_CONFIG]['CADDIE']['paiement'] == 3) {
			?>
			<p><strong>R&eacute;glement par ch&egrave;que :</strong></p>
			<?=quote(aff(fetchValue('info_cheques_fr')));?>
			<?
		}
		?>
        <p>L'Equipe de l'E-Atelier</p>
    </div>
</div>
<?
// RESET COMMANDE
$_SESSION[SITE_CONFIG]['CADDIE'] = NULL;

?>