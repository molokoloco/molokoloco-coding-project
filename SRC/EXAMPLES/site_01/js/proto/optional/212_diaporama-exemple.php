<div id="magazine_slide">
	<?php
	
	$JS = '<script type="text/javascript">'.chr(13).chr(10).'// <![CDATA['.chr(13).chr(10);
	$JSE = chr(13).chr(10).'// ]]>'.chr(13).chr(10).'</script>';	
	
	
	// Standard script balise ---------------------------------------------
	function js($script, $echo=TRUE) {
		global $JS,$JSE;
		$js = $JS.chr(13).chr(10).$script.chr(13).chr(10).$JSE;
		if ($echo) echo $js;
		else return $js;
	}

	function getScriptBiArray($arr_name, $arr) {
		$myJs = "var ".$arr_name." = {";
		$j=0;
		foreach($arr as $key=>$val) {
			$myJs .= $j.":{";
			foreach($val as $key2=>$val2) {
				$val2 = str_replace("'", "\'", $val2);
				$val2 = str_replace("{", "\{", $val2);
				$val2 = str_replace("}", "\}", $val2);
				$val2 = str_replace(chr(10), '', $val2);
				$val2 = str_replace(chr(13), '', $val2);
				$myJs .= $key2.":'".$val2."',";
			}
			$myJs = substr($myJs, 0, -1);
			$myJs .= "},";
			$j++;
		}
		$myJs = substr($myJs, 0, -1).'};';
		return $myJs;
	}
	
	
	// MAGAZINE
	$arr_images = array();
	$G =& new Q("SELECT id, titre, visuel FROM mod_magazines WHERE visuel!='' ORDER BY datepub DESC LIMIT 10");
	foreach ($G->V as $G) {
		$i =& new FILE();
		if ($i->isMedia('medias/magazine/medium/'.$G['visuel'])) {
			$arr_images[] = array(
				'img'=>'medias/magazine/medium/'.$G['visuel'],
				'lien'=>'#'.$G['id'],
				'titre'=>$V['titre']
			);
			if (!isset($once_mag)) {
				$once_mag = 1;
				$i->lien = '';
				$i->image();
			}
		}
	}
	$js_array = getScriptBiArray('arrImage', $arr_images);
	$js_array .= "\n myDiap = new diaporama(arrImage, {div : 'magazine_slide',tempo : 5,effect : 'appear'});"; //scroll
	
	js($js_array);
	?>
</div>