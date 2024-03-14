<?php if( !defined('B2') ) exit(0);

/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 03.07.2022
 * @version: 2.0
 *
 * Basis2 PHP Framework plugin that includes standard JavaScript and CSS libraries with most common functions and styles
 */

__set_config(['finances', 'reporting_currency_code'], 'EUR', false);              // Reporting currency code

__use('jquery');

include_once ('global.php');
include_once ('validator.php');

//------------------------------------------------------- EVENT LISTENERS ---------------------------------------------------------------------------
__on('reg', 'global_on_reg', 9);
__on('js', 'global_on_js', 9);
__on('css', 'global_on_css', 9);

//----------------------------------------------------------- ON REG --------------------------------------------------------------------------------
function global_on_reg() {
	// JS
	compressor::register_js(__info('modules_url') . '/' . basename(__dir__) . '/java.js', 'global');

	// CSS
	compressor::register_css(__info('modules_url') . '/' . basename(__dir__) . '/style.css', 'global');
}

//----------------------------------------------------------- ON JS ---------------------------------------------------------------------------------
function global_on_js() {
	compressor::include_js(__info('modules_url') . '/' . basename(__dir__) . '/java.js');
}

//----------------------------------------------------------- ON JS ---------------------------------------------------------------------------------
function global_on_css() {
	compressor::include_css(__info('modules_url') . '/' . basename(__dir__) . '/style.css');
}
