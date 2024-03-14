$(document).ready(function () {
    navbar_menu();
    App();
});

function navbar_menu() {
    $(".icon").click(function() {

        if($(".items").is(":visible")) {
           $(".items").removeClass("showitems");
           $(".menu_rectangle").removeClass("showitems");
        }
    
        else {
            $(".items").addClass("showitems");
            $(".menu_rectangle").addClass("showitems");
        }
    
    });
}

$(function Navbar_appendix() {                             // когда страница загружена
    $('.items li a').each(function () {      // проходим по нужным нам ссылками
        var location = window.location.href // переменная с адресом страницы
        var link = this.href                // переменная с url ссылки
        var result = location.match(link);  // результат возвращает объект если совпадение найдено и null при обратном
 
        if(result != null) {                // если НЕ равно null
            $(this).addClass('select');    // добавляем класс
        }
    });
});


