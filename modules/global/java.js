/**
 * Created by Arthur Matveyev on 03.07.2017.
 * Refactored on 03.07.2022
 */

let g_event_listeners = {};			// Global array of custom events handlers

$(function () {
    __secure_emails();
    if (__is_iframe() === true) {
        $('body').addClass('iframe');
    }
});

/**------------------------------------------------------------- GET INFO ---------------------------------------------------------------------------
 * Returns basic information based on the Basis2 configuration
 * @param {string} info_to_get - Codename of the information to get: site_url, plugins_url, view_url, host, cdn_host, cdn_url
 * @returns {string} - information based on the Basis2 configuration
 */
function __info(info_to_get = 'site_url') {
    let protocol = g_config['protocol'] || '';
    if (protocol == '') protocol = 'http://'; else protocol += '://';
    let host = g_config['site_host'];
    let cdn_host = g_config['cdn_host'] || g_config['site_host'];

    switch (info_to_get) {
        case 'site_url': {
            return protocol + host + '/';
        }
        case 'plugins_url': {
            return protocol + host + '/plugins/';
        }
        case 'view_url': {
            return protocol + host + '/view/';
        }
        case 'host': {
            return host;
        }
        case 'cdn_host': {
            return cdn_host;
        }
        case 'cdn_url': {
            return protocol + cdn_host + '/';
        }
    }

    return '';
}

/**-------------------------------------------------------------------- ON --------------------------------------------------------------------------
 * Registers an event handler
 * @param {string} event_name - Name of the event to handle
 * @param {function} fn - Function to launch when the event fires
 * @param {int} priority - Event handler priority. The smaller the value the more prioritized it is
 * @returns {boolean} - TRUE on success, FALSE otherwise
 */
function __on(event_name, fn, priority = 10) {
    if (__is_not_set(g_event_listeners)) {
        console.log('Unable to add event listener. No g_event_listeners specified');
        return false;
    }
    if (__is_not_set(g_event_listeners[event_name])) g_event_listeners[event_name] = [];
    if (__is_not_set(g_event_listeners[event_name][priority])) g_event_listeners[event_name][priority] = [];

    g_event_listeners[event_name][priority].push(fn);

    return true;
}

/**------------------------------------------------------------------ EVENT -------------------------------------------------------------------------
 * Fires the specified event and launches all the registered event handlers for it
 * @param {string} event_name - Name of the event to fire
 * @param {any} params - Parameters to pass to the event listeners
 */
function __event(event_name, params) {
    if (__is_not_set(g_event_listeners) || __is_not_set(g_event_listeners[event_name])) return;
    Object.keys(g_event_listeners[event_name]).forEach(key => {
        if (__is_not_set(g_event_listeners[event_name][key])) return;
        g_event_listeners[event_name][key].forEach(listener => {
            listener(params);
        });
    });
}

/**------------------------------------------------------------ PRELOAD IMAGE -----------------------------------------------------------------------
 * Preloads the specified image even if it is not used on the page, yet. Useful for images that must be placed immediately after some user action
 * (ex. mouse hover on some icon).
 * @param {string} image_url - URL of the image to preload
 */
function __preload_image(image_url) {
    let img = new Image();
    img.src = image_url;
}

/**-------------------------------------------------------------- IS EMAIL --------------------------------------------------------------------------
 * Checks if the specified string is an email
 * @param {string} string_to_check - String to check
 * @returns {boolean} - TRUE if the specified string is an email address, FALSE otherwise
 */
function __is_email(string_to_check) {
    let regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(string_to_check);
}

/**--------------------------------------------------------------- SEGMENT --------------------------------------------------------------------------
 * Returns the specified URL segment of the client request
 * @param {int} segment_index - Index of the segment
 * @returns {string} - The specified URL segment of the client request. Empty string if the segment doesn't exist
 */
function __segment(segment_index, override_url = null) {
    return window.location.pathname.split('/')[segment_index];
}

/**------------------------------------------------------------- SET COOKIE -------------------------------------------------------------------------
 * Sets a browser cookie
 * @param {string} name - Name of the cookie to set
 * @param {string} value - The value to set to the cookie
 * @param {int|null} expires - Cookie expiration date in seconds since the beginning of Unix epoch
 * @param {string|null} path - Site path to set cookie for. Ex: "/"
 * @param {string|null} domain - The domain to set cookie for.
 * @param {boolean} secure - TRUE to use secured cookies
 */
