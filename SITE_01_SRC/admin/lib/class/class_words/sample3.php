<?php
	require_once('clsMsDocGenerator.php');
		
	$doc = new clsMsDocGenerator();
	$doc->addParagraph('Sample with tables', array('text-align' => '"center"', 'font-size' => '16pt', 'font-weight' => 'bold'));


	$doc->addParagraph('Sample I');

	
	$doc->startTable();
	for($row = 1; $row <= 3; $row++){
		$cols = array();
		for($col = 1; $col <= 5; $col++){
			$cols[] = "coluna $col; linha $row";
		}
		$doc->addTableRow($cols);
		unset($cols);
	}
	$doc->endTable();
	

	$doc->addParagraph('');
	$doc->addParagraph('Sample II');
	
	$doc->startTable();
	$header = array('header 1', 'header 2', 'header 3', 'header 4');
	$aligns = array('center', 'center', 'center', 'right');
	$valigns = array('middle', 'middle', 'middle', 'bottom');
	$doc->addTableRow($header, $aligns, $valigns, array('font-weight' => 'bold'));
	for($row = 1; $row <= 3; $row++){
		$cols = array();
		for($col = 1; $col <= 4; $col++){
			$cols[] = "coluna $col; linha $row";
		}
		$doc->addTableRow($cols);
		unset($cols);
	}
	$doc->endTable();

	$doc->output();
?>