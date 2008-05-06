<?php
/**
 * Class clsMsDocGenerator
 * Compile MSWord DOC similar files
 *
 * ===================================================================================================
 * Usage Example:
 *
 * $doc = new clsMsDocGenerator();
 * $doc->addParagraph('this is the first paragraph');
 * $doc->addParagraph('this is the paragraph in justified style', array('text-align' => 'justify'));
 * $doc->output();
 * 
 * ===================================================================================================
 * @autor Max S. Ferreira	mxspan-sources[at]yahoo[dot]com[dot]br
 * @version 0.4 	29.01.2006
 *
 * ===================================================================================================
 * Changes:
 *
 * version 0.4
 *   added: insert images is now possible (thanks to Ron Wilson)
 *   added: document default font is now changeable
 *   added: now can save document otherwise show
 *   fixed: session page type and session page orientation is now working correctly
 *   fixed: session page margins is now working correctly
 * version 0.3
 *   fixed: bugs in table styles
 *   added: output document name is now changeable
 *   added: document lang or charset is now changeable
 * version 0.2
 *   added: table resources
 * version 0.1
 *   start of project 
 *
 * ===================================================================================================
 * TO-DO
 * - to improve formatting style (is complex)
 * - to improve documentation (is a hard work)
 * - to convert all, to make a code more dynamic
 * - add more resources
 *
 * ===================================================================================================
 * License:	GNU General Public License
 *
 * Copyright (c) 2005 Max S. Ferreira.  All rights reserved.
 *
 * clsMsDocGenerator is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * clsMsDocGenerator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307,
 * USA.
 *
 */

error_reporting(E_ALL);
 
class clsMsDocGenerator{
	var $appName = 'MsDocGenerator';
	var $appVersion = '0.4';
	var $isDebugging = false;
	
	var $leftMargin;
	var $rightMargin;
	var $topMargin;
	var $bottomMargin;
	var $pageOrientation;
	var $pageType;
	
	var $documentLang;
	var $documentCharset;
	var $fontFamily;
	var $fontSize;
	
	var $documentBuffer;
	var $formatBuffer;
	var $cssFile;
	var $lastSessionNumber;
	var $lastPageNumber;
	var $atualPageWidth;
	var $atualPageHeight;
	
	var $tableIsOpen;
	var $tableLastRow;
	var $tableBorderAlt;
	var $tablePaddingAltRight;
	var $tablePaddingAltLeft;
	var $tableBorderInsideH;
	var $tableBorderInsideV;
	
	var $numImages;

	
	/**
	 * constructor clsMsDocGenerator(const $pageOrientation = 'PORTRAIT', const $pageType = 'A4',  string $cssFile = '', int $topMargin = 3.0, int $rightMargin = 2.5, int $bottomMargin = 3.0, int $leftMargin = 2.5)
	 * @param $pageOrientation: The orientation of the pages of the initial session, 'PORTRAIT' or 'LANDSCAPE'
	 * @param $pageType: The initial type of the paper of the pages of the session
	 * @param $cssFile: extra file with formating configurations, in css file format
	 * @param $topMargin: top margin of the document
	 * @param $rightMargin: right margin of the document
	 * @param $bottomMargin: bottom margin of the document
	 * @param $leftMargin: left margin of the document 
	 */
	function clsMsDocGenerator($pageOrientation = 'PORTRAIT', $pageType = 'A4', $cssFile = '', $topMargin = 3.0, $rightMargin = 2.5, $bottomMargin = 3.0, $leftMargin = 2.5){
		$this->documentBuffer = '';
		$this->formatBuffer = '';
		$this->cssFile = $cssFile;
		$this->lastSessionNumber = 0;
		$this->lastPageNumber = 0;
		$this->atualPageWidth = 0;
		$this->atualPageHeight = 0;
		
		$this->tableIsOpen = false;
		$this->tableLastRow = 0;
		$this->tableBorderAlt = 0.5;
		$this->tablePaddingAltRight = 5.4;
		$this->tablePaddingAltLeft = 5.4;
		$this->tableBorderInsideH = 0.5;
		$this->tableBorderInsideV = 0.5;

		$this->documentLang = 'FR-FR';
		$this->documentCharset = 'windows-1252';
		$this->fontFamily = 'Arial';
		$this->fontSize = '12';
		
		$this->pageOrientation = $pageOrientation;
		$this->pageType = $pageType;
		
		$this->topMargin = $topMargin;
		$this->rightMargin = $rightMargin;
		$this->bottomMargin = $bottomMargin;
		$this->leftMargin = $leftMargin;
		
		$this->numImages = 0;
		
		$this->newSession($this->pageOrientation, $this->pageType, $this->topMargin, $this->rightMargin, $this->bottomMargin, $this->leftMargin);
		$this->newPage();
	}//end clsMsDocGenerator()
	
