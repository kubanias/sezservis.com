<?php if (!defined('B2')) exit(0);
/**
 * @package JetFin
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 02.04.2022
 * @version: 1.0
 *
 * The project main page
 */

__use('compressor');

__on('js', 'page_on_js');
__on('css', 'page_on_css');

include_once __dir__ . '/../header.php';
include_once __dir__ . '/../footer.php';
//include_once 'lang.php';

/**---------------------------------------------------------------- ON JS ----------------------------------------------------------------------------
 * Include compressed JS
 * @return void
 */
function page_on_js(): void {
	//compressor::include_js(__info('view_url') . '/pages/contacts/java.js');
}

/**---------------------------------------------------------------- ON CSS ---------------------------------------------------------------------------
 * Include compressed CSS
 * @return void
 */
function page_on_css(): void {
	compressor::include_css(__info('view_url') . '/pages/contacts/style.less');
}

/**---------------------------------------------------------------- MAIN -----------------------------------------------------------------------------
 * Generates main page
 * @return void
 */
function main(): void {
    page_header();
    page_content();
	about_company();
	contacts();
	maps();
	feedback();
    page_footer();
}
/**--------------------------------------------------------------- CONTENT ---------------------------------------------------------------------------
 * Generates content of page
 * @return void
 */
function page_content(): void {
	echo '<img src="/view/pages/images/cylinder.png" alt="" class="hydro_cylinder">';
	echo '<div class="content">';
		echo '<div class="right block">';
		echo '</div>';
		echo '<div class="left block">';
			echo '<h1>Производство<br> и <span style="color: #009fff">ремонт</span> <br> гидроцилиндров</h1>';
			echo '<h2>Ремонтируем гидравлику с 1961 года</h2>';
			echo '<p> Мы являемся дочерней компанией завода по <br> производству силовой гидравлики и горно-шахтному<br> оборудованию Северо-Задонского экспериментального<br> завода <a href="https://szez.ru/ru" style="color: #009fff">www.szez.ru</a>';
		echo '</div>';
		
	echo '</div>';  
}
//---------------------------------------------------------------- ABOUT COMPANY --------------------------------------------------------------------------
function about_company(): void{
	echo '<div class="about_company">';
		echo '<div class="left block">';
			echo '<h1>контакты</h1>';
			// echo '<p>История нашего завода началась в далёком 1961году. <br>
			// Завод был основан для ускорения производства <br>
			// опытных образцов и партий новых машин и <br>
			// мехаизмов для шахт, а также проведения <br>
			// автоматизоции.</p>';
		echo '</div>';
		echo '<div class="right block">';
			echo '<div class="history">';
				echo '<div class="history_text">С нами можно связаться</div>';
			echo '</div>';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\Origo-Mig-410-510-1.jpg" alt="" class="">';
					echo '<img src="view\pages\images\Rectangle 2.jpg" alt="" class="">';
					echo '<img src="view\pages\images\okraska-metallokonstruktsiy-2.jpg" alt="" class="cylinder">';
				echo '</div>';
		echo '</div>';
	echo '</div>';
}
function contacts(): void{
	echo '<div class="contacts">';
		echo '<div class="images_block">';
			echo '<img class="map" src="view\pages\images\adres.png">';
			echo '<img class="phone" src="view\pages\images\phone.png">';
			echo '<img class="mail" src="view\pages\images\mail.png">';
		echo '</div>';
		echo '<div class="text_block">';
			echo '<p>Адрес: 301790, РФ, Тульская область, г.Донской,<br>
			мкр. Северо-Задонск, пер. Школьный, стр. 1</p>';
			echo '<p>Телефоны:<br>
			+7 (48746) 7-18-64<br>
			+7 (960) 604-63-85</p>';
			echo '<p>E-mail: info@sezservis.ru</p>';
		echo '</div>';
	echo '</div>';
}


//---------------------------------------------------------------- MAPS -----------------------------------------------------------------------------
function maps(): void{
	echo '<div class="maps">';
		echo '<script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A2fe8a562a7f153033d1348c790cad1e5ed00f2e74d0b15fce837ba648668b9ed&amp;width=1280&amp;height=720&amp;lang=ru_RU&amp;scroll=false"></script>';
	echo '</div>';
}
//----------------------------------------------------------------FEEDBACK----------------------------------------------------------------------------
function feedback(): void {
	echo '<h1 class="form_feedback">Форма <br> <span style="color: #009fff">обратной связи</span></h1>';
	echo '<div class="feedback">';
		echo '<div class="left_block">';
			echo '<img src="view\pages\images\manufacturing-issues-featured-image.png">';
		echo '</div>';
		
		echo '<div class="right_block">';
		
			echo '<form action="/mailer" method="post">';
				echo '<input type="text" name="name" placeholder="Имя"></input>';
				echo '<input type="email" name="email" placeholder="E-mail"></input>';
				echo '<input type="tel" name="tel" placeholder="Телефон"></input>';
				echo '<input type="text" name="message" placeholder="Сообщение" class="message"></input>';
				echo '<input type="hidden" name="action" value="mail"></input>';
				echo '<input type="submit" class="submit" value="Отправить" class="submit_button"></input>';
			echo '</form>';
		echo '</div>';	
	echo '</div>';
}
