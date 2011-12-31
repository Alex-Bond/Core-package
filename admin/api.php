<?php

ob_start();

include ("./include/adodb5/adodb.inc.php");

$db = &ADONewConnection('mysqli');
$db->Connect('localhost', 'root', '', 'core_admin');
//$db->debug = true;
$db->Execute('SET NAMES utf8;');

$do = $_GET ['do'];
if (isset($_GET['id'])) {
    $id = $_GET ['id'];
}
if (isset($_GET['key'])) {
    $key = $_GET ['key'];
} else {
    $key = '';
}
if (isset($_GET['pass'])) {
    $pass = $_GET ['pass'];
} else {
    $pass = '';
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?> ";
$oXMLout = new XMLWriter ();
$oXMLout->openMemory();
$oXMLout->startElement("answer");


if ($do == 'login') {
    if (strlen($key) > 0) {
        $key = $db->qstr($key);

        $recordSet = &$db->Execute('SELECT `oauth`.`id` as ses_id, `sys_groups`.`sys_g_old` as perms, `sys_groups`.`sys_g_allcoms` as allcoms, `oauth`.`user`, `users`.* FROM `oauth`,`users`,`sys_groups` WHERE `sys_groups`.`id`=`users`.`sys_group` AND `ses`=' . $key . ' and `users`.`id`=`oauth`.`user`');

        if (!$recordSet)
            print $db->ErrorMsg();
        else {
            if ($recordSet->RecordCount() > 0) {
                $oXMLout->startElement("user");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("login", $recordSet->fields ['login']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->writeElement("lastname", $recordSet->fields ['lastname']);
                $oXMLout->writeElement("fathername", $recordSet->fields ['fathername']);
                $oXMLout->writeElement("lastvisit", $recordSet->fields ['lastvisit']);
                $oXMLout->writeElement("group", $recordSet->fields ['group']);
                $oXMLout->writeElement("commission", $recordSet->fields ['com']);
                $oXMLout->writeElement("access", $recordSet->fields ['perms']);
                $oXMLout->writeElement("allcoms", $recordSet->fields ['allcoms']);
                $oXMLout->endElement();
            } else {
                $oXMLout->startElement("error");
                $oXMLout->writeElement("code", "403_2");
                $oXMLout->endElement();
            }
        }
    }
}

if ($do == 'user2') {
    $recordSet = &$db->Execute('SELECT `users`.*, `sys_groups`.`sys_g_old` as perms, `sys_groups`.`sys_g_allcoms` as allcoms FROM `users`,`sys_groups` WHERE `sys_groups`.`id`=`users`.`sys_group` and `users`.`id`=' . $id);

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {
            $oXMLout->startElement("user");
            $oXMLout->writeElement("id", $recordSet->fields ['id']);
            $oXMLout->writeElement("login", $recordSet->fields ['login']);
            $oXMLout->writeElement("name", $recordSet->fields ['name']);
            $oXMLout->writeElement("lastname", $recordSet->fields ['lastname']);
            $oXMLout->writeElement("fathername", $recordSet->fields ['fathername']);
            $oXMLout->writeElement("lastvisit", $recordSet->fields ['lastvisit']);
            $oXMLout->writeElement("group", $recordSet->fields ['group']);
            $oXMLout->writeElement("commission", $recordSet->fields ['com']);
            $oXMLout->writeElement("access", $recordSet->fields ['perms']);
            $oXMLout->writeElement("allcoms", $recordSet->fields ['allcoms']);
            $oXMLout->endElement();
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}

if ($do == 'users') {
    
    ob_clean();
    $recordSet = &$db->Execute('SELECT `users`.*, `sys_groups`.`sys_g_old` as perms FROM `users`, `sys_groups` WHERE `sys_groups`.`id`=`users`.`sys_group` AND `users`.`archived`=0');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                

                $out[$recordSet->fields ['id']] = array(
                    'id' => $recordSet->fields ['id'],
                    'login' => $recordSet->fields ['login'],
                    'name' => $recordSet->fields ['name'],
                    'lastname' => $recordSet->fields ['lastname'],
                    'fathername' => $recordSet->fields ['fathername'],
                    'lastvisit' => $recordSet->fields ['lastvisit'],
                    'group' => $recordSet->fields ['group'],
                    'commission' => $recordSet->fields ['com'],
                    'access' => $recordSet->fields ['perms']
                );
                $recordSet->MoveNext();
            }
            echo json_encode($out);
            die();
        } else {
            echo json_encode(array('error'=>'404'));
			die();
        }
    }
}

