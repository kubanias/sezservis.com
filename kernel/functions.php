<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 19.03.2022
 * @version: 1.0
 *
 * Core Basis2 functions that will be accessible in any file
 */

__set_config(['custom_router'], null, false);						// Relative path to the custom router file. NULL to ignore
__set_config(['protocol'], 'https', false);							// Protocol to use for __info() function
																	// Default is the first one specified
__set_config(['decode_json_post'], false, false);					// To decode JSON to array of values during POST requests
__set_config(['json_flags'], JSON_UNESCAPED_UNICODE, false);		// JSON flags for output in global functions like __json_success()

__set_config(['lang', 'list'], ['en', 'ru', 'de'], false);			// List of supported languages. Empty array disable feature
__set_config(['lang', 'default'], 'en', false);						// Default language that will be returned by __lang()
__set_config(['lang', 'source'], 'slug', false);					// Source of lang data: 'slug' (first URL segment) or 'get_param' (in URL like ?lang=ru)
__set_config(['lang', 'get_param_name'], 'lang', false);			// Name of the parameter to use in URL. Ex: ex.com/page?lng=en for 'lng'
__set_config(['lang', 'use_cookies'], true, false);					// To get/set lang cookie or not

__set_config(['log', 'dir_relative'], 'downloads', false);			// Relative path to the dir with log files
__set_config(['log', 'dir_absolute'], null, false);					// Absolute path to log files dir. Priority is higher than dir_relative. NULL to ignore
__set_config(['log', 'basename'], 'log', false);					// Log file name without extension
__set_config(['log', 'extension'], 'log', false);					// Log file extension
__set_config(['log', 'suffix_format'], 'Ym', false);				// date(format) as suffix, like log202203.log. '' to ignore and use one file
__set_config(['log', 'max_files'], 5, false);						// Max number of files when using suffix_format. Lowest in abc sort order will be removed

// FALSE to check max_files every new log file added (good for daemons). TRUE - once per new file and launch
__set_config(['log', 'check_once'], true, false);

//###################################################################################################################################################
//############################################################## C O R E ############################################################################
//###################################################################################################################################################

//######################################################### ERRORS DISPLAYING #######################################################################
//---------------------------------------------------------- SET LAST ERROR -------------------------------------------------------------------------
// Always returns FALSE
function __error(string $error, mixed $return_value = false, string $dom_class = 'error', ?string $file_name = null) : bool {
	global $g_last_errors;
	$g_last_errors[] = $error;

	if (__get_config(['display_custom_errors'], false))	echo '<div class="' . $dom_class . '">' . $error . '</div>';
	__log($error, 'ERROR', $file_name);

	return $return_value;
}

//-------------------------------------------------------- SET LAST ERROR EX ------------------------------------------------------------------------
// Allows to skip an error setup and just return the specified value.
function __error_ex(bool $set_last_error = true, string $error = '', mixed $return_value = false,
										string $dom_class = 'error', ?string $file_name = null) : mixed {
	if ($set_last_error) __error($error, $return_value, $dom_class, $file_name);
	return $return_value;
}

//---------------------------------------------------------- GET LAST ERROR -------------------------------------------------------------------------
function __get_last_error(bool $return_full_array = false) : ?string {
	global $g_last_errors;
	if ($return_full_array) return $g_last_errors;
	else return count($g_last_errors) ? end($g_last_errors) : null;
}

//-------------------------------------------------------- SHOW CUSTOM ERRORS -----------------------------------------------------------------------
function __show_custom_errors(bool $show = true) : void {
	global $g_config;
	$g_config['display_custom_errors'] = $show;
}

//------------------------------------------------------------ WARNING ------------------------------------------------------------------------------
function __warning(mixed $message = '', mixed $return_value = false, ?string $file_name = null) : void {
	__log($message, 'WARNING', $file_name);
}

//-------------------------------------------------------------- LOG --------------------------------------------------------------------------------
function __log(mixed $message = '', ?string $error_type = null, ?string $file_name = null, $force_write = false) : void {
	global $g_cache;

	if (!$force_write && !__get_config(['log', 'write_to_file'], true)) return;
	if (is_array($message)) { __log('<pre>' . print_r($message, true) . '</pre>'); return; }	// Convert array to string and trace it

	// Generate log file name
	$log_file = $file_name;
	if ($log_file === null) {
		if (__get_config(['log', 'dir_absolute']) !== null)
			$log_file = __get_config(['log', 'dir_absolute']);
		if ($log_file === null && __get_config(['log', 'dir_relative']) !== null)
			$log_file = __info('base_dir') . '/' . __get_config(['log', 'dir_relative']);
		if ($log_file === null) return;
		$log_file.= '/' . __get_config(['log', 'basename'], 'log');
		if (__get_config(['log', 'suffix_format'], '') != '') $log_file.= date(__get_config(['log', 'suffix_format']));
		$extension = __get_config(['log', 'extension'], 'txt');
		$log_file.= $extension == '' ? '' : ('.' . $extension);
	}

	// Check if we need to clean up old cache files
	$remove_old = false;
	if (!__get_config(['log', 'check_once'], true) || !isset($g_cache['log']['file_exists'])) {
		if (!($g_cache['log']['file_exists'] = file_exists($log_file))) $remove_old = true;
	}

	$h_file = fopen($log_file, 'a+');
	if ($h_file === false) return;
	$error_type = $error_type === null ? '' : ($error_type . ' - ');
	fwrite($h_file, '['. date('Y-m-d H:i:s'). '] '. $error_type . ' ' . $message . RN);
	fclose($h_file);

	// Remove old cache files (we must call it after the new file appears)
	if ($remove_old) __remove_old_logs();
}

