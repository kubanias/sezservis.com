<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 19.03.2022
 * @version: 1.0
 */

__use('compressor');

/**-------------------------------------------------------------- NOT FOUND MAIN ---------------------------------------------------------------------
 * Generates 404 page
 * @return void
 */
function notfound_main(): void {   // This prefix is used to allow another pages to include this one
    header('HTTP/1.0 404 Not Found', true, 404);

    ?>
    <html lang="<?= __lang() ?>">
    <head>
        <title>Not found</title>
        <?php
        __event('css');
        __event('js');
        ?>
    </head>
    <body>
    <a href="/">
        <div class="error_page_image"></div>
    </a>
    <div class="error_page_text">404. PAGE NOT FOUND</div>
    </body>
    </html>
    <?php
}

/**-------------------------------------------------------- EVENT LISTENERS --------------------------------------------------------------------------
 */
__on('js', 'notfound_on_js', 5);
__on('css', 'notfound_on_css', 5);

/**---------------------------------------------------------------- ON JS ----------------------------------------------------------------------------
 * Compress JS
 * @return void
 */
function notfound_on_jS(): void {
    //compressor::include_js(__info('view_url').'/java.js');
}

/**---------------------------------------------------------------- ON CSS ---------------------------------------------------------------------------
 * Compress CSS
 * @return void
 */
function notfound_on_css(): void {
    //compressor::include_css(__info('view_url').'/style.css');
}

notfound_main();