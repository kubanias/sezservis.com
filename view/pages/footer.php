<?php if (!defined('B2')) exit(0);
/**
 * @package JetFin
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 28.10.2022
 * @version: 1.0
 *
 * Footer for all admin panel pages
 */

//------------------------------------------------------------------ FOOTER -------------------------------------------------------------------------
function page_footer(): void {
				echo '<footer>';
					echo '<div class="left_block">';
						echo '<img src="view\pages\images\logo.png">';
						echo '<p>301790, РФ, Тульская область, г. Донской,<br> мкр. Северо-Задонск, пер. Школьный, стр. 1<br> Телефон: +7 (48746) 7-18-64<br> Моб.: +7 (960) 604-63-85<br> e-mail:  info@sezservis.ru';
					echo '</div>';
					echo '<div class="right_block">';
						echo '<ul>';
							echo '<li><a href="/home">Главная</a></li>';
							echo '<li><a href="/about_company">О компании</a></li>';
							echo '<li><a href="/repair">Ремонт</a></li>';
							echo '<li><a href="/production">Производство</a></li>';
							echo '<li><a href="/designing">Проектирование</a></li>';
							echo '<li><a href="/import">Имортозамещение</a></li>';
							echo '<li><a href="/responsibility">Социальная ответственность</a></li>';
							echo '<li><a href="/mission">Миссия</a></li>';
							echo '<li><a href="/contacts">Контакты</a></li>';
						echo '</ul>';
					echo '</div>';
					echo '<div class="autor">';
						echo '<a href="https://artur-matveev.ru/">Создание сайта<br>©️ Студия Артура Матвеева</a>';
					echo '</div>';
				echo '</footer>';
			echo '</div>';
		echo '</body>' . NR;
    echo '</html>' . NR;
}