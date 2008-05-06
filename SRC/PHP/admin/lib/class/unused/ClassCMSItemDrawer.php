<?php

/**
* Classe permettant de déssiner l'ensemble des blocs du CMS
*
* Liste des blocs : 
*
*		br, hr, 
*		h1, h2, h3, h4, 
*		p, important, note, marquee, code, citation,
*		ul, ol, dl, 
*		rte, rte_image, image, video
*		table, focus, 
*		a_externe, a_interne, a_doc, a_list, a_doc_list,
*		iframe, flash, bouton, sommaire
*
*		
* EXAMPLES
*
*		CMSItemDrawer::getItemH1( "", "Titre de niveau 1" );
*
*		pour dessiner un paragraphe important : 
*		CMSItemDrawer::getItemImportant( "", "Paragraphe important" ); 
*		
*		OU bien :
*		 
*		$drawer =& new CMSItemDrawer(); 
*		$drawer->getView( "h1", "", "Titre de niveau 1" );
*		$drawer->getView( "important", "", "Paragraphe important" );
*/

class CMSItemDrawer
{
	/**
	* Tableau contenant les codes des blocs CMS utilisables dans l'éditeur de contenu
	* @var array
	*/
	var $components;

	/**
	* Chaîne retournée quand la valeur d'un bloc est vide
	* @var string
	*/
	var $empty_value;

	/**
	* Chaîne concaténée aux id des éléments contenus dans un bloc
	* @var string
	*/
	var $suffix;

	/**
	* Construit un objet permettant de dessiner les blocs du CMS
	*/
	function CMSItemDrawer()
	{
		$this->__construct();
	}

	/**
	* Constructeur (> PHP5)
	*/
	function __construct()
	{
		$this->setAllowedComponents();
		$this->setEmptyValue( "" );
	}

	/**
	* Définit la liste des composants utilisables dans le CMS
	*/
	function setAllowedComponents( $allowed_components="" )
	{
		if( empty( $allowed ) ) 
		{
			$this->components = $this->getDefaultAllowedComponents();
		}
		else
		{
			$this->components = $allowed_components;
		}
	}

	/**
	* Retourne la liste des composants utilisables dans le CMS
	*/
	function getAllowedComponents()
	{
		return $this->components;
	}

	/**
	* Définit la liste des composants par défaut utilisables dans le CMS
	*/
	function getDefaultAllowedComponents()
	{
		return array(	"br",				"hr",				"h1",				"h2",
						"h3",				"h4",				"p",				"important",
						"note",				"marquee",			"code",				"ul",
						"ol",				"dl",				"citation",			"rte",
						"rte_image",		"image",			"table",			"focus",
						"a_externe",		"a_interne",		"a_doc",			"a_list",
						"a_doc_list",		"video",			"iframe",			"flash",
						"bouton",			"sommaire"			);
	}

	/**
	* Définit le suffixe à ajouter au id des éléments pour générer un id unique
	*/
	function setIdsSuffix( $suffix )
	{
		$this->suffix = $suffix;
	}

	/**
	* Retourne le suffixe à ajouter au id des éléments pour générer un id unique
	*/
	function getIdsSuffix()
	{
		static $counter;
		return date( "YmdHis", time() ) . "_" . ( ++ $counter );

		return $this->suffix;
	}

	/**
	* Définit la valeur affectée à l'élément quand son contenu est vide
	*/
	function setEmptyValue( $empty_value="" )
	{
		$this->empty_value = $empty_value;
	}

	/**
	* Retourne la valeur affectée à l'élément quand son contenu est vide
	*/
	function getEmptyValue()
	{
		return $this->empty_value;
	}

	/**
	* Supprime les caractères non autorisés d'une valeur de type texte (plain text)
	*/
	function textValue( $value, $nl2br=false )
	{
		$text = trim( htmlentities( strip_tags( $value ) ) );

		$text = CMSItemDrawer::replaceOfficeCharacters( $text );

		if( $nl2br ) $text = nl2br( $text );

		return $text;
	}

