<?
/*
    Copyright (c) 2002 Stephane Garin <sgarin@sgarin.com>
    Released under the GNU General Public License
    description: A class to detect prefered language
             of the Internet browser of the visitor.
*/
class detect_language {
	var $available_languages, $accepted_language, $detected_language;
	
	// Constructor
	function detect_language() {
		$this->available_languages = array(
			'bg'         => array('bg|bulgarian', 'bulgarian-win1251'),
			'ca'         => array('ca|catalan', 'catala'),
			'cs-iso'     => array('cs|czech', 'czech-iso'),
			'cs-win1250' => array('cs|czech', 'czech-win1250'),
			'da'         => array('da|danish', 'danish'),
			'de'         => array('de([-_][[:alpha:]]{2})?|german', 'german'),
			'en'         => array('en([-_][[:alpha:]]{2})?|english', 'english'),
			'es'         => array('es([-_][[:alpha:]]{2})?|spanish', 'spanish'),
			'fr'         => array('fr([-_][[:alpha:]]{2})?|french', 'french'),
			'it'         => array('it|italian', 'italian'),
			'ja'         => array('ja|japanese', 'japanese'),
			'ko'         => array('ko|korean', 'korean'),
			'nl'         => array('nl([-_][[:alpha:]]{2})?|dutch', 'dutch'),
			'no'         => array('no|norwegian', 'norwegian'),
			'pl'         => array('pl|polish', 'polish'),
			'pt-br'      => array('pt[-_]br|brazilian portuguese', 'brazilian_portuguese'),
			'pt'         => array('pt([-_][[:alpha:]]{2})?|portuguese', 'portuguese'),
			'ru-koi8r'   => array('ru|russian', 'russian-koi8'),
			'ru-win1251' => array('ru|russian', 'russian-win1251'),
			'se'         => array('se|swedish', 'swedish'),
			'sk'         => array('sk|slovak', 'slovak-iso'),
			'th'         => array('th|thai', 'thai'),
			'zh-tw'      => array('zh[-_]tw|chinese traditional', 'chinese_big5'),
			'zh'         => array('zh|chinese simplified', 'chinese_gb')
		);
		
		$this->accepted_language = explode(',', getenv('HTTP_ACCEPT_LANGUAGE'));
		
		$this->detected_language = $this->getLanguage();
	}
		
	/*
	getLanguage
	-----------
	function that look for prefered language by browser.
	
	Input: -
	Output: language detected or default language (fr).
	*/
	function getLanguage() {
		if (empty($this->detected_language)) {
			$this->detected_language = 'fr';
			$cnt = 0;
			while ($cnt < sizeof($this->accepted_language)) {
				reset($this->available_languages);
				
				while (list($key, $value) = each($this->available_languages)) {
					if ((eregi('^(' . $value[0] . ')(;q=[0-9]\\.[0-9])?$', $this->accepted_language[$cnt]))) {
						$this->detected_language = $key;
						break 2;
					}
				}
			$cnt++;
			}
		}
		return $this->detected_language;
	}
}
?>