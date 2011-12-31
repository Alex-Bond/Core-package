<?php

global $db;

function get_groups()
{
    global $db;
    $recordSet = &$db->Execute('SELECT * from `groups` WHERE `archived`=0 order by `name`');

    $echo = '';
    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        while (!$recordSet->EOF) {
            $echo .= '<option value="' . $recordSet->fields['id'] . '">' . $recordSet->fields['name'] . '</option> ';
            $recordSet->MoveNext();
        }
        return $echo;
    }
}

function get_coms()
{
    global $db;
    $recordSet = &$db->Execute('SELECT * from `commissions` order by `name`');

    $echo = '';
    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        while (!$recordSet->EOF) {
            $echo .= '<option value="' . $recordSet->fields['id'] . '">' . $recordSet->fields['name'] . '</option> ';
            $recordSet->MoveNext();
        }
        return $echo;
    }
}

if (isset($_POST ['does']) && $_POST ['does'] == 'prenad') {
    $null = '<tr>
            <th width="250" scope="row">Номер студенського:</th> 
            <td><input name="studid_1" type="text" id="studid_1" style="height:40px; font-size:24px; vertical-align:middle; width:40px; text-align:center;  text-transform: uppercase;" value="" maxlength="2"/> 
              <input name="studid_2" type="text" id="studid_2" style="height:40px; font-size:24px; vertical-align:middle; text-align:center;" value="" size="8" maxlength="8"/></td> 
          </tr> 
          <tr> 
            <th scope="row">Група:</th> 
            <td><select name="group" id="group" style="width:400px; height:40px; font-size:24px; vertical-align:middle"> 
                ' . get_groups() . ' 
              </select></td> 
          </tr>';
    $one = '<tr>
            <th scope="row" width="250" >Комісія:</th> 
            <td><select name="com" id="com" style="width:400px; height:40px; font-size:24px; vertical-align:middle"> 
                ' . get_coms() . ' 
              </select></td> 
          </tr>';
    $third = '<tr>
            <th scope="row" width="250" >Група:</th> 
            <td><select name="group" id="group" style="width:400px; height:40px; font-size:24px; vertical-align:middle"> 
                ' . get_groups() . ' 
              </select></td> 
          </tr>';
    $fiths = '';


    $recordSet = &$db->Execute('SELECT * from `sys_groups` WHERE `sys_g_register`=1 AND `id`=' . $db->qstr($_POST ['prenad']));

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        echo '<table width="100%" border="0" cellspacing="0" cellpadding="5">';
        switch ($recordSet->fields['sys_g_studid']) {
            case 0:
                echo $null;
                break;
            case 1:
                echo $one;
                break;
            case 3:
                echo $third;
                break;
            case 4:
                echo $fiths;
                break;
            default:
                die();
        }
        if ($recordSet->fields['sys_g_onpass']) {
            echo '<tr> 
            <th width="250" scope="row">Пароль до групи:</th> 
            <td><input name="pass" type="password" id="pass" style="height:40px; font-size:24px; vertical-align:middle; width:400px;" value=""/></td> 
          </tr>';
        }
        echo '</table>';
    }
}

if (isset($_GET['cron'])) {
    header('Content-type: application/json');
    $auth->isDeny();
    if ($_SESSION['user']['sys_g_admin'] == 1) {
        $key = new Rediska_Key('onsite_admin');
        $on_site = $key->getValue();
        $a = 0;
        $i = 0;
        $out = array();
        if ($on_site != null) {
            foreach ($on_site as $key_a => $value) {
                if ($value['time'] > (time() - 20)) {
                    if ($value['id'] == $_SESSION['user']['id']) {
                        $value['time'] = time();
                        $value['lact'] = $_SERVER['HTTP_REFERER'];
                        $a = 1;
                    }
                    $out[$key_a] = $value;
                    if ($i < $key_a)
                        $i = $key_a;
                }
            }
        }
        if ($a == 0) {
            $out[$i + 1] = array(
                'id' => $_SESSION['user']['id'],
                'fio' => $_SESSION['user']['lastname'] . ' ' . $_SESSION['user']['name'],
                'lact' => $_SERVER['HTTP_REFERER'],
                'time' => time()
            );
        }
        $key->setValue($out);
    } else {
        $key = new Rediska_Key('onsite_users');
        $on_site = $key->getValue();
        $a = 0;
        $i = 0;
        $out = array();
        if ($on_site != null) {
            foreach ($on_site as $key_a => $value) {
                if ($value['time'] > (time() - 20)) {
                    if ($value['id'] == $_SESSION['user']['id']) {
                        $value['time'] = time();
                        $value['lact'] = $_SERVER['HTTP_REFERER'];
                        $a = 1;
                    }
                    $out[$key_a] = $value;
                    if ($i < $key_a)
                        $i = $key_a;
                }
            }
        }
        if ($a == 0) {
            $out[$i + 1] = array(
                'id' => $_SESSION['user']['id'],
                'fio' => $_SESSION['user']['lastname'] . ' ' . $_SESSION['user']['name'],
                'group' => $_SESSION['user']['group'],
                'lact' => $_SERVER['HTTP_REFERER'],
                'time' => time()
            );
        }
        $key->setValue($out);
    }
    checkTerm('admin');
    checkTerm('users');
    //$_SESSION['user'] = $_SESSION['user'];
    echo true;
}

