<?php if (!defined('B2')) exit(0);

/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 01.06.2016
 * @version: 2.0
 *
 * Basis2 PHP Framework plugin that simply includes jQuery library.
 */

/**------------------------------------------------------ EVENT LISTENERS ----------------------------------------------------------------------------
 */
__on('js', 'jquery_on_js', 2);

/**----------------------------------------------------------- ON JS ---------------------------------------------------------------------------------
 * @return void
 */
function jquery_on_js() {
    echo '<script type="text/javascript" src="/modules/jquery/js/jquery-3.6.0.min.js" charset="utf-8"></script>' . NR;
}