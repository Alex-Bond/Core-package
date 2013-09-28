<?php

function ckeck_login() {
    global $db;
    $login = $db->Execute('select * from `users` where `login` = ' . $db->qstr($_POST ['login']) . ' ');
    if ($login->RecordCount() > 0) {
        return false;
    } else {
        return true;
    }
}

function ckeck_fio() {
    global $db;
    if (strlen($_POST ['firstname']) > 3 or strlen($_POST ['lastname']) > 3 or strlen($_POST ['fathername']) > 3) {
        $login = $db->Execute('select * from `users` where `name` = ' . $db->qstr($_POST ['firstname']) . ' AND `lastname` = ' . $db->qstr($_POST ['lastname']) . ' AND `fathername` = ' . $db->qstr($_POST ['fathername'] . ' AND `archived`=0'));
        if ($login->RecordCount() > 0) {
            header('Location: http://kkzcore.pp.ua/?inform=Ви вже є у системі.');
	        die();
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function ckeck_studid() {
    global $db;
    if (strlen(utf8_decode($_POST ['studid_1'])) == 2 and strlen($_POST ['studid_2']) == 8) {
        $login = $db->Execute('select * from `users` where `studid` = ' . $db->qstr(mb_convert_case($_POST ['studid_1'], MB_CASE_UPPER, "utf-8") . ' №' . $_POST ['studid_2']) . ' ');
        if ($login->RecordCount() > 0) {
            header('Location: http://kkzcore.pp.ua/?inform=Ви вже є у системі.');
	        die();
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function check_pass($pass) {
    if ($pass == $_POST ['pass']) {
        return true;
    } else {
        return false;
    }
}

if (isset($_POST ['submit'])) {
    $group = $db->Execute('select * from `sys_groups` where `id` = ' . $_POST ['sys_group'] . ' ');
    if ($group->RecordCount() > 0) {
        if ($group->fields ['sys_g_register'] == 1) {

            //PASS
            if ($group->fields ['sys_g_onpass'] == 1) {
                $pass = check_pass($group->fields ['sys_g_pass']);
            } else {
                $pass = true;
            }

            //LOGIN
            $login = ckeck_login();
            if (!preg_match("/^[a-zA-Z0-9_-]{6,250}$/",$_POST ['login'])) {
                $error = 'Логiн мiстить забороненi символи!';
            }

            //STUDID
            if ($group->fields ['sys_g_studid'] == 0) {
                if (ckeck_studid() === true) {
                    $studid = true;
                    $studid_db = strtoupper($_POST ['studid_1']) . ' №' . $_POST ['studid_2'];
                } else {
                    $error = 'Студентський номер введений не правильно!';
                }
            } else {
                $studid = true;
                $studid_db = '';
            }

            //FIO
            $fio = ckeck_fio();

            //Email
            if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $_POST['email'])) {
                $error = 'Email введений не правильно!';
            }

            //Answer
            if (strlen($_POST['answer']) < 3) {
                $error = 'Ответ слишком короткий!';
            }

            if ($pass !== true) {
                $error = 'Пароль до групи невірний!';
            } elseif ($login !== true) {
                $error = 'Даний логін вже зареєстрований у системі!';
            } elseif ($fio !== true) {
                $error = 'Не введене Призвище / Ім\'я / По батькові!';
            }

            if ($group->fields ['sys_g_studid'] == 0) {
                $com = '';
                $group = $_POST['group'];
            } elseif ($group->fields ['sys_g_studid'] == 1) {
                $studid_db = '';
                $group = '';
                $com = $_POST['com'];
            } elseif ($group->fields ['sys_g_studid'] == 3) {
                $studid_db = '';
                $com = '';
                $group = $_POST['group'];
            } elseif ($group->fields ['sys_g_studid'] == 4) {
                $studid_db = '';
                $group = '';
                $com = '';
            }

            if ($pass === true and $login === true and $fio === true AND $studid === true && !isset($error)) {
                $sql = 'INSERT INTO `users` (`login`,`pass`,`studid`,`name`,`lastname`,`fathername`,`email`,`question`,`answer`,`group`,`com`,`lastvisit`,`cheked`,`register`,`sys_group`) VALUES (';
                $sql .=
                        $db->qstr($_POST['login']) . ', '
                        . $db->qstr(md5($_POST['pass'])) . ', '
                        . 'UPPER(' . $db->qstr($studid_db) . '), '
                        . $db->qstr($_POST['firstname']) . ', '
                        . $db->qstr($_POST['lastname']) . ', '
                        . $db->qstr($_POST['fathername']) . ', '
                        . $db->qstr($_POST['email']) . ', '
                        . $db->qstr($_POST['question']) . ', '
                        . $db->qstr(md5($_POST['answer'])) . ', '
                        . $db->qstr($group) . ', '
                        . $db->qstr($com) . ', '
                        . $db->qstr(date('Y-m-d H-i-s')) . ', '
                        . $db->qstr('0') . ', '
                        . $db->qstr(date('Y-m-d H-i-s')) . ', '
                        . $db->qstr($_POST ['sys_group']) . ' ';
                $sql .= ')';

                if ($db->Execute($sql) === false) {
                    die('error: ' . $db->ErrorMsg() . '<BR>');
                } else {
                    $id = $db->Insert_ID();
                    $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $id . ",'user','register-" . $id . "-success','0');";
                    $db->Execute($sql2);
                    //echo $sql;
                    //print_r($_POST); 
                    header('Location: http://kkzcore.pp.ua/?do=register&complete=1');
	                die();
                }
            }
        } else {
            die('Дана група закрита для реєстрацій.');
        }
    } else {
        die('Даної групи не існує.');
    }
} elseif ($_GET['complete'] == '1') {
    include ("./include/views/register/complete.php");
    die();
}

include './include/views/register/index.php';