<?php
	require_once('clsMsDocGenerator.php');
		
	$doc = new clsMsDocGenerator();
	
	$doc->addParagraph('Sample VI');
	$doc->addParagraph('');
	$doc->addParagraph('images');
	$doc->addImage('http://www.google.com.au/intl/en_au/images/logo.gif', 276, 110);
	$doc->addImage('http://www.google.com.br/intl/pt-BR_br/images/logo.gif', 276, 110);
	
	$doc->addParagraph($doc->bufferImage('http://www.google.com.au/intl/en_au/images/logo.gif',138,55));
	$doc->addParagraph($doc->bufferImage('http://www.google.com.au/intl/pt-BR_br/images/logo.gif',138,55), array('text-align' => 'right'));
	
	$doc->startTable(NULL, 'tableWithoutGrid');
	$header = array('header 1', 'header 2');
	$aligns = array('center', 'center');
	$valigns = array('middle', 'middle');
	$doc->addTableRow($header, $aligns, $valigns, array('font-weight' => 'bold'));
	for($row = 1; $row <= 3; $row++){
		$cols = array();
		
		$cols[0] = "column 1; row $row".$doc->bufferImage('http://www.google.com.au/intl/en_au/images/logo.gif',138,55);
		$cols[1] = $doc->bufferImage('http://www.google.com.br/intl/pt-BR_br/images/logo.gif',138,55) . "column 2; row $row";
		
		$doc->addTableRow($cols);
		unset($cols);
	}
	$doc->endTable();

	$doc->output();
?>