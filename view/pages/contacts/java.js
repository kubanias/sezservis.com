$(document).ready(function () {
    // Event listeners
    //main_block_el();
    //private_jets_el();
    //empty_legs_el();
    //mobile_menu_el();
    //main_form_el();
    //plane_rent_el();
    //enquire_el();
    //message_box_el();
    //activate_date_picker();
    //route_events();             // События FROM & TO
    //form_events();
    //metrika();
    //mail_send();

    // Effects
    //mobile_topline_fx();

    $('input#test').intlTelInput({});
});

//-------------------------------------------------------- MAIN BLOCK EVENT LISTENERS ---------------------------------------------------------------
function main_block_el() {
    $('.jet_input.passengers').on('keyup', function () {
        let field_value = $(this).val();
        if (isNaN(field_value)) $(this).val(1);
    });

    $('.passengers_input input').on('click', function () {
        let button_class = $(this).attr('class');
        let passengers_count = $(this).parent().find('#passengers').val();
        if (button_class === 'btn_plus') $(this).parent().find('#passengers').val(parseInt(passengers_count) + 1); else if (button_class === 'btn_minus' && passengers_count > 1) $(this).parent().find('#passengers').val(parseInt(passengers_count) - 1);
    });

    $('.service_more').on('click', function () {
        if ($(this).closest('.service').hasClass('expanded')) $(this).closest('.service').removeClass('expanded'); else $(this).closest('.service').addClass('expanded');
    });
}

//------------------------------------------------------- PRIVATE JETS EVENT LISTENERS --------------------------------------------------------------
function private_jets_el() {
    $('.select_passenger').on('click', function () {
        let animation_time = 300;
        let selected = $(this).data('passengers');
        $('.planes').css('display', 'none');
        $('.planes').css('opacity', '0');
        $('.select_passenger').removeClass('selected');
        switch (selected) {
            case 'small':
                $('#planes_small').css('display', 'flex');
                $('#planes_small').animate({opacity: 1}, animation_time);
                $('.select_passenger[data-passengers="small"]').addClass('selected');
                break;
            case 'medium':
                $('#planes_medium').css('display', 'flex');
                $('#planes_medium').animate({opacity: 1}, animation_time);
                $('.select_passenger[data-passengers="medium"]').addClass('selected');
                break;
            case 'big':
                $('#planes_big').css('display', 'flex');
                $('#planes_big').animate({opacity: 1}, animation_time);
                $('.select_passenger[data-passengers="big"]').addClass('selected');
                break;
        }
    });
}

//-------------------------------------------------------- EMPTY LEGS EVENT LISTENERS ---------------------------------------------------------------
function empty_legs_el() {
    $('.legs_list .list_pages button').on('click', function () {
        let selected_page = $(this).data('page_select');
        $('.legs').css('display', 'none');
        $('.legs[data-page="' + selected_page + '"]').css('display', 'block');
    });

    $('.legs_list .list_back').on('click', function () {
        let selected_page = $(this).parent().data('page_selected');
        if (selected_page !== 0) {
            $('.legs').css('display', 'none');
            $('.legs[data-page="' + (selected_page - 1) + '"]').css('display', 'block');
        }
    });

    $('.legs_list .list_next').on('click', function () {
        let selected_page = $(this).parent().data('page_selected');
        if (selected_page !== 3) {
            $('.legs').css('display', 'none');
            $('.legs[data-page="' + (selected_page + 1) + '"]').css('display', 'block');
        }
    });
}

//--------------------------------------------------------- MOBILE MENU EVENT LISTENERS -------------------------------------------------------------
function mobile_menu_el() {
    $('.header_wrap .open').on('click', function () {
        //$('.header_add.mobile').css('display', 'flex')
        $('.header_add.mobile').removeClass('closed');
        $('.header_add.mobile').addClass('opened');
    });
    $('.close_header').on('click', function () {
        //$('.header_add.mobile').css('display', 'none')
        $('.header_add.mobile').removeClass('opened');
        $('.header_add.mobile').addClass('closed');
    });
    $('.header_top .header_link').on('click', function () {
        //$('.header_add.mobile').css('display', 'none')
        $('.header_add.mobile').removeClass('opened');
        $('.header_add.mobile').addClass('closed');
    });
}

