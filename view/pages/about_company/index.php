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
	compressor::include_css(__info('view_url') . '/pages/about_company/style.less');
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
	history_company();
	about_doing();
	callage();
	technic_jobs();
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
			echo '<h1>о компании</h1>';
			echo '<p> История нашего завода началась 1961 году. Завод был основан для <br>
			ускорения производства опытных образцов и партий новых машин и <br>
			механизмов для шахт, а также проведения экспериментальных работ по <br>
			механизации и автоматизации производственных процессов.';

			echo '<p> Мы, как дочернее предприятие, полностью сохраняем приверженность к <br>
			традициям нашего завода. Основываясь на многолетнем опыте, компания <br>
			оптимизирует свои производственные процесы в угоду современным <br>
			треблованиям качеству изготавливаемой продукции.';
		echo '</div>';
		echo '<div class="right block">';
			echo '<div class="history">';
				echo '<div class="history_text">История предприятия</div>';
			echo '</div>';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\factory.webp" alt="" class="">';
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
			
			echo '<div class="repair">';
				echo '<img src="view\pages\images\gas-key.png" alt="" class="">';
				echo '<h2>Ремонт</h2>';
				echo '<p>Ремонт гидроцилиндров позволяет сэкономить <br>
				финансовые ресурсы вашей организации. <br>
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
			echo '<div class="production">';
				echo '<img src="view\pages\images\saw.png" alt="" class="">';
				echo '<h2>Производство</h2>';
				echo '<p>Наша организация занимается разработкой, ремонтом и <br>
				производством гидроцилиндров любых типоразмеров. <br>
				Компания располагает обширным штатом <br>
				ввысококвалифицированных специалистов...</p>';
				echo '<a href="/production"><b>Подробнее</b><span style="color: #009fff"> &gt;&gt;</span></a>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
}
function history_company(): void {
	echo '<div class="history_company">';
		echo '<p> Со второй половины шестидесятых годов на заводе начали выпускать опытные экспериментальные образцы<br>
		горно-шахтного оборудования и силовой гидравлики.';
		echo '<p> В это время было построено множество объектов инфраструктуры от специализированных складов до<br> собственной
		котельной, а общее количество станков на предприятии достигло 120 единиц против семнадцати,<br> которые были при 
		организации предприятия.';
		echo '<p> Особенно стоит отметить создание центральной заводской лаборатории в составе лаборатории линейно-угловых<br> мер, 
		лаборатории металлографии и лаборатории механических испытаний, что сразу превело к существенному<br> повышению 
		качества изготавливаемой продукции. Организован участок гальванопокрытий, предусматривающий<br> хромирование,
		цинкование, меднение, оксидирование.';
		echo '<p> В настоящее время станочный парк завода насчитывает 226 единиц оборудования,в том числе: токарные станки с ЧПУ,<br>
		фрезерные комплексы с ЧПУ, плазменный комплекс. Площадь предприятия составляет 5.7 Га. ';
	echo '</div>';
	}
function callage(): void {
	echo '<div class="callage">';
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