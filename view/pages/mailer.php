<?php if (!defined('B2')) exit(0);

/**
 * @package Basis2
 * @author: Arthur Matveyev <info@art07.ru>
 * @copyright 2014-2022 ООО "Студия Артура Матвеева" (Arthur Matveyev Studio LLC)
 * @date: 30.09.2020
 * @version: 1.0
 *
 * Mailer PHP
 */
//__use('mailbox');
if ( $_POST['action'] === "mail" ) send($_POST);
    else { include_once("404.php"); return null; }

/** ------------------------------------------------------------ SEND -------------------------------------------------------------------------------
 * @param array $data
 * @param ?string $captcha_code
 * @return string JSON
 */
function send(array $data, string $captcha_code = null) : string {
    global $g_config;
    $mail_text = "";
    $user_email = "-";
    $result = [];
    $result['error'] = "";
    $captcha = true;	// По умолчанию капча пропускает, сделано для того чтобы формы без капчи проходили.

    // if (count($data) > 0) {
    //     /** ------------------------------------------------------ VALIDATION ------------------------------------------------------------------- **/
    //     foreach ($data as $input) {

    //         // Проверка Email
    //         if ($input->type === 'email') {
    //         	if ($input->val !== '') {
    //             	if (!filter_var($input->val, FILTER_VALIDATE_EMAIL)) { $result['error'] = "Email is not valid"; }
    //             	$mail_text .= '<p><b>Email</b> : ' . $input->val . '</p>';
    //             	$user_email = $input->val;
	// 			}
    //         }

    //         // Проверка на анти-спам ловушку.
    //         else if ($input->type === "trap" && trim($input->val) != "") { return false; }

    //         else {
    //             // Любые другие input
    //             if ($input->type !== '' && trim($input->val) != '') {
    //             	if ($input->type === 'name') {
	// 					$mail_text .= '<p><b>Имя</b> : ' . $input->val . '</p>';
    //             	}
	// 				else if ($input->type === 'tel') {
	// 					$mail_text .= '<p><b>Телефон</b> : ' . $input->val . '</p>';
	// 				}
	// 				else if ($input->type === 'message') {
	// 					$mail_text .= '<p><b>Сообщение</b> : ' . $input->val . '</p>';
	// 				}
	// 				else {
    //                 	$mail_text .= '<p><b>' . $input->type . "</b> : " . $input->val . "</p>";
	// 				}
    //             }
    //         }
    //     }


	// 	/** ------------------------------------------------ Google recaptcha v 2.0 ------------------------------------------------------------- **/
    //     /*
    //     if (!empty($captcha_code))
    //     {
	// 		$google_url = "https://www.google.com/recaptcha/api/siteverify";
	// 		$ip = $_SERVER['REMOTE_ADDR'];
	// 		$url = $google_url . "?secret=" . $g_config['recaptcha']['secret_key'] . "&response=" . $captcha_code . "&remoteip=" . $ip;
	// 		$res = site_verify($url);
	// 		$res = json_decode($res, true);

	// 		// Проверка пройдена
	// 		if ($res['success']) { $captcha = true; }
	// 		// Проверка не пройдена
	// 		else { $captcha = false; }
	// 	}
	// 	else { $captcha = false; }
    //     */

	// 	/** ----------------------------------------------------- Email Verifier --------------------------------------------------------------------
	// 	 *  Doc
	// 	 *  https://hunter.io/api-documentation/v2
	// 	 * 	Example
	// 	 * 	https://api.hunter.io/v2/email-verifier?email=patrick@stripe.com&api_key=6a8e93622490725ca29bd1602f21c03eb13cb01d
	// 	 *
	// 	 * "deliverable": the email verification is successful and the email address is valid.
	// 	 * "undeliverable": the email address is not valid.
	// 	 * "risky": the verification can't be validated.
	// 	*/

	// 	/*
	// 	$mail_validator_api_key = "6a8e93622490725ca29bd1602f21c03eb13cb01d";
	// 	$mail_validator_url = "https://api.hunter.io/v2/email-verifier?email=" . $user_email . "&api_key=" . $mail_validator_api_key;
	// 	$hunter_response_json = site_verify($mail_validator_url);
	// 	if ($hunter_response_json) { $hunter_response = json_decode($hunter_response_json, true); } else { $hunter_response = null; }
	// 	$hunter_email_is_valid = true;		// По умолчанию пропускаем все email

	// 	if (is_array($hunter_response))
	// 	{
	// 		// risky пропускаем, undeliverable не
	// 		if ($hunter_response['data']['result'] === 'undeliverable') { $hunter_email_is_valid = false; }
	// 		$mail_text .= "<p><b>Email validity</b> - " . $hunter_response['data']['score'] . " (" . $hunter_response['data']['result'] . ") </p>";
	// 	}
	// 	*/

    //     // Если есть текст письма и куда отправлять и пройдена капча
    //     //$mailbox = new mailbox();
    //     //$spam_block = $mailbox->one_minute_block();
    //     //$spam_ip_block = $mailbox->ip_period_block();

	// 	// Если все проверки пройдены
    //    /* if (is_array($g_config['email']['from_form_to']) && trim($mail_text) != '' && $spam_block === false && $spam_ip_block === false
    //     	&& $captcha === true)*/

	//   	if (is_array($g_config['email']['from_form_to']) && trim($mail_text) != '')
    //     {
    //         /** --------------------------------------------------  Сбор UTM меток -------------------------------------------------------------------
    //          *  utm_source — источник перехода;
    //          *  utm_medium — тип трафика;
    //          *  utm_campaign — название рекламной кампании;
    //          *  utm_content — дополнительная информация, которая помогает различать объявления;
    //          *  utm_term — ключевая фраза.
    //          */
    //         /*
    //         if (isset($_GET['utm_source']) || isset($_GET['utm_medium']) || isset($_GET['utm_campaign']) ||
    //             isset($_GET['utm_content']) || isset($_GET['utm_term'])) {

    //             if (isset($_GET['utm_source']) || $_GET['utm_source'] != null || $_GET['utm_source'] != '') {
    //                 $mail_text .= '<p><b>utm_source(источник перехода)</b> - ' . htmlspecialchars($_GET['utm_source']) . "</p>";
    //             } else {
    //                 $mail_text .= '<p><b>utm_source(источник перехода)</b> - отсутствует</p>';
    //             }

    //             if (isset($_GET['utm_medium']) || $_GET['utm_medium'] != null || $_GET['utm_medium'] != '') {
    //                 $mail_text .= '<p><b>utm_medium(тип трафика)</b> - ' . htmlspecialchars($_GET['utm_medium']) . "</p>";
    //             } else {
    //                 $mail_text .= '<p><b>utm_medium(тип трафика)</b> - отсутствует</p>';
    //             }

    //             if (isset($_GET['utm_campaign']) || $_GET['utm_campaign'] != null || $_GET['utm_campaign'] != '') {
    //                 $mail_text .= '<p><b>utm_campaign(название рекламной кампании)</b> - ' . htmlspecialchars($_GET['utm_campaign']) . "</p>";
    //             } else {
    //                 $mail_text .= '<p><b>utm_campaign(название рекламной кампании)</b> - отсутствует</p>';
    //             }

    //             if (isset($_GET['utm_content']) || $_GET['utm_content'] != null || $_GET['utm_content'] != '') {
    //                 $mail_text .= '<p><b>utm_content(дополнительная информация, которая помогает различать объявления)</b> - ' . htmlspecialchars($_GET['utm_content']) . "</p>";
    //             } else {
    //                 $mail_text .= '<p><b>utm_content(дополнительная информация, которая помогает различать объявления)</b> - отсутствует</p>';
    //             }

    //             if (isset($_GET['utm_term']) ||$_GET['utm_term'] != null || $_GET['utm_term'] != '') {
    //                 $mail_text .= '<p><b>utm_term(ключевая фраза)</b> - ' . htmlspecialchars($_GET['utm_term']) . "</p>";
    //             } else {
    //                 $mail_text .= '<p><b>utm_term(ключевая фраза)</b> - отсутствует</p>';
    //             }
    //         } else {
    //             $mail_text .= '<p><b>Метки UTM</b> - отсутствуют</p>';
    //         }
    //         */

    //         /** -------------------------------------------------------- GET ClientID  ---------------------------------------------------------------
    //          * ClientID — это анонимный идентификатор, который Яндекс.Метрика автоматически присваивает каждому уникальному посетителю сайта
    //          * Пример значения куки:
    //          * _ga=GA1.3.475128143.1522318100
    //          * Значение _ym_uid состоит из не более чем 20 знаков, причем первые 10 знаков — это дата и время первого посещения сайта в формате UNIX.
    //          * То есть: 1528651862 — это 10.06.2018, 20:31:02
    //          * 599277088 — а эта часть, скорее всего, просто рандомное число.
    //          */
    //         /*if (isset($_COOKIE['_ga'])) {
    //             $gaUserId = preg_replace("/^.+\.(.+?\..+?)$/", "\\1", @$_COOKIE['_ga']);
    //             $mail_text .= '<p><b>Clientid</b> - ' . $gaUserId . '</p>';
    //             $mail_text .= '<p><b>Clientid(full)</b> - ' . $_COOKIE['_ga'] . '</p>';
    //         } else {
    //             $mail_text .= '<p><b>Clientid</b> - отсутствует</p>';
    //         }*/

			// Отправка
            foreach ($g_config['email']['from_form_to'] as $email) {
                $to = $email;
                $charset = "utf-8";
                $headers = "Content-type: text/html; charset=$charset\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Date: " . date('D, d M Y h:i:s O') . "\r\n";
                $subject = "New post message";
                $mail_text = implode(',', $data);
				if ($result['error'] === '') {
					if (!__mail($to, $g_config['email']['signature'], $mail_text, '', false, $g_config['email']['port'])) {
						$result['error'] = "Ошибка отправки сообщения. Пожалуйста свяжитесь с нами по телефону";
					}

					$result['message'] = "Сообщение отправлено";
				}
            }
            header('Location:/home');
        //}
        // else {
        //     $result['error'] = "Ошибка отправки сообщения. Нет сообщения или не указан адресат";
        // }

		return json_encode($result, JSON_UNESCAPED_UNICODE);
        // ------------------------------------------ Сохраняем в БД через плагин mailbox -----------------------------------------------------------
        /*
        if ($spam_block === false && $spam_ip_block === false) {
        	$mail_text .= "<p><b>captcha</b> - ".($captcha ? 'true' : 'false')."</p>";
            $mailbox->add($user_email, $g_config['email']['signature'], $mail_text);
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }  else {
            $result['error'] = "Защита от атаки";
            return json_encode($result, JSON_UNESCAPED_UNICODE);
        }
        */
    // }
    // // Если данных нет
    // else {
    //     $result['error'] = "Ошибка отправки сообщения. Пожалуйста свяжитесь с нами по телефону";
    //     return json_encode($result, JSON_UNESCAPED_UNICODE);
    // }
}

/** ----------------------------------------------------------------- SITE VERIFY -------------------------------------------------------------------
 * В PHP должно быть активировано расширение CURL
 * @param $url
 * @return bool|string
 */
function site_verify($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36");
	$curlData = curl_exec($curl);
	curl_close($curl);
	return $curlData;
}