function checkTerm($type)
{
    $key = new Rediska_Key('onsite_' . $type);
    $on_site = $key->getValue();
    $i = 0;
    $out = array();
    if ($on_site != null) {
        foreach ($on_site as $key_a => $value) {
            if ($value['time'] > (time() - 20)) {
                $out[$key_a] = $value;
            }
        }
    }
    $key->setValue($out);
}

if (isset($_GET['onsite_admin']) && $_SESSION['user']['sys_g_admin'] == 1) {
    $key = new Rediska_Key('onsite_admin');
    $on_site = $key->getValue();
    foreach ($on_site as $key => $value) {
        $out[$key] = $value;
        $out[$key]['where'] = getWhere($value['lact'], 1);
    }
    echo json_encode($out);
}

if (isset($_GET['onsite_users']) && $_SESSION['user']['sys_g_admin'] == 1) {
    $key = new Rediska_Key('onsite_users');
    $on_site = $key->getValue();
    foreach ($on_site as $key => $value) {
        $out[$key] = $value;
        $out[$key]['where'] = getWhere($value['lact'], 0);
    }
    echo isset($out) ? json_encode($out) : '';
}

function getWhere($url, $is_admin)
{
    $return = $url;
    if (mb_strpos($url, 'kkzcore.pp.ua/?do=', 0, 'UTF-8') != FALSE) {
        $return = 'Особистий кабiнет';
    }
    if (mb_strpos($url, 'kkzcore.pp.ua/admin/', 0, 'UTF-8') != FALSE) {
        $return = 'Панель керування';
    }
    if (mb_strpos($url, 'kkztest.pp.ua/', 0, 'UTF-8') != FALSE) {
        $return = 'ККЗ Tests';
        if (mb_strpos($url, 'kkztest.pp.ua', 0, 'UTF-8') != FALSE && $is_admin == 1) {
            $return = 'ККЗ Tests: Real-time';
        }
        if (mb_strpos($url, 'kkztest.pp.ua/question-bank.php', 0, 'UTF-8') != FALSE) {
            $return = 'ККЗ Tests: Банк питань';
        }
        if (mb_strpos($url, 'kkztest.pp.ua/test-manager.php', 0, 'UTF-8') != FALSE) {
            $return = 'ККЗ Tests: Менеджер тестiв';
        }
        if (mb_strpos($url, 'kkztest.pp.ua/reports-manager.php', 0, 'UTF-8') != FALSE) {
            $return = 'ККЗ Tests: Менеджер звітів';
        }
        if (mb_strpos($url, 'kkztest.pp.ua/test.php', 0, 'UTF-8')) {
            $return = 'ККЗ Tests: Тест';
        }
        if (mb_strpos($url, 'kkztest.pp.ua/config.php', 0, 'UTF-8') != FALSE) {
            $return = 'ККЗ Tests: Адміністрування';
        }
    }
    if (mb_strpos($url, 'kkzfiles.pp.ua/', 0, 'UTF-8') != FALSE) {
        $return = 'ККЗ Files';
    }
    if (mb_strpos($url, 'kkzlib.pp.ua/', 0, 'UTF-8') != FALSE) {
        $return = 'ККЗ Library';
    }
    return $return;
}