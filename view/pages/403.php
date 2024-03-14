<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 19.03.2022
 * @version: 1.0
 */

__use('compressor');

/**-------------------------------------------------------------- FORBIDDEN MAIN ---------------------------------------------------------------------
 * Generates 403 page
 * @return void
 */
function forbidden_main(): void {    // This prefix is used to allow another pages to include this one
	__clean_event_listeners('css');
	__clean_event_listeners('js');
    header('HTTP/1.0 403 Forbidden', true, 403);

    ?>
    <html lang="<?= __lang() ?>">
    <head>
        <title>Forbidden</title>
        <?php
        __event('css');
        __event('js');
        ?>
    </head>
    <body>
    <a href="/">
        <div class="error_page_image"></div>
    </a>
    <div class="error_page_text">403. ACCESS DENIED</div>
    </body>
    </html>
    <?php
}

/**-------------------------------------------------------- EVENT LISTENERS --------------------------------------------------------------------------
 */
__on('js', 'forbidden_on_js', 5);
__on('css', 'forbidden_on_css', 5);

/**---------------------------------------------------------------- ON JS ----------------------------------------------------------------------------
 * Compress JS
 * @return void
 */
function forbidden_on_js(): void {
    compressor::include_js(__info('view_url') . '/pages/java.js');
}

/**---------------------------------------------------------------- ON CSS ---------------------------------------------------------------------------
 * Compress CSS
 * @return void
 */
function forbidden_on_css(): void {
    compressor::include_css(__info('view_url') . '/pages/style_desktop.less');
    compressor::include_css(__info('view_url') . '/pages/style_mobile.less');
}

forbidden_main();