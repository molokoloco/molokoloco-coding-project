<? require('../../../admin/lib/racine.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>JS Framework mlklc</title>
</head>

<body bgcolor="#CCCCCC" text="#000000" link="#336699" leftmargin="20" topmargin="20" marginwidth="20" marginheight="20">
	<div style="font:11px verdana;color:black;"><b>Javascript Framework by <a href="molokoloco@gmail.com">molokoloco</a> 2007</b></div>
	<ul style="font:11px verdana;color:black;"><?
	
	$files = getFile('./','file');
	foreach($files as $file) {
		if (strpos($file,'.php') === false && strpos($file,'.BAK') === false) {
			?><li><a href="./<?=aff($file);?>"><?=aff($file);?></a></li><?
		}
	}
	
	?></ul>
</body>
</html>