function __set_cookie(name, value, expires = null, path = '/', domain = null, secure = true) {
    if (expires) {
        let date = new Date();
        date.setTime(date.getTime() + expires * 1000);
        expires = date.toGMTString();
    } else {
        expires = 'Thu, 01 Jan 1970 00:00:00 GMT';
    }

    document.cookie = name + '=' + encodeURIComponent(value) + ((expires) ? '; expires=' + expires : '') + ((path) ? '; path=' + path : '') + ((domain) ? '; domain=' + domain : '') + ((secure) ? '; secure' : '' + ';samesite=None;secure');
}

/**------------------------------------------------------------- GET COOKIE -------------------------------------------------------------------------
 * Returns the value of the specified browser cookie
 * @param {string} name - Name of the cookie to get value of
 * @returns {string|null} - The value of the specified cookie. NULL if the cookie wasn't found
 */
function __get_cookie(name) {
    let cookie = ' ' + document.cookie;
    let search = ' ' + name + '=';
    let result = null;
    let offset = 0;
    let end = 0;
    if (cookie.length > 0) {
        offset = cookie.indexOf(search);
        if (offset != -1) {
            offset += search.length;
            end = cookie.indexOf(';', offset);
            if (end == -1) end = cookie.length;
            result = decodeURIComponent(cookie.substring(offset, end));
        }
    }

    return null;
}

/**------------------------------------------------------------- GET INTERFACE COOKIE ---------------------------------------------------------------
 * Returns the value of the specified interface cookie. Interface cookies used to group several values in a single cookie record
 * @param {string|null} name - Name of the option to get value of. NULL to get the whole group as an object
 * @param {string} group - Name of the group which contains the specified option
 * @returns {boolean|any|null} - The value of the specified interface cookie or the value of the whole group. NULL if nothing found
 */
function __get_interface_cookie(name, group) {
    let cookie_value = __get_cookie(group);
    if (cookie_value && (name == null || cookie_value.indexOf('"' + name + '"') != -1)) {
        let parsed_values = JSON.parse(cookie_value);
        if (name == null) return parsed_values; else return parsed_values[name];
    }

    return null;
}

//---------------------------------------------------------------- SECURE EMAILS --------------------------------------------------------------------
function __secure_emails() {
    $('.email').each(function () {
        $(this).html($(this).html().replace(/__/g, '@'));
        $(this).attr('href', $(this).attr('href').replace(/__/g, '@'));
    });
}

/**----------------------------------------------------------------- OPEN URL -----------------------------------------------------------------------
 * Loads the specified page
 * @param {string} url - URL of the page to open
 * @param {boolean} use_new_tab - To open the page on the new browser tab or to use the current one
 * @returns {WindowProxy}
 */
function __open_url(url, use_new_tab = false) {
    use_new_tab = use_new_tab || false;
    if (!use_new_tab) {
        window.location.href = url;
        // If there are any pages inside of an "frame", то "Window.open('url','_self')"
        // will be redirected to the same frame. The is why we use window.location.href = url
        // window.open(url,'_top'); - Doesn't work. Will be broken in like 3 usages.
    } else return window.open(url, '_blank');
}

/**---------------------------------------------------------------- TRANSLIT ------------------------------------------------------------------------
 * URL safe translit the specified text.
 * @param {string} text - Text to translit
 * @returns {string} - URL safe version of the transliterated text. Spaces and special symbols will be removed or replaced with '-'
 */
