<?php

if (isset($_GET ['error']))
    $error = $_GET ['error'];

switch (@$_GET['p']) {
    case 'pass':
        if (isset($_POST['old_pass']) && isset($_POST['new_pass']) && strlen($_POST['new_pass']) > 5) {
            $user = $db->Execute('select * from `users` where `id`=' . $db->qstr($_SESSION['user']['id']));
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->fields['pass'] != md5($_POST['old_pass'])) {
                $splash->addError(0, 'Старий пароль не правильний.');
            } else {
                $user = $db->Execute('update `users` SET `pass`=' . $db->qstr(md5($_POST['new_pass'])) . ' WHERE `id`=' . $_SESSION['user']['id']);
                if (!$user)
                    die('error: ' . $db->ErrorMsg() . '<BR>');
                header('Location: /?do=cabinet&p=pass&inform=Пароль+оновлений.');
            }
        }
        include './include/views/cabinet/password.php';
        break;
    case 'question':
        if (isset($_POST['old_pass']) && isset($_POST['question']) && isset($_POST['answer']) && strlen($_POST['answer']) > 2) {
            $user = $db->Execute('select * from `users` where `id`=' . $db->qstr($_SESSION['user']['id']));
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->fields['pass'] != md5($_POST['old_pass'])) {
                $splash->addError(0, 'Старий пароль не правильний.');
            } else {
                $user = $db->Execute('update `users` SET `answer`=' . $db->qstr(md5($_POST['answer'])) . ', `question`=' . $db->qstr($_POST['question']) . ', `flush`=0 WHERE `id`=' . $_SESSION['user']['id']);
                if (!$user)
                    die('error: ' . $db->ErrorMsg() . '<BR>');
                header('Location: /?do=cabinet&p=question&inform=Питання+оновлене.');
            }
        }
        include './include/views/cabinet/question.php';
        break;
    case 'email':
        if (isset($_POST['old_pass']) && isset($_POST['email']) && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_POST['email'])) {
            $user = $db->Execute('select * from `users` where `id`=' . $db->qstr($_SESSION['user']['id']));
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->fields['pass'] != md5($_POST['old_pass'])) {
                $splash->addError(0, 'Старий пароль не правильний.');
            } else {
                $email = $db->Execute('select * from `users` where `email`=' . $db->qstr($_POST['email']) . ' AND `id`!=' . $_SESSION['user']['id']);
                if (!$email)
                    die('error: ' . $db->ErrorMsg() . '<BR>');
                if ($email->RecordCount() > 0) {
                    $splash->addError(0, 'Email вже зареєстрований у системі.');
                } else {
                    $user = $db->Execute('update `users` SET `email`=' . $db->qstr($_POST['email']) . ' WHERE `id`=' . $_SESSION['user']['id']);
                    if (!$user)
                        die('error: ' . $db->ErrorMsg() . '<BR>');
                    $splash->addInfo('Email оновлений.');
                }
            }
        }
        include './include/views/cabinet/email.php';
        break;
    case 'update_email_question':
        if (isset($_POST['old_pass']) && isset($_POST['email']) && isset($_POST['question']) && isset($_POST['answer']) && strlen($_POST['old_pass']) > 5 && strlen($_POST['answer']) > 2 && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_POST['email'])) {
            $user = $db->Execute('select * from `users` where `id`=' . $db->qstr($_SESSION['user']['id']));
            if (!$user)
                die('error: ' . $db->ErrorMsg() . '<BR>');
            if ($user->fields['pass'] != md5($_POST['old_pass'])) {
                $splash->addError(0, 'Старий пароль не правильний.');
            } else {
                $email = $db->Execute('select * from `users` where `email`=' . $db->qstr($_POST['email']) . ' AND `id`!=' . $_SESSION['user']['id']);
                if (!$email)
                    die('error: ' . $db->ErrorMsg() . '<BR>');
                if ($email->RecordCount() > 0) {
                    $splash->addError(0, 'Email вже зареєстрований у системі.');
                } else {
                    $user = $db->Execute('update `users` SET `email`=' . $db->qstr($_POST['email']) . ', `question`=' . $db->qstr($_POST['question']) . ', `answer`=' . $db->qstr(md5($_POST['answer'])) . ' WHERE `id`=' . $_SESSION['user']['id']);
                    if (!$user)
                        die('error: ' . $db->ErrorMsg() . '<BR>');
                    $_SESSION['user']['email'] = $_POST['email'];
                    $_SESSION['user']['answer'] = md5($_POST['answer']);
                    $_SESSION['user']['question'] = $_POST['question'];
                    session_write_close();
                    header('Location: /?do=cabinet&inform=Дані+оновлені.');
                    die();
                }
            }
        }
        include './include/views/cabinet/update_email_question.php';
        break;
    case 'go':
        if (isset($_GET['site']) && strlen($_GET['site']) > 0) {
            if ($_GET['site'] != 'admin') {
                $return = md5(sha1(date("y") . rand(0, time()) . 'site'));
                $sql = "insert into `oauth` (`ses`,`user`) values ('" . $return . "','" . $_SESSION['user']['id'] . "');";
                $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $_SESSION['user']['id'] . ",'login','success'," . $db->qstr($_GET['site']) . ");";
                if ($db->Execute($sql2) === false OR $db->Execute($sql) === false)
                    die('error: ' . $db->ErrorMsg());
            }
            if ($_GET['site'] == 'admin') {
                if ($_SESSION['user']['sys_g_admin'] == 1)
                    header('Location: /admin/');
                die();
            } else {
                header('Location: ' . $settings['sites'][$_GET['site']]['url'] . '?key=' . $return);
                die();
            }
        }
    default:
        if (strlen($_SESSION['user']['email']) == 0 OR strlen($_SESSION['user']['answer']) != 32 OR strlen($_SESSION['user']['question']) == 0) {
            header('Location: /?do=cabinet&p=update_email_question');
            die();
        }
        if ($_SESSION['user']['flush'] == 1) {
            $splash->addInfo('Після відновлення пароля за допомогою коду збросу просимо змінити секретне питання та відповідь до нього.');
        }
        include './include/views/cabinet/index.php';
        break;
}


    

