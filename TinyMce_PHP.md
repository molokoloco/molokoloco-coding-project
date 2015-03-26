_Library edited from 2005 to 2007..._ Framework here : [trunk/SITE\_01\_SRC/admin/lib/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fadmin%2Flib) et ici [trunk/SITE\_01\_SRC/admin/lib/class/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fadmin%2Flib%2Fclass)

# Personal intregration of [TinyMce](http://tinymce.moxiecode.com/) with PHP #

## admin/lib/class\_tinymce.php ##

```
<?php 
/*
 * TinyMce - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.TinyMce.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: TinyMce.php
 * 	This is the integration file for PHP.
 * 	
 * 	It defines the TinyMce class that can be used to create editor
 * 	instances in PHP pages on server side.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@TinyMce.net)
 
 * EXEMPLE 
 
 require_once('admin/lib/class_tinymce.php');
 $Ed = new TinyMce('myInputName');
 $Ed->ToolbarSet = 'Basic';  // BasicStyle | BasicTab | BasicImg
 $Ed->Width = '100%';
 $Ed->Height = '280';
 $Ed->Value = str_replace('&quot;','"','Ici le texte...');
 echo $Ed->Create();

 */

class TinyMce
{
	var $InstanceName ;
	var $BasePath ;
	var $Width ;
	var $Height ;
	var $ToolbarSet ;
	var $Value ;
	var $Config ;

	// PHP 5
	function __construct( $instanceName )
 	{
		$this->InstanceName	= $instanceName ;
		$this->BasePath		= '/tinymce/' ;
		$this->width		= '80%';
		$this->height		= '290';
		$this->ToolbarSet	= 'Default' ;
		$this->Value		= '' ;
		$this->Config		= array() ;
	}
	
	// PHP 4
	function TinyMce( $instanceName )
	{
		$this->__construct( $instanceName ) ;
	}

	function Create()
	{
		return $this->CreateHtml() ;
	}
	
	function CreateHtml()
	{
		global $WWW;
		$HtmlValue = htmlspecialchars( $this->Value ) ;

		// Default CONFIG
		$toolbar = $toolbar2 = $toolbar3 = '';
		$plugg = 'paste,preview,inlinepopups,contextmenu,';
		$config_supp = '';
		
		// Switch TOOLBAR
		switch($this->ToolbarSet) { // Default, Basic, BasicImg, BasicStyle, BasicTab
			
			case 'BasicStyle' :
				$arrayStyles = 'Bleu=bleu'; //;
				
				$toolbar .= 'preview,|,bold,italic,|,styleselect,|,link,unlink,|,bullist,numlist,indent,outdent,justifyleft,justifycenter,justifyright,justifyfull,|,pasteword,cleanup,removeformat,code';
				$config_supp .= "
					theme_advanced_styles : '$arrayStyles',
					theme_advanced_toolbar_location : 'top',
					theme_advanced_statusbar_location : 'bottom',
					theme_advanced_path : true,
					plugin_preview_width : '460',
					plugin_preview_height : '600',
					plugin_preview_pageurl : '".$WWW."admin/lib/tinymce/plugins/preview/example.html',
					popups_css_add : '".$WWW."admin/style_admin.css.php',
				";
			break;
			
			case 'BasicTab' :
				$toolbar .= 'preview,|,bold,italic,|,link,unlink,|,bullist,numlist,indent,outdent,justifyleft,justifycenter,justifyright,justifyfull,|,pasteword,cleanup,removeformat,code';
				$toolbar2 = 'tablecontrols';
				$plugg .= ',table';
				$config_supp .= "
					table_cell_limit : 100,
					table_row_limit : 6,
					table_col_limit : 6,
					theme_advanced_toolbar_location : 'top',
					theme_advanced_statusbar_location : 'bottom',
					plugin_preview_width : '460',
					plugin_preview_height : '600',
					plugin_preview_pageurl : '".$WWW."admin/lib/tinymce/plugins/preview/example.html',
					popups_css_add : '".$WWW."admin/style_admin.css.php',
				";
			break;
			
			case 'BasicImg' :
				$toolbar .= 'image,|,bold,italic,|,link,unlink,|,bullist,numlist,indent,outdent,justifyleft,justifycenter,justifyright,justifyfull,|,pasteword,cleanup,removeformat,code';
			break;
			
			case 'BasicFormat' :
				$toolbar .= 'preview,|,cut,copy,paste,pastetext,pasteword,cleanup,removeformat,|,blockquote,cite,abbr,acronym,del,ins,|,sub,sup,|,charmap,nonbreaking'; //,visualaid
				$toolbar2 = 'bold,italic,hr,|,formatselect,|,link,unlink,|,bullist,numlist,indent,outdent,justifyleft,justifycenter,justifyright,justifyfull,|,code';

				$plugg .= ',nonbreaking,xhtmlxtras';
				$config_supp .= "
					theme_advanced_styles : '$arrayStyles',
					theme_advanced_toolbar_location : 'top',
					theme_advanced_statusbar_location : 'bottom',
					theme_advanced_path : true,
					theme_advanced_blockformats : 'p,h1,h2,h3,h4,h5',
					plugin_preview_width : '460',
					plugin_preview_height : '600',
					plugin_preview_pageurl : '".$WWW."admin/lib/tinymce/plugins/preview/example.html',
					popups_css_add : '".$WWW."admin/style_admin.css.php',
				";
			break;
			
			case 'Default' : 
			case 'Basic' :
			default :
				$toolbar .= 'bold,italic,|,link,unlink,|,pasteword,cleanup,removeformat';
			break;
		}
		
		$js_file = '';
		$javascript = '';
		
		global $TinyMceEditorDone;
		if(!isset($TinyMceEditorDone) || $TinyMceEditorDone != 1 ) {
			$TinyMceEditorDone = 1;
			$js_file = '<script language="javascript" type="text/javascript" src="'.$WWW.'admin/lib/tinymce/tiny_mce.js"></script>';
		}
			
		// http://wiki.moxiecode.com/index.php/TinyMCE:Configuration

		$javascript = js("
		tinyMCE.init({
		mode : 'textareas',
		editor_selector : '".$this->InstanceName."_class',
			width : '640px',
			height : '".$this->height.(strpos($this->height,'%')!==false?'':'px')."',
			language : 'fr',
			plugins : '".$plugg."',
		theme : 'advanced',
			theme_advanced_buttons1 : '".$toolbar."',
			theme_advanced_buttons2 : '".$toolbar2."',
		theme_advanced_buttons3 : '".$toolbar3."',
			theme_advanced_resizing : true,
		theme_advanced_resizing_use_cookie : true,
			button_tile_map : true,
			auto_reset_designmode : true,
			dialog_type : 'modal',
		object_resizing : false,
		content_css : '".$WWW."admin/style_wysiwyg.css.php',
		relative_urls : true,
		document_base_url : '".$WWW."',
		remove_script_host : false,
		forced_root_block : 'p',
		remove_trailing_nbsp : true,
			cleanup_on_startup : true,
			cleanup: true,
		valid_elements : '+a[id|rel|name|href|target|title|class],-p[id|class|align],-strong/-b[class],-em/-i[class],-strike[class],-u[class],-ol[class],-ul[class],-li[class],br,img[id|class|src|border=0|alt=|title|hspace|vspace|width|height|align=left],-sub[class],-sup[class],-blockquote,-span[class],-pre[class|align],address[class|align],caption[id|class],-h1[id|class|align],-h2[id|class|align],-h3[id|class|align],-h4[id|class|align],-h5[id|class|align],-h6[id|class|align],hr[class],-font[size|id|class|color],dd[id|class|title|dir|lang],dl[id|class|title],dt[id|class|title],cite[title|id|class],abbr[title|id|class],acronym[title|id|class],del[title|id|class|datetime|cite],ins[title|id|class|dir|lang|datetime|cite]',
			".$config_supp."
			debug : false
		});
		",false);
		
		$html = '<div>
		<textarea name="'.$this->InstanceName.'" id="'.$this->InstanceName.'" class="'.$this->InstanceName.'_class" cols="40" rows="12" wrap="virtual" style="width:640px;height:'.$this->height.(strpos($this->height,'%')!==false?'':'px').';">'.$HtmlValue.'</textarea>
		</div>';

		$js_html = $js_file.$javascript.chr(13).chr(10).$html;
		
		return $js_html;

	}
}

?>
```

## Dependency ##

```

$WWW = 'http://www.b2bweb.fr/';
$JS = '<script type="text/javascript">'.chr(13).chr(10).'// <![CDATA['.chr(13).chr(10);
$JSE = chr(13).chr(10).'// ]]>'.chr(13).chr(10).'</script>';

function js($script, $echo=TRUE) {
	global $JS,$JSE;
	$js = $JS.chr(13).chr(10).$script.chr(13).chr(10).$JSE;
	if ($echo) echo $js;
	else return $js;
}

```

http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/admin/lib/class/class_tinymce.php?r=86