<?

if (!isset($_SESSION[SITE_CONFIG]['DEVIS']['devis_ref'])) goto($devis_url.'?etape=etape1');

?><div id="gal_mesure" class="etape4">
	<div class="titre_rub">
    	<h1 class="t_rub">Les Galeries</h1>
        <a href="javascript:void(0);">Retour à la liste</a>
    </div>    
    <div class="centre_double">
        <div class="centre_gauche"><?php include_once('./gauche.php');?></div>
        <div class="centre_droite">

            <div class="nav_gal">
                <ul>
                    <li id="ng1"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng1.gif" alt="Mes souhaits" /></a></li>
                    <li id="ng2"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng2.gif" alt="Mon objet" /></a></li>
                    <li id="ng3"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng3.gif" alt="Mes coordonn&eacute;es" /></a></li>
                    <li id="ng4"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng4_on.gif" alt="Confirmation" /></a></li>
                </ul>
            </div>

            <div class="msg">
                <p><strong>Merci d'avoir effectu&eacute; votre demande personnalis&eacute;e&nbsp;(<?=html(aff($_SESSION[SITE_CONFIG]['DEVIS']['devis_ref']));?>)</strong> sur l'E-Atelier, vous recevrez par mail vos acc&egrave;s &agrave; votre espace ainsi que le r&eacute;capitulatif de votre demande personnalis&eacute;e.</p>
                <p>Si vous n'avez pas pu transf&eacute;rer votre fichier via notre site, vous recevrez par mail les instructions pour nous le faire parvenir par voie postale. Vous recevrez tr&egrave;s bientot une r&eacute;ponse &agrave; votre demande. Merci de votre confiance.</p>
                <p>L'Equipe de l'E-Atelier</p>
            </div>
	    </div>
    </div>    
</div>
<?
// RESET DEVIS
$_SESSION[SITE_CONFIG]['DEVIS'] = NULL;

?>