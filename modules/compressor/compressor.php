<?php if (!defined('B2')) exit(0);

/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 25.03.2022
 * @version: 1.0
 *
 * Class that provides functionality to minify and assemble static JS and CSS files into one file or specified group of files
 */

class compressor {
	protected static array $available = [];		// Array of available static files grouped by file type
	protected static array $group = [];				// Array of file groups information
	protected static array $included = [];		// Array of included files

	//----------------------------------------------------------- IS ACTIVE ---------------------------------------------------------------------------
	public static function is_active() : bool {
		global $g_config;
		return $g_config['compressor']['enabled'] ?? true;
	}

	//----------------------------------------------------------- IS INLINE ---------------------------------------------------------------------------
	public static function is_inline() : bool {
		global $g_config;
		return $g_config['compressor']['is_inline'] ?? true;
	}

	//------------------------------------------------------- ADD AVAILABLE FILE ----------------------------------------------------------------------
	public static function add_available_file(string $type, string $url, array $file_info) : void {
		self::$available[$type][$url] = $file_info;
	}

	//------------------------------------------------------ CLEAR AVAILABLE FILES --------------------------------------------------------------------
	public static function clear_available_files(?string $type = null) : void {
		if ($type === null) self::$available = [];
		else unset(self::$available[$type]);
	}

	//------------------------------------------------------- REGISTER CSS FILES ----------------------------------------------------------------------
	// Register static CSS content to be joined
	public static function register_css(string $url, string $group = '') : bool {
		return self::register($url, $group, 'css');
	}

	//------------------------------------------------------- REGISTER JS FILES -----------------------------------------------------------------------
	// Register static Java Script content to be joined
	public static function register_js(string $url, string $group = '') : bool {
		if (__is_https_only()) $url = str_replace('http://', 'https://', $url);
		return self::register($url, $group, 'js');
	}

	/**--------------------------------------------------------- REGISTER -----------------------------------------------------------------------------
	 * Register static content to be processed
	 * @param string $url - File URL to get content from
	 * @param string $group - File group to join file content to
	 * @param string $type - Type of the file. Ex: 'js', 'css'
	 * @return boolean - TRUE on success, FALSE otherwise
	 */
	public static function register(string $url, string $group, string $type) : bool {
		if (!self::is_active()) return true;

		$file_path = self::get_file_path_from_url($url);
		if (!file_exists($file_path)) return __error('Unable to register file ' . $url . '. File not found');

		// Get file info
		@clearstatcache(true, $file_path);
		$file_modification_time = @filemtime($file_path);

		// Check if file has been processed before
		if (isset(self::$available[$type]) && isset(self::$available[$type][$url])) {
			$file_info = &self::$available[$type][$url];
			// Check if the file is already registered
			if (isset($file_info['registered'])) return true;
			// Check if the file modification time has been changed
			$is_file_changed = $file_modification_time != $file_info['mtime'];

			// Check if file has changed group
			$old_group = $file_info['group'];
			if ($old_group != $group) {
				if ($group != '') self::$group[$type][$group]['modified'] = true;
				if ($old_group != '') self::$group[$type][$old_group]['modified'] = true;

				// If the file was single and became part of a group, then delete minified file
				if ($old_group == '' && $group != '') {
					$old_file = __dir__ . '/' . $file_info['file'];
					unlink($old_file);
				}

				// If the file was part of the group and became single - mark file as modified
				elseif ($old_group != '' && $group == '')
					$file_info['modified'] = true;
			}

			if ( $is_file_changed )	{
				if ( $group != '' ) self::$group[$type][$group]['modified'] = true;
				else $file_info['modified'] = true;
				$file_info['mtime'] = $file_modification_time;
			}

			if ($group != '') self::$group[$type][$group]['files'][] = $url;
			$file_info['group'] = $group;
		}
		// New file
		else {
			if ($group != '') {
				self::$group[$type][$group]['modified'] = true;
				self::$group[$type][$group]['files'][] = $url;
			}
			else self::$available[$type][$url]['modified'] = true;

			self::$available[$type][$url]['mtime'] = $file_modification_time;
			self::$available[$type][$url]['group'] = $group;
		}
		self::$available[$type][$url]['registered'] = true;

		return true;
	}

