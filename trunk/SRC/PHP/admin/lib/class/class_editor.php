<?
if ( !defined('MLKLC') ) die('Lucky Duck');

// ----------------------------------------------- WYSIWYG ----------------------------------------------- //
define('EDITOR_VERSION', '2.0 FC'); 

class EDITOR {
	var $InstanceName;
	var $BasePath;
	var $Width;
	var $Height;
	var $ToolbarSet;
	var $Value;
	var $Config;
	function EDITOR($instanceName) {
		$this->InstanceName	= $instanceName;
		$this->BasePath		= '../lib/FCKeditor/';
		$this->Width		= '100%';
		$this->Height		= '200';
		$this->ToolbarSet	= 'Default';
		$this->Value		= '';
		$this->Config		= array();
	}
	function Create() { return $this->CreateHtml(); }
	function CreateHtml() {
		$HtmlValue = htmlspecialchars( $this->Value );
		$Html = '<div>';
		if ( $this->IsCompatible() ) {
			$Link = "{$this->BasePath}editor/fckeditor.html?InstanceName={$this->InstanceName}";
			if ( $this->ToolbarSet != '' ) $Link .= "&Toolbar={$this->ToolbarSet}";
			$Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}\" name=\"{$this->InstanceName}\" value=\"{$HtmlValue}\">";
			$Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}___Config\" value=\"" . $this->GetConfigFieldString() . "\">";
			$Html .= "<iframe id=\"{$this->InstanceName}___Frame\" src=\"{$Link}\" width=\"{$this->Width}\" height=\"{$this->Height}\" frameborder=\"no\" scrolling=\"no\"></iframe>";
		}
		else {
			if ( strpos( $this->Width, '%' ) === false ) $WidthCSS = $this->Width.'px';
			else $WidthCSS = $this->Width;
			if ( strpos( $this->Height, '%' ) === false ) $HeightCSS = $this->Height.'px';
			else $HeightCSS = $this->Height;
			$Html .= "<textarea name=\"{$this->InstanceName}\" rows=\"4\" cols=\"40\" style=\"width: {$WidthCSS}; height: {$HeightCSS}\" wrap=\"virtual\">{$HtmlValue}</textarea>";
		}
		$Html .= '</div>';
		return $Html;
	}
	function IsCompatible() {
		$sAgent = $_SERVER['HTTP_USER_AGENT'];
		if ( strpos($sAgent, 'MSIE') !== false && strpos($sAgent, 'mac') === false && strpos($sAgent, 'Opera') === false ) {
			$iVersion = (float)substr($sAgent, strpos($sAgent, 'MSIE') + 5, 3);
			return ($iVersion >= 5.5);
		}
		elseif ( strpos($sAgent, 'Gecko') !== false ) {
			$iVersion = (int)substr($sAgent, strpos($sAgent, 'Gecko/') + 6, 8);
			return ($iVersion >= 20030210);
		}
		else
			return false;
	}
	function GetConfigFieldString() {
		$sParams = '';
		$bFirst = true;
		foreach ( $this->Config as $sKey => $sValue ) {
			if ( $bFirst == false ) $sParams .= '&';
			else $bFirst = false;
			
			if ( $sValue === true ) $sParams .= $this->EncodeConfig( $sKey ) . '=true';
			elseif ( $sValue === false ) $sParams .= $this->EncodeConfig( $sKey ) . '=false';
			else $sParams .= $this->EncodeConfig( $sKey ) . '=' . $this->EncodeConfig( $sValue );
		}
		return $sParams;
	}
	function EncodeConfig( $valueToEncode ) {
		$chars = array('&' => '%26','=' => '%3D','"' => '%22' );
		return strtr( $valueToEncode,  $chars );
	}
}
?>