//---------------------------------------------------------- MAIN FORM EVENT LISTENERS --------------------------------------------------------------
function main_form_el() {
    $('.jet_search .jet_select .menu_selects .menu_select.round_trip').on('click', function () {
        $(this).closest('.jet_search').find('.jets_table').css('display', 'none');
        $(this).closest('.jet_search').find('.jets_table.round_trip_table').css('display', 'block');
        $(this).closest('.menu_selects').find('.selected').removeClass('selected');
        $(this).closest('.menu_selects').find('.menu_select.round_trip').addClass('selected');
    });

    $('.jet_search .jet_select .menu_selects .menu_select.one_way').on('click', function () {
        $(this).closest('.jet_search').find('.jets_table').css('display', 'none');
        $(this).closest('.jet_search').find('.jets_table.one_way_table').css('display', 'block');
        $(this).closest('.menu_selects').find('.selected').removeClass('selected');
        $(this).closest('.menu_selects').find('.menu_select.one_way').addClass('selected');
    });

    $('.jet_search .jet_select .menu_selects .menu_select.multi_leg').on('click', function () {
        $(this).closest('.jet_search').find('.jets_table').css('display', 'none');
        $(this).closest('.jet_search').find('.jets_table.multi_leg_table').css('display', 'block');
        $(this).closest('.menu_selects').find('.selected').removeClass('selected');
        $(this).closest('.menu_selects').find('.menu_select.multi_leg').addClass('selected');
    });

    // Add leg button on multi leg form
    $('.jet_search .jets_table.multi_leg_table .add_leg_button').on('click', function () {
        let leg = $(this).closest('form').find('.leg').first().clone();
        $(this).closest('form').find('.free_legs').append(leg.css('display', 'block'));
        activate_date_picker();
        route_events();
    })

    // Remove leg button on multi leg form
    $('.jet_search .jets_table.multi_leg_table .free_legs').on('click', '.remove_leg', function () {
        $(this).parents('.leg').remove();
    })

    $('.jet_search .add_notes').on('click', function () {
        let notes_hide = $(this).parent().parent().parent().find('.jets_input.notes').data('hide');
        if (notes_hide === true) {
            $(this).parent().parent().parent().find('.jets_input.notes').css('display', 'inline-block');
            $(this).text('Hide notes')
            $(this).parent().parent().parent().find('.jets_input.notes').data('hide', false);
        } else {
            $(this).parent().parent().parent().find('.jets_input.notes').css('display', 'none');
            $(this).text('+ Add Notes')
            $(this).parent().parent().parent().find('.jets_input.notes').data('hide', true);
        }
    });
}

//-------------------------------------------------------- PLANE RENT EVENT LISTENERS ---------------------------------------------------------------
function plane_rent_el() {
    $('.plane_rent').on('click', function () {
        clean_dialog();
        let plane_name = $(this).parent().find('.plane_name').text();
        let book_form = $('#message_book_empty').clone();
        book_form.find('.message_box .message_content .message_header span').text(plane_name);
        show_dialog(book_form);
    });
}

//--------------------------------------------------------- ENQUIRE EVENT LISTENERS -----------------------------------------------------------------
function enquire_el() {
    $('.enquire_content').on('click', function () {
        clean_dialog();
        let flight_direction = $(this).parent().parent().find('.leg_direction span').text();
        let enquire_form = $('#message_order_flight').clone();
        enquire_form.find('.message_box .message_content .message_header .flight_name').text(flight_direction);
        show_dialog(enquire_form);
    });
}

//-------------------------------------------------------- MESSAGE BOX EVENT LISTENERS --------------------------------------------------------------
function message_box_el() {
    $('.open_dialog').on('click', '.message_block .close_message', function () {
        clean_dialog();
    });

    $('.open_dialog').on('click', '.message_buttons .add_route_button', function () {
        let route = $(this).parent().parent().find('.message_inputs .additional_route').clone();
        $(this).parent().parent().find('.routes').append(route.attr('class', 'route'));
        activate_date_picker();
        route_events();
    });

    $('.open_dialog').on('click', '.message_block', function (e) {
        if ($(e.target).attr('class') === 'message_block') {
            clean_dialog();
        }
    })

    $('.message_block .ok_button, .message_block .close_message').on('click', '', function () {
        let window = $(this).parents('.message_block');
        $(window).fadeOut(400);
    });
}