	/**
	* Supprime les caractères non autorisés d'une valeur de type html (html text)
	*/
	function htmlValue( $value )
	{
		$html = trim( $value );
		
		return $html;
	}

	/**
	* Remplace les caractères spécifiques à Office et non autorisés dans un contenu texte ou html
	*/
	function replaceOfficeCharacters( $text )
	{
		if( trim( $text ) == "" ) return $text;

		$chars_ko = array
		(	
			'’', /* quote gauche inclinée (apostrophe) */
			'‘', 	/* quote droite inclinée */
			'…', 	/* ... */
			'œ' , 	/* e dans l'o (min) */
			'Œ' , 	/* E dans l'O (maj) */
			'«',	/* Guillemets ouvrante */
			'»',	/* Guillemets fermants */
			'€' 	/* euro, en denier car € apparaît dans certains codes (â€¦) */
		);

		$chars_ok = array
		(
			'&rsquo;', 
			'&lsquo;', 
			'&hellip;', 
			'&oelig;', 
			'&OElig;', 
			'&laquo;',
			'&raquo;',
			'&euro;'
		);

		return str_replace( $chars_ko, $chars_ok, $text );
	}
	
	function getView()
	{
		$all_args = func_get_args();

		$item = @array_shift( $all_args );
		$type = @array_shift( $all_args );

		foreach( $all_args as $k => $arg ) {
			$var = "p_".$k;
			$$var = $arg;
			$params[] = $var;
		}
		 
		if( ! in_array( $item, $this->getAllowedComponents() ) ) {
			return "CMS component with name " . $item . " was not found or is not allowed in this project !!!";
		}

		$method = "getItem".ucwords( strtolower( str_replace( "_", " ", $item ) ) );
		$code = "\$html = \$this->".$method."( \$type, ";

		foreach( $params as $k => $prm ) {
			$code.= ( $k > 0 ? ", " : "" ) . "\$".$prm;
		}
		$code.= " );";
		eval( $code );

		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un espacement vertical
	*/
	function getItemBr( $type="" )
	{
		$html = '
			<br class="portlet cms_br" />
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une ligne de séparation
	*/
	function getItemHr( $type="" )
	{
		$html = '
			<hr class="portlet cms_hr" />
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un titre de niveau 1
	*/
	function getItemH1( $type="", $value )
	{
		$html = '
			<div class="portlet cms_h1">
				<div class="d1">
					<div class="d2">
						<h1>'.CMSItemDrawer::textValue( $value ).'</h1>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un titre de niveau 2
	*/
	function getItemH2( $type="", $value )
	{
		$html = '
			<div class="portlet cms_h2">
				<div class="d1">
					<div class="d2">
						<h2>'.CMSItemDrawer::textValue( $value ).'</h2>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un titre de niveau 3
	*/
	function getItemH3( $type="", $value )
	{
		$html = '
			<div class="portlet cms_h3">
				<div class="d1">
					<div class="d2">
						<h3>'.CMSItemDrawer::textValue( $value ).'</h3>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un titre de niveau 4
	*/
	function getItemH4( $type="", $value )
	{
		$html = '
			<div class="portlet cms_h4">
				<div class="d1">
					<div class="d2">
						<h4>'.CMSItemDrawer::textValue( $value ).'</h4>
					</div>
				</div>
			</div>		
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un paragraphe standard
	*/
	function getItemP( $type="", $value, $nl2br=true )
	{
		$html = '
			<div class="portlet cms_p">
				<div class="d1">
					<div class="d2">
						<p>'.CMSItemDrawer::textValue( $value, $nl2br ).'</p>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un paragraphe important
	*/
	function getItemImportant( $type="", $value, $nl2br=true )
	{
		$html = '
			<div class="portlet cms_p_strong">
				<div class="d1">
					<div class="d2">
						<p><strong>'.CMSItemDrawer::textValue( $value, $nl2br ).'</strong></p>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une note
	*/
	function getItemNote( $type="", $value, $nl2br=true )
	{
		$html = '
			<div class="portlet cms_p_note">
				<div class="d1">
					<div class="d2">
						<p><em>'.CMSItemDrawer::textValue( $value, $nl2br ).'</em></p>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un texte défilant
	*/
	function getItemMarquee( $type="", $value, $direction="left", $amount="2", $delay="1" )
	{
		$html = '
			<div class="portlet cms_marquee">
				<div class="d1">
					<div class="d2">
						<marquee'.
							($direction!=""?' direction="'.$direction.'"':'').
							($amount!=""?' scrollamount="'.$amount.'"':'').
							($delay!=""?' scrolldelay="'.$delay.'"':'').'>
							<p>'.CMSItemDrawer::textValue( $value ).'</p>
						</marquee>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un bout de code dans un langage de programmation
	*/
	function getItemCode( $type="", $value="" )
	{
		$html = '
			<div class="portlet cms_code">
				<div class="d1">
					<div class="d2">
						<pre><code>'.$value.'</code></pre>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une liste à puce
	*/
	function getItemUl( $type="", $items )
	{
		if( count( $items ) <= 0 ) return $this->getEmptyValue();

		$value = "";
		foreach( $items as $item ) {
			if( $value != "" ) $value.= "\n\t\t\t\t\t\t\t";
			$value.= '<li>'.CMSItemDrawer::textValue( $item ).'</li>';
		}
		$html = '
			<div class="portlet cms_ul">
				<div class="d1">
					<div class="d2">
						'.($value!=""
						  ?'<ul>
							'.$value.'
						</ul>'
						  :'').'
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une liste numérotée
	*/
	function getItemOl( $type="", $items )
	{
		if( count( $items ) <= 0 ) return $this->getEmptyValue();

		$value = "";
		foreach( $items as $item ) {
			if( $value != "" ) $value.= "\n\t\t\t\t\t\t\t";
			$value.= '<li>'.CMSItemDrawer::textValue( $item ).'</li>';
		}
		$html = '
			<div class="portlet cms_ol">
				<div class="d1">
					<div class="d2">
						'.($value!=""
						  ?'<ol>
							'.$value.'
						</ol>'
						  :'').'
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une liste de définition
	*/
	function getItemDl( $type="", $items, $nl2br=true )
	{
		if( count( $items ) <= 0 ) return $this->getEmptyValue();

		$value = "";
		foreach( $items as $item ) {
			if( $value != "" ) $value.= "\n\t\t\t\t\t\t\t";
			$value.= '<dt>'.CMSItemDrawer::textValue( $item["title"] ).'</dt>';
			$value.= "\n\t\t\t\t\t\t\t";
			$value.= '<dd>'.CMSItemDrawer::textValue( $item["description"], $nl2br ).'</dd>';
		}

		$html = '
			<div class="portlet cms_definition">
				<div class="d1">
					<div class="d2">
						'.($value!=''
						  ?'<dl>
							'.$value.'
						</dl>'
						  :'').'
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une citation d'auteur
	*/
	function getItemCitation( $type="cite", $value, $author="", $text="", $items="", $nl2br=true )
	{
		$list = "";

		if( is_array( $items ) && count( $items ) > 0 ) {
			$list.= '<ul>
						';
			foreach( $items as $k => $item ) {
				if( $k > 0 ) $list.= "\n\t\t\t\t\t\t";
				$list.= '<li>'.CMSItemDrawer::textValue( $item ).'</li>';
			}
			$list.= '
					</ul>';
		}

		$html = "";
		if( $type == "cite" || $type == "" ) 
		{
		  $html = '
			<div class="portlet cms_quotation">
				<div class="d1">
					<div class="d2">
						<blockquote>
							<p>'.CMSItemDrawer::textValue( $value, $nl2br ).'</p>
							<cite>'.CMSItemDrawer::textValue( $author ).'</cite>
						</blockquote>
					</div>
				</div>
			</div>
		';
		}
		elseif( $type == "text_citeleft" )
		{
		  $html = '
			<div class="portlet cms_quotation_texte">
				<div class="citation_gauche">
					<div class="d1">
						<div class="d2">
							<blockquote>
								<p>'.CMSItemDrawer::textValue( $value, $nl2br ).'</p>
								<cite>'.CMSItemDrawer::textValue( $author ).'</cite>
							</blockquote>
						</div>
					</div>
				</div>
				'.( $text != "" ? '<p>'.CMSItemDrawer::htmlValue( $text ).'</p>' : '' ).'
				'.$list.'
			</div>
		';
		}
		elseif( $type == "text_citeright" )
		{
		  $html = '
			<div class="portlet cms_quotation_texte">
				<div class="citation_droite">
					<div class="d1">
						<div class="d2">
							<blockquote>
								<p>'.CMSItemDrawer::textValue( $value, $nl2br ).'</p>
								<cite>'.CMSItemDrawer::textValue( $author ).'</cite>
							</blockquote>
						</div>
					</div>
				</div>
				'.( $text != "" ? '<p>'.CMSItemDrawer::htmlValue( $text ).'</p>' : '' ).'
				'.$list.'
			</div>
		';
		}
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un contenu html
	*/
	function getItemRte( $type="", $value )
	{
		$html = '
			<div class="portlet cms_rte">
				'.CMSItemDrawer::htmlValue( $value ).'
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un contenu html et une image, 
	* positionnée à gauche ou à droite, avec ou sans lien
	*/
	function getItemRteImage( $type="text_imageleft", $value, $source, $legend="", $author="", $href="", $nl2br=false, $separator=" / " )
	{
		$html = '';
		if( $type == "text_imageleft" || $type == "" )
		{
		  $html = '
			<div class="portlet cms_txt_img">
				<div class="visu_gauche">
					<img src="'.$source.'" alt="" />
					<cite>'.CMSItemDrawer::textValue( $legend, $nl2br ).
				   ($legend!=""&&$author!=""?'<br />':'').
					CMSItemDrawer::textValue( $author ).'</cite>
				</div>
				'.CMSItemDrawer::htmlValue( $value ).'
			</div>
		';
		}
		elseif( $type == "text_imageright" )
		{
		  $html = '
			<div class="portlet cms_txt_img">
				<div class="visu_droite">
					<img src="'.$source.'" alt="" />
					<cite>'.CMSItemDrawer::textValue( $legend, $nl2br ).
				   ($legend!=""&&$author!=""?'<br />':'').
					CMSItemDrawer::textValue( $author ).'</cite>
				</div>
				'.$value.'
			</div>
		';
		}
		elseif( $type == "text_linkimageleft" )
		{
		  $html = '
			<div class="portlet cms_txt_img">
				<div class="visu_gauche">
					<a href="'.$href.'" rel="lightbox" title="'.
					CMSItemDrawer::textValue( $legend.$separator.$author ).'" >' .
					'<img src="'.$source.'" alt="" /></a>
					'.( $legend!=""&&$author!=""
						? '<cite>'.CMSItemDrawer::textValue( $legend, $nl2br ).
						  ($legend!=""&&$author!=""?'<br />':'').
						  CMSItemDrawer::textValue( $author ).'</cite>'
						:'').'
				</div>
				'.$value.'
			</div>
		';
		}
		elseif( $type == "text_linkimageright" )
		{
		  $html = '
			<div class="portlet cms_txt_img">
				<div class="visu_droite">
					<a href="'.$href.'" rel="lightbox" title="'.
					CMSItemDrawer::textValue( $legend.$separator.$author ).'" >' .
					'<img src="'.$source.'" alt="" /></a>
					'.( $legend!=""&&$author!=""
						? '<cite>'.CMSItemDrawer::textValue( $legend, $nl2br ).
						  ($legend!=""&&$author!=""?'<br />':'').
						  CMSItemDrawer::textValue( $author ).'</cite>'
						:'').'
				</div>
				'.$value.'
			</div>
			';
		}
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une image, avec ou sans lien
	*/
	function getItemImage( $type="image", $source, $legend="", $author="", $href="", $separator=" / " )
	{
		$html = "";
		if( $type == "image" || $type == "" ) 
		{
		  $html = '
			<div class="portlet cms_img">
				<img src="'.$source.'" alt="" />
				'.( $legend != "" || $author != "" 
					? '<cite>'.CMSItemDrawer::textValue( $legend ) .
					  ( $author != "" 
						? ( $legend != '' 
							? '<br />' 
							: '' ) . CMSItemDrawer::textValue( $author )
						  : '' ) . '</cite>'
					: '' ) . '
			</div>
		';
		}
		elseif( $type == "linkimage" )
		{
		  $html = '
			<div class="portlet cms_img">
				<a href="'.$href.'" rel="lightbox"'.
				( $legend != "" || $author != "" 
				  ? ' title="'.CMSItemDrawer::textValue( $legend.( $author != "" ? ( $legend != "" ? $separator : '' ) . $author : '' ) ).'"'
				  : '' ) . '>' .
				'<img src="'.$source.'" alt="" /></a>
				'.( $legend != "" || $author != "" 
					? '<cite>'.CMSItemDrawer::textValue( $legend ) .
					  ( $author != "" 
						? ( $legend != '' 
							? '<br />' 
							: '' ) . CMSItemDrawer::textValue( $author )
						: '' ) . '</cite>'
					: '' ) . '
			</div>
		';
		}
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un tableau
	*/
	function getItemTable( $type="", $items, $headers="", $title="" )
	{
		if( count( $headers ) <= 0 && count( $items ) <= 0 ) return $this->getEmptyValue();

		$value = "";
		if( count( $headers ) > 0 ) {
			$value.= '<tr>';
			foreach( $headers as $head ) {
				$value.= '<th>'.CMSItemDrawer::textValue( $head ).'</th>';
			}
			$value.= '</tr>';
		}
		if( count( $items ) > 0 ) {
			foreach( $items as $row ) {
				$value.= '<tr>';
				foreach( $row as $item ) {
					$value.= '<td>'.CMSItemDrawer::htmlValue( $item ).'</td>';
				}
				$value.= '</tr>';
			}
		}
		$html = '
			<div class="portlet cms_tableau">
				'.( $title != "" ? '<h1>'.CMSItemDrawer::textValue( $title ).'</h1>' : '' ) .'
				<table border="0" cellspacing="0" cellpadding="0" ' .
				( $title != "" ? ' summary="'.CMSItemDrawer::textValue( $title ).'"' : '' ) . '>
				  '.$value.'
				</table>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un bloc de mise en avant (focus)
	*/
	function getItemFocus( $type="focus", $title, $value="", $source="", $legend="", $author="", $href="", $url="", $doc="", $separator=" / " )
	{
		$html = '';
		if( $type == "focus" || $type == "" )
		{
			$html = '
				<div class="portlet cms_focus">
					<div class="d1">
						<div class="d2">
							<h1>'.CMSItemDrawer::textValue( $title ).'</h1>
							'.CMSItemDrawer::htmlValue( $value ).
							( ! empty( $url ) || ! empty( $doc ) ? '
							<ul class="liens">'.
							( ! empty( $url ) 
							  ? '
								<li class="url"><a href="'.$url["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $url["title"] ).'</a></li>' 
							  : '' ). 
							( ! empty( $doc ) 
							  ? '
								<li class="doc"><a href="'.$doc["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $doc["title"] ).'</a></li>'
							  : '' ) .'
							</ul>' : '' ) . '
						</div>
					</div>
				</div>
			';
		}
		elseif( $type == "focus_imageleft" )
		{
			$html = '
				<div class="portlet cms_focus">
					<div class="d1">
						<div class="d2">
							<h1>'.CMSItemDrawer::textValue( $title ).'</h1>
							<div class="visu_gauche">
								<img src="'.$source.'" alt="" />
								'.( $legend != "" || $author != "" 
									? '<cite>'.CMSItemDrawer::textValue( $legend ).
									  ( $legend != "" ? '<br />' : '' ) .
									  CMSItemDrawer::textValue( $author ).'</cite>' 
									: '').'
							</div>
							'.CMSItemDrawer::htmlValue( $value ).
							( ! empty( $url ) || ! empty( $doc ) ? '
							<ul class="liens">'.
							( ! empty( $url ) 
							  ? '
								<li class="url"><a href="'.$url["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $url["title"] ).'</a></li>' 
							  : '' ). 
							( ! empty( $doc ) 
							  ? '
								<li class="doc"><a href="'.$doc["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $doc["title"] ).'</a></li>'
							  : '' ) .'
							</ul>' : '' ) . '
						</div>
					</div>
				</div>
			';
		}
		elseif( $type == "focus_imageright" )
		{
			$html = '
				<div class="portlet cms_focus">
					<div class="d1">
						<div class="d2">
							<h1>'.CMSItemDrawer::textValue( $title ).'</h1>
							<div class="visu_droite">
								<img src="'.$source.'" alt="" />
								'.( $legend != "" || $author != "" 
									? '<cite>'.CMSItemDrawer::textValue( $legend ).
									  ( $legend != "" ? '<br />' : '' ) .
									  CMSItemDrawer::textValue( $author ).'</cite>' 
									: '').'
							</div>
							'.CMSItemDrawer::htmlValue( $value ).
							( ! empty( $url ) || ! empty( $doc ) ? '
							<ul class="liens">'.
							( ! empty( $url ) 
							  ? '
								<li class="url"><a href="'.$url["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $url["title"] ).'</a></li>' 
							  : '' ). 
							( ! empty( $doc ) 
							  ? '
								<li class="doc"><a href="'.$doc["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $doc["title"] ).'</a></li>'
							  : '' ) .'
							</ul>' : '' ) . '
						</div>
					</div>
				</div>
			';
		}
		elseif( $type == "focus_linkimageleft" )
		{
			$html = '
				<div class="portlet cms_focus">
					<div class="d1">
						<div class="d2">
							<h1>'.CMSItemDrawer::textValue( $title ).'</h1>
							<div class="visu_gauche">
								<a href="'.$href.'" rel="lightbox" title="'.
								CMSItemDrawer::textValue( $legend.$separator.$author ).'" >'.
							   '<img src="'.$source.'" alt="" /></a>
								'.( $legend != "" || $author != "" 
									? '<cite>'.CMSItemDrawer::textValue( $legend ).
									  ( $legend != "" ? '<br />' : '' ) .
									  CMSItemDrawer::textValue( $author ).'</cite>' 
									: '').'
							</div>
							'.CMSItemDrawer::htmlValue( $value ).
							( ! empty( $url ) || ! empty( $doc ) ? '
							<ul class="liens">'.
							( ! empty( $url ) 
							  ? '
								<li class="url"><a href="'.$url["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $url["title"] ).'</a></li>' 
							  : '' ). 
							( ! empty( $doc ) 
							  ? '
								<li class="doc"><a href="'.$doc["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $doc["title"] ).'</a></li>'
							  : '' ) .'
							</ul>' : '' ) . '
						</div>
					</div>
				</div>
			';
		}
		elseif( $type == "focus_linkimageright" )
		{
			$html = '
				<div class="portlet cms_focus">
					<div class="d1">
						<div class="d2">
							<h1>'.CMSItemDrawer::textValue( $title ).'</h1>
							<div class="visu_droite">
								<a href="'.$href.'" rel="lightbox" title="'.
								CMSItemDrawer::textValue( $legend.$separator.$author ).'" >'.
							   '<img src="'.$source.'" alt="" /></a>
								'.( $legend != "" || $author != "" 
									? '<cite>'.CMSItemDrawer::textValue( $legend ).
									  ( $legend != "" ? '<br />' : '' ) .
									  CMSItemDrawer::textValue( $author ).'</cite>' 
									: '').'
							</div>
							'.CMSItemDrawer::htmlValue( $value ).
							( ! empty( $url ) || ! empty( $doc ) ? '
							<ul class="liens">'.
							( ! empty( $url ) 
							  ? '
								<li class="url"><a href="'.$url["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $url["title"] ).'</a></li>' 
							  : '' ). 
							( ! empty( $doc ) 
							  ? '
								<li class="doc"><a href="'.$doc["href"].'" target="_blank">'.
								CMSItemDrawer::textValue( $doc["title"] ).'</a></li>'
							  : '' ) .'
							</ul>' : '' ) . '
						</div>
					</div>
				</div>
			';
		}
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un lien externe
	*/
	function getItemAExterne( $type="", $href, $value="" )
	{
		if( $value == "" && $href == "" ) return $this->getEmptyValue();
		if( $value == "" ) $value = $href;
		$html = '
			<div class="portlet cms_a_external">
				<div class="d1">
					<div class="d2">
						<a href="'.$href.'" target="'.
						(eregi("^javascript\:", $href)?"_self":"_blank").'">'.
						CMSItemDrawer::textValue( $value ).'</a>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un lien interne
	*/
	function getItemAInterne( $type="", $href, $value="" )
	{
		if( $value == "" && $href == "" ) return $this->getEmptyValue();
		if( $value == "" ) $value = $href;
		$html = '
			<div class="portlet cms_a_internal">
				<div class="d1">
					<div class="d2">
						<a href="'.$href.'">'.CMSItemDrawer::textValue( $value ).'</a>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un lien interne
	*/
	function getItemADoc( $type="", $href, $value="" )
	{
		if( $value == "" && $href == "" ) return $this->getEmptyValue();
		if( $value == "" ) $value = $href;
		$html = '
			<div class="portlet cms_a_download">
				<div class="d1">
					<div class="d2">
						<a href="'.$href.'">'.CMSItemDrawer::textValue( $value ).'</a>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une liste de liens
	*/
	function getItemAList( $type="", $items )
	{
		if( count( $items ) <= 0 ) return $this->getEmptyValue();

		$value = "";
		foreach( $items as $item ) {
			$value.= ( $value != "" ? "\n\t\t\t\t\t\t\t" : "" );
			$value.= '<li><a href="'.$item["href"].'">';
			$value.= ( $item["title"] != "" ? CMSItemDrawer::textValue( $item["title"] ) : $item["href"] );
			$value.= '</a></li>';
		}
		$html = '
			<div class="portlet cms_a_list">
				<div class="d1">
					<div class="d2">
						<ul>
							'.$value.'
						</ul>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une liste de documents à télécharger
	*/
	function getItemADocList( $type="", $items )
	{
		if( count( $items ) <= 0 ) return $this->getEmptyValue();

		$value = "";
		foreach( $items as $item ) {
			$value.= ( $value != "" ? "\n\t\t\t\t\t\t\t" : "" );
			$value.= '<li><a href="'.$item["href"].'">';
			$value.= ( $item["title"] != "" ? CMSItemDrawer::textValue( $item["title"] ) : $item["href"] );
			$value.= '</a></li>';
		}
		$html = '
			<div class="portlet cms_a_list_download">
				<div class="d1">
					<div class="d2">
						<ul>
							'.$value.'
						</ul>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une vidéo
	*/
	function getItemVideo( $type="flash", $source, $width="100%", $height="100%", $version="8", $quality="high", $wmode="transparent", $bgcolor="#ffffff", $autostart="true",  $controller="false" )
	{
		$html = '';
		$suffix = CMSItemDrawer::getIdsSuffix();
		if( $type == "flash" || $type == "" )
		{
			$html = '
				<div class="portlet cms_video">
					<div id="flash_video_'.$suffix.'"></div>
					<script type="text/javascript">
					// <![CDATA[
					var so = new SWFObject("'.$source.'", "swf_flash_video_'.$suffix.'", "'.$width.'", "'.$height.'", "'.$version.'", "");	
					'.( $quality != "" ? 'so.addParam("quality", "'.$quality.'");' : '' ).'
					'.( $bgcolor != "" ? 'so.addParam("bgcolor", "'.$bgcolor.'");' : '' ).'
					//so.addVariable("", "");
					so.write("flash_video_'.$suffix.'");
					// ]]>
					</script>
				</div>
			';
		}
		elseif( $type == "external" )
		{
			$html = '
				<div class="portlet cms_video">
					<object width="'.$width.'" height="'.$height.'">
						<param name="movie" value="'.$source.'"></param>
						<param name="wmode" value="'.$wmode.'"></param>
						<embed src="'.$source.'" type="application/x-shockwave-flash" wmode="'.$wmode.'" width="'.$width.'" height="'.$height.'"></embed>
					</object>
				</div>
			';
		}
		elseif( $type == "local" )
		{
			$html = '
				<div class="portlet cms_video">
					<object width="'.$width.'" height="'.$height.'">
						<embed type="application/x-mplayer2" src="'.$source.'" autostart="'.$autostart.'" controller="'.$controller.'" width="'.$width.'" height="'.$height.'">
					</object>
				</div>
			';
		}
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant une iframe
	*/
	function getItemIframe( $type="", $source )
	{
		$html = '
			<div class="portlet cms_iframe">
				<iframe src="'.$source.'"></iframe>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un composant flash
	*/
	function getItemFlash( $type="", $source, $width="100%", $height="100%", $version="8", $quality="high", $bgcolor="", $message="" )
	{
		if( $message == "" ) {
			$message = 'Vous devez disposer du ';
			$message.= '<a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">';
			$message.= 'Player Flash</a> pour lire cette animation';
		}
		$suffix = CMSItemDrawer::getIdsSuffix();
		$html = '
			<div class="portlet cms_flash">
				<div id="anim_'.$suffix.'">
					<p>'.$message.'</p>
				</div>
				<script type="text/javascript">
				// <![CDATA[
				var so = new SWFObject( "'.$source.'", "swf_anim_'.$suffix.'", "'.$width.'", "'.$height.'", "'.$version.'", "" );	
				so.addParam("quality", "'.$quality.'");
				so.addParam("bgcolor", "'.$bgcolor.'");
				//so.addVariable("", "");
				so.write("anim_'.$suffix.'");
				// ]]>
				</script>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un bouton d'action
	*/
	function getItemBouton( $type="", $value, $href )
	{
		$html = '
			<div class="portlet cms_bouton">
				<div class="d1">
					<div class="d2">
						<a href="'.$href.'">'.CMSItemDrawer::textValue( $value ).'</a>
					</div>
				</div>
			</div>
		';
		return $html;
	}

	/**
	* Retourne le contenu CMS représentant un sommaire
	*/
	function getItemSommaire( $type="", $items )
	{
		if( count( $items ) <= 0 ) return $this->getEmptyValue();
		
		$value = "";
		foreach( $items as $item ) {
			$value.= ( $value != "" ? "\n\t\t\t\t" : "" );
			$value.= '<li><a href="#'.$item["anchor"].'">'.$item["title"].'</a></li>';
		}
		$html = '
			<div class="portlet cms_sommaire">
				<div class="d1">
					<div class="d2">
						<ul>
							'.$value.'
						</ul>
					</div>
				</div>
			</div>
		';
		return $html;
	}
}

?>