<?php

class formulaire
{
	var $vardebug; 		// Mode debug ou pas
	var $sql;			// Requete sql
	var $sqlchamps; 	// Champs de la requete
	var $sqlvalues; 	// Valeurs des champs
	var $sqlwhere;		// Clause WHERE d'un update
	var $verifJscript; 	// mode vérification javascript
	var $table;			// nom de la table
	var $double;		// tableau pour vérification de doublon
	var $template; 		// Template de la ligne du tableau
	
	var $updir;
	var $upsortie;
	var $sizemini;
	var $sizemedium;
	var $sizegrand;
	var $champsfile;
	var $mailfooter;
	var $mailheader;
	
	var $name; 			// Nom du formulaire	=> frmnewsletter
	var $action;		// Action du formulaire => page.php
	var $method;		// Post ou GET
	var $javascript;	// Fonction javascript du submit	=> CheckPrix();
	var $style;			// Style des textes à coté
	var $content;		// html du formulaire
	
	/*
	========================================================================================================================================
	========================================================================================================================================
	Construction de mode debug
	*/
		// constructeur
		function formulaire($name = "", $action = "", $style = "", $method = "POST")
		{
			$this->name = $name;
			$this->action = $action;
			$this->method = $method;
			$this->style = $style;
			$this->verifJscript = 1;
		}
		
		// Mode debug
		function setdb($debug = 0)
		{
			$this->vardebug = $debug;
		}
		
		// Ajoute un template pour chaque ligne du formulaire
		function setTemplate($template)
		{
			$this->template = $template;
		}
	/*
	========================================================================================================================================
	========================================================================================================================================
	*/
	
	
	/*
	========================================================================================================================================
	========================================================================================================================================
	Fonction pour l'affichage du formulaire
	*/
		// Construit le formulaire
		function drawForm($tab)
		{
			if($this->template == '')
			{
				$this->template = '
				<tr>
					<td align="right" class="{CLASSCSS}" valign="top">{LABEL} </td>
					<td><img src="pix.gif" width="12" height="1" /></td>
					<td class="{CLASSCSS}">{INPUT}</td>
				</tr>
				<tr>
					<td colspan="3"><img src="pix.gif" width="9" height="9" /></td>
				</tr>';
			}
			/*
			// Textbox
			array("text","nom","label", "valeur","style","javascript"),
			
			// Textarea
			array("textarea","nom","label", "valeur","style","javascript"),
			
			// Checkbox
			array("checkbox","nom","label", "valeur","style","javascript", "checked"),
			
			// Radio
			array("radio","nom","label", "valeur","style","javascript", "checked"),
			
			// select
			array("select","nom","label", array('0' => 'Valeur', '1' => 'Valeur 1'),"style","javascript", "size"),
			
			// Image
			array("image","nom","label", "src","style","javascript"),
			
			// Button
			array("button","nom","label", "texte","style","javascript"),
			
			// Submit
			array("submit","nom","label", "texte","style","javascript"),
			
			  <tr>
				<td align="right" style="'.$this->style.'"> <span class="obligatoire">*</span></td>
				<td><input name="prenom" type="text" id="prenom" style="width:172px" />    </td>
			  </tr>
			  <tr>
				<td colspan=2"><img src="images/common/pix.gif" width="9" height="9" /></td>
			  </tr>
			*/
			
			$this->content = '<form action="'.$this->action.'" method="'.$this->method.'" name="'.$this->name.'" style="margin: 0px; padding: 0px;" enctype="multipart/form-data">
			<table border="0" cellspacing="0" cellpadding="0">';
			
			for($i=0; $i< count($tab); $i++)
			{
				$ligne = $tab[$i];
				
				if($ligne[0] == "text")
				{
					$this->addLine($ligne[2], '<input type="text" name="'.$ligne[1].'" id="'.$ligne[1].'" value="'.$ligne[3].'" style="'.$ligne[4].'" '.$ligne[5].' />');
				}
				elseif($ligne[0] == "file")
				{
					$this->addLine($ligne[2], '<input type="file" name="'.$ligne[1].'" id="'.$ligne[1].'" style="'.$ligne[4].'" '.$ligne[5].' />');
				}
				elseif($ligne[0] == "textarea")
				{
					$this->addLine($ligne[2], '<textarea name="'.$ligne[1].'" id="'.$ligne[1].'" style="'.$ligne[4].'" '.$ligne[5].'>'.$ligne[3].'</textarea>');
				}
				elseif($ligne[0] == "checkbox")
				{
					if($ligne[6] == "checked")
					{
						$checked = 'checked="checked" ';
					}
					else
					{
						$checked = '';
					}
					
					$this->addLine($ligne[2], '<input type="checkbox" name="'.$ligne[1].'" id="'.$ligne[1].'" value="'.$ligne[3].'"  style="'.$ligne[4].'" '.$ligne[5].' '.$checked.'/>');
				}
				elseif($ligne[0] == "radio")
				{
					$radio = "";
					
					foreach($ligne[3] as $key => $value)
					{
						if($ligne[6] == $key)
						{
							$checked = 'checked="checked" ';
						}
						else
						{
							$checked = '';
						}
						
						$radio .= '<input type="radio" name="'.$ligne[1].'" id="'.$ligne[1].'" value="'.$key.'"  style="'.$ligne[4].'" '.$ligne[5].' '.$checked.'/> '.$value.'  ';
					}
					
					$this->addLine($ligne[2], $radio);
				}
				elseif($ligne[0] == "select")
				{
					if($ligne[6] <> "")
					{
						$size = 'size="'.$ligne[6].'" ';
					}
					else
					{
						$size = '';
					}
					
					$select = '<select name="'.$ligne[1].'" '.$ligne[5].' '.$size.' style="'.$ligne[4].'">';
						foreach($ligne[3] as $key => $value)
						{
							$select .= '<option value="'.$key.'">'.$value.'</option>';
						}
					$select .= '</select>';
					
					$this->addLine($ligne[2], $select);
				}
				elseif($ligne[0] == "image")
				{
					$this->addLine($ligne[2], '<a href="javascript://" '.$ligne[5].'><img name="'.$ligne[1].'" id="'.$ligne[1].'" src="'.$ligne[3].'" style="'.$ligne[4].'" '.$ligne[6].' border="0" /></a>');
				}
				elseif($ligne[0] == "button")
				{
					$this->addLine($ligne[2], '<input type="button" name="'.$ligne[1].'" id="'.$ligne[1].'" value="'.$ligne[3].'" style="'.$ligne[4].'" '.$ligne[5].' />');
				}
				elseif($ligne[0] == "submit")
				{
					$this->addLine($ligne[2], '<input type="submit" name="'.$ligne[1].'" id="'.$ligne[1].'" value="'.$ligne[3].'" style="'.$ligne[4].'" '.$ligne[5].' />');
				}
			}
			
			$this->content .= '</table>
			</form>';
			
			echo $this->content;
		}
		
