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

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // Prevent cURL from verifying SSL certificate
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); // Script should fail silently on error
    curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE); // Use cookies
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // Follow Location: headers
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Reutrning transfer as a string
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); // Setting cookiefile
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie); // Setting cookiejar
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent); // Setting useragent
    curl_setopt($ch, CURLOPT_URL, $url); // Setting URL to POST

    if (!is_null($useragent)) {
        curl_setopt($ch, CURLOPT_POST, TRUE); // Setting method as POST
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postArray));
    }
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

if ($freeLearningPage = c(PACKT_URL_FREE_LEARNING, $loginData)) {
    $successString = 'Packt.user = {"uid":';
    if (strpos($freeLearningPage, $successString)) {
        preg_match('/\/freelearning-claim([^"]*)/ims', $freeLearningPage, $matches);
        preg_match('/<h2>(.*?)<\/h2>/ims', $freeLearningPage, $title);


        echo '<pre>';
        print_r(c(PACKT_URL . $matches[0]));
        echo '</pre>';
    } else {
        return FALSE;
    }
} else {
    echo 'Blad logowania';
}