	/**
	 * public int newSession(const $pageOrientation = NULL, const $pageType = NULL, int $topMargin = NULL, int $rightMargin = NULL, int $bottomMargin = NULL, int $leftMargin = NULL)
	 * @param $pageOrientation: The orientation of the pages of the this session, 'PORTRAIT' or 'LANDSCAPE'
	 * @param $pageType: The type of the paper of the pages of the this session
	 * @param $topMargin: top margin of the this session
	 * @param $rightMargin: right margin of the this session
	 * @param $bottomMargin: bottom margin of the this session
	 * @param $leftMargin: left margin of the this session
	 * @return int: the number of the new session
	 */
	function newSession($pageOrientation = NULL, $pageType = NULL, $topMargin = NULL, $rightMargin = NULL, $bottomMargin = NULL, $leftMargin = NULL){
		//don't setted now? then use document start values
		$pageOrientation = $pageOrientation === NULL ? $this->pageOrientation : $pageOrientation;
		$pageType = $pageType === NULL ? $this->pageType : $pageType;
		$topMargin = $topMargin === NULL ? $this->topMargin : $topMargin;
		$rightMargin = $rightMargin === NULL ? $this->rightMargin : $rightMargin;
		$bottomMargin = $bottomMargin === NULL ? $this->bottomMargin : $bottomMargin;
		$leftMargin = $leftMargin === NULL ? $this->leftMargin : $leftMargin;

		$this->lastSessionNumber++;
		
		if($this->lastSessionNumber != 1){
			$this->endSession();
			$this->documentBuffer .= "<br clear=\"all\" style=\"page-break-before: always; mso-break-type: section-break\">\n";
		}

		switch($pageOrientation){
			case 'PORTRAIT' :
				switch($pageType){
					case 'A4' :
						$this->atualPageWidth = A4_WIDTH * One_Cent;
						$this->atualPageHeight = A4_HEIGHT * One_Cent;
						break;
					case 'A5' :
						$this->atualPageWidth = A5_WIDTH * One_Cent;
						$this->atualPageHeight = A5_HEIGHT * One_Cent;
						break;
					case 'LETTER' :
						$this->atualPageWidth = LETTER_WIDTH * One_Cent;
						$this->atualPageHeight = LETTER_HEIGHT * One_Cent;
						break;
					case 'OFFICE' :
						$this->atualPageWidth = OFFICE_WIDTH * One_Cent;
						$this->atualPageHeight = OFFICE_HEIGHT * One_Cent;
						break;
					default:
						die("ERROR: PAGE TYPE ($pageType) IS NOT DEFINED");
				}
				$msoPageOrientation = 'portrait';
				break;
			case 'LANDSCAPE' :
				switch($pageType){
					case 'A4' :
						$this->atualPageWidth = A4_HEIGHT * One_Cent;
						$this->atualPageHeight = A4_WIDTH * One_Cent;
						break;
					case 'A5' :
						$this->atualPageWidth = A5_HEIGHT * One_Cent;
						$this->atualPageHeight = A5_WIDTH * One_Cent;
						break;
					case 'LETTER' :
						$this->atualPageWidth = LETTER_HEIGHT * One_Cent;
						$this->atualPageHeight = LETTER_WIDTH * One_Cent;
						break;
					case 'OFFICE' :
						$this->atualPageWidth = OFFICE_HEIGHT * One_Cent;
						$this->atualPageHeight = OFFICE_WIDTH * One_Cent;
						break;
					default:
						die("ERROR: PAGE TYPE ($pageType) IS NOT DEFINED");
				}
				$msoPageOrientation = 'landscape';
				break;
			default :
				die("ERROR: INVALID PAGE ORIENTATION ($pageOrientation)");
		}
		$pageSize = "{$this->atualPageWidth}pt {$this->atualPageHeight}pt";
		$pageMargins = "{$topMargin}cm {$rightMargin}cm {$bottomMargin}cm {$leftMargin}cm";
		
		$sessionName = "Section" . $this->lastSessionNumber;
		
		$this->formatBuffer .= "@page $sessionName\n";
		$this->formatBuffer .= "   {size: $pageSize;\n";
		$this->formatBuffer .= "   mso-page-orientation: $msoPageOrientation;\n";
		$this->formatBuffer .= "   margin: $pageMargins;\n";
		$this->formatBuffer .= "   mso-header-margin: 36pt;\n";
		$this->formatBuffer .= "   mso-footer-margin: 36pt;\n";
		$this->formatBuffer .= "   mso-paper-source: 0;}\n";
		$this->formatBuffer .= "div.$sessionName\n";
		$this->formatBuffer .= "  {page: $sessionName;}\n\n";
		
		$this->documentBuffer .= "<div class=\"$sessionName\">\n";
		
		return $this->lastSessionNumber;
	}//end newSession()
	
