<?php /* **************************************************************
* htmlwrap() function - v1.7
* Copyright (c) 2004-2008 Brian Huisman AKA GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
*
*
* htmlwrap -- Safely wraps a string containing HTML formatted text (not
* a full HTML document) to a specified width
*
* Description
*
* string htmlwrap ( string str [, int width [, string break [, string nobreak]]])
*
* htmlwrap() is a function which wraps HTML by breaking long words and
* preventing them from damaging your layout.  This function will NOT
* insert <br /> tags every "width" characters as in the PHP wordwrap()
* function.  HTML wraps automatically, so this function only ensures
* wrapping at "width" characters is possible.  Use in places where a
* page will accept user input in order to create HTML output like in
* forums or blog comments.
*
* htmlwrap() won't break text within HTML tags and also preserves any
* existing HTML entities within the string, like &nbsp; and &lt;  It
* will only count these entities as one character.
*
* The function also allows you to specify "protected" elements, where
* line-breaks are not inserted.  This is useful for elements like <pre>
* if you don't want the code to be damaged by insertion of newlines.
* Add the names of the elements you wish to protect from line-breaks as
* as a space separate list to the nobreak argument.  Only names of
* valid HTML tags are accepted.  (eg. "code pre blockquote")
*
* htmlwrap() will *always* break long strings of characters at the
* specified width.  In this way, the function behaves as if the
* wordwrap() "cut" flag is always set.  However, the function will try
* to find "safe" characters within strings it breaks, where inserting a
* line-break would make more sense.  You may edit these characters by
* adding or removing them from the $lbrks variable.
*
* htmlwrap() is safe to use on strings containing UTF-8 multi-byte
* characters.
*
* See the inline comments and http://www.greywyvern.com/php.php
* for more info
******************************************************************** */

function htmlwrap($str, $width = 60, $break = "\n", $nobreak = ' ') {

  // Split HTML content into an array delimited by < and >
  // The flags save the delimeters and remove empty variables
  $content = preg_split("/([<>])/", $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
  
  // Transform protected element lists into arrays
  $nobreak = explode(' ', strtolower($nobreak));

  // Variable setup
  $intag = false;
  $innbk = array();
  $drain = "";

  // List of characters it is "safe" to insert line-breaks at
  // It is not necessary to add < and > as they are automatically implied
  $lbrks = "/?!%)-}]\\\"':;&";
  
  $utf8 = '';
	/*if (!function_exists("mb_detect_encoding")) $utf8 = 'u';
	else {
		$cod = mb_detect_encoding($str, "UTF-8, ISO-8859-1");
		if ($cod == "UTF-8") $utf8 = 'u';
	}*/
	
  // Is $str a UTF8 string?
  //$utf8 = (preg_match("/^([\x09\x0A\x0D\x20-\x7E]|[\xC2-\xDF][\x80-\xBF]|\xE0[\xA0-\xBF][\x80-\xBF]|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}|\xED[\x80-\x9F][\x80-\xBF]|\xF0[\x90-\xBF][\x80-\xBF]{2}|[\xF1-\xF3][\x80-\xBF]{3}|\xF4[\x80-\x8F][\x80-\xBF]{2})*$/", $str)) ? "u" : "";


  while (list(, $value) = each($content)) {
    switch ($value) {

      // If a < is encountered, set the "in-tag" flag
      case "<": $intag = true; break;

      // If a > is encountered, remove the flag
      case ">": $intag = false; break;

      default:

        // If we are currently within a tag...
        if ($intag) {

          // Create a lowercase copy of this tag's contents
          $lvalue = strtolower($value);

          // If the first character is not a / then this is an opening tag
          if ($lvalue{0} != "/") {

            // Collect the tag name   
            preg_match("/^(\w*?)(\s|$)/", $lvalue, $t);

            // If this is a protected element, activate the associated protection flag
            if (in_array($t[1], $nobreak)) array_unshift($innbk, $t[1]);

          // Otherwise this is a closing tag
          } else {

            // If this is a closing tag for a protected element, unset the flag
            if (in_array(substr($lvalue, 1), $nobreak)) {
              reset($innbk);
              while (list($key, $tag) = each($innbk)) {
                if (substr($lvalue, 1) == $tag) {
                  unset($innbk[$key]);
                  break;
                }
              }
              $innbk = array_values($innbk);
            }
          }

        // Else if we're outside any tags...
        } else if ($value) {

          // If unprotected...
          if (!count($innbk)) {

            // Use the ACK (006) ASCII symbol to replace all HTML entities temporarily
            $value = str_replace("\x06", "", $value);
            preg_match_all("/&([a-z\d]{2,7}|#\d{2,5});/i", $value, $ents);
            $value = preg_replace("/&([a-z\d]{2,7}|#\d{2,5});/i", "\x06", $value);

            // Enter the line-break loop
            do {
              $store = $value;

              // Find the first stretch of characters over the $width limit

              if (preg_match("/^(.*?\s)?([^\s]{".$width."})(?!(".preg_quote($break, "/")."|\s))(.*)$/s{$utf8}", $value, $match)) {

                if (strlen($match[2])) {
                  // Determine the last "safe line-break" character within this match
                  for ($x = 0, $ledge = 0; $x < strlen($lbrks); $x++) $ledge = max($ledge, strrpos($match[2], $lbrks{$x}));
                  if (!$ledge) $ledge = strlen($match[2]) - 1;

                  // Insert the modified string
                  $value = $match[1].substr($match[2], 0, $ledge + 1).$break.substr($match[2], $ledge + 1).$match[4];
                }
              }

            // Loop while overlimit strings are still being found
            } while ($store != $value);

            // Put captured HTML entities back into the string
            foreach ($ents[0] as $ent) $value = preg_replace("/\x06/", $ent, $value, 1);
          }
        }
    }

    // Send the modified segment down the drain
    $drain .= $value;
  }

  // Return contents of the drain
  return $drain;
}

?> 