<?php

####### GNU General Public License #############################################
#                                                                              #
# This file is part of HOA Open Accessibility.                                 #
# Copyright (c) 2006 Ivan ENDERLIN. All rights reserved.                       #
#                                                                              #
# HOA Open Accessibility is free software; you can redistribute it and/or      #
# modify it under the terms of the GNU General Public License as published by  #
# the Free Software Foundation; either version 2 of the License, or            #
# (at your option) any later version.                                          #
#                                                                              #
# HOA Open Accessibility is distributed in the hope that it will be useful,    #
# but WITHOUT ANY WARRANTY; without even the implied warranty of               #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                #
# GNU General Public License for more details.                                 #
#                                                                              #
# You should have received a copy of the GNU General Public License            #
# along with HOA Open Accessibility; if not, write to the Free Software        #
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA   #
#                                                                              #
####### !GNU General Public License ############################################

/**
 * Class Xml.
 *
 * This class parse a Xml file and return the content in an array.
 * @author ENDERLIN Ivan <enderlin.ivan@firegates.com>
 * @copyright 2006 ENDERLIN Ivan.
 * @since PHP4
 * @version 0.2
 * @link (nothing)
 * @package XML
 * @licence GNU GPL
 */

class Xml {


	var $parser;
	var $pOut = array();
	var $track = array();
	var $tmpLevel = '';
	var $tmpAttrLevel = array();
	var $wOut = '';