	/**
	 * public int newPage(void)
	 * @return int: the number of the new page
	 */	
	function newPage(){
		$this->lastPageNumber++;
		if($this->lastPageNumber != 1)
			$this->documentBuffer .= "<br clear=\"all\" style=\"page-break-before: always;\">";
		return $this->lastPageNumber;
	}//end newPage()
	
	/**
	 * public void output(string $fileName = '', string $saveInPath = '')
	 * @param $fileName: the file name of document
	 * @param $saveInPath: if not empty will be the path to save document otherwise show
	 */
	function output($fileName = '', $saveInPath = ''){
		$this->endSession();
		
		$outputCode = '';
		$outputCode .= "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
		$outputCode .= "   xmlns:w=\"urn:schemas-microsoft-com:office:word\"\n";
		$outputCode .= "   xmlns=\"http://www.w3.org/TR/REC-html40\">\n";
		
		$outputCode .= $this->getHeader();
		
		$outputCode .= $this->getBody();
		
		$outputCode .= "</html>\n";
		
		$fileName = $fileName != '' ? $fileName : basename($_SERVER['PHP_SELF'], '.php') . '.doc';

		if($saveInPath == ''){
			if($this->isDebugging){
				return nl2br(htmlentities($outputCode));
			}else{
				header("Content-Type: application/msword; charset=\$this->documentCharset\"");
				
				header("Content-Disposition: attachment; filename=\"$fileName\"");
				
				return $outputCode;	
			}
		}else{
			if(substr($saveInPath,-1) <> "/")
				$saveInPath = $saveInPath."/";
			file_put_contents($saveInPath . $fileName, $outputCode);
		}
	}//end output()
	
	/**
	 * public void setDocumentLang(string $lang)
	 * @param $lang: document lang
	 */
	function setDocumentLang($lang){
		$this->documentLang = $lang;
	}//end setDocumentLang()
	
	/**
	 * public void setDocumentCharset(string $charset)
	 * @param $charset: document charset
	 */
	function setDocumentCharset($charset){
		$this->documentCharset = $charset;
	}//end setDocumentCharset()
	
