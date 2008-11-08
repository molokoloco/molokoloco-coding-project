<?
/* *******************************************
// LICENSE INFORMATION
// The code, "detecting Smartphones Using PHP" 
// by Anthony Hand, is licensed under a Creative Commons 
// Attribution 3.0 United States License.
// Anthony Hand, ahand@hand-interactive.com
// Web: www.hand-interactive.com
// 
// License info: http://creativecommons.org/licenses/by/3.0/us/
//***************** */
// The UA_info class encapsulates information about
//   a browser's connection to your web site. 
//   You can use it to find out whether the browser asking for
//   your site's content is probably running on a mobile device.
//   The methods were written so you can be as granular as you want.
//   For example, enquiring whether it's as specific as an iPod Touch or
//   as general as a smartphone class device.

class UA {

	var $useragent = '';
	var $httpaccept = '';
	
	//standardized values for true and false.
	var $true = TRUE;
	var $false = FALSE;
	
	//Initialize some initial smartphone string variables.
	var $engineWebKit = 'webkit';
	var $deviceIphone = 'iphone';
	var $deviceIpod = 'ipod';
	var $deviceSymbian = 'symbian';
	var $deviceS60 = 'series60';
	var $deviceS70 = 'series70';
	var $deviceS80 = 'series80';
	var $deviceS90 = 'series90';
	var $deviceWinMob = 'windows ce';
	var $deviceIeMob = 'iemobile';
	var $enginePie = 'wm5 pie'; //An old Windows Mobile
	var $deviceBB = 'blackberry';
	var $devicePalm = 'palm';
	var $engineBlazer = 'blazer'; //Old Palm
	var $engineXiino = 'xiino'; //Another old Palm
	
	//Initialize variables for mobile-specific content.
	var $vndwap = 'vnd.wap';
	var $wml = 'wml';   
	
	//Initialize variables for other random devices and mobile browsers.
	var $deviceBrew = 'brew';
	var $deviceDanger = 'danger';
	var $deviceHiptop = 'hiptop';
	var $devicePlaystation = 'playstation';
	var $deviceNintendoDs = 'nitro';
	var $deviceNintendo = 'nintendo';
	var $deviceWii = 'wii';
	var $deviceXbox = 'xbox';
	var $deviceArchos = 'archos';
	
	var $engineOpera = 'opera'; // ???
	var $engineOperaMini = 'opera mini'; //Commonly installed browser
	var $engineNetfront = 'netfront'; //Common embedded OS browser
	var $engineUpBrowser = 'up.browser'; //common on some phones
	var $engineOpenWeb = 'openweb'; //Transcoding by OpenWave server
	var $deviceMidp = 'midp'; //a mobile Java technology
	var $uplink = 'up.link';
	
	var $devicePda = 'pda'; //some devices report themselves as PDAs
	
	//Use Maemo, Tablet, and Linux to test for Nokia's Internet Tablets.
	var $maemo = 'maemo';
	var $maemoTablet = 'tablet';
	var $linux = 'linux';
	var $qtembedded = 'qt embedded'; //for Sony Mylo
	
	//In some UserAgents, the only clue is the manufacturer.
	var $manuSonyEricsson = 'sonyericsson';
	var $manuericsson = 'ericsson';
	var $manuSamsung1 = 'sec-sgh';
	var $manuSony = 'sony';
	
	//In some UserAgents, the only clue is the operator.
	var $svcDocomo = 'docomo';
	var $svcKddi = 'kddi';
	var $svcVodafone = 'vodafone';

