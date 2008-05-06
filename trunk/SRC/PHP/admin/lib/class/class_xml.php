<?
if ( !defined('MLKLC') ) die('Lucky Duck');

// ------------------------------------------------- FONCTIONS XML -------------------------------------------------//

// EXEMPLE ------------------------------------- 
// $X = xmlToArray($xmlfile);
// print_r($X);
// ---------------------------------------------

function xmlToArray($xmlfile,$encoding='ISO-8859-1') {
	ini_set('track_errors', '1'); // we want to know if an error occurs
	$xmlreaderror = false;
	$xmldata = @file_get_contents($xmlfile);
	if (!$xmldata) {
		db('Xml path error : '.$xmlfile);
		return FALSE;
	}

	$parser = @xml_parser_create($encoding);
	xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
	if (!xml_parse_into_struct($parser, $xmldata, $vals, $index)) {
		$xmlreaderror = true;
		echo 'Xml error';
	}
	xml_parser_free($parser);

	if (!$xmlreaderror && count($vals) > 1) {
		$result = array ();
		$i = 0;
		if (isset($vals[$i]['attributes'])) {
			foreach (array_keys($vals[$i]['attributes']) as $attkey)
				$attributes[$attkey] = $vals[$i]['attributes'][$attkey];
		}
		if (is_array($attributes)) $result[$vals[$i]['tag']] = array_merge($attributes, GetChildren($vals, $i, 'open'));
		else $result[$vals[$i]['tag']] = GetChildren($vals, $i, 'open');
	}
	ini_set('track_errors', '0');
	return $result;
}

function GetChildren ($vals, &$i, $type) {
	if ($type == 'complete') {
		if (isset($vals[$i]['value'])) return ($vals[$i]['value']);
		else return '';
	}
	$children = array (); // Contains node data

	while ($vals[++$i]['type'] != 'close') { // Loop through children
		$type = $vals[$i]['type'];
		if (isset($children[$vals[$i]['tag']]))  {// first check if we already have one and need to create an array
			if (is_array($children[$vals[$i]['tag']])) {
				$temp = array_keys($children[$vals[$i]['tag']]);
				if (is_string($temp[0])) { // there is one of these things already and it is itself an array
					$a = $children[$vals[$i]['tag']];
					unset($children[$vals[$i]['tag']]);
					$children[$vals[$i]['tag']][0] = $a;
				}
			}
			else {
				$a = $children[$vals[$i]['tag']];
				unset($children[$vals[$i]['tag']]);
				$children[$vals[$i]['tag']][0] = $a;
			}
			$children[$vals[$i]['tag']][] = GetChildren($vals, $i, $type);
		}
		else $children[$vals[$i]['tag']] = GetChildren($vals, $i, $type);
		
		if (isset ($vals[$i]['attributes'])) { // I don't think I need attributes but this is how I would do them:
			$attributes = array ();
			foreach (array_keys($vals[$i]['attributes']) as $attkey)
			$attributes[$attkey] = $vals[$i]['attributes'][$attkey];
			// now check: do we already have an array or a value?
			if (isset ($children[$vals[$i]['tag']])) {
				// case where there is an attribute but no value, a complete with an attribute in other words
				if ($children[$vals[$i]['tag']] == '') {
					unset($children[$vals[$i]['tag']]);
					$children[$vals[$i]['tag']] = $attributes;
				}
				// case where there is an array of identical items with attributes
				elseif (is_array($children[$vals[$i]['tag']])) {
					$index = count($children[$vals[$i]['tag']]) - 1;
					// probably also have to check here whether the individual item is also an array or not or what... all a bit messy
					if ($children[$vals[$i]['tag']][$index] == '') {
						unset ($children[$vals[$i]['tag']][$index]);
						$children[$vals[$i]['tag']][$index] = $attributes;
					}
					$children[$vals[$i]['tag']][$index] = array_merge($children[$vals[$i]['tag']][$index], $attributes);
				} else {
					$value = $children[$vals[$i]['tag']];
					unset($children[$vals[$i]['tag']]);
					$children[$vals[$i]['tag']]['value'] = $value;
					$children[$vals[$i]['tag']] = array_merge($children[$vals[$i]['tag']], $attributes);
				}
			}
			else $children[$vals[$i]['tag']] = $attributes;
		}
	}
	return $children;
}
?>