// Remove opened dialog
function clean_dialog() {
    $('.open_dialog').children().remove();
}

//------------------------------------------------------------ SHOW DIALOG ---------------------------------------------------------------------------
function show_dialog(dialog) {
    $('.open_dialog').append(dialog.css('display', 'block'));

    $('form input').on('input', function () {
        $(this).removeClass('error');
        $(this).parent('div').find('.error_label').html("");
    });
}

//----------------------------------------------------------- MOBILE TOPLINE EFFECTS ----------------------------------------------------------------
function mobile_topline_fx() {
    $(window).scroll(function () {
        $('body').get(0).style.setProperty('--header_bg', 'rgba(0, 0, 0, ' + Math.min(.7, $(window).scrollTop() / 300) + ')');
    });
}

/**----------------------------------------------------------- DATEPICKER ---------------------------------------------------------------------------
 * https://www.daterangepicker.com/#options
 */
function activate_date_picker() {
    $('.datepicker-input').daterangepicker({
        "singleDatePicker": true,
        "timePicker": false,
        "timePicker24Hour": true,
        "minYear": 2022,
        "maxYear": 2030,
        "showDropdowns" : true,
        "locale": {
            "direction": "ltr",
            /*"format": "MM/DD/YYYY HH:mm",*/
            "format": "YYYY-MM-DD",
            "separator": " - ",
            "applyLabel": "Apply",
            "cancelLabel": "Cancel",
            "fromLabel": "From",
            "toLabel": "To",
            "customRangeLabel": "Custom",
            "daysOfWeek": [
                "Su",
                "Mo",
                "Tu",
                "We",
                "Th",
                "Fr",
                "Sa"
            ],
            "monthNames": [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ],
            "firstDay": 1
        },
    }, function(start, end, label) {
        console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
    });

    $('.datepicker-time').daterangepicker({
        timePicker: true,
        timePicker24Hour: false,
        singleDatePicker:true,
        timePickerIncrement: 1,
        timePickerSeconds: false,
        locale: {
            format: 'HH:mm'
        }
    }).on('show.daterangepicker', function (ev, picker) {
        picker.container.find(".calendar-table").hide();
        $('#new_form_modal').css('overflow', 'hidden');

        // Делаем окна fixed
        //var nHightInput = 110;              // Высота input + label + отступы
        //picker.container[0].offsetTop = $(this)[0].offsetTop + 51;
        //picker.container.css({"position": "fixed", "top" : $(this)[0].offsetTop + nHightInput});

    }).on('hide.daterangepicker', function(ev, picker) { $('#new_form_modal').css('overflow', 'auto'); });
}