//------------------------------------------------------------- TRACE -------------------------------------------------------------------------------
// Force log to file
function __trace(mixed $message, ?string $file_name = null) : void {
	__log($message, null, $file_name, true);
}

//--------------------------------------------------------- REMOVE OLD LOGS -------------------------------------------------------------------------
function __remove_old_logs() : void {
	$log_dir = null;
	if (__get_config(['log', 'dir_absolute']) !== null)
		$log_dir = __get_config(['log', 'dir_absolute']);
	if ($log_dir === null && __get_config(['log', 'dir_relative']) !== null)
		$log_dir = __info('base_dir') . '/' . __get_config(['log', 'dir_relative']);
	$extension = __get_config(['log', 'extension'], 'txt');

	$log_files = glob($log_dir . '/' . __get_config(['log', 'filename'], 'log') . '*' . ($extension == '' ? '' : ('.' . $extension)));
	if (count($log_files) < __get_config(['log', 'max_files'], 5)) return;

	foreach (array_reverse($log_files) as $key => $item)
		if ($key >= __get_config(['log', 'max_files'], 5)) @unlink($item);
}

//-------------------------------------------------------------- USE --------------------------------------------------------------------------------
function __use(string $module_name) : bool {
	if (!file_exists(__info('modules_dir') . '/' . $module_name))
		return __error('Unable to include module "' . $module_name . '". Module not found');
	if (!file_exists(__info('modules_dir') . '/' . $module_name . '/index.php'))
		return __error('Unable to include module "' . $module_name . '". No module index file found');

	include_once __info('modules_dir') . '/' . $module_name . '/index.php';

	return true;
}

//------------------------------------------------------------ INSTALL ------------------------------------------------------------------------------
// Install the specified module
function __install($module_dir) {
	if (file_exists(__info('modules_dir') . '/' . $module_dir . '/install.php'))
		include_once (__info('modules_dir') . '/' . $module_dir . '/install.php');
}

//------------------------------------------------------------ INSTALL ------------------------------------------------------------------------------
// Installs all the modules
function __install_all() : void {
	foreach (glob(__info('modules_dir') . '/*') as $item)
		if (is_dir($item)) __install(basename($item));
}

/**--------------------------------------------------------- SET CONFIG -----------------------------------------------------------------------------
 * Set new value of the config parameter
 * @param array $parameter_path - Path to the parameter. For example for $g_config['one']['two']['three'] it will be ['one', 'two', 'three']
 * @param mixed $value - Value to set
 * @param bool $overwrite - FALSE to set the new value only if no other value was set
 * @return bool - TRUE on success, FALSE otherwise
 */
function __set_config(array $parameter_path, mixed $value, bool $overwrite = true) : bool {
	global $g_config;
	$param = &$g_config;
	if (!is_array($parameter_path)) return false;

	foreach ($parameter_path as $key => $segment) {
		if ($key >= (count($parameter_path) - 1)) continue;
		if (!isset($param[$segment])) $param[$segment] = [];
		$param = &$param[$segment];
	}

	if (!isset($param[$parameter_path[count($parameter_path) - 1]]) || $overwrite)
		$param[$parameter_path[count($parameter_path) - 1]] = $value;

	return true;
}

/**---------------------------------------------------------- GET CONFIG ----------------------------------------------------------------------------
 * @param array $parameter_path - Path to the parameter. For example for $g_config['one']['two']['three'] it will be ['one', 'two', 'three']
 * @param mixed $default_value - Default parameter value if it is not set
 * @return mixed - Configuration parameter value or default_value if nothing found
 */
function __get_config(array $parameter_path, mixed $default_value = null) : mixed {
	global $g_config;
	$param = &$g_config;
	if (!is_array($parameter_path)) return $default_value;

	foreach ($parameter_path as $key => $segment) {
		if (!isset($param[$segment])) return $default_value;
		$param = &$param[$segment];
	}

	return $param;
}

