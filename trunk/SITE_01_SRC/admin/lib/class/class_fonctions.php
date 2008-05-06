<?
if ( !defined('MLKLC') ) die('Lucky Duck');

// ------------------------------------------------- MENU SELECT DYNAMQUE -------------------------------------------------//
class menuSelect {
	/* EXEMPLE

	$table = array(array('1','oui'),array('0','non'));
	$m = new menuSelect('test',$table);
	$m->width = '300';
	$m->printMenuSelect();

	$m = new menuSelect('id','actualites');
	$m->first = 'defaut';
	$m->where = " actif='1' AND archive='1' ORDER BY titre ASC ";
	$m->valeur = 'id';
	$m->titre = 'date_actu';
	$m->selected = $_GET['id'];
	$m->url = 'index.php?goto=une&id=';
	$m->printMenuSelect();
	
	*/
	var $menu;
	function menuSelect($name,$table) {
		global $debug;
		
		$this->name			= $name;
		$this->width		= $width;
		$this->css			= $this->css;
		$this->style		= $this->style;
		
		$this->first		= $first; // titre 1er menu // Defaut == 1
		$this->firstValeur	= $firstValeur; //val  1er menu
		$this->table		= $table; // ex. 'client' OU array(array('1','oui'),array('0','non'))
		$this->where		= $where;
		$this->whereopt		= $whereopt;
		$this->valeur		= $valeur; // ex. id
		$this->titre		= $titre; // ex. nom // >> A FAIRE X valeur, ex. : civilite nom prenom
		
		$this->selected		= $selected; // Ex. 38 >>> if (valeur == 38)
		$this->url			= $url; // ex. index.php?id=
		$this->onchange		= $onchange;
		
		$this->debug		= $debug;
	}
	function printMenuSelect($echo=true) {  // Dynamicaly crypt text with javascript
		if ($this->width != '') $this->style = 'width:'.$this->width.'px';
		if ($this->width != '' && $this->style != '') $this->style .= ';'.$this->style;
		elseif ($this->style != '') $this->style = $this->style;

		$url = '';
		if ($this->url != '') $onchange = ' onchange="window.location=\''.$this->url.'\'+this.options[this.selectedIndex].value;" ';
		elseif ($this->onchange != '') $onchange = ' onchange="'.$this->onchange.'" ';
		
		$this->menu = '<select name="'.$this->name.'" id="'.$this->name.'" style="'.$this->style.'" class="'.$this->css.'" size="1" '.$onchange.'>';
		
		if ($this->first == 'defaut') $this->menu .= '<option value="">Choisir -&gt;</option>';
		elseif ($this->first != '') $this->menu .= '<option value="'.$this->firstValeur.'">'.$this->first.'</option>';
		
		if (is_array($this->table)) { // Build With Array
			if (!is_array($this->table) || count($this->table) < 1) return;
			foreach($this->table as $optionArray) {
				if ($this->selected != '') $selected = ($this->selected == $optionArray[0] ? 'selected="selected"' : '');
				$this->menu .= '<option value="'.aff($optionArray[0]).'" '.$selected.'>'.aff($optionArray[1]).'</option>'.chr(13).chr(10);
			}
		}
		else { // Build With Sql Table
			$A = new SQL($this->table);
			if ($this->valeur != '' && $this->valeur == $this->titre) $lire = array($this->valeur);
			elseif ($this->valeur != '' && $this->titre != '') $lire = array($this->valeur,$this->titre);
			else $lire = '*';
			if ($this->debug == 1) $A->debug = 1;
			$A->LireSql($lire,$this->where,$this->whereopt);
			if (count($A->V) > 0) {
				for ($i=0; $i<count($A->V); $i++) {
					if ($this->selected != '') $selected = $this->selected == $A->V[$i][$this->valeur] ? 'selected="selected"' : '';
					$this->menu .= '<option value="'.aff($A->V[$i][$this->valeur]).'" '.$selected.'>'.aff($A->V[$i][$this->titre]).'</option>'.chr(13).chr(10);
				}
			}
		}
		$this->menu .= '</select>';

		if ($echo == true) echo $this->menu;
		else return $this->menu;
	}
}

class emailcrypt {
	/* EXEMPLE
	$m = new emailcrypt($email,$text,$class);
	*/
	var $crypted_text;
	function _js_crypt ($text) {  // Dynamicaly crypt text with javascript
		//$this->html." "; // for a bug??
		$this->html = chunk_split( bin2hex($text ),2,'%');
		$this->html = '%'.substr($this->html,0,strlen($this->html)-1);
		$this->html = chunk_split($this->html,54,"'+'");
		$this->html = substr($this->html,0,strlen($this->html)-6);
		return js(" document.write(unescape('$this->html')); ", false);
	}
	function emailcrypt($email, $text, $class='', $crypt=true, $protect=true) {
		$temp = '<a href="mailto:'.$email.'" class="'.$class.'">'.$text.'</a>';
		$this->crypted_text = ($crypt ? $this->_js_crypt($temp) : $temp);
		if ($protect) echo '<table border="0" cellspacing="0" cellpadding="0" style="display:inline;vertical-align:middle;"><tr><td>'.$this->crypted_text.'</td></tr></table>';
		else echo $this->crypted_text;
	}
}

?>