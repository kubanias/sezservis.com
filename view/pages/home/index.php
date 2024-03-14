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
	compressor::include_css(__info('view_url') . '/pages/home/style.less');
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
	about_doing();
	production_cylinder();
	technic_jobs();
	power();
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
			echo '<h1>о компании</h1>';
			echo '<p> История нашего завода началась в 1961 году. Завод был основан <br>
			для ускорения производства опытных образцов и партий новых <br>
			машин и механизмов для шахт, а также проведения <br>
			экспериментальных работ по механизации и автоматизации <br>
			производственных процессов.<br></p>';
			echo '<a href="/about_company">Подробнее<span style="color: #009fff"> &gt;&gt;</span></a>';
		echo '</div>';
		echo '<div class="right block">';
			echo '<div class="history">';
				echo '<div class="history_text">История завода</div>';
			echo '</div>';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\hydraulic-cylinder.jpg" alt="" class="">';
					echo '<img src="view\pages\images\rectangle.jpg" alt="" class="">';
					echo '<img src="view\pages\images\hydraulic-cylinder-2.jpg" alt="" class="cylinder">';
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
				штатом ввысококвалифицированных специалистов...<br></p>';
				echo '<a href="/production"><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';

			echo '<div class="repair">';
				echo '<img src="view\pages\images\gas-key.png" alt="" class="">';
				echo '<h2>Ремонт</h2>';
				echo '<p>Ремонт гидроцилиндров позволяет сэкономить <br>
				финансовые ресурсы вашей организации. <br>
				СЭЗ-СЕРВИС профессионально и специализированно <br>
				производит ремонт гидравлических цилиндров...<br></p>';
			echo '<a href="/repair"><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';

			echo '<div class="designing">';
				echo '<img src="view\pages\images\compass.png" alt="" class="">';
				echo '<h2>Проектирование</h2>';
				echo '<p>Ремонт гидроцилиндров позволяет сэкономить <br>
				финансовые ресурсы вашей организации. <br>
				Наша команда готова взяться за ремонт гидроцилиндров <br>
				любой сложности.<br></p>';
				echo '<a><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
//---------------------------------------------------------------- PRODUCTION CYLINDER --------------------------------------------------------------------------
function production_cylinder(): void{
	echo '<div class="production_cylinder">';
		echo '<div class="history">';
			echo '<div class="history_text">Изготовление гидроцилиндров</div>';
		echo '</div>';
		echo '<img src="view\pages\images\hydraulic-cylinder-with-logo.png">';
		echo '<div class="right_block">';
			echo '<h1>ИЗГОТАВЛИВАЕМ<br> <span style="color: #1280c3">ГИДРОЦИЛИНДРЫ</span></h1>';
			echo '<p>Производство и проектировка гидроцилиндров является одним из основным <br>
			направлений деятельности компании. В рамках проектов импортозамещения <br>
			освоили изготовление аналогов превосходящих по качеству зарубежные <br>
			образцы таких фирм как: Rexroth, Parker, Hydac, Liebherr и других <br>
			производителей.</p>';
			echo '<p>Завод имеет возможность производства как штучных так и серийных <br>
			гидроцилиндров. Наша продукция используется в предприятиях следующих <br>
			отраслей:';
				echo '<ul>';
					echo '<li>Лёгкое и тяжёлое машиностроение;</li>';
					echo '<li>Нефтедобывающая отрасль;</li>';
					echo '<li>Краны телескопические;</li>';
					echo '<li>Прессовое оборудование;</li>';
					echo '<li>Горношахтная промышленность;</li>';
					echo '<li>Металлургическая промышленность;</li>';
				echo '</ul>';
		echo '</div>';
	echo '</div>';
}
//---------------------------------------------------------------- TECHNIC JOBS --------------------------------------------------------------------------
function technic_jobs(): void{
	echo '<div class="technic_jobs">';
		echo '<h1><span style="color: #1280c3">Работаем</span> с техникой</h1>';
		echo '<hr></hr>';
	echo '<div class="technic">';
		echo '<img src="view\pages\images\Komatsu-Logo.png" class="komatsu">';
		echo '<img src="view\pages\images\volvo-logo.png"class="volvo">';
		echo '<img src="view\pages\images\jcb-logo.png"class="jcb">';
		echo '<img src="view\pages\images\Hitachi_Logo.png"class="hitachi">';
		echo '<img src="view\pages\images\cat-logo.png"class="cat">';
		echo '<img src="view\pages\images\liebherr-logo.png"class="liebherr">';
		echo '<img src="view\pages\images\HyundaiLogo.png"class="Hyundai">';
	echo '</div>';	
	echo '</div>';
}
//---------------------------------------------------------------- POWER --------------------------------------------------------------------------
function power(): void{
	echo '<div class="power">';
		echo '<div class="left_block">';
			echo '<h1>Производственные<br> <span style="color: #1280c3">мощности</span></h1>';
			echo '<img src="view\pages\images\welder.jpg">';
		echo '</div>';
		echo '<div class="right_block">';
			echo '<ul>';
				echo '<li>токарные универсальные станки;</li>';
				echo '<li>токарные станки с ЧПУ разных конфигураций и размеров;</li>';
				echo '<li>фрезерное универсальное оборудование;</li>';
				echo '<li>фрезерные 4-х координатные обрабатывающие центры;</li>';
				echo '<li>горизонтально расточное оборудование;</li>';
				echo '<li>сварочное и наплавочное оборудование;</li>';
				echo '<li>сверлильное оборудование;</li>';
				echo '<li>хонинговальные станки;</li>';
				echo '<li>оборудование для глубокого свердения;</li>';
				echo '<li>шлифовальное оборудование;</li>';
				echo '<li>оборудование для объемной термообработки и обработки ТВЧ;</li>';
				echo '<li>оборудование для производства уплотнений;</li>';
				echo '<li>разнообразное сборочное и испытательное оборудование;</li>';
				echo '<li>оборудование для мойки высоким давлением и ультразвуковой мойки;</li>';
				echo '<li>оборудование для окраски жидкими красками и для порошковой окраски.</li>';
			echo '</ul>';
		echo '</div>';
	echo '</div>';	
}