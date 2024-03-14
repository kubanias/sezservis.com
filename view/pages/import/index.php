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
	compressor::include_css(__info('view_url') . '/pages/import/style.less');
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
    technic_jobss();
    foreign();
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
			echo '<h1>Импортозамещение</h1>';
			echo '<p> Предприятие готово рассмотреть предложения по изготовлению <br>
            отечественных аналогов зарубежного гидравлического оборудования, <br>
            систем силовой гидравлики, а также запчастей к ним. Освоение <br>
            современных технологий и оборудования позволяет нам производить <br>
            аналоги гидроцилиндров, гидростоек и гидродомкратов иностранных <br>
            производителей.</p>';
			
			echo '<p>Конструкторско-технологический отдел имеет опыт адаптации зарубежных <br>
            чертежей под российские стандарты.</p>';
		echo '</div>';
		echo '<div class="right block">';
			echo '<div class="history">';
				echo '<div class="history_text">Импортозамещение</div>';
			echo '</div>';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\flags.webp" alt="" class="">';
				echo '</div>';
		echo '</div>';
	echo '</div>';
}//---------------------------------------------------------------- TECHNIC JOBS --------------------------------------------------------------------------
function technic_jobss(): void{
	echo '<div class="technic_jobs">';
		echo '<h1><span style="color: #1280c3">Работаем</span> с техникой</h1>';
		echo '<hr></hr>';
        echo '<p class="other">и прочих</p>';
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
function foreign(): void{
    echo '<div class="foreign">';
        echo '<h1 class="title">Размещение производства <span style="color: #1280c3">зарубежных</span> <br>
        <span style="color: #1280c3">компаний</span> на наших площадях</h1>';
        echo '<div class="block">';
            echo '<p>Наше предприятие всегда открыто для предложений о взаимодействии с зарубежными компаниями по <br>
            размещению ремонта и производства гидроцилиндров.</p>';
            echo '<p><b>Такой подход позволяет:</b></p>';
            echo '<ul>';
                echo '<li>-участвовать в государственных тендерах на выгодных условиях</li>';
                echo '<li>-многократно снизить издержки на логистику</li>';
                echo '<li>-резко расширить клиентскую базу, так как мы работаем в отрасли более 50 лет</li>';
                echo '<li>-снизить затраты на производство за счёт относительно невысокой стоимости труда всвязи со значительным <br>
                изменением курсов валют.</li>';
            echo '</ul>';
            echo '<p>Уже сейчас мы сотрудничаем с рядом иностранных компаний. Продуктивыный опыт такого взамодействия <br>
            показал, что мы можем гарантировать выпуск продукции со строгим соблюдением технологий <br>
            компаний-партнеров, а наши специалисты готовы адаптировать производство изделий на нашем оборудовании. <br>
            Конструкторский отдел нашего предприятия имеет опыт в адаптации чертежей иностранных компаний под <br>
            отечественные ГОСТы, стандарты и металл.</p>';
        echo '</div>';
    echo '</div>';
}