/**---------------------------------------------------------- SET CACHE -----------------------------------------------------------------------------
 * Cache a value to the global variable
 * @param array $value_path - Path to the value. For example for $g_cache['one']['two']['three'] it will be ['one', 'two', 'three']
 * @param mixed $value - Value to set
 * @return bool - TRUE on success, FALSE otherwise
 */
function __set_cache(array $value_path, mixed $value) : bool {
	global $g_cache;
	$param = &$g_cache;
	if (!is_array($value_path)) return false;

	foreach ($value_path as $key => $segment) {
		if ($key >= (count($value_path) - 1)) continue;
		if (!isset($g_cache[$segment])) $param[$segment] = [];
		$param = &$param[$segment];
	}

	$param[$value_path[count($value_path) - 1]] = $value;

	return true;
}

/**---------------------------------------------------------- GET CACHE -----------------------------------------------------------------------------
 * @param array $value_path - Path to the value. For example for $g_cache['one']['two']['three'] it will be ['one', 'two', 'three']
 * @param mixed $default_value - Default parameter value if it is not set
 * @return mixed - Cached value or default_value if nothing found
 */
function __get_cache(array $value_path, mixed $default_value = null) : mixed {
	global $g_cache;
	$param = &$g_cache;
	if (!is_array($value_path)) return $default_value;

	foreach ($value_path as $key => $segment) {
		if (!isset($param[$segment])) return $default_value;
		$param = &$param[$segment];
	}

	return $param;
}

//--------------------------------------------------------- (localization) --------------------------------------------------------------------------
function __(string $key) : string {
	global $g_l10n;
	return $g_l10n[$key] ?? $key;
}

//-------------------------------------------------------- SET LOCALIZATION -------------------------------------------------------------------------
function __set_l10n(string $key, string $value) : void {
	global $g_l10n;
	$g_l10n[$key] = $value;
}

//----------------------------------------------------- SET LOCALIZATION LIST ----------------------------------------------------------------------0
function __set_l10n_list(array $l10n, string $key_prefix = '') {
	global $g_l10n;
	foreach ($l10n as $key => $item) $g_l10n[$key_prefix . $key] = $item;
}

//############################################################# EVENTS ##############################################################################
//--------------------------------------------------------------- ON --------------------------------------------------------------------------------
function __on(string $event_name, string $func_action, int $priority = 10) : void {
	global $g_config, $g_events;

	// Save event listener to the global events array
	if ($g_config['debug'] ?? true) $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	else $backtrace[0]['file'] = '';
	$g_events[$event_name][$priority][] = array('function' => $func_action, 'file' => $backtrace[0]['file']);
	ksort($g_events[$event_name]);
}

//-------------------------------------------------------------- EVENT ------------------------------------------------------------------------------
function __event(string $event_name) : void {
	global $g_events;

	// Return if there are no event listeners for this event
	if (!isset($g_events[$event_name])) return;

	// Prepare arguments
	$args = array();
	if (func_num_args() > 1)
		for($i = 1; $i < func_num_args(); ++$i) $args[] = func_get_arg($i);

	// Call event listeners
	foreach ($g_events[$event_name] as $key=>$priority)
		foreach ($g_events[$event_name][$key] as $func) call_user_func_array($func['function'], $args);
}

//------------------------------------------------------- SHOW EVENT LISTENERS ----------------------------------------------------------------------
function __show_event_listeners(?string $event_name = null) {
	global $g_events;
	echo '<pre>';
		echo '<br />';
		if ($event_name === null)	{					// If we need to show all events
			foreach ($g_events as $event_key => $event) {
				echo '<b>' . $event_key . '</b><br />';
				foreach ($g_events[$event_key] as $key => $priority)
					foreach ($g_events[$event_key][$key] as $listener) {
						echo ' ' . $key . ' - ' . $listener['function'] . '()';
						if ($listener['file'] != '') echo ' - ' . $listener['file'];
						echo '<br />';
					}
			}
		}
		else {		// One specific event
			echo '<b>Event: ' . $event_name . '</b><br />';
			foreach ($g_events[$event_name] as $key => $priority)
				foreach ($g_events[$event_name][$key] as $listener) {
					echo ' ' . $key . ' - ' . $listener['function'] . '()';
					if( $listener['file']!='' ) echo ' - ' . $listener['file'];
					echo '<br />';
				}
		}
	echo '</pre>';
}

//------------------------------------------------------- CLEAN EVENT LISTENERS ---------------------------------------------------------------------
function __clean_event_listeners(string $event_name) {
	global $g_events;
	unset($g_events[$event_name]);
}

//###################################################################################################################################################
//############################################################ M I S C ##############################################################################
//###################################################################################################################################################
//--------------------------------------------------------- JSON SUCCESS ----------------------------------------------------------------------------
function __json_success(string $message = '', ?array $data = null, int $http_response_code = 200) : bool {
	if ($data === null) echo '{ "success" : true, "status" : ' . $http_response_code . ', "message" : "' . $message . '" }';
	else echo json_encode(array(
        'success' => true,
        'status' => $http_response_code,
        'message' => $message,
        'data' => $data
    ), __get_config(['json_flags'], 0));

	return true;
}