function __translit(text) {
    let ru_str = 'ЄІЃі№єѓАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯабвгдеёжзийклмнопрстуфхцчшщъыьэюя«»— %\\/|*:?<>"&\'.';
    let en_str = ['YE', 'I', 'G', 'i', '#', 'ye', 'g', 'A', 'B', 'V', 'G', 'D', 'E', 'YO', 'ZH', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'X', 'C', 'CH', 'SH', 'SHH', '\'', 'Y', '', 'E', 'YU', 'YA', 'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'x', 'c', 'ch', 'sh', 'shh', '', 'y', '', 'e', 'yu', 'ya', '', '', '-', '-', '', '', '', '', '', '', '', '', '', '', '-', '-', '-'];

    let tmp_str = '';
    for (let i = 0, l = text.length; i < l; i++) {
        let s = text.charAt(i), n = ru_str.indexOf(s);
        if (n >= 0) tmp_str += en_str[n]; else tmp_str += s;
    }

    return tmp_str;
}

//------------------------------------------------------------------- LANG --------------------------------------------------------------------------
function __lang() {
    switch (__segment(0)) {
        case 'ru' :
            return 'ru';
        case 'en' :
            return 'en';
        case 'de' :
            return 'de';
        default :
            return g_config['lang']['default'] || 'en';
    }
}

/**-------------------------------------------------------- (localization) --------------------------------------------------------------------------
 * Returns localized string for the specified message code/key based on the data from PHP
 * @param key - Code or "key" of the message to return
 * @returns {string} - Localized string based on the data from PHP
 */
function __(key) {
    return g_l10n[key] || key;
}

/**-------------------------------------------------------- INSERT TO POSITION ----------------------------------------------------------------------
 * Inserts an element into an array at a specific position.
 * @param {Array} array - The original array.
 * @param {*} element - The element to insert.
 * @param {number} position - The position where the element should be inserted.
 * @returns {Array} - The modified array with the new element inserted at the specified position.
 */
function __insert_to_position(array, element, position) {
  array.splice(position, 0, element);
  return array;
}

/**----------------------------------------------------------- GET URL PARAMETER --------------------------------------------------------------------
 * Returns the value of the specified parameter of the URL query (GET-parameter)
 * @param {string} param_name - Name of the GET-parameter to return value of
 * @param {string|null} override_url - URL to parse. NULL to use the current URL of the page
 * @returns {string|null} - the value of the parameter. NULL if the parameter wasn't found
 */
function __get_url_param(param_name, override_url = null) {
    let vars = {};
    let url = override_url || window.location.href;
    let parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });

    return typeof (vars[param_name]) == 'undefined' ? null : vars[param_name];
}

/**------------------------------------------------------- GET URL PARAMETERS STRING ----------------------------------------------------------------
 * Returns the URL query string
 * @param {string|null} override_url - URL to parse. NULL to use the current URL of the page
 * @returns {string} - The URL query string
 */
function __get_url_params_string(override_url = null) {
    let url = override_url || window.location.href;
    let url_blocks = url.split('?', 2);
    if (url_blocks.length == 2) return '?' + url_blocks[1];

    return '';
}

/**------------------------------------------------------- GET URL PARAMETERS ARRAY -----------------------------------------------------------------
 * Returns the URL query array
 * @param {string} param_name - Name of the GET-parameter to return value of
 * @param {string|null} override_url - URL to parse. NULL to use the current URL of the page
 * @returns {array} - The URL param's array - [null, 'Legacy outcome', 'Legacy income']
 * , - returns as null
 */
function __get_url_params_array(param_name, override_url = null) {
    let cleaned_params = [];
    let params = __get_url_param(param_name, override_url);
    if (params === null) return [];
    params = params.split(',');

    params.forEach(function (param) {
        if (param !== '') cleaned_params.push(decodeURI(param.replaceAll('%20', ' ')))
        else cleaned_params.push(null);
    })
    return cleaned_params;
}

/**------------------------------------------------------- SET HISTORY PUSH STATE -------------------------------------------------------------------
 * Returns the URL query array
 * @param {string} url - URL which will be added to location pathname
 */
function __set_history_push_state(url) {
    history.pushState(null, null, window.location.pathname + url);
}

/**------------------------------------------------------- REMOVE URL PARAM --------------------------------------------------------------------------
 * Remove any param from url (Including param_name[any operator])
 *
 * Regexp - /param_name\[.*?\]/; Backup - \[.*?\];
 *
 * @param {string} url - URL
 * @param {string} param_name - Param name
 * @return {string} - cleaned URL
 */
function __remove_url_param(url, param_name) {
    url = __set_url_param(url, param_name, null);

    if (!url.includes(param_name)) return url;

    // let regex = new RegExp(param_name + '\\[.*?\\]');
    //
    // do {
    //     let url_param_name = url.match(regex)[0];
    //     url = __set_url_param(url, url_param_name, null);
    // } while (url.includes(param_name))


    let params = __get_param_operators(url, param_name);

    params.forEach(function (param) {
        url = __set_url_param(url, param_name + param, null);
    })

    return url;
}