	/**-------------------------------------------------------------- INCLUDE JS ----------------------------------------------------------------------
	 * Include JS or group where it belongs
	 * @param string $url - Original file URL
	 * @param bool $instant - TRUE to ignore grouping and return the original file
	 */
	public static function include_js(string $url, bool $instant = false) : void {
		if (__is_https_only()) $url = str_replace('http://', 'https://', $url);
		if (!self::is_active()) { echo '<script type="text/javascript" src="' . $url . '"></script>' . NR; return; }
		self::include($url, 'js', $instant);
	}

	/**------------------------------------------------------------- INCLUDE CSS ----------------------------------------------------------------------
	 * Include CSS or group where it belongs
	 * @param string $url - Original file URL
	 * @param bool $instant - TRUE to ignore grouping and return the original file
	 */
	public static function include_css(string $url, bool $instant = false) {
		if (!self::is_active()) { echo '<link rel="stylesheet" href="' . $url . '" type="text/css" media="screen" />' . NR; return; }
		self::include($url, 'css', $instant);
	}

	/**--------------------------------------------------------------- INCLUDE ------------------------------------------------------------------------
	 * Includes the specified file or group where it belongs
	 * @param string $url - Original file URL
	 * @param string $type - Type of the file. Ex: 'css', 'js'
	 * @param bool $instant - TRUE to ignore grouping and return the original file
	 */
	public static function include(string $url, string $type, bool $instant) : void {
		$include_url = $url;
		if (!$instant && isset(self::$available[$type][$url])) {
			$result_file = self::$available[$type][$url]['file'];

			if (!isset(self::$included[$result_file])) {
				$include_url = __dir__ . '/' . $result_file;
				self::$included[$result_file] = true;
			}
			// Do not include twice
			else return;
		}
		else {
			if (!isset(self::$included[$url])) {
				self::$included[$url] = true;
				$url_info = parse_url($include_url);
				if (isset($url_info['scheme']))
					$include_url = '//' . $url_info['host'] . $url_info['path'];
			}
			// Do not include twice
			else return;
		}

		if ($type == 'js' && __is_https_only()) $include_url = str_replace('http://', 'https://', $include_url);

		if ($type == 'js') {
			if (self::is_inline() && file_exists(str_replace(__info('site_url').'/', __info('base_dir').'/', $include_url))) {
				echo '<script type="text/javascript">' . NR;
					echo file_get_contents(str_replace(__info('site_url').'/', __info('base_dir').'/', $include_url)) . NR;
				echo '</script>' . NR;
			}
			else echo '<script async type="text/javascript" src="' . $include_url . '"></script>' . NR;
		}
		elseif($type == 'css') {
			if (self::is_inline() && file_exists(str_replace(__info('site_url').'/', __info('base_dir').'/', $include_url))) {
				echo '<style type="text/css">' . NR;
					echo file_get_contents(str_replace(__info('site_url').'/', __info('base_dir').'/', $include_url)) . NR;
				echo '</style>' . NR;
			}
			else echo '<link rel="stylesheet" href="' . $include_url . '" type="text/css" media="screen" />' . NR;
		}
	}

	/**----------------------------------------------------------- SAVE FILES LIST CACHE --------------------------------------------------------------
	 * Save processed files list
	 * @param string $type - Type of the files. Ex: 'css', 'js'
	 */
	public static function save_files_list_cache(string $type) : void {
		$content = '<?php if(!defined(\'B2\'))exit(\'No direct access allowed\');'.NR;

		foreach ((self::$available[$type] ?? []) as $path => $file) {
			$content .= 'compressor::add_available_file(\''.$type.'\', \''.$path.'\', array(\'mtime\'=>\''.
				$file['mtime'] .'\', \'group\'=>\''. $file['group'] .'\', \'file\'=>\''.$file['file'].'\'));'.NR;
		}

		file_put_contents(__dir__ . '/' . $type . '/files.php', $content);
	}