		// Ajoute le champs du formulaire dans une ligne formatée de tableau
		function addLine($label, $input)
		{
			$source = array(
							"{CLASSCSS}",
							"{LABEL}",
							"{INPUT}"
							);
			
			$value = array(
							$this->style,
							$this->addObligatoire($label),
							$input
							);
			
			$this->content .= str_replace($source,$value,$this->template);
			
			/*
			$this->content .= '
			<tr>
				<td align="right" class="'.$this->style.'" valign="top">'.$this->addObligatoire($label).' </td>
				<td><img src="images/common/pix.gif" width="12" height="1" /></td>
				<td class="'.$this->style.'" > '.$input.'</td>
			</tr>
			<tr>
				<td colspan="3"><img src="images/common/pix.gif" width="9" height="9" /></td>
			</tr>
			';
			*/
		}
		
		// Si il y a une * dans le label alors il est obligatoire (juste pour l'affichage)
		function addObligatoire($label)
		{
			if(strpos($label,"*"))
			{
				return str_replace('*', '<span class="obligatoire">*</span>',$label);
			}
			else
			{
				return $label;
			}
		}
	/*
	========================================================================================================================================
	========================================================================================================================================
	*/
	
	
	/*
	========================================================================================================================================
	========================================================================================================================================
	Fonction pour le traitement du formulaire
	*/
		// Vérification des variable passées dans le formulaire
		function checkFrm($what = "", $where = "", $ids = "", $dossier = "", $page = "")
		{
			$tab = array();
			
			// Tableau source
			if($where == '')
			{
				$where = $_POST;
				$this->db("Formulaire POST");
			}
			elseif($where == "get")
			{
				$where = $_GET;
				$this->db("Formulaire GET");
			}
			
			if($this->vardebug == 1)
			{
				$_SESSION[SITE_CONFIG]['info'] .= "<h3>DEBUG</h3>";
				echo "<pre>";
				print_r($where);
				echo "</pre>";
			}
			
			$this->db("D&eacute;but de la boucle");
			
			// Pour chaque champ du formulaire passé
			foreach($where as $key => $value)
			{
				$value = $this->clean($value);
							
				// Si la variable n'est pas une variable id pour l'update (exemple : x_id)
				// On ajoute le champs et sa valeur à la liste des champs à ajouter/mettre à jour
				
				if(is_array($ids))
				{
					if(in_array($key,$ids))
					{
						$this->sqlwhere .= $key." = '".$value."' and ";
					}
					else
					{
						if($key <> 'Submit')
						{
							$this->sqlchamps[count($this->sqlchamps)] = $key;
							$this->sqlvalues[count($this->sqlvalues)] = $value;
						}
					}
				}
				else
				{
					if($key <> 'Submit')
					{
						$this->sqlchamps[count($this->sqlchamps)] = $key;
						$this->sqlvalues[count($this->sqlvalues)] = $value;
					}
				}
				
				$this->db("<hr style=\"margin: 0px; padding: 0px;\" />");
				$this->db("\$where['".$key."'] => ".$value);
				
				// Vérification du contenu (vide ou pas)
				$this->db("Vérification si vide");
				
				foreach($what as $wk => $wvalue)
				{
					if($wk == $key)
					{
						$this->checkVide($value, $wvalue." est obligatoire");
					}
				}
				
				/*
				if(in_array($key,$what))//$what <> "")
				{
					echo $key."<br />";
					$this->checkVide($value, $what[$key]." est obligatoire");
				}
				*/
				
				// On met les variables bettoyé dans un tableau si on a besoin de le retourner
				$tab[$key] = $value;
			}
			
			if(is_array($this->champsfile))
			{
				foreach($_FILES as $key => $value)
				{
					if(in_array($key,$this->champsfile))
					{
						$inputName = $key;
						$fileDir = $this->updir;
						
						if (checkUploadError($inputName))
						{
							$file = uploadFile($inputName,$fileDir);
							$this->sqlchamps[count($this->sqlchamps)] = $key;
							$this->sqlvalues[count($this->sqlvalues)] = $file;
						}
					}
				}
			}
			
			// On coupe le dernier and de la clause where
			if($this->sqlwhere <> '')
			{
				$this->sqlwhere = substr($this->sqlwhere,0,strlen($this->sqlwhere)-5);
			}
			
			$this->db("Fin de la boucle");
			$this->db("Vérification erreur");
			
			// Si on a une erreur on redirige sur la page d'avant sinon on continu
			$this->checkErr($page);
			
			// Si POST ou GET on remplace
			if($where == '')
			{
				$_POST = $tab;
			}
			elseif($where == "get")
			{
				$_GET = $tab;
			}
			else
			{
				return $tab;
			}
		}
		