	/**
	 * public void setFontFamily(string $fontFamily)
	 * @param $fontFamily: default document font family
	 */
	function setFontFamily($fontFamily){
		$this->fontFamily = $fontFamily;
	}//end setFontFamily()
	
	/**
	 * public void setFontSize(string $fontSize)
	 * @param $fontSize: default document font Size
	 */
	function setFontSize($fontSize){
		$this->fontSize = $fontSize;
	}//end setFontSize()
	
	/**
	 * public void addParagraph(string $content, array $inlineStyle = NULL, string $className = 'normalText')
	 * @param $content: content of the paragraph
	 * @param $inlineStyle: array of css block properties
	 * #param $className: class name of any class defined in extra format file
	 */
	function addParagraph($content, $inlineStyle = NULL, $className = 'normalText'){
		$style = '';
		if(is_array($inlineStyle)){
			foreach($inlineStyle as $key => $value)
				$style .= "$key: $value;";
		}
		$this->documentBuffer .= "<p class=\"$className\"" . ($style != '' ? " style=\"$style\"" : '') . ">".($content == '' ? '<o:p></o:p>' : $content)."</p>\n";
	}//end addParagraph()
	
	/**
	 * void bufferImage(string $imagePath, int $width, int $height, string $title = ''){
	 * @param $imagePath: url of the image
	 * @param $width: width to show image in pixels
	 * @param $height: height to show image in pixels
	 */
	function bufferImage($imagePath, $width, $height, $title = ''){
		$this->numImages++;
		$buffer = "<!--[if gte vml 1]>";
		if($this->numImages == 1){
			$buffer .= "<v:shapetype id=\"_x0000_t75\" coordsize=\"21600,21600\"
		   o:spt=\"75\" o:preferrelative=\"t\" path=\"m@4@5l@4@11@9@11@9@5xe\" filled=\"f\"
		   stroked=\"f\">
		   <v:stroke joinstyle=\"miter\"/>
		   <v:formulas>
			<v:f eqn=\"if lineDrawn pixelLineWidth 0\"/>
			<v:f eqn=\"sum @0 1 0\"/>
			<v:f eqn=\"sum 0 0 @1\"/>
			<v:f eqn=\"prod @2 1 2\"/>
			<v:f eqn=\"prod @3 21600 pixelWidth\"/>
			<v:f eqn=\"prod @3 21600 pixelHeight\"/>
			<v:f eqn=\"sum @0 0 1\"/>
			<v:f eqn=\"prod @6 1 2\"/>
			<v:f eqn=\"prod @7 21600 pixelWidth\"/>
			<v:f eqn=\"sum @8 21600 0\"/>
			<v:f eqn=\"prod @7 21600 pixelHeight\"/>
			<v:f eqn=\"sum @10 21600 0\"/>
		   </v:formulas>
		   <v:path o:extrusionok=\"f\" gradientshapeok=\"t\" o:connecttype=\"rect\"/>
		   <o:lock v:ext=\"edit\" aspectratio=\"t\"/>
		  </v:shapetype>";
		}
		$buffer .= "<v:shape id=\"_x0000_i102{$this->numImages}\" type=\"#_x0000_t75\" style='width:".$this->pixelsToPoints($width)."pt;
		   height:".$this->pixelsToPoints($height)."pt'>
		   <v:imagedata src=\"$imagePath\" o:title=\"accessibilityIssues\"/>
		  </v:shape><![endif]--><![if !vml]><img width=\"$width\" height=\"$height\" src=\"$imagePath\" v:shapes=\"_x0000_i102{$this->numImages}\"><![endif]>";
		return $buffer;
	}//end bufferImage()
	
