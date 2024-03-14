<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 19.03.2022
 * @version: 1.0
 *
 * File to list all the static files to be grouped and minified by the compressor module.
 */

__use('compressor');

// Include modules that has custom registration event listeners

// Process all registrations from other files of included modules
__event('reg');

/**--------------------------------------------------------- TENANT/ADMIN ---------------------------------------------------------------------------
 * Register all project static files
 */

//---------------------------------------------------------- REGISTER JS ----------------------------------------------------------------------------

// Main/Global
compressor::register_js(__info('view_url') . '/pages/java.js', 'page_global');
compressor::register_js(__info('view_url') . '/pages/contacts/java.js', 'page_contacts');

//---------------------------------------------------------- REGISTER CSS ---------------------------------------------------------------------------
compressor::register_css(__info('view_url') . '/pages/home/style.less', 'page_home');
compressor::register_css(__info('view_url') . '/pages/about_company/style.less', 'page_about_company');
compressor::register_css(__info('view_url') . '/pages/about_company/style_mobile.less', 'page_about_company');
compressor::register_css(__info('view_url') . '/pages/production/style.less', 'page_production');
compressor::register_css(__info('view_url') . '/pages/repair/style.less', 'page_repair');
compressor::register_css(__info('view_url') . '/pages/repair/style_mobile.less', 'page_repair');
compressor::register_css(__info('view_url') . '/pages/contacts/style.less', 'page_contacts');
compressor::register_css(__info('view_url') . '/pages/import/style.less', 'page_import');
compressor::register_css(__info('view_url') . '/pages/responsibility/style.less', 'page_responsibility');
compressor::register_css(__info('view_url') . '/pages/mission/style.less', 'page_mission');
compressor::register_css(__info('view_url') . '/pages/designing/style.less', 'page_designing');
compressor::register_css(__info('view_url') . '/pages/contacts/style_mobile.less', 'page_contacts');

// Main/Global
compressor::register_css(__info('view_url') . '/pages/style_desktop.less', 'page_global');
compressor::register_css(__info('view_url') . '/pages/style_mobile.less', 'page_global');
