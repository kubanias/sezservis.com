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
	compressor::include_css(__info('view_url') . '/pages/repair/style.less');
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
    advantages();
    page_footer();
}//---------------------------------------------------------------- CONTENT --------------------------------------------------------------------------
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
			echo '<h1>ремонт <span style="color: #009fff"><br>гидроцилиндров</span></h1>';
			echo '<p> <b>Ремонт гидроцилиндров позволяет сэкономить финансовые <br>
            ресурсы вашей организации.</b>';
            echo '<p><span style="color: #009fff">СЭЗ-СЕРВИС</span> профессионально и специализированно <br>
            производиит ремонт гидравлических цилиндров, всех возможных <br>
            типов и устройств. Также осуществляется ремонт <br>
            пневматических цилиндров любых размеров и типов.</p>';
		echo '</div>';
		echo '<div class="right block">';
			echo '<div class="history">';
				echo '<div class="history_text">Ремонтируем</div>';
			echo '</div>';
				echo '<div class="image_block">';
					echo '<img src="view\pages\images\WDH-overzicht_hal.jpg" alt="" class="">';
					echo '<img src="view\pages\images\rectangle.jpg" alt="" class="">';
					echo '<img src="view\pages\images\046.jpg" alt="" class="cylinder">';
				echo '</div>';
		echo '</div>';
	echo '</div>';
}
function lists(): void{
    echo '<div class="lists">';
        echo '<p class="works">Наша команда состоит из высококвалифицированных специалистов, которые владеют современными<br> 
        технологиями и используют качественные запчасти для ремонта гидроцилиндров. Мы гарантируем <br> высокое качество и долговечность нашей работы.';
        echo '<p class="work">Наша команда готова взяться за ремонт гидроцилиндров любой сложности. Мы оперативно и качественно устраняем поломки,<br> 
        восстанавливаем изношенные детали и великолепно настраиваем гидроцилиндры для оптимальной работы вашего оборудования.';
        echo '<div class="works">';
            echo '<p>основные этапы и <br>
            принципы качественного <br>
            ремонта гидроцилиндров:';
            echo '<ul>';
                echo '<li>Мойка и полная разработка гидроцилиндра;</li>';
                echo '<li>Квалифицированный обмер и дефектация с использованием <br>
                специализированного инструмента и оборудования для измерения;</li>';
                echo '<li>Принятие технических решений об объеме и способе ремонта <br>
                опытным инженером-конструктором;</li>';
                echo '<li>Согласование объемов ремонта с заказчиком;</li>';
                echo '<li>Выполнение всех ремонтных работ на основании разработанной <br>
                конструкторской документации на новейшем специализированном <br>
                оборудовании с использованием проверенных высококачественных <br>
                материалов;</li>';
                echo '<li>Сборка и испытание гидроцилиндра на аттестованном стенде <br>
                согласно утвержденноц методике с документированием <br>
                необходимых параметров работы цилиндра;</li>';
                echo '<li>Окраска, упаковка и отгрузка цилиндра;></li>';
                echo '<li class="term">&#42; При ремонте эксклюзивных гидроцилиндров срок согласуется с  <br>
                заказчиком отдельно.</li>';
            echo'</ul>';
        echo '</div>';
    echo '</div>';
}
function advantages(): void{
    echo '<div class="advantages">';

        echo '<h1 class="title">Наши основные <span style="color: #009fff">преимущества</span></h1>';

        echo '<ul>';
            echo '<li>Многолетний опыт ремонта гидроцилиндров всех возможных конструкций;</li>';
            echo '<li>Мощная, высокопрофессиональная конструкторская служба;</li>';
            echo '<li>Новейшее специализированное оборудование для производства всех необходимых частей гидроцилиндра;</li>';
        echo '</ul>';
        echo '<p>Гидроцилиндры должны ремонтироваться и обслуживаться профессионалами, четко разбирающимися в их конструкциях и <br>
        особенностях эксплуатации. Наша компания предлагает вам услуги ремонта гидроцилиндров в минимальные сроки высокого <br>
        качества. У нас в компании работают специалисты с двадцатилетним стажем, поэтому мы можем гарантировать качество своих <br>
        работ. Мы предлагаем вам выгодные условия расчетов и гарантию на наши работы.</p>';

        echo '<h1 class="title">Отраслевые <span style="color: #009fff">нюансы ремонта</span></h1>';

        echo '<p>При ремонте гидроцилиндра, так же как и при его проектировке, очень важным моментом является понимание всех аспектов <br>
        условий его эксплуатации. В качестве примера возьмем некоторые отрасли:<p>';
        echo '<p>1. Металургия. <br>
        Гидроцилиндры для металургических предприятий имеют разные условия эксплуатации, поэтому унифицированный подход к их <br>
        ремонту, так же как к изготовлению нежелателен, так как это может существенно снизить срок службы цилиндра. Некоторые <br>
        гидроцлиндры работают при постоянной комфортной температуре, атмосфере и влажности. Здесь могут быть использованы уже <br>
        обкатанные решения по замене гильз, штоков и уплотнений. Но в металургии также часто встречаются цилиндры которые <br>
        работают при очень высоких температурах, соответственно нужно учитывать это при подборе материала из которого будут <br>
        изготовлены части под замену неисправных. Помимо этих есть еще многие факторы, один из которых - цикличность работы, а <br>
        также динамические нагрузки.</p>';

        echo '<p>2. Нефтедобывающая отрасль. <br>
        Также как и в металургии, существуют различные типы цилиндров и условия их эксплуатации. Стоит обратить внимание на то, что <br>
        некторые из цилиндров работают в соляных условиях, условиях морской воды и минусовых температур (к примеру на буровых <br>
        платформах в море). Поэтому мы сразу не рекомендуем использовать хонингованную трубу, для изготовления ремонтных гильз <br>
        этих цилиндров, так как она достаточно быстро подвергается коррозии и приходит в негодность. А также ударную вязкость стали <br>
        при работе в условиях минусовых температур.</p>';

        echo '<p>3. Цилиндры для спецтехники. <br>
        Основной чертой работы этих цилиндров является высокая частота циклов. А также различные температурные условия <br>
        (спецтехника должна работать в любую погоду). <br>
        Для решения вопросов по ремонту цилиндров, наш конструкторский отдел всегда анализирует полученную от заказчика <br>
        информацию, предлагает свои решения для увеличения срока службы цилиндра. Мы тщательно подбираем не только сталь для <br>
        изготовления, но и материалы для уплотнений гидроцилиндра. <br>
        Обращаясь к нам за ремонтом, Вы экономите не только время, так как команда профессионалов быстро найдет необходимое <br>
        решение, но и денежные средства за счет более ждолгой службы цилиндра до следующего планового ремонта.</p>';
    echo '</div>';
}