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
 */
 
 /* EXEMPLE 
 
	require_once('admin/lib/class/class_tinymce.php');
	$Ed = new TinyMce('myInputName');
	$Ed->ToolbarSet = 'Basic';  // BasicStyle | BasicTab | BasicImg
	$Ed->Width = '100%';
	$Ed->Height = '280px';
	$Ed->Value = 'Bla bla &lt;strong&gt; bold &lt;/strong&gt; ';
	$Ed->Create();

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
		$plugg = 'paste,preview,inlinepopups,contextmenu';
		$config_supp = '';

		// Switch TOOLBAR
		switch($this->ToolbarSet) { // Default, Basic, BasicImg, BasicStyle, BasicTab

			case 'BasicStyle' :
				$arrayStyles = 'Titre rouge=titre_rouge'; // ;)
				
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
				$toolbar .= 'pasteword,removeformat,|,blockquote,cite,abbr,acronym,del,ins,|,sub,sup,|,charmap,nonbreaking,|,preview'; //,visualaid
				$toolbar2 = 'bold,italic,hr,|,formatselect,|,link,unlink,|,bullist,numlist,indent,outdent,justifyleft,justifycenter,justifyright,justifyfull,|,code';

				$plugg .= ',nonbreaking,xhtmlxtras';
				$config_supp .= "
					theme_advanced_styles : '$arrayStyles',
					theme_advanced_toolbar_location : 'top',
					theme_advanced_statusbar_location : 'bottom',
					theme_advanced_path : true,
					theme_advanced_blockformats : 'p,h2,h3,h4', //h5,h1,
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

		$js_file = $javascript = '';
		
		// Include once the JS file
		global $TinyMceEditorDone;
		if( $TinyMceEditorDone != 1) {
			$TinyMceEditorDone = 1;
			global $WWW;
			$js_file = '<script language="javascript" type="text/javascript" src="'.$WWW.'admin/lib/tinymce/tiny_mce.js"></script>';
		}
		
		// http://wiki.moxiecode.com/index.php/TinyMCE:Configuration
		
		$javascript = js("
		tinyMCE.init({
			width : '".$this->width.(strpos($this->width,'%')!==false?'':'px')."',
			//height : '".$this->height.(strpos($this->height,'%')!==false?'':'px')."',
			mode : 'exact',
			cleanup : true,
			theme : 'advanced',
			elements : '' ,
			language : 'fr',
			plugins : '".$plugg."',
			theme_advanced_buttons1 : '".$toolbar."',
			theme_advanced_buttons2 : '".$toolbar2."',
			theme_advanced_buttons3 : '".$toolbar3."',
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			theme_advanced_resizing_use_cookie : false,
			button_tile_map : true,
			auto_reset_designmode : true,
			dialog_type : 'modal',
			cleanup_on_startup : true,
			cleanup: true,
			object_resizing : false,
			relative_urls : false,
			remove_script_host : false,
			document_base_url : '".$WWW."',
			".($this->ToolbarSet != 'BasicTab' ? "valid_elements : '+a[id|rel|name|href|target|title|class],-p[id|class|align|style],-strong/-b[class],-em/-i[class],-strike[class],-u[class],-ol[class],-ul[class],-li[class],br,img[id|class|src|border=0|alt=|title|hspace|vspace|width|height|align=left],-sub[class],-sup[class],-blockquote,-span[class],-pre[class|align],address[class|align],caption[id|class],-h1[id|class|align],-h2[id|class|align],-h3[id|class|align],-h4[id|class|align],-h5[id|class|align],-h6[id|class|align],hr[class],-font[size|id|class|color],dd[id|class|title|dir|lang],dl[id|class|title],dt[id|class|title],cite[title|id|class],abbr[title|id|class],acronym[title|id|class],del[title|id|class|datetime|cite],ins[title|id|class|dir|lang|datetime|cite]'," : '')."
			".$config_supp."
			debug : false
		});
		", false);
		
		/*
			content_css : "/mycontent.css",
			verify_css_classes : true, //class names placed in class attributes will be verified agains the content CSS. So elements with a class attribute containing a class that doesn't exist in the CSS will be removed
			
			auto_resize : true,
			//editor_selector : '".$this->InstanceName."_class',
			forced_root_block : 'p',
			execcommand_callback : 'myCustomExecCommandHandler',
			hide_selects_on_submit : true,
			strict_loading_mode : false,
			theme_advanced_buttons1_add_before : '',
			theme_advanced_text_colors : 'FF00FF,FFFF00,000000',
			theme_advanced_fonts : 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace',
			theme_advanced_background_colors : 'FF00FF,FFFF00,000000',
			theme_advanced_toolbar_location : "top",';
			theme_advanced_statusbar_location : "bottom",';
			-table[border=0|cellspacing=0|cellpadding=4|width|height|class|align|id|bgcolor|background|bordercolor],-tr[id|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor],#td[id|class|colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor],-th[id|class|colspan|rowspan|width|height|align|valign],-div[id|class|align],
	
			external_image_list_url : "myexternallist.js"
			var tinyMCEImageList = new Array(
				// Name, URL
				["Logo 1", "logo.jpg"],
				["Logo 2 Over", "logo_over.jpg"]
			);
			
			content_css : "/mycontent.css",
			verify_css_classes : true, //class names placed in class attributes will be verified agains the content CSS. So elements with a class attribute containing a class that doesn't exist in the CSS will be removed
			elements : '' ,
			auto_resize : true,
			forced_root_block : 'p',
			execcommand_callback : 'myCustomExecCommandHandler',
			hide_selects_on_submit : true,
			strict_loading_mode : false,
			theme_advanced_buttons1_add_before : '',
			theme_advanced_text_colors : 'FF00FF,FFFF00,000000',
			theme_advanced_fonts : 'Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace',
			theme_advanced_background_colors : 'FF00FF,FFFF00,000000',
			theme_advanced_toolbar_location : "top",';
			theme_advanced_statusbar_location : "bottom",';
		*/

		$html = '<div><textarea name="'.$this->InstanceName.'" id="'.$this->InstanceName.'" cols="40" rows="'.round(intval($this->height) / 10).'" wrap="virtual">'.$HtmlValue.'</textarea></div>';
		$javascript_E = js("tinyMCE.execCommand('mceAddControl', false, '".$this->InstanceName."');", false);
		
		$js_html = $js_file.$javascript.chr(13).chr(10).$html.chr(13).chr(10).$javascript_E;

		//die(db(htmlentities($js_html)));
		return $js_html;
	}
}

?>