	/**----------------------------------------------------------- PROCESS FILES ----------------------------------------------------------------------
	 * Combines listed files into the one and place it to the 'compressor' folder with unique filename
	 * @param string $type - Type of the files. Ex: 'css' or 'js'
	 * @return bool - TRUE on success, FALSE otherwise
	 */
	public static function process_files(string $type) : bool {
		$saved = false;

		foreach((self::$available[$type] ?? []) as $url => $file) {
			$file_group = $file['group'];
			// Check if grouped file was removed completely
			if ($file_group != '' && !isset(self::$group[$type][$file_group]['modified'])) {
				$group_files = self::$group[$type][$file_group]['files'] ?? [];
				if ($group_files === null || !in_array($url, $group_files))	{
					// Mark group as modified
					self::$group[$type][$file_group]['modified'] = true;
					// Remove file from list
					unset(self::$available[$type][$url]);
					$saved = true;
				}
			}
			// Check if single file was removed completely
			elseif ($file_group == '' && !isset($file['registered'])) {
				$file_path = __dir__ . '/' . $file['file'];
				unlink($file_path);
				unset(self::$available[$type][$url]);
				$saved = true;
			}
		}

		// Combine modified groups
		foreach ((self::$group[$type] ?? []) as $name => $group) {
			if (isset($group['modified'])) {
				// Check if group became empty
				if (!isset($group['files'])) {
					// Find and delete group files
					$files_list = glob(self::get_group_file_path($name, $type, false) . '+*.' . $type);
					if (!empty($files_list))
						foreach ($files_list as $item)
							@unlink($item);

					continue;
				}

				$content = self::assemble_file_group($name, $type);
				if (!$content) continue;
				$result_url = self::save_combined_file($name, $type, $content);
				$saved = true;

				foreach($group['files'] as $group_file)
					self::$available[$type][$group_file]['file'] = $result_url;
			}
		}

		// Minify modified single files
		foreach ((self::$available[$type] ?? []) as $url => $file) {
			if (isset($file['modified'])) {
				$content = self::minify_file($url, $type);
				self::$available[$type][$url]['file'] = self::save_combined_file(md5($url), $type, $content);
				$saved = true;
			}
		}

		return $saved;
	}

	//-------------------------------------------------------------- PROCESS ALL ----------------------------------------------------------------------
	// Process all registered files
	public static function process_all() : bool {
		if (!count(self::$available)) return true;

		foreach (self::$available as $key => $item) {
			@include_once($key . '/files.php');
			compressor::process_files($key);
			compressor::save_files_list_cache($key);
		}

		return true;
	}

	/**--------------------------------------------------------- SAVE COMBINED FILE -------------------------------------------------------------------
	 * Save combined file to the disk. It saves file to the 'compressor' directory with unique filename
	 * @param string $base_name Basename of the file. Ex: 'SOMEFILE'
	 * @param string $type File type. By default 'css' or 'js'
	 * @param string $file_content Binary data to save to the disk
	 * @return ?string URL of the combined file
	 */
	protected static function save_combined_file($base_name, $type, $file_content) : ?string {
		// Remove old files
		foreach (glob(self::get_group_file_path($base_name, $type, false).'+*.'.$type) as $Item )
			@unlink( $Item );
		// Create new one
		$suffix = md5($file_content) . mb_strlen($file_content , 'utf8');
		$output_file_name = self::get_group_file_path($base_name.'+'.$suffix, $type);
		if (!file_exists(dirname($output_file_name))) mkdir(dirname($output_file_name));
		$output_url = $type . '/' . $base_name . '+' . $suffix . '.' . $type;
		$file_handle = @fopen($output_file_name, "wb+");

		// Try to unlock file (if it is locked of course)
		if ($file_handle === false) {
				/*@flock( $file_handle, LOCK_UN );
				$file_handle = @fopen( $output_file_name, "wb+" );
				if( $file_handle===false )*/return null;
		}
		@flock($file_handle, LOCK_EX);
		@fwrite($file_handle, $file_content);
		@fflush($file_handle);
		@flock($file_handle, LOCK_UN);
		@fclose($file_handle);

		return $output_url;
	}

	//--------------------------------------------------------- GET GROUP FILE PATH -------------------------------------------------------------------
	// Returns file path to the combined and optimized group file
	protected static function get_group_file_path(string $group, string $type, bool $with_extension = true ) : string {
		return __dir__ . '/' . $type . '/' . $group . ($with_extension ? ('.' . $type) : '');
	}

	//---------------------------------------------------------- GET GROUP FILE URL -------------------------------------------------------------------
	// Returns URL of the combined and optimized group file
	protected static function get_group_file_url($group, $type, $with_extension = true) : string {
		return __dir__ . '/' . $type . '/' . $group . ($with_extension ? ('.' . $type) : '');
	}

