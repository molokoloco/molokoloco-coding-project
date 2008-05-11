<?
$A =& new Q(" SELECT * FROM mod_page_intro WHERE id='6' LIMIT 1 ");
$A = $A->V[0];

?>
<div id="mentions">
	<h1 class="t_page"><?=aff($A['titre']);?></h1>
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

	<p class="t_page">&nbsp;</p>
</div>