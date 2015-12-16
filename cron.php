<?php

define('LOGIN', '');
define('PASSWORD', '');
define('PACKT_URL', 'https://www.packtpub.com/');
define('PACKT_URL_FREE_LEARNING', PACKT_URL . 'packt/offers/free-learning');

$loginData = array(
    'email' => LOGIN,
    'password' => PASSWORD,
    'op' => 'Login',
    'form_build_id' => '',
    'form_id' => 'packt_user_login_form',
);

function c($url, $postArray = null) {
    $cookie = __DIR__ . '/cookie.txt';
    $ch = curl_init();
    $useragent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6";

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_URL, $url);

    if (!is_null($postArray)) {
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

if ($freeLearningPage = c(PACKT_URL_FREE_LEARNING, $loginData)) {
    $successString = '"mail":"' . LOGIN . '"';
    if (strpos($freeLearningPage, $successString)) {
        preg_match('/\/freelearning-claim([^"]*)/ims', $freeLearningPage, $matches);
        preg_match('/<h2>(.*?)<\/h2>/ims', $freeLearningPage, $title);
        preg_match('/<div class="dotd-title">.*?<\/div>.*?<div>(.*?)<\/div>/ims', $freeLearningPage, $description);

        c(PACKT_URL . trim($matches[0], '/'));

        $emailTitle = trim($title[1]);
        $emailBody = trim($title[1]) . "\n\n" . trim($description[1]);

        $x = mail(LOGIN, $emailTitle, $emailBody);
    } else {
        return FALSE;
    }
} else {
    echo 'Blad logowania';
}