//--------------------------------------------------------- JSON FAILURE ----------------------------------------------------------------------------
function __json_failure(string $message = '', ?array $data = null, int $http_response_code = 200) : bool {
	if ($data === null) echo '{ "success" : false, "status" : ' . $http_response_code . ', "message" : "' . $message . '" }';
	else echo json_encode(array(
        'success' => false,
        'status' => $http_response_code,
        'message' => $message,
        'data' => $data
    ), __get_config(['json_flags'], 0));

    return false;
}

/**----------------------------------------------------------- JSON RESULT --------------------------------------------------------------------------
 * @param bool $success - True if request was successful
 * @param string $message - Message to return
 * @param ?array $data - Data if it needs
 * @param int $http_response_code - HTTP response code
 * @return string - JSON of that params
 */
function __json_result(bool $success, string $message, ?array $data = null, int $http_response_code = 200): string {
	if ($success) return __json_success($message, $data, $http_response_code);
	return __json_failure($message, $data, $http_response_code);
}

//-------------------------------------------------------------- JSON ERROR -------------------------------------------------------------------------
function __json_error(int $http_response_code, string $message, ?array $data = null) : bool {
	switch ($http_response_code) {
		case 400: header('HTTP/1.0 400 Bad Request'); break;
		case 401: header('HTTP/1.0 401 Unauthorized'); break;
		case 402: header('HTTP/1.0 402 Payment Required'); break;
		case 403: header('HTTP/1.0 403 Forbidden'); break;
		case 404: header('HTTP/1.0 404 Not Found'); break;
		case 405: header('HTTP/1.0 405 Method Not Allowed'); break;
		case 406: header('HTTP/1.0 406 Not Acceptable'); break;
		case 407: header('HTTP/1.0 407 Not Acceptable'); break;
		case 408: header('HTTP/1.0 408 Request Timeout'); break;
		case 409: header('HTTP/1.0 409 Conflict'); break;
		case 410: header('HTTP/1.0 410 Gone'); break;
		case 411: header('HTTP/1.0 411 Length Required'); break;
		case 412: header('HTTP/1.0 412 Precondition Failed'); break;
		case 413: header('HTTP/1.0 413 Request Entity Too Large'); break;
		case 414: header('HTTP/1.0 414 Request-URI Too Long'); break;
		case 415: header('HTTP/1.0 415 Unsupported Media Type'); break;
		case 416: header('HTTP/1.0 416 Requested Range Not Satisfiable'); break;
		case 417: header('HTTP/1.0 417 Expectation Failed'); break;
		case 418: header('HTTP/1.0 418 I\'m a teapot'); break;
		case 421: header('HTTP/1.0 421 Misdirected Request'); break;
		case 422: header('HTTP/1.0 422 Unprocessable Entity'); break;
		case 423: header('HTTP/1.0 423 Locked'); break;
		case 424: header('HTTP/1.0 424 Failed Dependency'); break;
		case 425: header('HTTP/1.0 425 Too Early'); break;
		case 426: header('HTTP/1.0 426 Upgrade Required'); break;
		case 428: header('HTTP/1.0 428 Precondition Required'); break;
		case 429: header('HTTP/1.0 429 Too Many Requests'); break;
		case 431: header('HTTP/1.0 431 Request Header Fields Too Large'); break;
		case 451: header('HTTP/1.0 451 Unavailable For Legal Reasons'); break;
		case 500: header('HTTP/1.0 500 Server Error'); break;
	}
	return __json_failure($message, $data, $http_response_code);
}

//-------------------------------------------------------- IS HTTPS ONLY ----------------------------------------------------------------------------
function __is_https_only() : bool {
	global $g_config;
	return $g_config['https_only'];
}

//---------------------------------------------------------- LANGUAGE -------------------------------------------------------------------------------
function __lang() : string {
	global $g_config;

	$segment_0 = __segment(0);
	$lang = $g_config['lang']['default'];
	$forced = false;			// TRUE if the language is specified directly in URL

	// Try to get language data from the first URL-segment
	if ($g_config['lang']['source'] = 'slug') {
		if (in_array($segment_0, $g_config['lang']['list'] ?? [])) { $lang = $segment_0; $forced = true; }
	}

	// Try to get language data from the GET-parameter
	if ($g_config['lang']['source'] = 'get_param') {
		$get_lang = $_GET[$g_config['lang']['get_param_name']] ?? $lang;
		if (in_array($get_lang, $g_config['lang']['list'] ?? [])) { $lang = $get_lang; $forced = true; }
	}

	// Process cookies
	if ($g_config['lang']['use_cookies']) {
		// If we know that it is exact language specified in URL, but it is not set in COOKIE, set it
		$lang_cookie = __get_interface_cookie('lang');
		if ($lang_cookie != $lang && $forced)
			__set_interface_cookie(['lang' => $lang]);
		else {
			// Try to get language from cookie
			if (!$forced && $lang_cookie != $lang && in_array($lang_cookie, $g_config['lang']['list'] ?? [])) $lang = $lang_cookie;
		}
	}

	return $lang;
}