	/**
	 * void addImage(string $imagePath, int $width, int $height, string $title = ''){
	 * @param $imagePath: url of the image
	 * @param $width: width to show image in pixels
	 * @param $height: height to show image in pixels
	 */
	function addImage($imagePath, $width, $height, $title = ''){
		$this->documentBuffer.= $this->bufferImage($imagePath, $width, $height, $title);
	}//end addImage()
	
	/**
	 * public void startTable(array $inlineStyle = NULL, string $className = 'normalTable')
	 * @param $inlineStyle: array of css table properties, property => value
	 * @param $className: class name of any class defined, may be in extra format file
	 */
	function startTable($inlineStyle = NULL, $className = 'normalTable'){
		$style = '';
		if(is_array($inlineStyle)){
			foreach($inlineStyle as $key => $value)
				$style .= "$key: $value;";
		}
		$this->documentBuffer .= "<table class=\"$className\" style=\"$style\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
		
		$this->tableIsOpen = true;
	}//end startTable()
	
	/**
	 * public int addTableRow(array $cells, array $aligns = NULL, array $vAligns = NULL, array $inlineStyle = NULL, array $classesName = NULL)
	 * @param $cells: array with content of cells of the row
	 * @param $aligns: array with align cell constants in html style, a item for each cell item
	 * @param $vAligns: array with vertical align cell constants in html style, a item for each cell item
	 * @param $inlineStyle: array of css block properties, property => value
	 * @param $classesName: array with class name of any class defined in extra format file, a item for each cell item
	 */
	function addTableRow($cells, $aligns = NULL, $vAligns = NULL, $inlineStyle = NULL, $classesName = NULL){
		if(! $this->tableIsOpen)
			die('ERROR: TABLE IS NOT STARTED');
			
		if(is_array($classesName) && count($classesName) != count($cells))
			die('ERROR: COUNT OF CLASSES IS DIFERENT OF COUNT OF CELLS');
		if(is_array($aligns) && count($aligns) != count($cells))
			die('ERROR: COUNT OF ALIGNS IS DIFERENT OF COUNT OF CELLS');
		if(is_array($vAligns) && count($vAligns) != count($cells))
			die('ERROR: COUNT OF VALIGNS IS DIFERENT OF COUNT OF CELLS');
		
		$style = '';
		if(is_array($inlineStyle)){
			foreach($inlineStyle as $key => $value)
				$style .= "$key: $value;";
		}
		
		$tableWidth = $this->atualPageWidth;// - ($this->leftMargin * One_Cent + $this->rightMargin * One_Cent);
		//$tableWidth -= (BORDER_ALT*2 + PADDING_ALT_RIGHT + PADDING_ALT_LEFT + BORDER_INSIDEH*2 + BORDER_INSIDEV*2);
		$cellWidth = floor($tableWidth / count($cells));
		
		
		$this->documentBuffer .= "<tr style=\"mso-yfti-irow: $this->tableLastRow\">\n";
		for($i = 0; $i < count($cells); $i++){
			$align = is_array($aligns) ? $aligns[$i] : 'left';
			$vAlign = is_array($vAligns) ? $vAligns[$i] : 'top';
			$classAttr = is_array($classesName) ? " class=\"$classesName[$i]\"" : '';
			
			$this->documentBuffer .= "<td width=\"$cellWidth\" align=\"$align\" valign=\"$vAlign\" style=\"$style\"{$classAttr}>$cells[$i]</td>\n";
		}
		$this->documentBuffer .= "</tr>\n";
		
		$this->tableLastRow++;
		return $this->tableLastRow;
	}//end addTableRow()
	
	/**
	 * public void endTable(void)
	 */
	function endTable(){
		if(! $this->tableIsOpen)
			die('ERROR: TABLE IS NOT STARTED');
			
		$this->documentBuffer .= "</table>\n";
		
		$this->tableIsOpen = false;
		$this->tableLastRow = 0;
	}//end endTable()


	/****************************************************
	 * begin private functions
	 ***************************************************/
	
	/**
	 * private void endSession(void)
	 */
	function endSession(){
		$this->documentBuffer .= "</div>\n";
	}//end newSession()	
	