		// Définie le répertoire d'upload et la taille des miniatures
		function setUpload($champsfile, $repertoire)
		{
			$this->champsfile = $champsfile;
			$this->updir = $repertoire;
		}
		
		// Regarde si une variable est vide. Si elle est vide alors on ajoute son message d'erreur à la liste des messages d'erreurs
		function checkVide($what,$msg)
		{
			if($what == '')
			{
				$_SESSION[SITE_CONFIG]['info'] .= $msg."<br />";
				$this->db("Vide ! ".$msg,1);
			}
		}
		
		// Regarde si il y a des erreurs. Si il y en a alors on est redirigé sur le referer sinon on continu
		function checkErr($page = "")
		{
			if($_SESSION[SITE_CONFIG]['info'] <> '')
			{
				if($page <> '')
				{
					$lien = $page;
				}
				else
				{
					$lien = $_SERVER['HTTP_REFERER'];
				}
				
				if($this->vardebug <> 1)
				{
					header("location: ".$lien);
					exit;
				}
				else
				{
					echo $_SESSION[SITE_CONFIG]['info'];
					$_SESSION[SITE_CONFIG]['info'] = "";
				}
			}
		}
		
		// Donne la liste de doublons
		function setDouble($tab)
		{
			$this->double = $tab;
		}
		
		// Vérifie si avec les données du formulaire il y a des doublons dans la base
		function checkDoublon()
		{
			$sql = "";
			$i = 0;
			
			foreach($this->double as $k => $v)
			{
				foreach($this->sqlchamps as $key => $value)
				{
					if($value == $k)
					{
						$sql .= $value." = '".$this->sqlvalues[$i+1]."' and ";
					}
				}
				
				$i++;
			}
			
			$sql = "SELECT * from ".$this->table." WHERE ".substr($sql,0,strlen($sql)-5);
			
			$req = mysql_query($sql);
			
			if(mysql_num_rows($req) > 0)
			{
				foreach($this->double as $k => $v)
				{
					$err .= $v.", ";
				}
				
				if(count($this->double) > 1)
				{
					$err = "Les valeurs que vous avez entré dans les champs <b>".substr($err,0,strlen($err)-2)."</b> sont déjà utilisé";
				}
				else
				{
					$err = "La valeur que vous avez entré dans le champ <b>".substr($err,0,strlen($err)-2)."</b> sont déjà utilisé";
				}
				
				$_SESSION[SITE_CONFIG]['info'] = $err;
				
				$this->checkErr();
			}
		}
		
		// Donne le nom de la table dans laquel on va travailler
		function setTable($table)
		{
			$this->table = $table;
		}
		
