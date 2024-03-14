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
}

/**---------------------------------------------------------------- ON CSS ---------------------------------------------------------------------------
 * Include compressed CSS
 * @return void
 */
function page_on_css(): void {
	compressor::include_css(__info('view_url') . '/pages/mission/style.less');
	echo '<style type="text/css">
		.about_doing_content img:hover{
			filter: brightness(0) saturate(100%) invert(45%) sepia(76%) saturate(2672%) hue-rotate(178deg) brightness(101%) contrast(108%);
		}</style>';
}

/**---------------------------------------------------------------- MAIN -----------------------------------------------------------------------------
 * Generates main page
 * @return void
 */
function main(): void {
    page_header();
    page_content();
	about_company();
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
			//echo '<img src="/view/pages/images/jobs.png" alt="" class="jobs">';
		echo '</div>';
		echo '<div class="left block">';
			echo '<h1>Производство<br> и <span style="color: #009fff">ремонт</span> <br> гидроцилиндров</h1>';
			echo '<h2>Ремонтируем гидравлику с 1961 года</h2>';
			echo '<p> Мы являемся дочерней компанией завода по <br> производству силовой гидравлики и горно-шахтному<br> оборудованию Северо-Задонского экспериментального<br> завода <a href="https://szez.ru/ru "style="color: #009fff">www.szez.ru</a>';
		echo '</div>';
		
	echo '</div>';  
}
//---------------------------------------------------------------- ABOUT COMPANY --------------------------------------------------------------------------
function about_company(): void{
	echo '<div class="about_company">';
		echo '<div class="left block">';
			echo '<h1>Миссия</h1>';
			echo '<p> - Освоение новых областей применения продукции компании в <br>
			различных отраслях промышленности с постоянным расширением <br>
			товарной линейки, непрерывное совершенствование качества <br>
			выпускаемой продукции и оказываемых услуг, ориентированных на <br>
			потребности клиентов;</p>';
            echo '<div class="description">';
                echo '<p> - Вытеснение зарубежных компаний с рынка России за счет предложения <br>
				более технологического оборудования в высококачественном исполнении;</p>';
                echo '<p>- Поднятие авторитета машиностроительной отрасли России путем <br>
				повышения культуры производства и применения более совершенных <br>
				методов управления предприятием, укрепление духовно-нравственной <br>
				культуры, семейных ценностей и традиций России.</p>';
            echo '</div>';
		echo '</div>';
		echo '<div class="right block">';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\family.webp" alt="" class="">';
				echo '</div>';
		echo '</div>';

	echo '</div>';
}