/**------------------------------------------------------- GET PARAM OPERATORS -----------------------------------------------------------------------
 * Return param operators
 * @param {string} url - URL
 * @param {string} param_name - Param name
 * @return {array} - param operators | ?test_param[not]=123&test_param2[lte]=123 - ['[not]', '[lte]']
 * @private
 */
function __get_param_operators(url, param_name) {
    let params = [];

    url = __set_url_param(url, param_name, null);

    // Возможно костыль
    if (url.includes('sort_by=' + param_name) || url.includes('sort_by=-' + param_name)) return params;
    if (!url.includes(param_name)) return params;

    let regex = new RegExp(param_name + '\\[.*?\\]');

    do {
        let url_param_name = url.match(regex)[0];
        url = __set_url_param(url, url_param_name, null);
        params.push(url_param_name.replace(param_name, ''));
    } while (url.includes(param_name));

    return params;
}

/**------------------------------------------------------------ SET URL PARAM -----------------------------------------------------------------------
 * Sets or updates the value of the specified URL query parameter.
 * @param {string} url - URL to set parameter to
 * @param {string} param_name - Name of the parameter to set
 * @param {?string} param_value - The value of the specified parameter
 * @returns {string} - The resulting URL-string with the specified parameter
 */
function __set_url_param(url, param_name, param_value) {
    // Check if we need just to add the parameter
    if (param_value !== null) {
        if (__get_url_params_string(url) === '') return url + '?' + param_name + '=' + param_value;
        if (__get_url_params_string(url) === '?') return url + param_name + '=' + param_value;
        if (__get_url_param(param_name, url) === null) return url + '&' + param_name + '=' + param_value;
    }

    // Update the parameter value and rebuild the URL query string
    let vars = {};
    let parts = url.replace(/[?&]+([^=&]+)[=]*([^&]*)/gi, function (m, key, value) {           // Was /[?&]+([^=&]+)=([^&]*)/gi
        vars[key] = value;
    });
    vars[param_name] = param_value;
    if (param_value === null) delete (vars[param_name]);
    let result = url.split('?', 2)[0];
    if (Object.keys(vars).length) result += '?';
    Object.keys(vars).forEach(function (item) {
        if (result.indexOf('?') !== (result.length - 1)) result += '&';
        result += item + '=' + vars[item];
    });

    return result;
}

/**----------------------------------------------------------------- LOG ----------------------------------------------------------------------------
 * Logs messages to the console only in debug environment (only if g_config['debug'] is 1)
 * @param {any} message - Message to log to the browser console
 */
function __log(message) {
    if (g_config !== undefined && g_config['debug'] === true) console.log(message);
}

/**------------------------------------------------------------- LOG CURRENT ------------------------------------------------------------------------
 * Logs all the values of the specified array or object right in the "execution" time (the default console.log calculates all the values of the
 * object in "view" time). So if you want to know what values were used during script run - use this func.
 * @param {any} obj - Object or array to get current values of
 */
function __log_current(obj) {
    let cache = [];
    console.log(JSON.stringify(obj, function (key, value) {
        if (typeof value === 'object' && value !== null) {
            if (cache.indexOf(value) !== -1) {
                // Duplicate reference found
                try {
                    // If this value does not reference a parent it can be deduced
                    return JSON.parse(JSON.stringify(value));
                } catch (error) {
                    // Discard key if value cannot be deduced
                    return;
                }
            }
            // Store value in our collection
            cache.push(value);
        }
        return value;
    }));
}

/**----------------------------------------------------------------- ERROR --------------------------------------------------------------------------
 * Logs the specified error message to the console
 * @param {string} message - Message to log as an error
 * @returns {boolean} - Always FALSE to use in constructions like `return __error('An error occured')`
 */
function __error(message) {
    console.log(message);
    return false;
}

/**--------------------------------------------------------------- IS IFRAME ------------------------------------------------------------------------
 * Checks if the current document is located in iFrame
 * @returns {boolean} - TRUE if the current document if shown in iFrame, FALSE otherwise
 */
function __is_iframe() {
    let is_framed = false;
    try {
        is_framed = window != window.top || document != top.document || self.location != top.location;
    } catch (e) {
        is_framed = true;
    }
    return is_framed;
}

