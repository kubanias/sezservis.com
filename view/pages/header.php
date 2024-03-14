<?php if (!defined('B2')) exit(0);
/**
 * @package JetFin
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 02.10.2022
 * @version: 1.0
 *
 * Header for all admin panel pages
 */

//include_once 'lang.php';

__on('css', 'navbar_on_css', 10);
__on('js', 'navbar_on_js', 10);

__use('jquery');

/**---------------------------------------------------------------- ON CSS --------------------------------------------------------------------------
 * Include compressed CSS
 * @return void
 */
function navbar_on_css() {
    compressor::include_css(__info('view_url') . '/pages/style_desktop.less');
    compressor::include_css(__info('view_url') . '/pages/style_mobile.less');
}

/**---------------------------------------------------------------- ON JS ---------------------------------------------------------------------------
 * Include compressed JS
 * @return void
 */
function navbar_on_js() {
    compressor::include_js(__info('view_url') . '/pages/java.js');
}

/**---------------------------------------------------------------- HEADER --------------------------------------------------------------------------
 * HTML head and navigation bar
 * @param ?string $title - Page title
 * @param ?string $desc - Page description
 * @param ?string $body_id - ID of the `body` HTML element. NULL to skip the ID
 * @return void
 */
function page_header(?string $title = null, ?string $desc = null, ?string $body_id = null): void {
    // @formatter:off
    echo '<!DOCTYPE html>' . NR;
    echo '<html lang="' . __lang() . '">' . NR;
        echo '<head>' . NR;
			echo '<meta charset="utf-8" />' . NR;
        	echo '<meta name="viewport" content="width=device-width" />' . NR;
        	if ($title !== null) echo '<title>' . $title . '</title>' . NR;
        	if ($desc !== null) echo '<meta name="description" itemprop="description" content="' . $desc . '" />' . NR;
        	echo '<link rel="icon" type="image/x-icon" href="/view/images/favicons/favicon.ico?">' . NR;
        	echo '<link rel="apple-touch-icon" sizes="180x180" href="/view/images/favicons/apple-touch-icon.png">' . NR;
        	echo '<link rel="icon" type="image/png" sizes="32x32" href="view/images/favicons/favicon-32x32.png">' . NR;
        	echo '<link rel="icon" type="image/png" sizes="16x16" href="view/images/favicons/favicon-16x16.png">' . NR;
        	echo '<link rel="mask-icon" href="/view/images/favicons/safari-pinned-tab.svg" color="#5bbad5">' . NR;
        	echo '<meta name="msapplication-TileColor" content="#da532c">' . NR;
        	echo '<meta name="theme-color" content="#ffffff">' . NR;
            __event('css');
            __event('js');
        echo '</head>' . NR;
        echo '<body' . ($body_id === null ? '' : (' id="' . $body_id . '"')) . '>' . NR;
            echo '<div class="container">' . NR;
		    page_navbar();
    // @formatter:on
}

/**---------------------------------------------------------------- NAVBAR --------------------------------------------------------------------------
 * Generates page top navigation bar
 * @return void
 */
function page_navbar() {
	$items = [
		
	];

    // @formatter:off
    //$tenant_current = tenant::get_current();
    echo '<header>';
        echo '<nav class="navbar">' . NR;
            echo '<div class="navbar_top">';
                echo '<div class="navbar_content">';
                    echo '<div class="navbar_left">';
                        echo '<img src="/view/pages/images/logo.png" alt="" class="logo">';
					echo '</div>' . NR;
                    echo '<ul class="menu">';
                    echo '<div class="menu_rectangle"></div>';
                        echo '<div class="icon">&#8230;</div>';
                        echo '<div class="items">';
                            echo '<li><a href="/home">Главная</a></li>';
                            echo '<li><a href="/about_company">О компании</a></li>';
                            echo '<li><a href="/repair">Ремонт</a></li>';
                            echo '<li><a href="/production">Производство</a></li>';
                            echo '<li><a href="/designing">Проектирование</a></li>';
                            echo '<li><a href="/import">Импортозамещение</a></li>';
                            echo '<li><a href="/responsibility">Соц. ответственность</a></li>';
                            echo '<li><a href="/mission">Миссия</a></li>';
                            echo '<li><a href="/contacts">Контакты</a></li>';
                        echo '</div>';
                    echo '</ul>';
                echo '</div>';
            echo '</div>' . NR;
        echo '</nav>';
    echo '</header>' . NR;
    // @formatter: on
}