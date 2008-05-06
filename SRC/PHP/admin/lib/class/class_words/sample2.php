<?php
	require_once('clsMsDocGenerator.php');
		
	$doc = new clsMsDocGenerator();
	$doc->addParagraph('this is the first paragraph, this is the first paragraph, this is the first paragraph, this is the first paragraph, this is the first paragraph, this is the first paragraph, this is the first paragraph.');
	$doc->addParagraph('this is the this is the second paragraph, this is the second paragraph, this is the second paragraph, this is the second paragraph, in justified style,', array('text-align' => '"justify"', 'color' => 'red'));
	
	$doc->newPage();
	$doc->addParagraph('this is the this is the second paragraph, this is the second paragraph, this is', array('text-align' => '"right"', 'color' => 'green'));

	$doc->newSession('LANDSCAPE');
	$doc->addParagraph('this is the this is the second paragraph, this is the second paragraph, this is', array('text-align' => '"left"', 'color' => 'blue'));
	
	$doc->newSession();
	$doc->addParagraph('this is the this is the second paragraph, this is the second paragraph, this is', array('color' => '#FFFF00'));
	
	$doc->output();
?>