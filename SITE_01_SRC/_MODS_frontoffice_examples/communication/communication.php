<?
$A =& new Q(" SELECT * FROM mod_page_intro WHERE id='2' LIMIT 1 ");
$A = $A->V[0];

?>
<div id="communication">
	<h1 class="t_page">Pr&eacute;sentation</h1>
	<? include('communication/smenu.php');?>
	<div class="page">
		<h2><?=aff($A['titre']);?></h2>
		<? $m =& new FILE();
		if ($m->isMedia('imgs/pages/medium/'.$A['visuel'])) {
			$m->css = 'visu_gauche';
			$m->_pathzoom = 'imgs/pages/pop/'.$A['visuel'];
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
	</div>
</div>