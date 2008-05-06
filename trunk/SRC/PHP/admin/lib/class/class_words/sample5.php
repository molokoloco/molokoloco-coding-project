<?php
	require_once('clsMsDocGenerator.php');
		
	$doc = new clsMsDocGenerator();
	
	$doc->setFontFamily('Times New Roman');
	$doc->setFontSize('14');

	$doc->addParagraph('Sample V');
	$doc->addParagraph('');
	$doc->addParagraph('table without grid borders and font changes');
	
	$doc->startTable(NULL, 'tableWithoutGrid');
	$header = array('header 1', 'header 2', 'header 3', 'header 4');
	$aligns = array('center', 'center', 'center', 'right');
	$valigns = array('middle', 'middle', 'middle', 'bottom');
	$doc->addTableRow($header, $aligns, $valigns, array('font-weight' => 'bold', 'font-size' => '12pt',
						'height' => '80pt', 'background-color' => '#FF0000'));
	for($row = 1; $row <= 3; $row++){
		$cols = array();
		for($col = 1; $col <= 4; $col++){
			$cols[] = "column $col; row $row";
		}
		$doc->addTableRow($cols, NULL, NULL, array('font-size' => '10pt'));
		unset($cols);
	}
	$doc->endTable();

	$doc->output();
?>