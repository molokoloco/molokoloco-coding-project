<?
$A =& new Q(" SELECT * FROM mod_page_intro WHERE id='1' LIMIT 1 ");
$A = $A->V[0];

?><div id="charte">
	<h1 class="t_page">Pr&eacute;sentation</h1>
	<? include('charte/smenu.php');?>
	<div class="page">
		<h2><?=aff($A['titre']);?></h2>
		<? $m =& new FILE();
		if ($m->isMedia('imgs/pages/medium/'.$A['visuel'])) {
			$m->css = 'visu_gauche';
			$m->_pathzoom = 'imgs/pages/pop/'.$A['visuel'];
			$m->alt = html(aff($A['titre']));
			$m->popImage();
		} ?>
		<p><strong><?=aff($A['intro']);?></strong></p>
		<?=quote(aff($A['texte']));?>
		<? $m =& new FILE();
		if ($m->isMedia('imgs/pages/'.$A['document'])) {
			$m->css = 'telecharger';
			$m->texte = ( !empty($A['titre_document']) ? '<span>'.$A['titre_document'].' ('.$m->ext.', '.$m->size.')</span>' : '' );
			$m->lien();
		}
		?>
		<ul class="liens">
			<li><a href="javascript:void(0)" onclick="myLightWindow.activateWindow({href: 'video.php', title: '', width: '492', height:'296'});"><img src="images/fr/bt_video.gif" alt="Voir la vidéo complète" class="rollover" /></a></li>
			<li><a href="index2.php?goto=adherer"><img src="images/fr/bt_adherer.gif" alt="Adhérez à la Charte Qualité Fleur" class="rollover" /></a></li>
		</ul>
	</div>
</div>