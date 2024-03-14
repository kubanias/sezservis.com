<?php
/*
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 19.03.2022
 * @version: 1.0
 *
 * Basis2 PHP Framework configuration
 */

if (!defined('B2')) exit(0);

global $g_config;			// Configuration that can only be accessed from PHP
global $g_js_config;		// Configuration that can be accessed from PHP and JS

//-------------------------------------------------------------- GLOBAL -----------------------------------------------------------------------------
$g_js_config['site_host'] = 'sezservis.com';
$g_js_config['site_name'] = 'СЭЗ-Сервис';
$g_config['debug'] = true;									// TRUE to turn on displaying system errors, tracing events and display errors in console
$g_config['is_daemon'] = false;								// Is this a daemon or a regular site. Some other config depends on it
$g_config['decode_json_post'] = false;						// To decode JSON to array of values during POST requests
$g_config['display_custom_errors'] = true;					// TRUE to automatically show all errors that were added through __error() and __error_ex()
$g_config['https_only'] = false;							// When true - we will try to use only https requests for JS files
$g_config['protocol'] = 'https';							// Protocol to use for __info() function
$g_config['json_flags'] = JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION;			// JSON flags for output in global functions like __json_success()
$g_config['custom_router'] = null;							// Relative path to the custom router file. NULL to ignore

//------------------------------------------------------------ COMPRESSOR ---------------------------------------------------------------------------
$is_api = false;
$segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
if (isset($segments[0]) && $segments[0] == 'api') $is_api = true;

$g_config['compressor']['enabled'] = true;								// Enable or disable the compressor. FALSE to use full content of static files
$g_config['compressor']['is_inline'] = true;							// TRUE to show css and JS directly in HTML code
$g_config['compressor']['reg_file'] = '/view/compressor.php';			// Place where all the files to be compressed are registered
$g_config['compressor']['auto'] = ($g_config['debug'] & !$is_api);		// TRUE to regenerate all compressed files each request

//------------------------------------------------------------- LANGUAGE ----------------------------------------------------------------------------
$g_js_config['lang']['list'] = ['ru', 'en', 'de'];		// List of supported languages. Empty array or one language to disable feature
$g_js_config['lang']['default'] = 'ru';					// Default language that will be returned by __lang()
$g_js_config['lang']['source'] = 'slug';				// Source of lang data: 'slug' (the first URL segment) or 'get_param' (in URL like ?lang=ru)
$g_js_config['lang']['get_param_name'] = 'lang';		// Name of the parameter to use in URL. Ex: ex.com/page?lng=en for 'lng'
$g_js_config['lang']['use_cookies'] = false;			// To get/set lang cookie or not

//--------------------------------------------------------------- MAIL ------------------------------------------------------------------------------
$g_config['email']['server'] = 'ssl://smtp.beget.com';
$g_config['email']['host'] = 'sezservis.ru';
$g_config['email']['mailer'] = 'SEZ-mailer';
$g_config['email']['login'] = 'noreply@sezservis.ru';
$g_config['email']['password'] = 'CicpH07&';
$g_config['email']['signature'] = '';
$g_config['email']['port'] = 465;
$g_config['email']['from_form_to'] = ['dir@sezservis.ru', 'm1@sezservis.ru', 'm2@sezservis.ru', 'zakupki@sezservis.ru', 'info@sezservis.ru'];// Where to send emails
$g_config['email']['logo'] = '';

//--------------------------------------------------------------- LOG -------------------------------------------------------------------------------
// Logs and __trace() setup
$g_config['log']['write_to_file'] = true;								// Write logs to file or not. FALSE to turn off logging
$g_config['log']['dir_relative'] = 'downloads';			  				// Relative path to the dir with log files
$g_config['log']['dir_absolute'] = null;								// Absolute path to the log files dir. Priority is higher than dir_relative. NULL to ignore
$g_config['log']['basename'] = 'log';									// Log file name without extension
$g_config['log']['extension'] = 'txt';									// Log file extension
$g_config['log']['suffix_format'] = $g_config['debug'] ? '' : '_Ym';	// date(format) as suffix, like log202203.log. '' to ignore and use one file
$g_config['log']['max_files'] = 5;										// Max number of files when using suffix_format. Lowest in abc sort order will be removed
$g_config['log']['check_once'] = !$g_config['is_daemon'];				// FALSE to check max_files every new log file added. TRUE - once per new file and launch

//-------------------------------------------------- PREPARE CONFIG BASED ON JS CONFIG --------------------------------------------------------------
if (is_array($g_js_config) && count($g_js_config))
	foreach($g_js_config as $key => $item)
		if (is_array($item)) {
			foreach ($item as $sub_key => $sub_item) {
				if (is_array($sub_item)) {
					foreach ($sub_item as $sub_sub_key => $sub_sub_item)
						$g_config[$key][$sub_key][$sub_sub_key] = $sub_sub_item;
				}
				else $g_config[$key][$sub_key] = $sub_item;
			}
		}
		else $g_config[$key] = $item;