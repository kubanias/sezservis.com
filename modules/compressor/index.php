<?php if (!defined('B2')) exit(0);
/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 20.03.2022
 * @version: 1.0
 *
 * Provides functionality to minify and assemble static JS and CSS files into one file or specified group of files.
 * Events:
 * 'js' - When we need to include .js files.
 * 'css' - When to include .css files
 * 'reg' - When to register all static files
 *
 * 1. Для минимизации файлов .js и .css используется модуль compressor.
 *
 * 2. Также именно компрессор переносит все значения занесённые в g_js_config непосредственно в JavaScript.
 *
 * 3. Для предварительной подготовки каждого файла, необходимо его зарегистрировать и прикрепить к определённой группе (см функции
 *    compressor::register_js() / compressor::register_css()). После того, как все файлы будут зарегистрированы, необходимо вызвать функцию
 *    compressor::process_all(). В итоге, исходные файлы одной группы будут минимизированы и объединены в один файл. Готовые файлы и информация об их
 *    группировке, сохраняется в папках js и css модуля compressor. Поэтому эти папки должны быть разрешены для чтения на боевых серверах.
 *
 * 4. Регистрация и обработка файлов на рабочих серверах происходит обособленно через запуск специальной команды в адресной строке браузера.
 *    В тестовом окружении, обычно, процесс обновления файлов автоматизирован.
 *
 * 5. Подключение файлов происходит посредством использования compressor::include_js(). При этом, если файл не был ранее обработан, то содержимое
 *    указанного файла будет загружено на страницу без какой-либо предварительной обработки. Если же файл был найден среди обработанных, то
 *    подключается вся группа, которая его содержит.
 *
 * 6. Компрессор можно отключить в любой момент через конфигурацию Базиса и тогда файлы будут отдаваться в исходном виде.
 *
 * 7. По умолчанию, в настройках включен режим вывода содержимого непосредственно в код страницы, что снижает количество дополнительных запросов к
 *    серверу и сокращает время загрузки страницы. Такой режим можно отключить через настройки Базиса и подключать скрипты и стили отдельными
 *    (заранее подготовленными, уменьшенными и объединёнными в группы) файлами.
 */

__set_config(['compressor', 'enabled'], true, false);                                            // Just to disable static in case of errors
__set_config(['compressor', 'inline'], true, false);                                            // To show css and JS directly in HTML code
__set_config(['compressor', 'reg_file'], '/view/compressor.php', false);    // Path relative to BASEDIR, where all the files to be compressed are registered
__set_config(['compressor', 'auto'], true, false);                                                // TRUE to regenerate all compressed files each request

require_once('compressor.php');

/**------------------------------------------------------------ COMPRESSOR MAIN ----------------------------------------------------------------------
 * @return void
 */
function compressor_main(): void {
    // Check if we need to automatically process static files
    if (__get_config(['compressor', 'auto'])) {
        include_once(__info('base_dir') . __get_config(['compressor', 'reg_file']));
        compressor::process_all();
    }
}

compressor_main();

/**------------------------------------------------------- EVENT LISTENERS ---------------------------------------------------------------------------
 */
__on('js', 'compressor_on_js', 3);
__on('css', 'compressor_on_css', 3);

/**---------------------------------------------------------------- COMPRESSOR ON JS -----------------------------------------------------------------
 * @return void
 */
function compressor_on_js(): void {
    global $g_js_config, $g_l10n;
    echo '<script type="text/javascript">';

    // Generate JavaScript configuration from $g_js_config
    echo 'var g_config = new Array();';
    if (is_array($g_js_config) && count($g_js_config)) {
        foreach ($g_js_config as $key => $item)
            if (is_array($item)) {
                echo 'g_config[\'' . $key . '\'] = new Array();';
                foreach ($item as $sub_key => $sub_item)
                    if (is_array($sub_item)) {
                        echo 'g_config[\'' . $key . '\'][\'' . $sub_key . '\'] = new Array();';
                        foreach ($sub_item as $sub_key2 => $sub_item2) {
                            echo 'g_config[\'' . $key . '\'][\'' . $sub_key . '\'][\'' . $sub_key2 . '\'] = ' .
                                (is_string($sub_item2) ? '"' . $sub_item2 . '"' : $sub_item2) . ';';
                        }
                    } else echo 'g_config[\'' . $key . '\'][\'' . $sub_key . '\'] = ' . (is_string($sub_item) ? '"' . $sub_item . '"' : $sub_item) . ';';
            } else echo 'g_config[\'' . $key . '\'] = ' . (is_string($item) ? '"' . $item . '"' : $item) . ';';
    }

    // Generate JavaScript localization data
    echo 'var g_l10n = new Array();';
    if (is_array($g_l10n) && count($g_l10n)) {
        foreach ($g_l10n as $key => $item)
            echo 'g_l10n["' . $key . '"] = "' . $item . '";';
    }

    echo '</script>' . NR;

    // Update list of compressed files
    compressor::clear_available_files('js');
    @include 'js/files.php';
}

/**---------------------------------------------------------------- COMPRESSOR ON CSS -----------------------------------------------------------------
 * @return void
 */
function compressor_on_css() {
    // Update list of compressed files
    compressor::clear_available_files('css');
    @include 'css/files.php';
}