//---------------------------------------------------  DEBUGGING IN CONSOLE -------------------------------------------------------------------------
function __console($string_or_object = null) : void {
	global $g_config;
//    if (!($g_config['debug'] ?? true)) return;
    echo '<script>console.log('. json_encode($string_or_object) .')</script>';
}

//---------------------------------------------------------- SEGMENT --------------------------------------------------------------------------------
function __segment(int $url_segment) : string {
	$aSegments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
	return $aSegments[$url_segment] ?? '';
}

//-------------------------------------------------------- SEGMENT EX -------------------------------------------------------------------------------
// It takes in account the language segment
function __segment_ex(int $url_segment) : string {
	global $g_config;

	$segment0 = __segment(0);
	if ($segment0 != '' && in_array($segment0, $g_config['lang']['list'] ?? [])) $url_segment++;
	$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

	return $segments[$url_segment] ?? '';
}

//-------------------------------------------------------- XML TO ARRAY ------------------------------------------------------------------------------
function __xml_to_array($xmlObject, array $out = []): array {
    foreach ((array)$xmlObject as $index => $node) $out[$index] = (is_object($node)) ? __xml_to_array($node) : $node;
    return $out;
}

//-------------------------------------------------------- PRINT ARRAY ------------------------------------------------------------------------------
function __print_array(array $array) : void {
	echo '<pre>' . print_r($array, true) . '</pre>';
}

//------------------------------------------------------------ INFO ---------------------------------------------------------------------------------
function __info(string $info_to_get) : string {
	global $g_config;
	$protocol = 'http://';
	if (isset($g_config['protocol'])) $protocol = $g_config['protocol'] . '://';

	switch ($info_to_get) {
		case	'host':					{ return $g_config['site_host']; }
		case	'base_dir':			{ return BASEDIR . ''; }
		case	'site_url':			{ return $protocol . $g_config['site_host'] . ''; }
		case	'view_dir':			{ return BASEDIR . '/view'; }
		case	'view_url':			{ return $protocol . $g_config['site_host'] . '/view'; }
		case	'modules_dir':	{ return BASEDIR . '/modules'; }
		case	'modules_url':	{ return $protocol . $g_config['site_host'] . '/modules'; }
	}

	if (isset($g_config['info'][$info_to_get])) return $g_config['info'][$info_to_get];

	return '';
}

//------------------------------------------------------------- HASH --------------------------------------------------------------------------------
function __hash(string $value_to_hash) : string {
	$hash = md5($value_to_hash);

	// Encode
	for ($i = 0; $i < 31; $i++) {
		$temp = sprintf('%02x', ((hexdec($hash[$i] . $hash[$i+1])^156)<<5)&0xff | ((hexdec($hash[$i] . $hash[$i+1])^156)>>3));
		$hash[$i] = $temp[0];
		$hash[$i+1] = $temp[1];
	}

	// Decode DO NOT DELETE!!!
	// for ($i = 30; $i >= 0; $i--) {
	// 	$temp = sprintf('%02x', ((hexdec($hash[$i] . $hash[$i+1])>>5)^156)&7 | ((hexdec($hash[$i] . $hash[$i+1])<<3)^156)&0xff&(255-7));
	// 	$hash[$i] = $temp[0];
	// 	$hash[$i+1] = $temp[1];
	// }

	return $hash;
}

//------------------------------------------------------------ ENCODE -------------------------------------------------------------------------------
function __encode(string $str) : string {
	$len = strlen($str);
	for ($i = 0; $i < $len; ++$i)
		$str[$i] = chr((ord($str[$i]) + $i*3) % 256);
	$str = base64_encode($str);
	$str = substr_count($str, '=') . str_replace('=', '', $str);
	$str = str_replace('/', '~', $str);

	return $str;
}

//------------------------------------------------------------ DECODE -------------------------------------------------------------------------------
function __decode(string $str) : string {
	$str = str_replace('~', '/', $str);
	for ($i = 0; $i < intval($str[0]); ++$i) $str.= '=';
	$str = substr($str, 1);
	$str = base64_decode($str);
	$len = strlen($str);
	for ($i = 0; $i < $len; ++$i)
		$str[$i] = chr((ord($str[$i]) + (256-$i*3)) % 256);

	return $str;
}

//-------------------------------------------------------- UTF-8 URLDECODE --------------------------------------------------------------------------
function __utf8_url_decode(string $str) : string {
    $str = preg_replace('/%u([0-9a-f]{3,4})/i', '&#x\\1;', urldecode($str));
    return html_entity_decode($str, ENT_COMPAT, 'UTF-8');
}