//--------------------------------------------------------------- ROUTE EVENTS ----------------------------------------------------------------------
function route_events() {
    $('.input-route').on('input', function() {
        let text = $(this).val();
        let list = $(this).attr('list');
        let parent_div = $(this).parent('div');
        let drop_dic = $(parent_div).find('.dropdown-content');

        // Items event listener
        $(parent_div).find('#' + list).on('click', 'span.dropdown-a', function() {
            let parent_div = $(this).parents('.jets_input');
            let input = $(parent_div).find('input');
            $(input).val($(this).html());
        });

        // DropDown
        $(drop_dic).addClass("show");

        $.post("php_interface", {
            type : "airport_search",
            text : text
        },function(data) {
            if (data) {
                let content = JSON.parse(data);
                let html = "";
                if ($('#' + list).prop("tagName") === 'DATALIST') {
                    // Datalist legacy
                    $.each(content, function( index, value ) { html += "<option value='" + value + "'>"; });
                } else {
                    $.each(content, function( index, value ) { html += "<span class='dropdown-a'>"+ value +"</span>"; });
                }
                $(parent_div).find('#' + list).html(html);
            }
            else {
                return null;
            }
        });
    });

    $('input').on('focus', function() {
        if ($('.dropdown-content').hasClass("show")) { $('.dropdown-content').removeClass("show"); }
        reset_unappropriate_routes();
    });

    // Process routes inputs clicks
    $('.input-route').on('focus', function() {
        let parent_div = $(this).parent('div');
        let drop_dic = $(parent_div).find('.dropdown-content');
        $(drop_dic).addClass('show');
    });

    // Process TABs
    $('.input-route').on('keyup', function(e) {
        if (e.which == 9 || e.which == 13) {
            if ($('.dropdown-content').hasClass("show")) { $('.dropdown-content').removeClass("show"); }
            reset_unappropriate_routes();
        }
    });

    // Processing clicks on the body to hide droplists and check their values
    $('body').click(function (event) {
        if (!$(event.target).hasClass('input-route')) {
            if ($('.dropdown-content').hasClass("show")) { $('.dropdown-content').removeClass("show"); }
        }

        // Reset all unappropriate routes
        reset_unappropriate_routes();
    });

}

//------------------------------------------------------- RESET UNAPPROPRIATE ROUTES ----------------------------------------------------------------
function reset_unappropriate_routes() {
    $('.input-route').each(function () {
        let input = $(this);
        let parent_div = $(this).parent('div');
        let drop_dic = $(parent_div).find('.dropdown-content');
        if (!drop_dic.find('.dropdown-a').filter(function() {return $(this).text() == input.val();}).length) {
            input.val('');
        }
    });
}

//--------------------------------------------------------------- FORM EVENTS -----------------------------------------------------------------------
function form_events() {
    $('form input').on('input', function () {
        $(this).removeClass('error');
        $(this).parent('div').find('.error_label').html("");
    });

    $('.submit_form').on('click', function () {
        let button = this;
        let form = $(button).parents('form:first');
        let determinant = $(form).data("determinant");
        mail_send(button, determinant);
        $(button).prop('disabled', true);
        setTimeout(function(){ $(button).prop('disabled', false); }, 2000);
    });

    $('.open_dialog').on('click', '.order_a_call',function () {
        let button = this;
        let form = $(button).parents('form:first');
        let determinant = $(form).data("determinant");
        mail_send(this, determinant);
        $(button).prop('disabled', true);
        setTimeout(function(){ $(button).prop('disabled', false); }, 2000);
    });

    $('.cancel').on('click', function () {
        let button = this;
        let form = $(button).parents('form:first');
        let submit_block = $(form).find('.jets_submit_buttons');
        $(submit_block).find('button').each(function (index,el) {
            if (!$(el).hasClass("hidden")) { $(el).addClass("hidden"); } else { $(el).removeClass("hidden"); }
        });

        // Удаляем капчу
        $(submit_block).find('.g-recaptcha').remove();
        $(submit_block).find('.error_captcha').remove();
    });

}

//--------------------------------------------------------------- VALIDATE EMAIL --------------------------------------------------------------------
function validate_email(email) {
    let re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    return re.test(String(email).toLowerCase());
}

