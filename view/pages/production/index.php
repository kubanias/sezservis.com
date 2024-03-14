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
	compressor::include_css(__info('view_url') . '/pages/production/style.less');
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
    lists();
	produce();
    about_doing();
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
			echo '<p> Мы являемся дочерней компанией завода по <br> производству силовой гидравлики и горно-шахтному<br> оборудованию Северо-Задонского экспериментального<br> завода <a href="https://szez.ru/ru" style="color: #009fff">www.szez.ru</a>';
		echo '</div>';
		
	echo '</div>';  
}
//---------------------------------------------------------------- ABOUT COMPANY --------------------------------------------------------------------------
function about_company(): void{
	echo '<div class="about_company">';
		echo '<div class="left block">';
			echo '<h1>Производство</h1>';
			echo '<p> Наша организация занимается разработкой, ремонтом и производством <br>
            гидроцилиндров любых типоразмеров. Компания располагает <br>обширным 
            штатом высококвалифицированных специалистов, которые <br>постоянно изучают
            новые веяния и технологии, чтобы предприятие всегда <br>сохраняло 
            лидирующие позиции.';
			
			echo '<p> Понимая условия современного рынка, наше предприятие прикладывает <br>
            максимальные усилия, для того, чтобы максимально сократить для <br>заказчика 
            время получения готовой продукции.';
		echo '</div>';
		echo '<div class="right block">';
			echo '<div class="history">';
				echo '<div class="history_text">Производим</div>';
			echo '</div>';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\DSC05692.webp" alt="" class="">';
				echo '</div>';
		echo '</div>';
	echo '</div>';
}
function lists(): void{
    echo '<div class="lists">';
            echo '<p>Изготавливаем гидроцилиндры для таких отраслей как:';
            echo '<ul>';
                echo '<li>Лёгкое и тяжёлое машиностроение;</li>';
                echo '<li>Нефтедобывающая отрасль;</li>';
                echo '<li>Прессовое оборудование;</li>';
                echo '<li>Горношахтная промышленность;</li>';
                echo '<li>Металлургическая промышленность;</li>';
            echo'</ul>';
        echo '</div>';
}
function produce(): void{
    echo '<div class="produce">';
        echo '<h1>Производим</h1>';
        echo '<div class="cylinders">';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 9.jpg">';
                echo '<p>Цилиндры телескопические';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 10.jpg">';
                echo '<p>Поршневые целиндры';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 11.jpg">';
                echo '<p>Цилиндры плунжерные';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 14.jpg">';
                echo '<p>Пневмогидроаккумулятор';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 12.jpg">';
                echo '<p>Гидропоры';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\detail.jpg">';
                echo '<p>Гидростоки двойной<br> раздвижности';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 13.jpg">';
                echo '<p>Гидроцилиндры с <br>двумя штоками';
            echo '</div>';
            echo '<div class="cylinder">';
                echo '<img src="view\pages\images\layer 15.jpg">';
                echo '<p>Гидроцилиндры передвижения<br> (с герконовой линейкой)';
            echo '</div>';
        echo '</div>';
    echo '</div>';
}
//---------------------------------------------------------------- ABOUT DOING --------------------------------------------------------------------------
function about_doing(): void{
	echo '<div class="about_doing">';
		echo '<div class="about_doing_text">';
			echo '<h1>Чем мы <span style="color: #1280c3">занимаемся</span></h1>';
		echo '</div>';
		echo '<div class="about_doing_content">';
			echo '<div class="production">';
				echo '<img src="view\pages\images\saw.png" alt="" class="">';
				echo '<h2>Производство</h2>';
				echo '<p>Наша организация занимается разработкой, ремонтом <br>
                и производством гидроцилиндров любых <br>
                типоразмеров. Компания располагает обширным <br>
                штатом высококвалифицированных спесиалистов...</p>';
				echo '<a href="/production"><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';
			echo '<div class="repair">';
				echo '<img src="view\pages\images\gas-key.png" alt="" class="">';
				echo '<h2>Ремонт</h2>';
				echo '<p>Ремонт гидроцилиндров позволяет сэкономить <br>
                финансовык ресурсы вашей организации. <br>
                СЭЗ-СЕРВИС профессионально и специализированно <br>
                производит ремонт гидравлических цилиндров...</p>';
			echo '<a href="/repair"><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';
			echo '<div class="designing">';
				echo '<img src="view\pages\images\compass.png" alt="" class="">';
				echo '<h2>Проектирование</h2>';
				echo '<p>Банальные, но неопровержимые выводы, а также<br> тщательные исследования конкурентов лишь<br> добавляют фракционных разногласий и подвергнуты<br> целой серии независимых исследований.<br></p>';
			echo '<a><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}