//------------------------------------------------------------ MAILER -------------------------------------------------------------------------------
function __mail(string $to, string $subject, string $message, string $headers = '', bool $use_template = false, int $port = 25) : bool {
	global $g_config;

	$result = __mail_submit($to, $subject, $message, $g_config['email']['signature'], $g_config['email']['login'], $g_config['email']['password'],
		$g_config['email']['server'], $headers, $use_template, $port);

	return $result === true;
}

//-------------------------------------------------------- GET SMTP DATA ----------------------------------------------------------------------------
function __get_smtp_data($smtp_conn) : string {
	$data = '';
	while($str = fgets($smtp_conn, 515)) {
		$data.= $str;
		if (substr($str, 3, 1) == ' ') { break; }
	}
	return $data;
}

//---------------------------------------------------------- SUBMIT EMAIL ---------------------------------------------------------------------------
function __mail_submit(string $to, string $subject, string $message, string $email_signature, string $email_login, string $email_password,
	string $email_server, string $headers = '', bool $use_template = false, int $port = 25) : bool {
	global $g_config;

	$debug = $g_config['debug'] ?? true;

	$header = 'From: =?utf-8?B?' . base64_encode($email_signature) . '?= <' . $email_login . '>' . RN;
	$header.= 'To: =?utf-8?B?' . base64_encode($to) . '?= <' . $to . '>' . RN;
	$header.= 'Subject: =?utf-8?B?'.base64_encode($subject).'?=' . RN;
	$header.= 'Date: ' . date('D, j M Y G:i:s') . ' +0700' . RN;
	$header.= 'Message-ID: <172562218.' . date('YmjHis') . '@' . $g_config['email']['host'] . '>' . RN;
	$header.= 'MIME-Version: 1.0' . RN;
	$header.= 'Content-type:text/html;charset=utf8;' . RN;
	$header.= 'X-Mailer: ' . $g_config['email']['mailer'] . ';' . RN;
	$header.= 'Content-Language: en;' . RN;
	if ($headers != '') $header.= $headers;
	$text = '';
	$message_header = '';
	$message_footer = '';
	if (isset($g_config['email']['logo']) && $g_config['email']['logo'] != '')
		$message_footer = RN . '<br /><br /><img src="' . $g_config['email']['logo'] . '" />' . RN;
	$text.= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' .
		'<html xmlns="http://www.w3.org/1999/xhtml"><head></head><body>';
	if ($use_template === true) $text.= $message_header;
	$text.= $message;
	if ($use_template === true) $text.= $message_footer;
	$text.= '</body></html>';

	$smtp_conn = fsockopen($email_server, $port, $errno, $errstr, 30);
	if (!$smtp_conn) { if ($debug) __log('Соединение с сервером не прошло'); fclose($smtp_conn); return false; }
	__trace('1');
	$data = __get_smtp_data($smtp_conn);
	__trace('2');
	fputs($smtp_conn, 'EHLO ' . $_SERVER['SERVER_NAME'] . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 250) { if($debug) __log('Ошибка приветсвия EHLO'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, 'AUTH LOGIN' . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);

	if ($code != 334) { if ($debug) __log('Сервер не разрешил начать авторизацию'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, base64_encode($email_login) . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 334) { if ($debug) __log('Ошибка доступа к такому пользователю'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, base64_encode($email_password) . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 235) { if ($debug) __log('Неправильный пароль'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, 'MAIL FROM:' . $email_login . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 250) { if ($debug) __log('Сервер отказал в команде MAIL FROM'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, 'RCPT TO:' . $to . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 250 && $code != 251) { if ($debug) __log('Сервер не принял команду RCPT TO'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, 'DATA' . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 354) { if ($debug) __log('Сервер не принял DATA'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, $header . RN . $text . RN . '.' . RN);
	$code = substr(__get_smtp_data($smtp_conn), 0, 3);
	if ($code != 250) { if($debug) __log('Ошибка отправки письма'); fclose($smtp_conn); return false; }

	fputs($smtp_conn, 'QUIT' . RN);
	fclose($smtp_conn);
	if ($debug) __log('Mail successful!');

	return true;
}

//-------------------------------------------------------------- SHOW BLOB IMAGE --------------------------------------------------------------------
function __show_blob_image(mixed $image, string $format = 'png', ?string $dom_attributes = null) {
	echo '<img src="data:image/' . $format . ';base64,' . base64_encode($image) . '" ';
	if ($dom_attributes !== null ) echo $dom_attributes;
	echo '/>' . NR;
}

//----------------------------------------------------------------- CHECK IP ------------------------------------------------------------------------
function __check_ip(?string $ip = null, string $config_key = 'allowed_from') : bool {
	global $g_config;

	if ($ip === null && isset($_SERVER['HTTP_X_REAL_IP'])) $cIP = __get_ip();

	if (array_search($ip, $g_config[$config_key]) !== false) return true;
	return false;
}

//----------------------------------------------------------------- GET IP --------------------------------------------------------------------------
function __get_ip() : string {
	if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
	return '0.0.0.0';
}

//------------------------------------------------------------- SHOW CHAMOMILE ----------------------------------------------------------------------
function __show_chamomile(?string $id = null) : void {
	echo '<div ' . ($id !== null ? ('id="' . $id . '" ') : '') . 'class="chamomile">';
		echo '<div class="loader">';
			echo '<div class="arc"></div>';
		echo '</div>';
	echo '</div>' . NR;
}

//--------------------------------------------------------- GET INTERFACE COOKIE --------------------------------------------------------------------
function __get_interface_cookie(string $name, string $group = 'interface') : ?string {
	if (!isset($_COOKIE[$group])) return null;
	if ($name !== false && strpos($_COOKIE[$group], $name) === false) return null;
	$cookies = json_decode($_COOKIE[$group], true);
	if ($name === false && $cookies !== null) return $cookies;
	if (isset($cookies[$name])) return $cookies[$name];
	else return null;
}

//--------------------------------------------------------- SET INTERFACE COOKIE --------------------------------------------------------------------
function __set_interface_cookie(array $data, string $group = 'interface') : void {
	$cookies = array();
	if (isset($_COOKIE[$group]))
		$cookies = json_decode($_COOKIE[$group], true);
	$cookies['cookie_date'] = (intval(date('Y'))+1) . date('md');
	foreach ($data as $key => $value)
		$cookies[$key] = $value;
	$cookie_to_set = json_encode($cookies);
	setcookie($group, $cookie_to_set, time()+60*60*24*366, '/', '', __is_https_only());
	$_COOKIE[$group] = $cookie_to_set;
}

//------------------------------------------------------------- UNSET COOKIE ------------------------------------------------------------------------
function __unset_cookie(string $group) : void {
	setcookie($group, '', time(), '/');
}

//--------------------------------------------------------------- TRANSLIT --------------------------------------------------------------------------
// Returns translit string (' ' -> ' ')
function __translit(string $text) : string {
	$iso = array(
		'Є'=>'YE', 'І'=>'I', 'Ѓ'=>'G', 'і'=>'i', '№'=>'#', 'є'=>'ye', 'ѓ'=>'g',
		'А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D',
		'Е'=>'E', 'Ё'=>'YO', 'Ж'=>'ZH',
		'З'=>'Z', 'И'=>'I', 'Й'=>'J', 'К'=>'K', 'Л'=>'L',
		'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
		'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Х'=>'X',
		'Ц'=>'C', 'Ч'=>'CH', 'Ш'=>'SH', 'Щ'=>'SHH', 'Ъ'=>'\'',
		'Ы'=>'Y', 'Ь'=>'', 'Э'=>'E', 'Ю'=>'YU', 'Я'=>'YA',
		'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d',
		'е'=>'e', 'ё'=>'yo', 'ж'=>'zh',
		'з'=>'z', 'и'=>'i', 'й'=>'j', 'к'=>'k', 'л'=>'l',
		'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p', 'р'=>'r',
		'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'х'=>'x',
		'ц'=>'c', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shh', 'ъ'=>'',
		'ы'=>'y', 'ь'=>'', 'э'=>'e', 'ю'=>'yu', 'я'=>'ya', '«'=>'', '»'=>'', '—'=>'-', ' '=>' ', '%'=>''
	);

	return strtr($text, $iso);
}

//--------------------------------------------------------------- TRANSLIT SLUG ----------------------------------------------------------------------
// Returns translit string (' ' -> '-', '.' -> '-')
function __translit_slug(string $text) : string {
    $iso = array(
        'Є'=>'YE', 'І'=>'I', 'Ѓ'=>'G', 'і'=>'i', '№'=>'#', 'є'=>'ye', 'ѓ'=>'g',
        'А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D',
        'Е'=>'E', 'Ё'=>'YO', 'Ж'=>'ZH',
        'З'=>'Z', 'И'=>'I', 'Й'=>'J', 'К'=>'K', 'Л'=>'L',
        'М'=>'M', 'Н'=>'N', 'О'=>'O', 'П'=>'P', 'Р'=>'R',
        'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Х'=>'X',
        'Ц'=>'C', 'Ч'=>'CH', 'Ш'=>'SH', 'Щ'=>'SHH', 'Ъ'=>'\'',
        'Ы'=>'Y', 'Ь'=>'', 'Э'=>'E', 'Ю'=>'YU', 'Я'=>'YA',
        'а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d',
        'е'=>'e', 'ё'=>'yo', 'ж'=>'zh',
        'з'=>'z', 'и'=>'i', 'й'=>'j', 'к'=>'k', 'л'=>'l',
        'м'=>'m', 'н'=>'n', 'о'=>'o', 'п'=>'p', 'р'=>'r',
        'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'х'=>'x',
        'ц'=>'c', 'ч'=>'ch', 'ш'=>'sh', 'щ'=>'shh', 'ъ'=>'',
        'ы'=>'y', 'ь'=>'', 'э'=>'e', 'ю'=>'yu', 'я'=>'ya', '«'=>'', '»'=>'', '—'=>'-', ' '=>'-', '%'=>'', '.'=>'-'
    );

    return strtr($text, $iso);
}



//------------------------------------------------------------ STRING TO SLUG -----------------------------------------------------------------------
function __string_to_slug(string $str) : string {
	$str = mb_strtolower(__translit(trim($str)), 'utf-8');

	// Replace all non-letter and non-digit
	$str = preg_replace('/[^a-z0-9]/', '-', $str);

	return $str;
}

//-------------------------------------------------------------- HTML SAFE --------------------------------------------------------------------------
function __html_safe(?string $str) : string {
    if ($str === null) return '';
    return htmlspecialchars(stripcslashes($str));
}

//------------------------------------------------------------- PLURAL FORM -------------------------------------------------------------------------
// Returns right form of Russian plural.
// Ex: __plural_form(5, array('секунда', 'секунды', 'секунд' ))
function __plural_form(int $number, array $forms) : string {
	return $number%10==1&&$number%100!=11?$forms[0]:($number%10>=2&&$number%10<=4&&($number%100<10||$number%100>=20)?$forms[1]:$forms[2]);
}

//--------------------------------------------------------- REPLACE QUERY PARAM ---------------------------------------------------------------------
function __replace_query_param(string $url, string $param_name, ?string $new_value = null) : string {
	$query_array = explode('?', $url);
	$query_string = isset($query_array[1]) ? $query_array[1] : '';
	parse_str($query_string, $aResult);
	if ($new_value === null) unset($aResult[$param_name]);
	else $aResult[$param_name] = $new_value;
	$query_string = http_build_query($aResult);
	return $query_array[0] . ($query_string != '' ? '?' . $query_string : '');
}

//--------------------------------------------------------------- IS MOBILE -------------------------------------------------------------------------
function __is_mobile() : bool {
	return preg_match('/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i',
		$_SERVER["HTTP_USER_AGENT"]);
}

/**------------------------------------------------------------- ARRAY GET --------------------------------------------------------------------------
 * Shorthand to get an array element data by the specified key. Checks that the initial value is an array
 * @param mixed $array_data - An array to get element from. A NULL will be returned if the variable type is anything except "array"
 * @param string $key - The key of the element
 * @param mixed|null $default_value - The default value to return if the specified array_data is not an array or the key wasn't found
 * @return mixed - Element of the array specified by the key or the default value if nothing found
 */
function __array_get(mixed $array_data, string $key, mixed $default_value = null) {
	if (!is_array($array_data)) return $default_value;
	return $array_data[$key] ?? $default_value;
}

/**------------------------------------------------------------- MULTI IMPLODE ----------------------------------------------------------------------
 * Example: You have an array like -
 * [
 *  [
 *      'id' = '1',
 *  ],
 *  [
 *      'id' = '2',
 *  ],
 *  [
 *      'id' = '2',
 *  ], ...
 * ]
 * With $sep = ',' you will get string like: 1,2,3...
 * @param string $sep - Separator
 * @param array $array - Array of data
 * @return string|null
 */
function __multi_implode(string $sep, array $array): ?string {
    foreach ($array as $val) $_array[] = is_array($val) ? __multi_implode($sep, $val) : $val;
    return (!empty($_array) ? implode($sep, $_array) : null);
}

//-------------------------------------------------------------- FORMAT DATE ------------------------------------------------------------------------
function __format_date(string $mysql_date = '2022-01-01') : string {
	return date('d.m.Y', strtotime($mysql_date));
}

//------------------------------------------------------------- FORMAT AMOUNT -----------------------------------------------------------------------
function __format_amount(float $amount) : string {
	return number_format($amount, 2, '.', ' ');
}

//--------------------------------------------------------------- MONTH NAME ------------------------------------------------------------------------
function __month_name(int $month_number = 1, bool $short_name = false) : string {
	/*
	ATTENTION!!! This code works only on servers with installed specified language packs
	$current_locale = setlocale(LC_TIME, 0);
	setlocale(LC_TIME, __lang());
	$month_name = date($short_name ? 'M' : 'F', mktime(0, 0, 0, $month_number));
	setlocale(LC_TIME, $current_locale);*/

	$months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', '???'];
	if (__lang() == 'ru')
		$months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь', '???'];

	if ($month_number < 1 || $month_number > 12) return $months[12];

	if ($short_name) return mb_substr($months[$month_number - 1], 0, 3, 'utf8');

	return $months[$month_number - 1];
}