	// PHP 5
	function __construct() {
		$this->useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$this->httpaccept = strtolower($_SERVER['HTTP_ACCEPT']);
	}
   //The constructor. Initializes several default variables.
   function UA() { 
      $this->__construct();
   }
   //Returns the contents of the User Agent value, in lower case.
   function getUA() { 
       return $this->useragent;
   }
   //Returns the contents of the HTTP Accept value, in lower case.
   function getHttpAccept() { 
       return $this->httpaccept;
   }
   
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // detects if the current device is an iPhone.
   function detectIphone() {
      if (stripos($this->useragent, $this->deviceIphone) > -1) {
         //The iPod touch says it's an iPhone! So let's disambiguate.
         if ($this->detectIpod() == $this->true) {
            return $this->false;
         }
         else return $this->true; 
      }
      else return $this->false; 
   }
   // detects if the current device is an iPod Touch.
   function detectIpod() {
      if (stripos($this->useragent, $this->deviceIpod) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device is an iPhone or iPod Touch.
   function detectIphoneOrIpod() {
		//We repeat the searches here because some iPods may report themselves as an iPhone, which would be okay.
		if (stripos($this->useragent, $this->deviceIphone) > -1 || stripos($this->useragent, $this->deviceIpod) > -1) return $this->true; 
		else return $this->false; 
   }
   // detects if the current browser is the Nokia S60 Open Source Browser.
   function detectS60OssBrowser() {
      //First, test for WebKit, then make sure it's either Symbian or S60.
      if (stripos($this->useragent, $this->engineWebKit) > -1) {
        if (stripos($this->useragent, $this->deviceSymbian) > -1 || stripos($this->useragent, $this->deviceS60) > -1) {
           return $this->true;
        }
        else return $this->false; 
      }
      else return $this->false; 
   }
   // detects if the current device is any Symbian OS-based device,
   //   including older S60, Series 70, Series 80, Series 90, and UIQ, 
   //   or other browsers running on these devices.
   function detectSymbianOS() {
       if (stripos($this->useragent, $this->deviceSymbian) > -1 || 
           stripos($this->useragent, $this->deviceS60) > -1 || 
           stripos($this->useragent, $this->deviceS80) > -1 ||
           stripos($this->useragent, $this->deviceS70) > -1 || 
           stripos($this->useragent, $this->deviceS90) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current browser is a Windows Mobile device.
   function detectWindowsMobile() {
      // Most devices use 'Windows CE', but some report 'iemobile' 
      //  and some older ones report as 'PIE' for Pocket IE. 
      if (stripos($this->useragent, $this->deviceWinMob) > -1 ||
          stripos($this->useragent, $this->deviceIeMob) > -1 ||
          stripos($this->useragent, $this->enginePie) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current browser is a BlackBerry of some sort.
   function detectBlackBerry() {
       if (stripos($this->useragent, $this->deviceBB) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current browser is on a PalmOS device.
   function detectPalmOS() {
      //Most devices nowadays report as 'Palm', but some older ones reported as Blazer or Xiino.
      if (stripos($this->useragent, $this->devicePalm) > -1 || stripos($this->useragent, $this->engineBlazer) > -1 || stripos($this->useragent, $this->engineXiino) > -1) return $this->true; 
      else return $this->false; 
   }
  
   // detects whether the device is a Brew-powered device.
   function detectBrewDevice() {
       if (stripos($this->useragent, $this->deviceBrew) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects the Danger Hiptop device.
   function detectDangerHiptop() {
      if (stripos($this->useragent, $this->deviceDanger) > -1 || stripos($this->useragent, $this->deviceHiptop) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects whether the device supports WAP or WML.
   function detectWapWml() {
       if (stripos($this->httpaccept, $this->vndwap) > -1 || stripos($this->httpaccept, $this->wml) > -1) return $this->true; 
      else return $this->false; 
   }
  
   // detects if the current device is a Sony Playstation.
   function detectSonyPlaystation() {
      if (stripos($this->useragent, $this->devicePlaystation) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device is a Nintendo game device.
   function detectNintendo() {
      if (stripos($this->useragent, $this->deviceNintendo) > -1 || stripos($this->useragent, $this->deviceWii) > -1 || stripos($this->useragent, $this->deviceNintendoDs) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device is a Microsoft Xbox.
   function detectXbox() {
      if (stripos($this->useragent, $this->deviceXbox) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device is an Internet-capable game console.
   function detectGameConsole() {
      if ($this->detectSonyPlaystation() == $this->true) return $this->true; 
      else if ($this->detectNintendo() == $this->true) return $this->true; 
      else if ($this->detectXbox() == $this->true) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device supports MIDP, a mobile Java technology.
   function detectMidpCapable() {
       if (stripos($this->useragent, $this->deviceMidp) > -1 || stripos($this->httpaccept, $this->deviceMidp) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device is on one of the Maemo-based Nokia Internet Tablets.
   function detectMaemoTablet() {
      if (stripos($this->useragent, $this->maemo) > -1) return $this->true; 
      //Must be Linux + Tablet, or else it could be something else. 
      else if (stripos($this->useragent, $this->maemoTablet) > -1 && stripos($this->useragent, $this->linux) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current device is an Archos media player/Internet tablet.
   function detectArchos() {
      if (stripos($this->useragent, $this->deviceArchos) > -1) return $this->true; 
      else return $this->false; 
   }
   // detects if the current browser is a Sony Mylo device.
   function detectSonyMylo() {
      //Most devices use 'Windows CE', but some older ones reported as 'PIE' for Pocket IE. 
      if (stripos($this->useragent, $this->manuSony) > -1 && stripos($this->useragent, $this->qtembedded) > -1) return $this->true; 
      else return $this->false; 
   }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // Check to see whether the device is any device
   //   in the 'smartphone' category.
   //   We'll add Android here someday.
   function detectSmartphone() {
      if ($this->detectIphoneOrIpod() == $this->true) return $this->true; 
      if ($this->detectS60OssBrowser() == $this->true) return $this->true; 
      if ($this->detectSymbianOS() == $this->true) return $this->true; 
      if ($this->detectWindowsMobile() == $this->true) return $this->true; 
      if ($this->detectBlackBerry() == $this->true) return $this->true; 
      if ($this->detectPalmOS() == $this->true) return $this->true; 
      else return $this->false; 
   }
   // The quick way to detect for a mobile device.
   //   Will probably detect most recent/current mid-tier Feature Phones
   //   as well as smartphone-class devices.
   function detectMobileQuick() {
      //Ordered roughly by market share, WAP/XML > Brew > Smartphone.
      if ($this->detectWapWml() == $this->true) return $this->true; 
      if ($this->detectBrewDevice() == $this->true) return $this->true; 
      if (stripos($this->useragent, $this->engineOpera) > -1 &&  //detects Opera Mini
          stripos($this->useragent, $this->mini) > -1) return $this->true; 
      if (stripos($this->useragent, $this->engineUpBrowser) > -1) return $this->true; 
      if (stripos($this->useragent, $this->engineUpBrowser) > -1) return $this->true; 
      if (stripos($this->useragent, $this->engineOpenWeb) > -1) return $this->true; 
      if (stripos($this->useragent, $this->deviceMidp) > -1) return $this->true; 
      if ($this->detectSmartphone() == $this->true) return $this->true;    
      if ($this->detectDangerHiptop() == $this->true) return $this->true;    
      else return $this->false; 
   }
   // The longer and more thorough way to detect for a mobile device.
   //   Will probably detect most feature phones,
   //   smartphone-class devices, Internet Tablets, 
   //   Internet-enabled game consoles, etc.
   //   This ought to catch a lot of the more obscure and older devices, also --
   //   but no promises on thoroughness!
   function detectMobileLong() {
		if ($this->detectMobileQuick() == $this->true) return $this->true; 
		if ($this->detectMidpCapable() == $this->true) return $this->true; 
		if ($this->detectMaemoTablet() == $this->true) return $this->true; 
		if ($this->detectGameConsole() == $this->true) return $this->true; 
		if (stripos($this->useragent, $this->devicePda) > -1) return $this->true; 
		
		//detect older phones from certain manufacturers and operators. 
		if (stripos($this->useragent, $this->uplink) > -1) return $this->true; 
		if (stripos($this->useragent, $this->manuSonyEricsson) > -1) return $this->true; 
		if (stripos($this->useragent, $this->manuericsson) > -1) return $this->true; 
		if (stripos($this->useragent, $this->manuSamsung1) > -1) return $this->true; 
		if (stripos($this->useragent, $this->svcDocomo) > -1) return $this->true; 
		if (stripos($this->useragent, $this->svcKddi) > -1) return $this->true; 
		if (stripos($this->useragent, $this->svcVodafone) > -1) return $this->true; 
		
		else return $this->false; 
   }

}
