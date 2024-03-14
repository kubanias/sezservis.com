<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @copyright
 * (c) 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * All rights reserved.
 * @author: Robert Hafner <tedivm@tedivm.com>, Dmitri <info@volnorez.com>, Arthur Matveyev <info@art07.ru>
 * @date: 25.03.2022
 * @version: 1.0
 */

class minifier {
	protected string $input;				// The input javascript to be minified.
	protected int $index = 0;				// The location of the character (in the input string) that is next to be processe
	protected string $a = '';			// The first of the characters currently being looked a
	protected string $b = '';			// The next character being looked at (after a);
	protected string $c;						// This character is only active when certain look ahead actions take place.
	protected array $options;				// Contains the options for the current minification process.

	// Contains the default options for minification. This array is merged with the one passed in by the user to create
	// the request specific set of options (stored in the $options attribute).
	static protected array $default_options = array();

  // Contains a copy of the Minifier object used to run minification. This is only used internally, and is only stored
  // for performance reasons. There is no internal data shared between minification requests.
	static protected minifier $minifier;

	/**------------------------------------------------------------ MINIFY ----------------------------------------------------------------------------
	 * Minifier::minify takes a string containing javascript and removes unneeded characters in order to shrink the code
	 * without altering its functionality.
	 */
	static public function minify(string $js, $options = array()) : ?string {
		$current_options = array_merge(self::$default_options, $options);

		try {
			if(!isset(self::$minifier))	self::$minifier = new minifier();
			return self::$minifier->breakdown_script($js, $current_options);
		} catch (Exception $e) {
			return null;
		}
	}

	/**-------------------------------------------------------- BREAKDOWN SCRIPT ----------------------------------------------------------------------
	 * Processes a javascript string and outputs only the required characters, stripping out all unneeded characters.
	 * @param string $js The raw javascript to be minified
	 * @param array $current_options Various runtime options in an associative array
	 * @return string - Minified Java Script
	 */
	protected function breakdown_script(string $js, array $current_options) : string {
		$buffer = '';

		// reset work attributes in case this isn't the first run.
		$this->clean();

		$this->options = $current_options;
		$js = str_replace("\r\n", "\n", $js);
		$this->input = str_replace("\r", "\n", $js);
		$this->a = $this->get_real();

		// The only time the length can be higher than 1 is if a conditional comment needs to be displayed
		// and the only time that can happen for $a is on the very first run
		while (strlen($this->a) > 1)	{
			$buffer .= $this->a;
			$this->a = $this->get_real();
		}

		$this->b = $this->get_real();

		while ($this->a !== false && !is_null($this->a) && $this->a !== '') {

			// Now we give $b the same check for conditional comments we gave $a before we began looping
			if (strlen($this->b) > 1) {
				$buffer .= $this->a . $this->b;
				$this->a = $this->get_real();
				$this->b = $this->get_real();
				continue;
			}

			switch ($this->a)	{
				// New lines
				case "\n":
					// If the next line is something that can't stand alone preserve the newline
					if(strpos('(-+{[@', $this->b) !== false) {
						$buffer .= $this->a;
						$buffer .= $this->save_string();
						break;
					}

					// If it is a space we move down to the string test below
					if($this->b === ' ')
						break;

					// Otherwise we treat the newline like a space

				case ' ':
					if(self::is_alpha_numeric($this->b))
						$buffer .= $this->a;

					$buffer .= $this->save_string();
					break;

				default:
					switch($this->b) {
						case "\n":
							if(strpos('}])+-"\'', $this->a) !== false) {
								$buffer .= $this->a;
								$buffer .= $this->save_string();
								break;
							}
							else {
								if (self::is_alpha_numeric($this->a)) {
									$buffer .= $this->a;
									$buffer .= $this->save_string();
								}
							}
							break;

						case ' ':
							if(!self::is_alpha_numeric($this->a))
								break;

						default:
							// Check for some regex that breaks stuff
							if($this->a == '/' && ($this->b == '\'' || $this->b == '"')) {
								$buffer .= $this->save_regex();
								break; // continue;
							}

							$buffer .= $this->a;
							$buffer .= $this->save_string();
							break;
					}
			}

			// Do reg check of doom
			$this->b = $this->get_real();

			if (($this->b == '/' && strpos('(,=:[!&|?', $this->a) !== false))
				$buffer .= $this->save_regex();
		}
		$this->clean();

		return $buffer;
	}