		// déclare qu'il s'agit d'un insert
		function insert()
		{
			$this->db("Insert : ",1);
			
			if(is_array($this->double))
			{
				$this->checkDoublon();
			}
			
			$this->drawSql("insert",$this->table);
	
			$this->db($this->sql,1);
			
			mysql_query($this->sql);
		}
		
		// déclare qu'il s'agit d'un update
		function update()
		{
			$this->db("Update : ",1);
			
			$this->drawSql("update",$this->table);
	
			$this->db($this->sql,1);
			
			mysql_query($this->sql);
		}
		
		// Construit la requete sql qu'il s'agisse d'une update ou d'un insert
		function drawSql($type, $table)
		{
			$query1 = "";
			$query2 = "";
			
			for($i=0;$i<count($this->sqlvalues);$i++)
			{
				if($type == "insert")
				{
					$query1 .= "`".$this->sqlchamps[$i]."`, ";
					$query2 .= "'".$this->sqlvalues[$i]."', ";
				}
				elseif($type == "update")
				{
					$query1 .= $this->sqlchamps[$i]." = '".$this->sqlvalues[$i]."', ";
				}
			}
			
			$query1 = substr($query1,0,strlen($query1)-2);
			$query2 = substr($query2,0,strlen($query2)-2);
			
			if($type == "insert")
			{
				$this->sql = "INSERT INTO ".$table." (".$query1.") VALUES (".$query2.")";
			}
			elseif($type == "update")
			{
				$this->sql = "UPDATE ".$table." set ".$query1." WHERE ".$this->sqlwhere;
			}
		}
	/*
	========================================================================================================================================
	========================================================================================================================================
	*/
	
	
	/*
	========================================================================================================================================
	========================================================================================================================================
	Fonction d'envoi de mail aprés le formulaire
	*/
		// HTML au dessus du message
		function setHeader($html)
		{
			$this->mailheader = $html;
		}
		
		// HTML en dessous du message
		function setFooter($html)
		{
			$this->mailfooter = $html;
		}
		
		// Envoi du mail
		function sendMail($from,$to,$titre, $entete = "")
		{
			$html = "";

			for($i = 0; $i < count($this->sqlchamps); $i++)
			{
				$html .=  "<b>".str_replace("_"," ",$this->sqlchamps[$i])."</b> : ".$this->sqlvalues[$i]."<br />";
			}
			
			$html = $this->mailheader.$html.$this->mailfooter;
			
			mail($to,$titre,$html,"MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: ".$from."\r\n");
		}
	/*
	========================================================================================================================================
	========================================================================================================================================
	*/
	
	
	/*
	========================================================================================================================================
	========================================================================================================================================
	Fonction divers
	*/
		// Ecrit le mode debug
		function db($what, $bold = 0)
		{
			if($this->vardebug == 1)
			{
				if($bold == 0)
				{
					echo $what."<br />";
				}
				else
				{
					echo "<b>".$what."</b><br />";
				}
			}
		}
		
		// Nettoi
		function sanitize($string)
		{
			$search = array('|</?\s*SCRIPT.*?>|si','|</?\s*FRAME.*?>|si','|</?\s*OBJECT.*?>|si','|</?\s*META.*?>|si','|</?\s*APPLET.*?>|si','|</?\s*LINK.*?>|si','|</?\s*IFRAME.*?>|si','|STYLE\s*=\s*"[^"]*"|si','|javascript|si','|</?\s*FORM.*?>|si','|</?\s*INPUT.*?>|si');
			$replace = array('');
			$string = preg_replace($search, $replace,$string);
			return $string;
		}
		
		// Aussi
		function clean($string,$br=1)
		{
			$string = $this->sanitize(' '.$string.' ');
			if (strpos($string,'\\') === false) $string = addslashes($string); // Magic Quotes ?
			$unwanted = array('|</?\s*P.*?>|si','|</?\s*DIV.*?>|si','|</?\s*BR.*?>|si');
			$wanted = array('<br />','<br />','<br />');
			$string = preg_replace($unwanted,$wanted,$string);
			$string = str_replace('&rdquo;','"',str_replace('”','"',$string));
			if (strpos($string,'<br />') !== false)
			{
				$string = str_replace('<br />&nbsp;<br />','',$string);
				while (strpos(substr($string,0,8),'<br />') !== false) $string = str_replace('<br />','',substr($string,0,8)).substr($string,8); // BR de début
				while (strpos(substr($string,-8),'<br />') !== false) $string = substr($string,0,-8).str_replace('<br />','',substr($string,-8));
				if ($br == 0) $string = str_replace('<br />',' ',str_replace(chr(13).chr(10),'',$string));
			}
			
			if ($br == 2) str_replace(chr(13).chr(10),'<br />',$string);
			return trim($string);
		}
	/*
	========================================================================================================================================
	========================================================================================================================================
	*/
}
?>