//------------------------------------------------------------------ MAIL SEND ----------------------------------------------------------------------
function mail_send(target, determinant = "") {
    let error = false;
    let form_data = [];
    let form = $(target).parents('form:first');
    let submit_block = $(form).find('.submit_button');

    $(form).find('input:not(input[type=button])').each(function(key, element) {
        $(element).removeClass('error');
        let target = $(element);
        let attr = target.attr("name");
        let message_block = $(element).parents('div').children('.error_label');

        //-------------------------------------------------------- field validation -----------------------------------------------------------------
        // EMAIL
        if (attr.toLowerCase() === "email") {
            if (validate_email($(element).val()) === false) {
                $(message_block).html('Введите верный Email');
                $(element).addClass('error');
                setTimeout(function(){  $(message_block).html(''); $(element).removeClass('error'); }, 6000);
                error = true;
            }
            else {
                if ($(element).prop('required')) {
                    if ( $(element).val() === '' || $(element).val() === undefined || $(element).val() === null ) {
                        $(element).addClass('error');
                        $(message_block).html('Enter a valid email');
                        error = true;
                        setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                    } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
                } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
            }
        }
        // PHONE
        else if (attr.toLowerCase() === "phone") {
            if ( $(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null ||  $(element).val().length <= 8) {
                $(message_block).html('Введите верный номер');
                $(element).addClass('error');
                error = true;
                setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
            }
            else if ($(element).val().charAt(0) !== "+") {
                $(message_block).html('Invalid country code');
                $(element).addClass('error');
                error = true;
                setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
            }
            else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // FROM
        else if (attr.toLowerCase() === "from") {
            if ($(element).prop('required')) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Выберите место отправления');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // TO
        else if (attr.toLowerCase() === "to") {
            if ($(element).prop('required')) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Выберите пункт назначения');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // DEPARTURE DATE
        else if (attr.toLowerCase() === "departure date") {
            if ($(element).prop('required')) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Pick a date');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // RETURN DATE
        else if (attr.toLowerCase() === "return date") {
            if ($(element).prop('required')) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Pick a date');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // FIRST NAME
        else if (attr.toLowerCase() === "first name") {
            if( $(element).prop('required') ) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Введите действительное имя');
                    setTimeout(function(){ $(message_block).html('');$(element).removeClass('error'); }, 6000);
                }
                // Проверка чисел в строке, если они есть то вернет true.
                else if (/(?=.*\d)(?=.*[a-z])/i.test($(element).val()) == true) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Введите действительное имя');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                }
                // Проверка на минимум 3 буквы
                else if ($(element).val().trim().length < 3) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Введите действительное имя');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                }
                else {
                    form_data.push({"type" : attr, "val" : $(element).val()});
                }
            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // LAST NAME
        else if (attr.toLowerCase() === "last name") {
            if( $(element).prop('required') ) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Введите действительную фамилию');
                    setTimeout(function(){ $(message_block).html('');$(element).removeClass('error'); }, 6000);
                }
                // Проверка чисел в строке, если они есть то вернет true.
                else if (/(?=.*\d)(?=.*[a-z])/i.test($(element).val()) == true) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Введите действительную фамилию');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                }
                // Проверка на минимум 3 буквы
                else if ($(element).val().trim().length < 3) {
                    $(element).addClass('error');
                    error = true;
                    $(message_block).html('Введите действительную фамилию');
                    setTimeout(function(){  $(message_block).html('');$(element).removeClass('error'); }, 6000);
                }
                else {
                    form_data.push({"type" : attr, "val" : $(element).val()});
                }
            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // PASSENGERS
        else if (attr.toLowerCase() === "passengers") {
            if( $(element).prop('required') ) {
                // Проверка числа пассажиров
                if (/[^0-9]/g.test($(element).val()) == true) {
                    $(element).addClass('error');
                    error = true;
                }
                else {
                    if (parseInt($(element).val()) < 0 || parseInt($(element).val()) > 99)
                    {
                        $(element).addClass('error');
                        error = true;
                        $(message_block).html('1-100');
                        setTimeout(function(){ $(message_block).html('');$(element).removeClass('error'); }, 6000);
                    } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
                }

            } else { form_data.push({"type" : attr, "val" : $(element).val()}); }
        }
        // OTHER
        else {
            if ($(element).prop('required')) {
                if ($(element).val().trim() === '' || $(element).val() === undefined || $(element).val() === null) {
                    $(element).addClass('error');
                    error = true;
                } else {
                    if (attr === undefined) { attr = "No name field"; }
                    form_data.push({"type" : attr, "val" : $(element).val()});
                }
            } else {
                if (attr === undefined) { attr = "No name field"; }
                form_data.push({"type" : attr, "val" : $(element).val()});
            }
        }
    });


    // Если нет ошибок отправляем
    if (error === false) {
        let post_message = {'action': 'mail', 'formData': JSON.stringify(form_data), 'recaptcha_response': $(form).find('.g-recaptcha-response').val()}
        let url_params = window.location.search.replace( '?', '');

        //-------------------------------------------------------------------------------------------------------------------------------------------
        // Используем determinant как уникальный id
        let form_id = $(form).data("determinant") + "_captcha";
        let captchablock = true;

        // Исключаем срабатывание капчи в модальных формах
        if ( $(form).data("determinant") !== 'modal_legs' && $(form).data("determinant") !== 'modal_book') {
            if ($(submit_block).find('#' + form_id).length === 0) {
                // Открываем кнопки Cancel/OK
                $(submit_block).find('button').each(function (index,el) {
                    if (!$(el).hasClass("hidden")) { $(el).addClass("hidden"); } else { $(el).removeClass("hidden"); }
                });

                // Создаем блок капчи
                //let form_parent_id = $(form).closest('.jet_search').attr('id');
                //$(submit_block).prepend('<div class="error_captcha"></div>' +
                 //'<div id="' + form_id + '" class="g-recaptcha main-form" data-callback="on_captcha_' + form_parent_id + '_' + form_id + '" data-sitekey="6LdJ5l8jAAAAAPKXxly5nFEUxsbbb2EueNWCRE5T"></div>');

                // Запускаем капчу
                //grecaptcha.render(document.getElementById(form_id), {'sitekey' : '6LdJ5l8jAAAAAPKXxly5nFEUxsbbb2EueNWCRE5T' });

                // Блокируем все input
                //$(form).find('input:not(input[type=button])').each(function(key, element) { $(element).prop( "disabled", true ); });
            }
            else {
                if ($(form).find('.g-recaptcha-response').val() === '') {
                    // Капча не заполнена, ошибка, ничего не отправляем
                    $(form).find('.error_captcha').html("Сaptcha not passed");
                    error = true;
                    setTimeout(function(){ $(form).find('.error_captcha').html(''); }, 6000);
                }
                else
                {
                    captchablock = false;
                }
            }
        }
        else {
            captchablock = false;
        }
        //-------------------------------------------------------------------------------------------------------------------------------------------
        // Повторно проверяем ошибку, т.к капча может ее вызывать, если ее не заполнят.
        if (error === false) {
            $(target).addClass("loading");
            $(target).text("processing...");
            $.post('/mailer?' + url_params, post_message ).done(function(data) {
                let response = JSON.parse(data);
                grecaptcha.reset(); // Ресет капчи
                $(target).removeClass("loading");
                $(target).text("Ok");
                if ( response.message === "Сообщение отправлено" ) {

                    // Метрика по отправке формы
                    /* legacy
                    if (determinant === 'legs') {
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'order',
                            'eAction': 'sendForm',
                            'eLabel': 'empty_legs',
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    } else if (determinant === 'order-header') {
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'order',
                            'eAction': 'sendForm',
                            'eLabel': "callback",
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    } else if (determinant === 'pdf') {
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'request',
                            'eAction': 'pdf_request',
                            'eLabel': 'content_send',
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    } else {
                        // При успешной отправке любой формы заявки на перелет выполнять код:
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'order',
                            'eAction': 'sendForm',
                            'eLabel': 'book_flight',
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    }*/

                    if (determinant === 'modal_legs') {
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'order',
                            'eAction': 'sendForm',
                            'eLabel': 'empty_legs',
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    }
                    // Из форм вверху и внизу страницы
                    else if (determinant === 'top_round_trip_table' || determinant === 'top_one_way_table' || determinant === 'top_multi_leg_table') {
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'order',
                            'eAction': 'sendForm',
                            'eLabel': 'book_flight',
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    }
                    // Из формы заказа самолёта
                    else if (determinant === 'modal_book') {
                        (dataLayer = window.dataLayer || []).push({
                            'eCategory': 'order',
                            'eAction': 'sendForm',
                            'eLabel': 'book_aircraft',
                            'eNI': false,
                            'event': 'GAEvent'
                        });
                    }

                    $('#message_info').fadeIn(400);
                    $(form).find('input').each(function(key, element) { $(element).val(""); });
                }
                else {
                    //$('#message_info').fadeIn(400);
                    $('#message_error').fadeIn(400);
                }
            }).fail(function() {
                //$('#message_info').fadeIn(400);
                $('#message_error').fadeIn(400);
            });
        }
    }
    else {
        // nothing
        // есть ошибки они будут подсвечены
    }
}

//---------------------------------------------------------- CAPTCHA EVENT LISTENERS ----------------------------------------------------------------
function on_captcha_top_form_top_round_trip_table_captcha() { $('#top_form .top_round_trip_table .button_ok').click(); }
function on_captcha_top_form_top_one_way_table_captcha() { $('#top_form .top_round_trip_table .button_ok').click(); }
function on_captcha_top_form_top_multi_leg_table_captcha() { $('#top_form .top_round_trip_table .button_ok').click(); }
function on_captcha_bottom_form_top_round_trip_table_captcha() { $('#bottom_form .top_round_trip_table .button_ok').click(); }
function on_captcha_bottom_form_top_one_way_table_captcha() { $('#bottom_form .top_round_trip_table .button_ok').click(); }
function on_captcha_bottom_form_top_multi_leg_table_captcha() { $('#bottom_form .top_round_trip_table .button_ok').click(); }

//----------------------------------------------------------------- BTN EVENTS -----------------------------------------------------------------------
function metrika() {

    // Tell header click event
    $('.contact_info .contact_phone').on("click",function() {
        //При клике по номеру телефона в шапке выполнять код:
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'phoneNumber',
            'eAction': 'click',
            'eLabel': $(this).text(), // передавать значение в зависимости от номера телефона, по которому совершен клик
            'eNI': false,
            'event': 'GAEvent'
        });
    })
    // Mobile Tell header click event
    $('.header_info .header_link.phone').on("click",function() {
        //При клике по номеру телефона в шапке выполнять код:
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'phoneNumber',
            'eAction': 'click',
            'eLabel': $(this).text(), // передавать значение в зависимости от номера телефона, по которому совершен клик
            'eNI': false,
            'event': 'GAEvent'
        });
    })
    //Клики по номерам в подвале в контактах
    $('.contact_number').on("click",function() {
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'phoneNumber',
            'eAction': 'click',
            'eLabel': $(this).text(), // передавать значение в зависимости от номера телефона, по которому совершен клик
            'eNI': false,
            'event': 'GAEvent'
        });
    });

    // Email header click event
    $('.header_info .header_link.letter').on("click",function() {
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'email',
            'eAction': 'click',
            'eLabel': $(this).text(), // передавать значение в зависимости от адреса электронной почты, по которому совершен клик
            'eNI': false,
            'event': 'GAEvent'
        });
    });

    // Email footer
    $('.contact_email').on("click",function() {
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'email',
            'eAction': 'click',
            'eLabel': $(this).text(), // передавать значение в зависимости от адреса электронной почты, по которому совершен клик
            'eNI': false,
            'event': 'GAEvent'
        });
    });

    // Клики по услугам на кнопку More
    $('button.more').on("click",function() {
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'order',
            'eAction': 'button_click',
            'eLabel': 'more_services',
            'eNI': false,
            'event': 'GAEvent'
        });
    });

/*
    Клики по услугам на кнопку More
    'eCategory': 'order',
        'eAction': 'button_click',
        'eLabel': 'more_services'

    Клики по номерам телефонов в том числе в подвале в контактах
    'eCategory': 'phoneNumber',
        'eAction': 'click',
        'eLabel': 'ХХХХХХХХХХ', // передавать значение в зависимости от номера телефона, по которому совершен клик

        Клики по адресам email в том числе в подвале в контактах
    'eCategory': 'email',
        'eAction': 'click',
        'eLabel': ''ХХХХХХХХХХ'', // передавать значение в зависимости от адреса электронной почты, по которому совершен клик
*/
    // Call me click event
    /*$('.header-callback').click(function() {
        //При клике на кнопку “Call Me” выполнять js-код:
        (dataLayer = window.dataLayer || []).push({
            'eCategory': 'callback',
            'eAction': 'click',
            'eLabel': 'callback_request',
            'eNI': false,
            'event': 'GAEvent'
        });
    });*/
}