/**------------------------------------------------------------- IS OUR FRAME -----------------------------------------------------------------------
 * Checks if the document is shown in iFrame of the page that has the same host name (if the iFrame is located in our site)
 * @returns {boolean} - TRUE if the current document if shown in iFrame of the page that has the same host name, FALSE otherwise
 */
function __is_our_iframe() {
    let is_our_iframe = false;
    try {
        is_our_iframe = window != window.top || document != top.document || self.location != top.location;
        if (is_our_iframe) is_our_iframe = window.parent.location.href.indexOf('//' + __info('host') + '/') != -1;
    } catch (e) {
        is_our_iframe = false;	// This is third-party iframe
    }

    return is_our_iframe;
}

//---------------------------------------------------------------- IS MOBILE ------------------------------------------------------------------------
function __is_mobile() {
    return $('body').hasClass('mobile');
}

//------------------------------------------------------------ GET BROWSER LANG ---------------------------------------------------------------------
function __get_browser_lang() {
    let language = window.navigator ? (window.navigator.language || window.navigator.systemLanguage || window.navigator.userLanguage) : null;
    if (language !== null) {
        language = language.substr(0, 2).toLowerCase();
    }
    return language;
}

//----------------------------------------------------------------- IS EDGE -------------------------------------------------------------------------
// Check whether it is an Edge browser
function __is_edge() {
    return /Edge\/\d./i.test(navigator.userAgent);
}

/**-------------------------------------------------------------- FORMAT DATE -----------------------------------------------------------------------
 * Converts string with date in MySQL format to the string with the date in the desired default application format, like: DD.MM.YYYY
 * @param {?string} mysql_date - Date in MySQL format YYYY-MM-DD. NULL will return an empty string
 * @returns {string} - Date in the desired default application format, like: DD.MM.YYYY
 */
function __format_date(mysql_date = '2022-03-04') {
    if (mysql_date === null) return '';
    let date = new Date(mysql_date);
    return ('0' + date.getDate()).slice(-2) + '.' + ('0' + (date.getMonth() + 1)).slice(-2) + '.' + date.getFullYear();
}

/**---------------------------------------------------------- FORMAT DATE MYSQL ---------------------------------------------------------------------
 * Converts JS Date object to the string in SQL format YYYY-MM-DD
 * @param {Date} date_object - Date object to convert
 * @returns {string} - Date in the SQL format YYYY-MM-DD
 */