if ($do == 'users2') {
    $recordSet = &$db->Execute('SELECT `users`.*, `sys_groups`.`sys_g_old` as perms FROM `users`, `sys_groups` WHERE `sys_groups`.`id`=`users`.`sys_group` and `group`=' . $id . ' ORDER BY lastname');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("user");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("login", $recordSet->fields ['login']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->writeElement("lastname", $recordSet->fields ['lastname']);
                $oXMLout->writeElement("fathername", $recordSet->fields ['fathername']);
                $oXMLout->writeElement("lastvisit", $recordSet->fields ['lastvisit']);
                $oXMLout->writeElement("group", $recordSet->fields ['group']);
                $oXMLout->writeElement("commission", $recordSet->fields ['com']);
                $oXMLout->writeElement("access", $recordSet->fields ['perms']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}

if ($do == 'groups') {

    $recordSet = &$db->Execute('SELECT *  FROM `groups` WHERE `archived`=0 order by name');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("group");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}

if ($do == 'groups_users') {
    
    ob_clean();

    $g = &$db->Execute('SELECT `groups`.`id` as g_id, `groups`.`name` as g_name, `users`.*  FROM `users` INNER JOIN `groups` ON `groups`.`id`=`users`.group  WHERE `users`.`archived`=0 AND `users`.`group`>0 order by `groups`.`name`, `users`.`lastname`');

    if (!$g)
        print $db->ErrorMsg();
    else {
        if ($g->RecordCount() > 0) {

            while (!$g->EOF) {
                $f = &$g->fields;
                $out[$f['g_id']]['id'] = $f['g_id'];
                $out[$f['g_id']]['name'] = $f['g_name'];
                $out[$f['g_id']]['users'][$f['id']] = array(
                    'id' => $f ['id'],
                    'login' => $f ['login'],
                    'name' => $f ['name'],
                    'lastname' => $f ['lastname'],
                    'fathername' => $f ['fathername'],
                    'lastvisit' => $f ['lastvisit'],
                    'group' => $f ['group'],
                    'commission' => $f ['com']
                );

                $g->MoveNext();
            }
        } else {
            $out=array('error'=>'404');
        }
    }
    echo json_encode($out);
    die();
}

if ($do == "group") {
    $recordSet = &$db->Execute('SELECT *  FROM `groups` WHERE `id` = ' . $id);

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("group");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}

if ($do == "commissions") {

    $recordSet = &$db->Execute('SELECT *  FROM `commissions` order by `name`');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("commission");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}
if ($do == "commission_id") {
    $recordSet = &$db->Execute('SELECT *  FROM `commissions` WHERE id = ' . $_GET ['id'] . ' order by `name`');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("commission");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}
if ($do == "courses_id") {

    $recordSet = &$db->Execute('SELECT *  FROM `courses` WHERE com =' . $id  . ' order by `name`');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("course");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}
if ($do == "courses_all") {

    $recordSet = &$db->Execute('SELECT *  FROM `courses` order by `name`');

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("course");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}
if ($do == "course_id") {
    $recordSet = &$db->Execute('SELECT *  FROM `courses` WHERE id = ' . $_GET ['id']);

    if (!$recordSet)
        print $db->ErrorMsg();
    else {
        if ($recordSet->RecordCount() > 0) {

            while (!$recordSet->EOF) {

                $oXMLout->startElement("course");
                $oXMLout->writeElement("id", $recordSet->fields ['id']);
                $oXMLout->writeElement("name", $recordSet->fields ['name']);
                $oXMLout->writeElement("com", $recordSet->fields ['com']);
                $oXMLout->endElement();

                $recordSet->MoveNext();
            }
        } else {
            $oXMLout->startElement("error");
            $oXMLout->writeElement("code", "403_2");
            $oXMLout->endElement();
        }
    }
}

$oXMLout->endElement();
print $oXMLout->outputMemory();
?>