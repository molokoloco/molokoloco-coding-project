<?php
	require_once('clsMsDocGenerator.php');
		
	$titleFormat = array(
							'text-align' 	=> 'center',
							'font-weight' 	=> 'bold',
							'font-size'		=> '18pt',
							'color'			=> 'blue');
	
	$doc = new clsMsDocGenerator();
	$doc->addParagraph('sample I', $titleFormat);
	$doc->addParagraph('this is the first paragraph');
	$doc->addParagraph('this is the paragraph in justified style', array('text-align' => 'justify'));
	$doc->output();
?>