	/**
	 * parse
	 * This method sets the parser Xml and theses options.
	 * Xml file could be a string, a file, or curl.
	 * When the source is loaded, we run the parse.
	 * After, we clean all the memory and variables,
	 * and return the result in an array.
	 *
	 * @access  public
	 * @param   src       string    Source
	 * @param   typeof    string    Source type : NULL, FILE, CURL.
	 * @param   encoding  string    Encoding type.
	 * @return  array
	 */
	function parse ( $src, $typeof = 'FILE', $encoding = 'UTF-8' ) {

		// ini;
		// (re)set array;
		$this->pOut = array();
		$this->parser = xml_parser_create();

		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $encoding);

		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'startHandler', 'endHandler');
		xml_set_character_data_handler($this->parser, 'contentHandler');


		// format source;
		if($typeof == NULL)
			$data = $src;
		elseif($typeof == 'FILE') {
			$fop = fopen($src, 'r');
			$data = fread($fop, filesize($src));
			fclose($fop);
		}
		elseif($typeof == 'CURL') {
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $src);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			$data = curl_exec($curl);
			curl_close($curl);
		}
		else
			trigger_error('Xml parser need data', E_USER_ERROR);

		// parse $data;
		$parse = xml_parse($this->parser, $data);
		if(!$parse)
			trigger_error('XML Error : '.xml_error_string(xml_get_error_code($this->parser)).
                          ' at line '.xml_get_current_line_number($this->parser), E_USER_ERROR);

		// destroy parser;
		xml_parser_free($this->parser);

		// unset extra vars;
		unset($data, $this->track, $this->tmpLevel, $this->tmpAttrLevel);

		// remove global tag and return the result;
		return $this->pOut[0][key($this->pOut[0])];
	}



	/**
	 * startHandler
	 * This method manages the open tag, and these attributs by callback.
	 * The purpose is to create a pointer : {{int ptr}}.
	 * If the pointer exists, we have a multi-tag situation.
	 * Tag name  is stocked like : '<tag>'
	 * Attributs is stocked like : '<tag>-ATTR'
	 * This method returns TRUE but built $this->pOut.
	 *
	 * @access  private
	 * @param   parser  resource    Parser resource.
	 * @param   tag     string      Tag name.
	 * @param   attr    array       Attribut.
	 * @return  TRUE
	 */
	function startHandler ( $parser, $tag, $attr ) {

		static $fnstAttr = TRUE;

		// built $this->track;
		$this->track[] = $tag;
		// place pointer to the end;
		end($this->track);
		// temp level;
		$this->tmpLevel = key($this->track);

		// built attrLevel into $this->tmpAttrLevel
		if(isset($this->tmpAttrLevel[$this->tmpLevel]['attrLevel']))
			$this->tmpAttrLevel[$this->tmpLevel]['attrLevel']++;

		// built $this->pOut;
		if(!isset($this->pOut[key($this->track)][$tag])) {
			$this->pOut[key($this->track)][$tag] = '{{'.key($this->track).'}}';

			if(!isset($this->tmpAttrLevel[$this->tmpLevel]['attrLevel']))
				$this->tmpAttrLevel[$this->tmpLevel]['attrLevel'] = 0;				
		}

		// built attributs;
		if(!empty($attr)) {

			$this->tmpAttrLevel[$this->tmpLevel][] = $this->tmpAttrLevel[$this->tmpLevel]['attrLevel'];
			end($this->tmpAttrLevel[$this->tmpLevel]);

			// it's the first attribut;
			if(!isset($this->pOut[key($this->track)][$tag.'-ATTR']))
					$this->pOut[key($this->track)][$tag.'-ATTR'] = $attr;

			// or it's not the first;
			else {
				// so it's the second;
				if($fnstAttr === TRUE) {
					$this->pOut[key($this->track)][$tag.'-ATTR'] = array(
						prev($this->tmpAttrLevel[$this->tmpLevel]) => $this->pOut[key($this->track)][$tag.'-ATTR'],
						next($this->tmpAttrLevel[$this->tmpLevel]) => $attr
					);
					$fnstAttr = FALSE;
				}
				// or one other;
				else
					$this->pOut[key($this->track)][$tag.'-ATTR'][$this->tmpAttrLevel[$this->tmpLevel]['attrLevel']] = $attr;
			}
		}

		return TRUE;
	}



	/**
	 * contentHandler
	 * This method detect the pointer, or the multi-tag by callback.
	 * If we have a pointer, the method replaces this pointer by the content.
	 * Else we have a multi-tag, the method add a element to this array.
	 * This method returns TRUE but built $this->pOut.
	 *
	 * @access  private
	 * @param   parser          resource    Parser resource.
	 * @param   contentHandler  string      Tag content.
	 * @return  TRUE
	 */
	function contentHandler ( $parser, $contentHandler ) {

		// remove all spaces;
		if(!preg_match('#^\s*$#', $contentHandler)) {

			// $contentHandler is a string;
			if(is_string($this->pOut[key($this->track)][current($this->track)])) {

				// then $contentHandler is a pointer : {{int ptr}}     case 1;
				if(preg_match('#{{([0-9]+)}}#', $this->pOut[key($this->track)][current($this->track)]))
					$this->pOut[key($this->track)][current($this->track)] = $contentHandler;

				// or then $contentHandler is a multi-tag content      case 2;
				else {
					$this->pOut[key($this->track)][current($this->track)] = array(
						0 => $this->pOut[key($this->track)][current($this->track)],
						1 => $contentHandler
					);
				}
			}
			// or $contentHandler is an array;
			else {

				// then $contentHandler is the multi-tag array         case 1;
				if(isset($this->pOut[key($this->track)][current($this->track)][0]))
					$this->pOut[key($this->track)][current($this->track)][] = $contentHandler;

				// or then $contentHandler is a node-tag               case 2;
				else
					$this->pOut[key($this->track)][current($this->track)] = array(
						0 => $this->pOut[key($this->track)][current($this->track)],
						1 => $contentHandler
					);
			}

		}

		return TRUE;
	}



	/**
	 * endHandler
	 * This method detects the last pointer by callback.
	 * Move the last tags block up.
	 * And reset some temp variables.
	 * This method returns TRUE but built $this->pOut.
	 *
	 * @access  private
	 * @param   parser  resource    Parser resource.
	 * @param   tag     string      Tag name.
	 * @return  TRUE
	 */
	function endHandler ( $parser, $tag ) {

		// if level--;
		if(key($this->track) == $this->tmpLevel-1) {
			// search up tag;
			// use array_keys if an empty tag exists (taking the last tag);

			// if it's a normal framaset;
			$keyBack = array_keys($this->pOut[key($this->track)], '{{'.key($this->track).'}}');
			$count = count($keyBack);

			if($count != 0) {
				$keyBack = $keyBack{$count-1};
				// move this level up;
				$this->pOut[key($this->track)][$keyBack] = $this->pOut[key($this->track)+1];
			}

			// if we have a multi-tag framaset ($count == 0);
			else {
				// if place is set;
				if(isset($this->pOut[key($this->track)][current($this->track)][0])) {

					// if it's a string, we built an array;
					if(is_string($this->pOut[key($this->track)][current($this->track)]))
						$this->pOut[key($this->track)][current($this->track)] = array(
							0 => $this->pOut[key($this->track)][current($this->track)],
							1 => $this->pOut[key($this->track)+1]
						);

					// else add an index into the array;
					else
						$this->pOut[key($this->track)][current($this->track)][] = $this->pOut[key($this->track)+1];
				}
				// else set the place;
				else
					$this->pOut[key($this->track)][current($this->track)] = array(
						0 => $this->pOut[key($this->track)][current($this->track)],
						1 => $this->pOut[key($this->track)+1]
					);
			}

			// kick $this->pOut level out;
			array_pop($this->pOut);
			end($this->pOut);
		}

		// re-temp level;
		$this->tmpLevel = key($this->track);

		// kick $this->track level out;
		array_pop($this->track);
		end($this->track);

		return TRUE;
	}

}

?>