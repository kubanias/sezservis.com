<?php
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 19.03.2022
 * @version: 1.0
 *
 * Basis2 framework kernel.
 */

if (!defined('B2')) exit(0);

const NR = "\n\r";
const RN = "\r\n";
const TAB = "\t";
const TAB2 = "\t\t";
const TAB3 = "\t\t\t";
const TAB4 = "\t\t\t\t";
const TAB5 = "\t\t\t\t\t";
const TAB6 = "\t\t\t\t\t\t";
const TAB7 = "\t\t\t\t\t\t\t";

// Turn system errors display on or off
global $g_config;
if ($g_config['debug'] ?? true) { ini_set('display_errors', 1);	error_reporting(E_ALL); }
else ini_set('display_errors', 0);

// Global array of errors
global $g_last_errors;
$g_last_errors = [];

// Global array of events
global $g_events;
$g_events = [];

// Global array of cached data
global $g_cache;
$g_cache = [];				// Each module must use its own root key. Like $g_cache['db']

// Global localization array
global $g_l10n;
$g_l10n = [];

include_once('functions.php');

//------------------------------------------------------------------ MAIN ---------------------------------------------------------------------------
function kernel_main() : void {
	global $g_config;

	date_default_timezone_set('Europe/Moscow');

	// Prepare $_POST
	if ($g_config['decode_json_post'] ?? false) kernel_decode_json_post();

	// Check if we need to use custom router
	if ($g_config['custom_router'] !== null && file_exists(BASEDIR . $g_config['custom_router'])) {
		include_once BASEDIR . $g_config['custom_router'];
	}
	else {
		// Include appropriate view file and run main()
		$pages_dir = BASEDIR . '/view/pages';
		if (in_array(__segment(0), $g_config['lang']['list'] ?? []))	{		// Language request
			if (__segment(1) == '') $view_file = $pages_dir . '/home';
			else $view_file = $pages_dir . '/' . __segment(1);
		}
		elseif (__segment(0) != '') $view_file = $pages_dir . '/' . __segment(0);	// Requests to view
		else $view_file = $pages_dir . '/home';																	// Home request
		if (!file_exists($view_file) || !is_dir($view_file)) $view_file.= '.php';			// Check if the page is represented by folder
		else $view_file.= '/index.php';
		if (file_exists($view_file) && is_file($view_file))	{
			include_once($view_file);
			if (function_exists('main')) main();
		}
		elseif (file_exists($pages_dir . '/default.php')) include_once($pages_dir . '/default.php');
		elseif (file_exists($pages_dir . '/404.php')) include_once($pages_dir . '/404.php');
	}
}

/**--------------------------------------------------------- DECODE JSON POST -----------------------------------------------------------------------
 * Decode JSON POST requests. Otherwise $_POST will be empty
 */
function kernel_decode_json_post() : void {
	if ($_POST != array()) return;

	$json = file_get_contents('php://input');
	$_POST = json_decode($json, true);
}

//-------------------------------------------------------------- STARTER ----------------------------------------------------------------------------
kernel_main();