function __format_date_mysql(date_object) {
    let d = new Date(date_object);
    return d.getFullYear() + '-' + ('0' + (d.getMonth() + 1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2);
}

/**-------------------------------------------------------------- PLURAL FORM -----------------------------------------------------------------------
 * Reuturns the right plural form for the specified Russian word
 * @param number - Number of objects for which the right plural form must be found
 * @param {string} one - Form of the word for 1, 21, 31... Like: (один) 'чебурек'
 * @param {string} two_to_four - Form of the word for 2, 32, 104... Like: (три) 'чебурека'
 * @param {string} five_to_nine_and_zero - Form of the word for 5, 37, 139... Like: (семь) 'чебуреков'
 * @returns {string}
 */
function __plural_form(number, one, two_to_four, five_to_nine_and_zero) {
    let remainder = number % 10;
    if ((number % 100) === 11 || (number % 100) === 12 || (number % 100) === 13 || (number % 100) === 14) return five_to_nine_and_zero;
    if (remainder === 0) return five_to_nine_and_zero;
    if (remainder === 1) return one;
    if (remainder > 1 && remainder < 5) return two_to_four;

    return five_to_nine_and_zero;
}

/**------------------------------------------------------------- SET CHAR AT ------------------------------------------------------------------------
 * Replaces a character in the string
 * @param str - String to set character in
 * @param index - Index of the character
 * @param char - A character to set
 * @returns {string} - The string with replaced character
 */
function __set_char_at(str, index, char) {
    if (index > str.length - 1) return str;
    return str.substr(0, index) + char + str.substr(index + 1);
}

//-------------------------------------------------------------- GET LOCALE -------------------------------------------------------------------------
function __get_locale() {
    if (navigator.languages && navigator.languages.length) return navigator.languages[0];
    return navigator.userLanguage || navigator.language || navigator.browserLanguage || 'en-US';
}

/**---------------------------------------------------------- CURRENCY FORMAT -----------------------------------------------------------------------
 * Returns the amount in the specified currency formatted according to the user locale
 * @param amount - Amount of money
 * @param currency - The currency codename, like: USD, RUB, EUR
 * @returns {string} - The amount in the specified currency formatted according to the user locale
 */
function __currency_format(amount, currency) {
    let formatterCurrency = new Intl.NumberFormat(__get_locale(), {
        style: 'currency', currency: currency, currencyDisplay: 'symbol'
    });

    return formatterCurrency.format(amount);
}

/**-------------------------------------------------------------- IS NOT SET ------------------------------------------------------------------------
 * Checks is the value not defined or it is a null
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the value is undefined or it is a null, FALSE otherwise
 */
function __is_not_set(value) {
    return typeof (value) === 'undefined' || value === null;
}

/**---------------------------------------------------------------- IS SET --------------------------------------------------------------------------
 * Checks if the value is defined and it is not null
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the value is defined and it is not a null, FALSE otherwise
 */
function __is_set(value) {
    return !(typeof (value) === 'undefined' || value === null);
}

/**------------------------------------------------------------- IS UNDEFINED -----------------------------------------------------------------------
 * Checks if the value is undefined
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the value is not defined, FALSE otherwise
 */
function __is_undefined(value) {
    return (typeof (value) === 'undefined');
}

/**-------------------------------------------------------------- IS DEFINED ------------------------------------------------------------------------
 * Checks if the value is defined
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the value is defined, FALSE otherwise
 */
function __is_defined(value) {
    return typeof (value) !== 'undefined';
}

/**-------------------------------------------------------------- IS STRING -------------------------------------------------------------------------
 * Checks if the specified value is a string
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the specified value is a string, FALSE otherwise
 */
function __is_string(value) {
    return typeof value === 'string' || value instanceof String;
}

/**------------------------------------------------------------- IS FUNCTION ------------------------------------------------------------------------
 * Checks if the specified value is a function
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the specified value is a function, FALSE otherwise
 */
function __is_function(value) {
    return typeof value === 'function' || value instanceof Function;
}

/**-------------------------------------------------------------- IS ARRAY --------------------------------------------------------------------------
 * Checks if the specified value is an array
 * @param {any} value - The value to check
 * @returns {boolean} - TRUE if the specified value is an array, FALSE otherwise
 */
function __is_array(value) {
    return Array.isArray(value);
}

/**-------------------------------------------------------------- HTML SAVE -------------------------------------------------------------------------
 * Returns clear string that can be safely shown like text in HTML
 * @param {?string} string - Initial string to be escaped
 * @returns {string} - Safe escaped string
 */
function __html_safe(string) {
    if (__is_not_set(string)) return '';
    if ((typeof string) == 'number') return String(string);

    return string.replaceAll('"', '&quot;');
}

//-------------------------------------------------------------- GET HTML TEXT ----------------------------------------------------------------------
function __get_html_text(html)  {
    let span = document.createElement('span');
    span.innerHTML = html;
    return (span.innerText);
}

//------------------------------------------------------------ ESCAPE DOUBLE QUOTES -----------------------------------------------------------------
function __escape_double_quotes(string) {
    if (__is_not_set(string)) return '';
    if ((typeof string) == 'number') return String(string);

    return string.replaceAll('"', '\\"');
}

/**------------------------------------------------------------- FILL TEMPLATE ----------------------------------------------------------------------
 * Replaces all variables in template with data. Template example: '/api/{tenant}/users/{id}'. Data example: {'id' => 245, 'tenant' => 'my_company'}
 * @param {string} template - Template with variable placeholders like '/api/{tenant}/users/{id}'
 * @param {object} data - Data to place instead of variable placeholders. Ex: {'id' => 245, 'tenant' => 'my_company'}
 * @param {string} vars_open_tag - Symbols used to mark start of variable names in template. Ex: '{', '{{', '%%'...
 * @param {string} vars_close_tag - Symbols used to mark end of variable names in template. Ex: '}', '}}', '%%'...
 * @returns {string} - string with replaced variables
 */
function __fill_template(template, data, vars_open_tag = '{', vars_close_tag = '}') {
    Object.keys(data).forEach(key => {
        template = template.replace(vars_open_tag + key + vars_close_tag, data[key]);
    });

    return template;
}

/**----------------------------------------------------------- GET ROLE WEIGHT ----------------------------------------------------------------------
 * Return weight by name of role
 * @param {string} role
 * @returns {number}
 */
function __get_role_weight(role) {
    switch (role) {
        case 'super_admin':
            return 5;
        case 'admin':
            return 4;
        case 'editor':
            return 3;
        case 'viewer':
            return 2;
        case 'guest':
            return 1;
        default:
            return 1;
    }
}

/**----------------------------------------------------------- GET ARRAY FIRST ----------------------------------------------------------------------
 * If exists returns first array element. Else return null
 * @param {array} array
 * @returns {mixed}
 */
function __get_array_first(array) {
    return (array.length === 0) ? null : array[0];
}

/**----------------------------------------------------------- CAPITALIZE FIRST ---------------------------------------------------------------------
 * Make the first charter of string uppercase.
 * @param string
 * @return {string}
 * @private
 */
function __capitalize_first(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

//--------------------------------------------------------------- CHAMOMILE -------------------------------------------------------------------------
function __chamomile(html_id = null) {
	return '' +
        '<div ' + (html_id !== null ? ('id="' + html_id + '" ') : '') + 'class="chamomile">' +
            '<div class="loader">' +
                '<div class="arc"></div>' +
            '</div>' +
        '</div>';
}

//-------------------------------------------------------------- FILE EXTENSION ---------------------------------------------------------------------
function __file_extension(file_path) {
    let extension = /[^.]+$/.exec(file_path);
    if (!extension) return '';

    return extension[0].split('?')[0];     // Avoid query string for URLs
}

//-------------------------------------------------------------- FORMAT AMOUNT ----------------------------------------------------------------------
function __format_amount(amount, decimal_count = 2, decimal_delimiter = '.', thousands_delimiter = ' ') {
    try {
        decimal_count = Math.abs(decimal_count);
        decimal_count = isNaN(decimal_count) ? 2 : decimal_count;

        const negative_sign = amount < 0 ? '-' : '';

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimal_count)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negative_sign +
            (j ? i.substr(0, j) + thousands_delimiter : '') +
            i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + thousands_delimiter) +
            (decimal_count ? decimal_delimiter + Math.abs(amount - i).toFixed(decimal_count).slice(2) : '');
    } catch (e) {
        console.warn(e)
    }
}