	//-------------------------------------------------------- GET FILE PATH FROM URL -----------------------------------------------------------------
	protected static function get_file_path_from_url(string $url) : string {
		$url_info = parse_url($url);
		$rel_path = substr($url, strlen($url_info['scheme'] . '://' . $url_info['host'] . '/'));
		return __info('base_dir') . '/' . $rel_path;
	}

	/**---------------------------------------------------------- ASSEMBLY FILE GROUP -----------------------------------------------------------------
	 * Minify and assemble group files into one file
	 * @param string $group - Group name
	 * @param string $type - Type of the files. Ex: 'css', 'js'
	 * @return string - New file content
	 */
	protected static function assemble_file_group(string $group, string $type) : string {
		if (!isset(self::$group[$type][$group]['files'] )) return '';

		$buffer = '';
		if( $type === 'js' ) $common_separator = ';';
		else $common_separator = ' ';

		foreach(self::$group[$type][$group]['files'] as $item)
			$buffer.= self::minify_file($item, $type) . $common_separator;

		return $buffer;
	}

	/**-------------------------------------------------------------- MINIFY FILE ---------------------------------------------------------------------
	 * Minify file
	 * @param string $url - URL of the file
	 * @param string $type - Type of the file. Ex: 'css', 'js'
	 * @return ?string - Minified data, or NULL on error
	 */
	protected static function minify_file(string $url, string $type) : ?string {
		$file_path = self::get_file_path_from_url($url);
		$ext = pathinfo($file_path, PATHINFO_EXTENSION);

		$content = @file_get_contents($file_path);
		if ($content === false) return null;

		if ($type === 'js') {
			require_once('minifier.php');
			$minified = minifier::minify($content);
			if ($minified !== null) $content = $minified;
		}
		else {
			// Check if it is a less file
			if ($ext == 'less') {
				require_once('lessc.php');
				$less = new lessc;
				$content = $less->compile($content);
			}

			// It is supposed that it is CSS
			$content = self::fix_css($content, $url);
			$content = self::minify_css($content);
		}
		return $content;
	}

	//------------------------------------------------------------------ FIX CSS ----------------------------------------------------------------------
	// Fixes CSS relative paths
	// NOTE: @import fix currently does not supported.
	protected static function fix_css(string $css_content, string $url) : string {
		// find and replace url(URI) strings and replace URI to URL
		$path = preg_replace( '/\/[^\/]*$/', '/', $url );
		$aParts = parse_url($path);
		//$cCSS = preg_replace('/url\s*\(\s*[\"\']{0,1}(.*?)[\"\']{0,1}\s*\)/', 'url(' . $path . '$1)', $cCSS); // not for URL
		$css_content = preg_replace_callback( '/url\s*\(\s*[\"\']{0,1}(.*?)[\"\']{0,1}\s*\)/',
			function ($math) use (&$aParts) {
				if (preg_match('/^\/\//', $math[1]) || strpos($math[1], 'data:') === 0)return 'url(' . $math[1] . ')'; // for URL
				else return 'url('.'//'.$aParts['host']. self::resolve_rel_path($aParts['path'].$math[1]) . ')';
			}, $css_content
		);

		return $css_content;
	}

	//------------------------------------------------------------- RESOLVE REL PATH ------------------------------------------------------------------
	protected static function resolve_rel_path(string $path) : string {
		$ret_val = [];
		foreach(explode('/', $path) as $i => $part) {
			if ($part == '' || $part == '.') continue;
			if ($part == '..' && $i > 0 && end($ret_val) != '..') array_pop($ret_val);
			else $ret_val[] = $part;
		}
		return ($path[0] == '/' ? '/' : '') . join('/', $ret_val);
	}

	//--------------------------------------------------------------- MINIFY CSS ----------------------------------------------------------------------
	protected static function minify_css(string $content) : string {
		// Strip Comments
		$content = preg_replace('!/\*.*?\*/!s', '', $content);
		$content = preg_replace('/\n\s*\n/', "\n", $content);

		// Minify
		$content = preg_replace('/[\n\r \t]/', ' ', $content);
		$content = preg_replace('/ +/', ' ', $content);
		$content = preg_replace('/ ?([,:;{}]) ?/', '$1', $content);

		// Kill trailing semicolon
		$content = preg_replace('/;}/', '}', $content);

		// Return minified CSS
		return $content;
	}

}