	/**---------------------------------------------------------------- GET CHAR ----------------------------------------------------------------------
	 * Returns the next string for processing based off of the current index.
	 */
	protected function get_char() : string {
		if (isset($this->c)) {
			$char = $this->c;
			unset($this->c);
		}
		else {
			$tchar = substr($this->input, $this->index, 1);
			if (isset($tchar) && $tchar !== false && $tchar !== '') {
				$char = $tchar;
				$this->index++;
			}
			else return '';
		}

		if ($char !== "\n" && ord($char) < 32)
			return ' ';

		return $char;
	}

	/**---------------------------------------------------------- GET REAL ----------------------------------------------------------------------------
	 * This function gets the next "real" character. It is essentially a wrapper around the getChar function that skips
	 * comments. This has significant performance benefits as the skipping is done using native functions (ie, c code)
	 * rather than in script php.
	 * @return string - Next 'real' character to be processed.
	 */
	protected function get_real() : string {
		$start_index = $this->index;
		$char = $this->get_char();

		if($char == '/') {
			$this->c = $this->get_char();

			if($this->c == '/')	{
				$third_comment_string = substr($this->input, $this->index, 1);

				// kill rest of line
				$char = $this->get_next("\n");

				if($third_comment_string == '@') {
					$end_point = ($this->index) - $start_index;
					unset($this->c);
					$char = "\n" . substr($this->input, $start_index, $end_point);// . "\n";
				}
				else {
					$char = $this->get_char();
					$char = $this->get_char();
				}
			}
			elseif ($this->c == '*') {
				$this->get_char(); // Current C
				$third_comment_string = $this->get_char();

				if($third_comment_string == '@') {
					// Conditional comment
					// We're gonna back up a bit and and send the comment back, where the first
					// char will be echoed and the rest will be treated like a string
					$this->index = $this->index - 2;
					return '/';
				}
				elseif ($this->get_next('*/')) {
					// Kill everything up to the next */
					$this->get_char(); // get *
					$this->get_char(); // get /
					$char = $this->get_char(); // get next real character
				}
				else $char = '';

				if ($char === '') throw new \RuntimeException('Stray comment. ' . $this->index);

				// If we're here c is part of the comment and therefore tossed
				if (isset($this->c)) unset($this->c);
			}
		}
		return $char;
	}

	/**---------------------------------------------------------------- GET NEXT ----------------------------------------------------------------------
	 * Pushes the index ahead to the next instance of the supplied string. If it is found the first character of the
	 * string is returned.
	 * @return ?string Returns the first character of the string if found, false otherwise.
	 */
	protected function get_next($string) : string {
		$pos = strpos($this->input, $string, $this->index);
		if ($pos === false) return '';
		$this->index = $pos;
		return substr($this->input, $this->index, 1);
	}

	/**-------------------------------------------------------------- SAVE STRING ---------------------------------------------------------------------
	 * When a javascript string is detected this function crawls for the end of it and saves the whole string.
	 */
	protected function save_string() : string	{
		$buffer = '';
		$this->a = $this->b;
		if ($this->a == "'" || $this->a == '"') { // Is the character a quote
			// save literal string
			$stringType = $this->a;

			while (1) {
				$buffer .= $this->a;
				$this->a = $this->get_char();

				switch ($this->a) {
					case $stringType:
						break 2;

					case "\n":
						throw new \RuntimeException('Unclosed string. ' . $this->index);
						break;

					case '\\':
						$buffer .= $this->a;
						$this->a = $this->get_char();
				}
			}
		}

		return $buffer;
	}

	/**-------------------------------------------------------------- SAVE REGEX ----------------------------------------------------------------------
	 * When a regular expression is detected this function crawls for the end of it and saves the whole regex.
	 */
	protected function save_regex() : string {
		$buffer = $this->a . $this->b;

		while(($this->a = $this->get_char()) !== false) {
			if($this->a == '/')	break;

			if($this->a == '\\') {
				$buffer .= $this->a;
				$this->a = $this->get_char();
			}

			if($this->a == "\n") throw new \RuntimeException('Stray regex pattern. ' . $this->index);

			$buffer .= $this->a;
		}
		$this->b = $this->get_real();

		return $buffer;
	}

	/**----------------------------------------------------------------- CLEAN ------------------------------------------------------------------------
	 * Resets attributes that do not need to be stored between requests so that the next request is ready to go.
	 */
	protected function clean() : void {
		unset($this->input);
		$this->index = 0;
		$this->a = $this->b = '';
		unset($this->c);
		unset($this->options);
	}

	/**------------------------------------------------------------ IS ALPHA NUMERIC ------------------------------------------------------------------
	 * Checks to see if a character is alphanumeric.
	 */
	static protected function is_alpha_numeric($char) : bool	{
		return preg_match('/^[\w\$]$/', $char) === 1 || $char == '/';
	}

}