/**-------------------------------------------------------------- CONVERT DATE TO MYSQL FORMAT -------------------------------------------------------
 * Convert 17.02.2023 to 2023-02-17
 * @param {string} date - Date in dd.mm.yyyy format
 * @return {string|null} - String date is valid. Null - date is not valid
 */
function __convert_date_to_mysql_format(date) {
    const parts = date.split('.');
    const formatted_date = `${parts[2]}-${parts[1]}-${parts[0]}`;
    const datetime = new Date(formatted_date);

    if (isNaN(datetime.getTime())) return null;
    return formatted_date;
}

/**-------------------------------------------------------------- CONVERT DATE TO MYSQL FORMAT -------------------------------------------------------
 * Convert 2023-02-17 to 17.02.2023
 * @param {string} date - Date in yyyy-mm-dd format
 * @return {string|null} - String date is valid. Null - date is not valid
 */
function __convert_date_to_input_format(date) {
    const parts = date.split('-');
    const formatted_date = `${parts[2]}.${parts[1]}.${parts[0]}`;
    const datetime = new Date(date);

    if (isNaN(datetime.getTime())) return null;
    return formatted_date;
}

//-------------------------------------------------------------- GET FILE ICON ----------------------------------------------------------------------
function __get_file_icon(file_name) {
    let file_extension = __file_extension(file_name)
    switch (file_extension) {
        case 'pdf':
            return 'fa-file-pdf';

        case 'csv':
        case 'xlsx':
        case 'xls':
            return 'fa-file-excel';

        case 'doc':
        case 'docx':
        case 'dot':
            return 'fa-file-word';

        case 'pptx':
        case 'ppt':
        case 'pptm':
            return 'fa-file-powerpoint';

        case 'jpeg':
        case 'png':
        case 'webp':
        case 'jpg':
        case 'raw':
        case 'gif':
            return 'fa-file-image';

        case 'mp4':
        case 'mov':
        case 'wmv':
        case 'avi':
        case 'flv':
        case 'webm':
        case 'f4v':
            return 'fa-file-video';

        default:
            return 'fa-file-lines'

    }
}