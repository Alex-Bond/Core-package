<?php

if (isset($_GET ['error']))
    $error = $_GET ['error'];

switch (@$_GET['step']) {
    case '2':
        if (isset($_GET['stud']) && strlen($_GET['stud']) == 8) {
            $user = $db->Execute('select * from `users` where `studid` LIKE \'%' . $_GET ['stud'] . '%\' ');
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->RecordCount() == 0) {
                header('Location: /?do=lostpass&error=Немає+такого+студенського');
                die();
            } else {
                include './include/views/lostpass/step2.php';
                die();
            }
        }
        if (isset($_GET['id']) && strlen($_GET['id']) > 0) {
            $user = $db->Execute('select * from `users` where `id` = ' . $db->qstr($_GET ['id']) . ' ');
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->RecordCount() == 0) {
                header('Location: /?do=lostpass&error=Немає+такого+студента');
                die();
            } else {
                include './include/views/lostpass/step2.php';
                die();
            }
        }
        if (isset($_GET['login']) && strlen($_GET['login']) > 5) {
            $user = $db->Execute('select * from `users` where `login` = ' . $db->qstr($_GET ['login']) . ' ');
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->RecordCount() == 0) {
                header('Location: /?do=lostpass&error=Немає+такого+логіна');
                die();
            } else {
                include './include/views/lostpass/step2.php';
                die();
            }
        }
        if (isset($_GET['flush']) && strlen($_GET['flush']) == 6) {
            $user = $db->Execute('select * from `lostpass` where `key` = ' . $db->qstr($_GET ['flush']) . ' ');
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->RecordCount() == 0) {
                header('Location: /?do=lostpass&error=Немає+такого+ключа+збросу');
                die();
            } else {
                $_SESSION['lostpass']['hash'] = md5(sha1(date("y") . rand(0, time()) . 'site'));
                $_SESSION['lostpass']['user'] = $user->fields['user'];
                $_SESSION['lostpass']['flush'] = TRUE;
                $db->Execute('DELETE from `lostpass` where `key` = ' . $db->qstr($_GET ['flush']) . ' ');
                header('Location: /?do=lostpass&step=flush');
                die();
            }
        }
        header('Location: /?do=lostpass');
        break;

    case '3':
        if (isset($_POST['answer']) && strlen($_POST['answer']) > 5) {
            $user = $db->Execute('select * from `users` where `id`=' . $db->qstr($_POST['id']));
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->RecordCount() == 0) {
                die('HACK');
            } else {
                if ($user->fields['answer'] != md5($_POST['answer'])) {
                    header('Location: /?do=lostpass&step=2&id=' . urlencode($user->fields['id']) . '$&error=Невірна+відповідь.');
                    die();
                }
                $_SESSION['lostpass']['hash'] = md5(sha1(date("y") . rand(0, time()) . 'site'));
                $_SESSION['lostpass']['user'] = $user->fields['id'];
                include './include/views/lostpass/step3.php';
                die();
            }
        }
        header('Location: /?do=lostpass&step=2&id=' . urlencode($user->fileds['id']) . '$&error=Невірна+відповідь.');
        break;
    case 'flush':
        if (isset($_SESSION['lostpass']['hash']) && isset($_SESSION['lostpass']['user'])) {
            $user = $db->Execute('select * from `users` where `id`=' . $db->qstr($_SESSION['lostpass']['user']));
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->RecordCount() == 0) {
                die('HACK');
            }
            include './include/views/lostpass/step3.php';
        }
        break;
    case 'save':
        if (isset($_POST['pass']) && isset($_POST['hash']) && $_POST['hash'] == $_SESSION['lostpass']['hash']) {
            if (strlen($_POST['pass']) > 5) {
                $sqlAdd = '';
                if ($_SESSION['lostpass']['flush'] === TRUE)
                    $sqlAdd = ', `flush`=1 ';
                $user = $db->Execute('update `users` SET `pass`=' . $db->qstr(md5($_POST['pass'])) . $sqlAdd . ' WHERE `id`=' . $_SESSION['lostpass']['user'] . ' ');
                if (!$user)
                    die('error: ' . $db->ErrorMsg() . '<BR>');
                unset($_SESSION['lostpass']);
                header('Location: /?inform=Пароль+оновлений.');
                die();
            }
            header('Location: /?do=lostpass&error=Пароль+замалий.');
            die();
        }
        break;
    default:
        include './include/views/lostpass/index.php';
        die();
        break;
}


    