	/**
	 * private float endSession(int $pixels)
	 * @param $pixels: number of pixels to convert
	 */
	function pixelsToPoints($pixels){
		$points = 0.75 * floatval($pixels);
		return number_format($points,2);
	}//end pixelsToPoints()
	
	/**
	 * private void prepareDefaultHeader(void)
	 */
	function prepareDefaultHeader(){	
		$this->formatBuffer .= "p.normalText, li.normalText, div.normalText{\n";
		$this->formatBuffer .= "   mso-style-parent: \"\";\n";
		$this->formatBuffer .= "   margin: 0cm;\n";
		$this->formatBuffer .= "   margin-bottom: 6pt;\n";
		$this->formatBuffer .= "   mso-pagination: widow-orphan;\n";
		$this->formatBuffer .= "   font-size: {$this->fontSize}pt;\n";
		$this->formatBuffer .= "   font-family: \"{$this->fontFamily}\";\n";
		$this->formatBuffer .= "   mso-fareast-font-family: \"{$this->fontFamily}\";\n";
		$this->formatBuffer .= "}\n\n";
		
		$this->formatBuffer .= "table.normalTable{\n";
		$this->formatBuffer .= "   mso-style-name: \"Tabela com grade\";\n";
		$this->formatBuffer .= "   mso-tstyle-rowband-size: 0;\n";
		$this->formatBuffer .= "   mso-tstyle-colband-size: 0;\n";
		$this->formatBuffer .= "   border-collapse: collapse;\n";
		$this->formatBuffer .= "   mso-border-alt: solid windowtext {$this->tableBorderAlt}pt;\n";
		$this->formatBuffer .= "   mso-yfti-tbllook: 480;\n";
		$this->formatBuffer .= "   mso-padding-alt: 0cm {$this->tablePaddingAltRight}pt 0cm {$this->tablePaddingAltLeft}pt;\n";
		$this->formatBuffer .= "   mso-border-insideh: {$this->tableBorderInsideH}pt solid windowtext;\n";
		$this->formatBuffer .= "   mso-border-insidev: {$this->tableBorderInsideV}pt solid windowtext;\n";
		$this->formatBuffer .= "   mso-para-margin: 0cm;\n";
		$this->formatBuffer .= "   mso-para-margin-bottom: .0001pt;\n";
		$this->formatBuffer .= "   mso-pagination: widow-orphan;\n";
		$this->formatBuffer .= "   font-size: {$this->fontSize}pt;\n";
		$this->formatBuffer .= "   font-family: \"{$this->fontFamily}\";\n";
		$this->formatBuffer .= "}\n";
		$this->formatBuffer .= "table.normalTable td{\n";
		$this->formatBuffer .= "   border: solid windowtext 1.0pt;\n";
		$this->formatBuffer .= "   border-left: none;\n";
		$this->formatBuffer .= "   mso-border-left-alt: solid windowtext .5pt;\n";
		$this->formatBuffer .= "   mso-border-alt: solid windowtext .5pt;\n";
		$this->formatBuffer .= "   padding: 0cm 5.4pt 0cm 5.4pt;\n";
		$this->formatBuffer .= "}\n\n";

		$this->formatBuffer .= "table.tableWithoutGrid{\n";
		$this->formatBuffer .= "   mso-style-name: \"Tabela sem grade\";\n";
		$this->formatBuffer .= "   mso-tstyle-rowband-size: 0;\n";
		$this->formatBuffer .= "   mso-tstyle-colband-size: 0;\n";
		$this->formatBuffer .= "   border-collapse: collapse;\n";
		$this->formatBuffer .= "   border: none;\n";
		$this->formatBuffer .= "   mso-border-alt: none;\n";
		$this->formatBuffer .= "   mso-yfti-tbllook: 480;\n";
		$this->formatBuffer .= "   mso-padding-alt: 0cm {$this->tablePaddingAltRight}pt 0cm {$this->tablePaddingAltLeft}pt;\n";
		$this->formatBuffer .= "   mso-border-insideh: {$this->tableBorderInsideH}pt solid windowtext;\n";
		$this->formatBuffer .= "   mso-border-insidev: {$this->tableBorderInsideV}pt solid windowtext;\n";
		$this->formatBuffer .= "   mso-para-margin: 0cm;\n";
		$this->formatBuffer .= "   mso-para-margin-bottom: .0001pt;\n";
		$this->formatBuffer .= "   mso-pagination: widow-orphan;\n";
		$this->formatBuffer .= "   font-size: {$this->fontSize}pt;\n";
		$this->formatBuffer .= "   font-family: \"{$this->fontFamily}\";\n";
		$this->formatBuffer .= "}\n\n";
		
		if($this->cssFile != ''){
			if(file_exists($this->cssFile))
				$this->formatBuffer .= file_get_contents($this->cssFile);
		}
	}//end prepareDefaultHeader()
	
