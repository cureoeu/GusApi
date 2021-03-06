<?php

require_once '../vendor/autoload.php';

use GusApi\GusApi;
use GusApi\ReportType;

session_start();

$gus = new GusApi("aaaaaabbbbbcccccdddd");

if (!isset($_SESSION['sid'])) {
    $_SESSION['sid'] = $gus->login();
}

if (isset($_GET['reset'])) {
    $_SESSION = [];
}

if (isset($_GET['captcha'])) {
    $image = fopen("captcha.jpeg",'w+');

    $captcha = $gus->getCaptcha($_SESSION['sid']);

    fwrite($image, base64_decode($captcha));
    fclose($image);
}

if (isset($_POST['captcha'])) {
    if ($gus->checkCaptcha($_SESSION['sid'], $_POST['captcha'])) {

        echo '<form action="" method="POST">';
        echo '<input type="text" name="nip" >';
        echo '<input type="submit" value="check">';
        echo '</form>';

    }
}

if (isset($_POST['nip'])) {
    $gusReport = $gus->getByNip($_SESSION['sid'], '5250010976');

    var_dump($gus->getFullData($_SESSION['sid'], $gusReport->getRegon(), ReportType::BASIC));
}


echo '<img src="captcha.jpeg?'.time().'">';
echo '<form action="" method="POST">';
echo '<input type="text" name="captcha" >';
echo '<input type="submit" value="check">';
echo '</form>';
