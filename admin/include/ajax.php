<?php

class kkzajax {

    public function home() {
        global $db, $user;
        if ($_POST ['do'] == 'users_search' and isset($_POST ['queryString'])) {
            $users = &$db->Execute("SELECT `users`.`id` AS userid, `users`.*, `sys_groups`.* from `users`, `sys_groups` WHERE `sys_groups`.`id`=`users`.`sys_group` and `users`.`studid` LIKE " . $db->qstr('%' . $_POST ['queryString'] . '%') . ' LIMIT 10');
            if (!$users) {
                print $db->ErrorMsg();
                die();
            } else {
                if ($users->RecordCount() > 0) {
                    echo '<table width="400" border="0" cellspacing="0" cellpadding="5">';
                    $echo = '';
                    while (!$users->EOF) {
                        if ($users->fields['sys_g_level'] >= $user['admin']) {
                            $echo .= '<tr><th style="border-bottom:#000 1px solid; background-color:#CCC; cursor:pointer;" onclick="alert(\'Ви не маєте прав на редагування даного користувача.\');">' . $users->fields ['lastname'] . ' ' . $users->fields ['name'] . ' ' . $users->fields ['fathername'] . '</th></tr>';
                        } else {
                            $echo .= '<tr><th style="border-bottom:#000 1px solid; background-color:#CCC; cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $users->fields ['userid'] . '\';">' . $users->fields ['lastname'] . ' ' . $users->fields ['name'] . ' ' . $users->fields ['fathername'] . '</th></tr>';
                        }
                        $users->MoveNext();
                    }
                    echo $echo;
                    echo '</table>';
                } else {
                    echo '<table width="400" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th bgcolor="#990000" style="color:#FFF">Користувач не знайдений</th>
  </tr>
</table>';
                }
            }
        } else {
            die('403');
        }
    }

    public function genPass() {
        global $db;
        $id = $_POST['id'];
        $code = rand(100000, 999999);
        $users1 = &$db->Execute("DELETE FROM `lostpass` WHERE `user`=".$id);
        $users2 = &$db->Execute("INSERT INTO `lostpass` (`key`,`user`) VALUES (".$code.",".$id.")");
        if (!$users1 OR !$users2) {
            print $db->ErrorMsg();
            die();
        } else {
            echo $code;
        }
    }

}

?>