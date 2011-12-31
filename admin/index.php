<?php

//ini_set("session.gc_maxlifetime", 30);
session_name('core');
session_start();

include_once ("./include/adodb5/adodb.inc.php");
require_once ("./include/rediska/Rediska.php");

$db = &ADONewConnection('mysqli');
$db->Connect('localhost', 'root', '', 'core_admin');
//$db->query("SET NAMES utf8;");
//$db->debug = true;

$options = array(
    'namespace' => 'kkzcore_',
    'servers' => array(
        array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'persistent' => TRUE
        )
    ),
);

$rediska = new Rediska($options);

//$db->Execute('DELETE FROM `sessions` WHERE `time`<(NOW()-60);');
if ((isset($_GET['do']) && $_GET['do'] != "ajax") || !isset($_GET['do'])) {
    $db->Execute('DELETE FROM `lostpass` WHERE `exp`<(NOW()-30);');
    $db->Execute('DELETE FROM `oauth` WHERE `time`<(NOW()-5);');
}

$user_agent = $_SERVER['HTTP_USER_AGENT'];
if (stripos($user_agent, 'MSIE 6.0') !== false && stripos($user_agent, 'MSIE 8.0') === false && stripos($user_agent, 'MSIE 7.0') === false) {
    header("Location: /ie6/ie6.html");
}


require_once './include/helpers/splash.php';
$splash = new SplashHelper();


if (isset($_GET ['inform'])) $splash->addInfo($_GET ['inform']);
if (isset($_GET ['error'])) $splash->addError(0, $_GET ['error']);

$settings = array(
    'questions' => array(
        '1' => 'Дівоче прізвище матері?', '2' => 'Улюбленна книга?', '3' => 'Улюбленний фільм?', '4' => 'Ім\'я домашнього улюбленця?', '5' => 'Поштовий індекс?', '6' => 'Телефон батька?', '7' => 'Телефон матері?', '8' => 'Улюблений колір?', '9' => 'Пін-код мобільного телефону?',
    ), 'sites' => array(
        '1' => array(
            'url' => 'http://kkzlib.pp.ua/', 'name' => 'ККЗ Library'
        ), '2' => array(
            'url' => 'http://kkztest.pp.ua/', 'name' => 'ККЗ Tests'
        ), '3' => array(
            'url' => 'http://kkzfiles.pp.ua/', 'name' => 'ККЗ Files'
        ),
    )
);

require_once './include/helpers/auth.php';
$auth = new auth(isset($_SESSION['session']) ? $_SESSION['session'] : null);

switch (@$_GET['do']) {
    case 'register':
        include ('./include/controllers/register.php');
        break;
    case 'ajax':
        include ('./include/controllers/ajax.php');
        break;
    case 'lostpass':
        include ('./include/controllers/lostpass.php');
        break;
    case 'logout':
        $auth->isDeny();
        unset($_SESSION['session'], $_SESSION['user']);
        header('Location: /');
        die();
        break;
    case 'cabinet':
        $auth->isDeny();
        include ('./include/controllers/cabinet.php');
        break;
    default:
        include ('./include/controllers/index.php');
        break;
}