	/**
	 * private string getHeader(void)
	 */
	function getHeader(){
		$header = '';
		$header .= "<head>\n";
		$header .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$this->documentCharset\">\n";
		$header .= "<meta name=\"ProgId\" content=\"Word.Document\">\n";
		$header .= "<meta name=\"Generator\" content=\"$this->appName $this->appVersion\">\n";
		$header .= "<meta name=\"Originator\" content=\"$this->appName $this->appVersion\">\n";
		$header .= "<!--[if !mso]>\n";
		$header .= "<style>\n";
		$header .= "v\:* {behavior:url(#default#VML);}\n";
		$header .= "o\:* {behavior:url(#default#VML);}\n";
		$header .= "w\:* {behavior:url(#default#VML);}\n";
		$header .= ".shape {behavior:url(#default#VML);}\n";
		$header .= "</style>\n";
		$header .= "<![endif]-->\n";

		$header .= "<style>\n";
		$header .= "<!--\n";
		$header .= "/* Style Definitions */\n";
		
		$this->prepareDefaultHeader();
		
		$header .= $this->formatBuffer ."\n";
		
		$header .= "-->\n";
		$header .= "</style>\n";
		$header .= "</head>\n";
		
		return $header;
	}//end getHeader()
	
	/**
	 * private string getBody(void)
	 */
	function getBody(){
		$body = '';
		$body .= "<body lang=\"$this->documentLang\" style=\"tab-interval: 35.4pt\">\n";
		
		$body .= $this->documentBuffer . "\n";
		
		$body .= "</body>\n";
		
		return $body;
	}//end getBody()
}//end class clsMsDocGenerator


/****************************************************
 * constant definition
 ***************************************************/
define('One_Cent', 28.35);//1cm = 28.35pt

//paper sizes in cm
define('A4_WIDTH', 21.0);
define('A4_HEIGHT', 29.7);
define('A5_WIDTH', 14.8);
define('A5_HEIGHT', 21.0);
define('LETTER_WIDTH', 21.59);
define('LETTER_HEIGHT', 27.94);
define('OFFICE_WIDTH', 21.59);
define('OFFICE_HEIGHT', 35.56);


/****************************************************
 * functions definition
 ***************************************************/
 
if(! function_exists('file_get_contents')){
  function file_get_contents($filename, $useIncludePath = '', $context = ''){
    if(empty($useIncludePath)){
      return implode('',file($filename));
    }elseif(empty($content)){
      return implode('',file($filename, $useIncludePath));
    }else{
      return implode('',file($filename, $useIncludePath, $content));
    }
  }//end file_get_contents()
}//end if

if(! function_exists('file_put_contents')){
  function file_put_contents($filename, $data){
    $file = fopen($filename, 'wb');
    $return = fwrite($file, $data);
    fclose($file);
	return $return;
  }//end file_put_contents()
}//end if
?>