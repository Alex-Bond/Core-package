<?php

$f_username = readPostVar('username');
if (get_magic_quotes_gpc())
    $f_username = stripslashes($f_username);
$f_password = readPostVar('password');
if (get_magic_quotes_gpc())
    $f_password = stripslashes($f_password);

if (!$_GET['key']) {
    if (signinUser($f_username, $f_password, isset($_POST["bguest"]))) {

        $page_title = $lngstr['page_title_panel'];
        include_once($DOCUMENT_PAGES . "home.inc.php");
    } else {
        $page_title = $lngstr['page_title_signin'];
        $input_err_msg = $lngstr['err_signin_incorrect'];
//include_once($DOCUMENT_PAGES."signin-1.inc.php");
    }
} else {
    if (signinUserById($_GET['key'])) {

        $page_title = $lngstr['page_title_panel'];
        include_once($DOCUMENT_PAGES . "home.inc.php");
    } else {
        $page_title = $lngstr['page_title_signin'];
        $input_err_msg = $lngstr['err_signin_incorrect'];
//include_once($DOCUMENT_PAGES."signin-1.inc.php");
    }
}
?>
