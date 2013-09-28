<?php

class kkzgroups {

    public function getgroups_select($id) {
        global $db;
        $groups = $db->Execute('select * from `groups` WHERE `archived`=0 order by `name`');
        if ($groups->RecordCount() > 0) {
            while (!$groups->EOF) {
                if ($groups->fields ['id'] == $id) {
                    $echo .= '<option value="' . $groups->fields ['id'] . '" selected="selected">' . $groups->fields ['name'] . '</option>';
                } else {
                    $echo .= '<option value="' . $groups->fields ['id'] . '">' . $groups->fields ['name'] . '</option>';
                }
                $groups->MoveNext();
            }
        } else {
            die('База груп не може бути пуста.');
        }
        return $echo;
    }

    public function get_sys_groups_select($id) {
        global $db, $user;
        $groups = $db->Execute('select * from `sys_groups` where `sys_g_level`<' . $user ['admin'] . ' order by `sys_g_level`');
        if ($groups->RecordCount() > 0) {
            while (!$groups->EOF) {
                if ($groups->fields ['id'] == $id) {
                    $echo .= '<option value="' . $groups->fields ['id'] . '" selected="selected">' . $groups->fields ['sys_g_name'] . '</option>';
                } else {
                    $echo .= '<option value="' . $groups->fields ['id'] . '">' . $groups->fields ['sys_g_name'] . '</option>';
                }
                $groups->MoveNext();
            }
        } else {
            die('База груп не може бути пуста.');
        }
        return $echo;
    }

    public function home() {
        global $user, $db;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `groups` order by `archived`, `name`');
                if ($groups->RecordCount() > 0) {
                    if (isset($_GET ['inform'])) {
                        $echo = '<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th bgcolor="#006600" style="color:#FFF">' . $_GET ['inform'] . '</th>
  </tr>
</table><br />';
                    }
                    if ($user['all']['sys_g_delete'] == 1) {
                        $h_delete = '<th width="100" scope="col">Видалити</th>';
                    } else {
                        $h_delete = '';
                    }

                    $echo .= '<br>
					<center>[<a href="?do=groups&f=add">Додати групу</a>]</center><br>
					<table width="100%" border="0" cellspacing="0" class="tablecolored">
  <tr>
    <th width="40" scope="col">ID</th>
    <th scope="col">Назва</th>
    <th width="100" scope="col">В архiв</th>
    ' . $h_delete . '
  </tr>';
                    while (!$groups->EOF) {
                        if ($user['all']['sys_g_delete'] == 1) {
                            $r_delete = '<td align=center>[<a href="?do=groups&f=del&id=' . $groups->fields ['id'] . '" onClick="return confirm(\'Ви дійсно хочете видалити групу ' . $groups->fields ['name'] . '? \nУвага! При видаленні групи, усі корістувачі що пропісані у цій групі, також видаляться!\');">Видалити</a>]</td>';
                        } else {
                            $r_delete = '';
                        }

                        if ($groups->fields['archived'] == 0) {
                            $tr_add = '';
                            $arch = '<td align=center>[<a href="?do=groups&f=archive&id=' . $groups->fields ['id'] . '" onClick="return confirm(\'Ви дійсно хочете вiдпривтити в архiв групу ' . $groups->fields ['name'] . '? \nУвага! При вiдправленнi в архiв групи, усі корістувачі що пропісані у цій групі, також архiвуються!\');">В архiв</a>]</td>';
                        } else {
                            $tr_add = 'bgcolor="#87CEFA"';
                            $arch = '<td align=center>[<a href="?do=groups&f=archive&id=' . $groups->fields ['id'] . '" onClick="return confirm(\'Ви дійсно хочете повернути з архiву групу ' . $groups->fields ['name'] . '? \nУвага! При поверненнi групи з архiву, усі корістувачі що пропісані у цій групі, також повераються!\');">З архiву</a>]</td>';
                        }
                        $echo .= '<tr ' . $tr_add . '>
    <td align=center style="cursor:pointer;" onclick="window.location=\'?do=groups&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['id'] . '</td>
    <td style="cursor:pointer;" onclick="window.location=\'?do=groups&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['name'] . '</td>
    ' . $arch . $r_delete . '
  </tr>';
                        $groups->MoveNext();
                    }
                    $echo .= '</table>';
                    return $echo;
                }
            } else {
                die("У Вас немає доступу до цього розділу.");
            }
        }
    }

    public function edit_view($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `groups` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $echo = '<form id="form1" name="form1" method="post" action="">

<p>&nbsp;</p>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="100" scope="row">Назва групи:</th>
    <th align="left">
    <input name="group" type="text" id="group" value="' . $groups->fields ['name'] . '" size="60" /></th>
  </tr>
</table><br>
<center>
<input name="id" type="hidden" id="id" value="' . $groups->fields ['id'] . '" />
<input type="submit" name="submit" id="submit" value="Зберегти" />
</center>
</form>';
                    return $echo;
                }
            }
        }
    }

    public function edit_submit($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `groups` where `id`=' . $_POST ['id']);
                if ($groups->RecordCount() > 0) {
                    $sql = 'UPDATE `groups` SET `name`=' . $db->qstr($_POST ['group']) . ' WHERE `groups`.`id`=' . $_POST ['id'];

                    if ($db->Execute($sql) === false) {
                        die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'group','edit-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        //echo $sql;
                        header('Location: http://kkzcore.pp.ua/admin/?do=groups&inform=Група оновлена');
                    }
                }
            }
        }
    }

    public function add_view() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {

                $echo = '<form id="form1" name="form1" method="post" action="">

<p>&nbsp;</p>
<table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="100" scope="row">Назва групи:</th>
    <th align="left">
    <input name="group" type="text" id="group" value="" size="60" /></th>
  </tr>
</table><br>
<center>
<input type="submit" name="submit" id="submit" value="Додати" />
</center>
</form>';
                return $echo;
            }
        }
    }

    public function add_submit() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {

                $sql = 'insert into `groups` (`name`) values (' . $db->qstr($_POST ['group']) . ')';

                if ($db->Execute($sql) === false) {
                    die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                } else {
                    $id = $db->Insert_ID();
                    $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'group','add-" . $id . "-success','0');";
                    $db->Execute($sql2);
                    //echo $sql;
                    header('Location: http://kkzcore.pp.ua/admin/?do=groups&inform=Група додана');
                }
            }
        }
    }

    public function delete($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `groups` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $sql = 'delete from `groups` WHERE `groups`.`id`=' . $id;
                    $sql2 = 'delete from `users` WHERE `users`.`group`=' . $id;

                    if ($db->Execute($sql) === false or $db->Execute($sql2) === false) {
                        die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'group','del-" . $id . "-success','0');";
                        $db->Execute($sql2);
                        //echo $sql;
                        header('Location: http://kkzcore.pp.ua/admin/?do=groups&inform=Група видалена');
                    }
                }
            }
        }
    }

    public function archive($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `groups` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    if ($groups->fields['archived'] == 0) {
                        $sql = 'UPDATE `groups` SET `archived`=1 WHERE `groups`.`id`=' . $id;
                        $sql2 = 'UPDATE `users` SET `archived`=1 WHERE `users`.`group`=' . $id;
                    } else {
                        $sql = 'UPDATE `groups` SET `archived`=0 WHERE `groups`.`id`=' . $id;
                        $sql2 = 'UPDATE `users` SET `archived`=0 WHERE `users`.`group`=' . $id;
                    }

                    if ($db->Execute($sql) === false or $db->Execute($sql2) === false) {
                        die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'group','arch-" . $id . "-success','0');";
                        $db->Execute($sql2);
                        //echo $sql;
                        header('Location: http://kkzcore.pp.ua/admin/?do=groups&inform=Група вiдправлена до архiву.');
                    }
                }
            }
        }
    }

}

class kkzcoms {

    public function getcoms_select($id) {
        global $db;
        $groups = $db->Execute('select * from `commissions` order by `name`');
        if ($groups->RecordCount() > 0) {
            while (!$groups->EOF) {
                if ($groups->fields ['id'] == $id) {
                    $echo .= '<option value="' . $groups->fields ['id'] . '" selected="selected">' . $groups->fields ['name'] . '</option>';
                } else {
                    $echo .= '<option value="' . $groups->fields ['id'] . '">' . $groups->fields ['name'] . '</option>';
                }
                $groups->MoveNext();
            }
        } else {
            die('База груп не може бути пуста.');
        }
        return $echo;
    }

    //EDIT------------------------------------


    public function home() {
        global $user, $db;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_coms'] == 1) {
                $groups = $db->Execute('select * from `commissions` order by `name`');
                if ($groups->RecordCount() > 0) {
                    if (isset($_GET ['inform'])) {
                        $echo = '<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th bgcolor="#006600" style="color:#FFF">' . $_GET ['inform'] . '</th>
  </tr>
</table><br />';
                    }
                    $echo .= '<br>
					<center>[<a href="?do=coms&f=add">Додати комісію</a>]</center><br>
					<table width="100%" border="0" cellspacing="0" class="tablecolored">
  <tr>
    <th width="40" scope="col">ID</th>
    <th scope="col">Назва</th>
    <th width="100" scope="col">Видалити</th>
  </tr>';
                    while (!$groups->EOF) {
                        $echo .= '<tr>
    <td align=center style="cursor:pointer;" onclick="window.location=\'?do=coms&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['id'] . '</td>
    <td style="cursor:pointer;" onclick="window.location=\'?do=coms&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['name'] . '</td>
    <td align=center>[<a href="?do=coms&f=del&id=' . $groups->fields ['id'] . '" onClick="return confirm(\'Ви дійсно хочете видалити ' . $groups->fields ['name'] . '? \nУвага! При видаленні комісії, усі предмети що прописані у цій комісії, також видаляться!\');">Видалити</a>]</td>
  </tr>';
                        $groups->MoveNext();
                    }
                    $echo .= '</table>';
                    return $echo;
                }
            } else {
                die("У Вас немає доступу до цього розділу.");
            }
        }
    }

    public function edit_view($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_coms'] == 1) {
                $groups = $db->Execute('select * from `commissions` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $echo = '<form id="form1" name="form1" method="post" action="">

<p>&nbsp;</p>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="150" scope="row">Назва комісії:</th>
    <th align="left">
    <input name="group" type="text" id="group" value="' . $groups->fields ['name'] . '" size="60" /></th>
  </tr>
</table><br>
<center>
<input name="id" type="hidden" id="id" value="' . $groups->fields ['id'] . '" />
<input type="submit" name="submit" id="submit" value="Зберегти" />
</center>
</form>';
                    return $echo;
                }
            }
        }
    }

    public function edit_submit($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_coms'] == 1) {
                $groups = $db->Execute('select * from `groups` where `id`=' . $_POST ['id']);
                if ($groups->RecordCount() > 0) {
                    $sql = 'UPDATE `commissions` SET `name`=' . $db->qstr($_POST ['group']) . ' WHERE `commissions`.`id`=' . $_POST ['id'];

                    if ($db->Execute($sql) === false) {
                        die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'commission','edit-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        header('Location: http://kkzcore.pp.ua/admin/?do=coms&inform=Комісія оновлена');
                    }
                }
            }
        }
    }

    public function add_view() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {

                $echo = '<form id="form1" name="form1" method="post" action="">

<p>&nbsp;</p>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="150" scope="row">Назва комісії:</th>
    <th align="left">
    <input name="group" type="text" id="group" value="" size="60" /></th>
  </tr>
</table><br>
<center>
<input type="submit" name="submit" id="submit" value="Додати" />
</center>
</form>';
                return $echo;
            }
        }
    }

    public function add_submit() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_coms'] == 1) {

                $sql = 'insert into `commissions` (`name`) values (' . $db->qstr($_POST ['group']) . ')';

                if ($db->Execute($sql) === false) {
                    die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                } else {
                    $id = $db->Insert_ID();
                    $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'commission','add-" . $id . "-success','0');";
                    $db->Execute($sql2);
                    //echo $sql;
                    header('Location: http://kkzcore.pp.ua/admin/?do=coms&inform=Комісія додана');
                }
            }
        }
    }

    public function delete($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `commissions` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $sql = 'delete from `commissions` WHERE `commissions`.`id`=' . $id;
                    $sql2 = 'delete from `courses` WHERE `courses`.`com`=' . $id;

                    if ($db->Execute($sql) === false or $db->Execute($sql2) === false) {
                        die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'commission','del-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        //echo $sql;
                        header('Location: http://kkzcore.pp.ua/admin/?do=coms&inform=Комісія видалена');
                    }
                }
            }
        }
    }

}

class kkzcourses {

    public function home() {
        global $user, $db;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_courses'] == 1) {
                $groups = $db->Execute('select `courses`.`name` as courname, `courses`.*, `commissions`.`id` as comid, `commissions`.`name` as comname, `commissions`.`name` from `courses`,`commissions` where `commissions`.`id`=`courses`.`com` ORDER BY `commissions`.`name`,`courses`.`name`');
                if ($groups->RecordCount() > 0) {
                    if (isset($_GET ['inform'])) {
                        $echo = '<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th bgcolor="#006600" style="color:#FFF">' . $_GET ['inform'] . '</th>
  </tr>
</table><br />';
                    }
                    $echo .= '<br>
					<center>[<a href="?do=courses&f=add">Додати предмет</a>]</center><br>
					<table width="100%" border="0" cellspacing="0" class="tablecolored">
  <tr>
    <th width="40" scope="col">ID</th>
    <th scope="col">Назва</th>
    <th scope="col">Комісія</th>
    <th width="100" scope="col">Видалити</th>
  </tr>';
                    while (!$groups->EOF) {
                        $echo .= '<tr>
    <td align=center style="cursor:pointer;" onclick="window.location=\'?do=courses&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['id'] . '</td>
    <td style="cursor:pointer;" onclick="window.location=\'?do=courses&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['courname'] . '</td>
    <td style="cursor:pointer;" onclick="window.location=\'?do=courses&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['comname'] . '</td>
    <td align=center>[<a href="?do=courses&f=del&id=' . $groups->fields ['id'] . '" onClick="return confirm(\'Ви дійсно хочете видалити ' . $groups->fields ['courname'] . '? \n\');">Видалити</a>]</td>
  </tr>';
                        $groups->MoveNext();
                    }
                    $echo .= '</table>';
                    return $echo;
                }
            } else {
                die("У Вас немає доступу до цього розділу.");
            }
        }
    }

    public function edit_view($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_courses'] == 1) {
                $groups = $db->Execute('select * from `courses` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $echo = '<form id="form1" name="form1" method="post" action="">

<p>&nbsp;</p>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="150" scope="row">Назва предмету:</th>
    <th align="left">
    <input name="group" type="text" id="group" value="' . $groups->fields ['name'] . '" size="60" /></th>
  </tr>
  <tr>
    <th width="150" scope="row">Комісія:</th>
    <th align="left"><select name="com" id="com">
          ' . kkzcoms::getcoms_select($groups->fields ['com']) . '
        </select></th>
  </tr>
</table><br>
<center>
<input name="id" type="hidden" id="id" value="' . $groups->fields ['id'] . '" />
<input type="submit" name="submit" id="submit" value="Зберегти" />
</center>
</form>';
                    return $echo;
                }
            }
        }
    }

    public function edit_submit($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_courses'] == 1) {
                $groups = $db->Execute('select * from `courses` where `id`=' . $_POST ['id']);
                if ($groups->RecordCount() > 0) {
                    $sql = 'UPDATE `courses` SET `name`=' . $db->qstr($_POST ['group']) . ', `com`=' . $db->qstr($_POST ['com']) . ' WHERE `courses`.`id`=' . $_POST ['id'];

                    if ($db->Execute($sql) === false) {
                        die($sql . 'error update: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'course','edit-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        //echo $sql;
                        header('Location: http://kkzcore.pp.ua/admin/?do=courses&inform=Предмет оновлений');
                    }
                }
            }
        }
    }

    public function add_view() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_courses'] == 1) {

                $echo = '<form id="form1" name="form1" method="post" action="">

<p>&nbsp;</p>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="150" scope="row">Назва предмету:</th>
    <th align="left">
    <input name="group" type="text" id="group" value="" size="60" /></th>
  </tr>
  <tr>
    <th width="150" scope="row">Комісія:</th>
    <th align="left"><select name="com" id="com">
          ' . kkzcoms::getcoms_select(0) . '
        </select></th>
  </tr>
</table><br>
<center>
<input type="submit" name="submit" id="submit" value="Додати" />
</center>
</form>';
                return $echo;
            }
        }
    }

    public function add_submit() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_courses'] == 1) {

                $sql = 'insert into `courses` (`name`,`com`) values (' . $db->qstr($_POST ['group']) . ',' . $db->qstr($_POST ['com']) . ')';

                if ($db->Execute($sql) === false) {
                    die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                } else {
                    $id = $db->Insert_ID();
                    $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'course','add-" . $id . "-success','0');";
                    $db->Execute($sql2);
                    //echo $sql;
                    header('Location: http://kkzcore.pp.ua/admin/?do=courses&inform=Предмет доданий');
                }
            }
        }
    }

    public function delete($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `commissions` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $sql = 'delete from `courses` WHERE `courses`.`id`=' . $id;

                    if ($db->Execute($sql) === false) {
                        die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'course','del-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        //echo $sql;
                        header('Location: http://kkzcore.pp.ua/admin/?do=courses&inform=Предмет видалений');
                    }
                }
            }
        }
    }

}

class kkzstats {
	public function home(){
		$content = '<script type="text/javascript" src="/js/amline/swfobject.js"></script>
<div id="amcharts_core">You need to upgrade your Flash Player</div>
<script type="text/javascript">
	var so = new SWFObject("/js/amline/amline.swf", "amline", "100%", "400", "8", "#FFFFFF");
	so.addVariable("path", "/js/amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><background><color>E3E3E3</color><alpha>100</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><legend><enabled>0</enabled></legend><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><graphs><graph gid=\'0\'><title>Кiлькiсть користувачiв</title><color>00CC33</color><color_hover>FF0F00</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid=\'0\'><text><![CDATA[<b>Вiдвiдування системи (до особистого кабiнету)</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("data_file", encodeURIComponent("/admin/?do=stats&f=get_csv&sf=visits_core"));
	so.write("amcharts_core");
</script>
<br />
<div id="amcharts_lib">You need to upgrade your Flash Player</div>
<script type="text/javascript">
	var so = new SWFObject("/js/amline/amline.swf", "amline", "100%", "400", "8", "#FFFFFF");
	so.addVariable("path", "/js/amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><background><color>E3E3E3</color><alpha>100</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><legend><enabled>0</enabled></legend><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><graphs><graph gid=\'0\'><title>Кiлькiсть користувачiв</title><color>00CCFF</color><color_hover>FF9900</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid=\'0\'><text><![CDATA[<b>Вiдвiдування системи Library</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("data_file", encodeURIComponent("/admin/?do=stats&f=get_csv&sf=visits_lib"));
	so.write("amcharts_lib");
</script>
<br />
<div id="amcharts_tests">You need to upgrade your Flash Player</div>
<script type="text/javascript">
	var so = new SWFObject("/js/amline/amline.swf", "amline", "100%", "400", "8", "#FFFFFF");
	so.addVariable("path", "/js/amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><background><color>E3E3E3</color><alpha>100</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><legend><enabled>0</enabled></legend><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><graphs><graph gid=\'0\'><title>Кiлькiсть користувачiв</title><color>00CCFF</color><color_hover>FF9900</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid=\'0\'><text><![CDATA[<b>Вiдвiдування системи Tests</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("data_file", encodeURIComponent("/admin/?do=stats&f=get_csv&sf=visits_tests"));
	so.write("amcharts_tests");
</script>
<br />
<div id="amcharts_files">You need to upgrade your Flash Player</div>
<script type="text/javascript">
	var so = new SWFObject("/js/amline/amline.swf", "amline", "100%", "400", "8", "#FFFFFF");
	so.addVariable("path", "/js/amline/");
	so.addVariable("chart_settings", encodeURIComponent("<settings><font>Tahoma</font><hide_bullets_count>18</hide_bullets_count><data_type>csv</data_type><background><color>E3E3E3</color><alpha>100</alpha><border_alpha>10</border_alpha></background><plot_area><margins><left>50</left><right>40</right><bottom>65</bottom></margins></plot_area><grid><x><alpha>10</alpha><approx_count>9</approx_count></x><y_left><alpha>10</alpha></y_left></grid><axes><x><width>1</width><color>0D8ECF</color></x><y_left><width>1</width><color>0D8ECF</color></y_left></axes><indicator><color>0D8ECF</color><x_balloon_text_color>FFFFFF</x_balloon_text_color><line_alpha>50</line_alpha><selection_color>0D8ECF</selection_color><selection_alpha>20</selection_alpha></indicator><legend><enabled>0</enabled></legend><zoom_out_button><text_color_hover>FF0F00</text_color_hover></zoom_out_button><graphs><graph gid=\'0\'><title>Кiлькiсть користувачiв</title><color>00CCFF</color><color_hover>FF9900</color_hover><line_width>2</line_width><fill_alpha>30</fill_alpha><bullet>round</bullet></graph></graphs><labels><label lid=\'0\'><text><![CDATA[<b>Вiдвiдування системи Files</b>]]></text><y>25</y><text_size>13</text_size><align>center</align></label></labels></settings>"));
	so.addVariable("data_file", encodeURIComponent("/admin/?do=stats&f=get_csv&sf=visits_files"));
	so.write("amcharts_files");
</script>';
		return $content;
	}

	public function get_csv($sf){
		global $db;
		switch($sf){
			case 'visits_core': $site = '0'; break;
			case 'visits_lib': $site = '1'; break;
			case 'visits_tests': $site = '2'; break;
			case 'visits_files': $site = '3'; break;
		}

		$visitors = array ();
		//$recordSet = &$db->Execute ( 'SELECT dates.time, count(stats.id) as cnt FROM dates LEFT JOIN stats ON (TO_DAYS(dates.time) = TO_DAYS(stats.date) AND stats.event=\'login\') WHERE dates.time <NOW() GROUP BY dates.time ORDER BY dates.time' );
		$recordSet = &$db->Execute ( 'SELECT FROM_DAYS(TO_DAYS(stats.date)) as time, count(TO_DAYS(stats.date)) as cnt FROM stats WHERE stats.do=\'login\' AND site='.$site.' GROUP BY time ORDER BY time DESC' );
		if (! $recordSet)
			die ( $db->ErrorMsg () );
		while ( ! $recordSet->EOF ) {
			$visitors [$recordSet->fields ['time']] = $recordSet->fields ['cnt'];
			$recordSet->MoveNext ();
		}
		$dates = kkzstats::get_dates_array ();
		foreach ( $dates as $item ) {
			if (isset ( $visitors [$item] ))
				echo $item . ";" . $visitors [$item] . "\n";
			else
				echo $item . ";0\n";
		}
		die ();
	}

	public function get_dates_array() {
		function createDateRangeArray($strDateFrom, $strDateTo) {
			$aryRange = array ();

			$iDateFrom = mktime ( 1, 0, 0, substr ( $strDateFrom, 5, 2 ), substr ( $strDateFrom, 8, 2 ), substr ( $strDateFrom, 0, 4 ) );
			$iDateTo = mktime ( 1, 0, 0, substr ( $strDateTo, 5, 2 ), substr ( $strDateTo, 8, 2 ), substr ( $strDateTo, 0, 4 ) );

			if ($iDateTo >= $iDateFrom) {
				array_push ( $aryRange, date ( 'Y-m-d', $iDateFrom ) ); // first entry


				while ( $iDateFrom < $iDateTo-86400 ) {
					$iDateFrom += 86400; // add 24 hours
					$aryRange [date ( 'Y-m-d', $iDateFrom )] = date ( 'Y-m-d', $iDateFrom );
				}
			}
			return $aryRange;
		}
		$arr = createDateRangeArray ( '2011-09-01', date ( 'Y-m-d' ) );
		return $arr;
	}
}

class kkzoptions {

    function get_view_coms($id) {
        if ($id == 0) {
            return '№ Студентського <br />Група';
        }
        if ($id == 1) {
            return 'Комісія';
        }
        if ($id == 3) {
            return 'Група';
        }
        if ($id == 4) {
            return 'Ніяких';
        }
    }

    function get_view_color($id) {
        if ($id == 0) {
            return ' bgcolor="#990000" ';
        }
        if ($id == 1) {
            return ' bgcolor="#009900" ';
        }
    }

    function get_select_level($user, $id = 0) {
        $i = 1;
        while ($i < $user) {
            if ($i == $id) {
                $echo .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                $echo .= '<option value="' . $i . '">' . $i . '</option>';
            }
            $i++;
        }
        return $echo;
    }

    function get_radio_studid($id = 0) {
        if ($id == 0) {
            $echo = ' <tr>
        <th scope="row">Поля:</th>
        <td align="left">
          <label>
            <input name="sys_g_studid" type="radio" id="sys_g_studid" value="0" checked="checked" />
            № Студентського + Група
          </label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="1" id="sys_g_studid" />
            Комісія</label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="3" id="sys_g_studid" />
            Тільки група</label>
            <br />
          <label>
            <input type="radio" name="sys_g_studid" value="4" id="sys_g_studid"  />
            Ніяких</label>
          </td>
      </tr>';
        } elseif ($id == 1) {
            $echo = ' <tr>
        <th scope="row">Поля:</th>
        <td align="left">
          <label>
            <input name="sys_g_studid" type="radio" id="sys_g_studid" value="0" />
            № Студентського + Група
          </label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="1" id="sys_g_studid" checked="checked" />
            Комісія</label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="3" id="sys_g_studid" />
            Тільки група</label>
            <br />
          <label>
            <input type="radio" name="sys_g_studid" value="4" id="sys_g_studid"  />
            Ніяких</label>
          </td>
      </tr>';
        } elseif ($id == 3) {
            $echo = ' <tr>
        <th scope="row">Поля:</th>
        <td align="left">
          <label>
            <input name="sys_g_studid" type="radio" id="sys_g_studid" value="0" />
            № Студентського + Група
          </label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="1" id="sys_g_studid" />
            Комісія</label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="3" id="sys_g_studid" checked="checked" />
            Тільки група</label>
            <br />
          <label>
            <input type="radio" name="sys_g_studid" value="4" id="sys_g_studid"  />
            Ніяких</label>
          </td>
      </tr>';
        } elseif ($id == 4) {
            $echo = ' <tr>
        <th scope="row">Поля:</th>
        <td align="left">
          <label>
            <input name="sys_g_studid" type="radio" id="sys_g_studid" value="0" />
            № Студентського + Група
          </label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="1" id="sys_g_studid" />
            Комісія</label>
          <br />
          <label>
            <input type="radio" name="sys_g_studid" value="3" id="sys_g_studid"/>
            Тільки група</label>
         	<br />
          <label>
            <input type="radio" name="sys_g_studid" value="4" id="sys_g_studid" checked="checked" />
            Ніяких</label>
          </td>
      </tr>';
        }
        return $echo;
    }

    function get_radio($name, $user, $ins = 0) {
        if ($user == 1) {
            if ($ins == 1) {
                $echo = '<label>
              <input name="' . $name . '" type="radio" id="' . $name . '" value="1" checked="checked" />
              Так
          </label>  
          <label>
              <input type="radio" name="' . $name . '" value="0" id="' . $name . '" />
              Ні</label>';
            } else {

                $echo = '<label>
              <input name="' . $name . '" type="radio" id="' . $name . '" value="1" />
              Так
          </label>  
          <label>
              <input type="radio" name="' . $name . '" value="0" id="' . $name . '" checked="checked"/>
              Ні</label>';
            }
        } else {
            if ($ins == 0) {
                $echo = '<input name="' . $name . '" type="hidden" id="' . $name . '" value="' . $ins . '" /><b>Ні</b>';
            } else {
                $echo = '<input name="' . $name . '" type="hidden" id="' . $name . '" value="' . $ins . '" /><b>Так</b>';
            }
        }
        return $echo;
    }

    public function home() {
        global $user, $db;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_options'] == 1) {
                $groups = $db->Execute('select * from `sys_groups` order by `sys_g_level`');
                if ($groups->RecordCount() > 0) {
                    if (isset($_GET ['inform'])) {
                        $echo = '<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th bgcolor="#006600" style="color:#FFF">' . $_GET ['inform'] . '</th>
  </tr>
</table><br />';
                    }
                    $echo .= '<br>
					<center>[<a href="?do=options&f=add">Додати групу</a>]</center><br>
					<table width="100%" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <th width="30" scope="col">ID</th>
    <th scope="col">Назва</th>
    <th width="120" scope="col">Рівень доступу</th>
    <th width="100" scope="col">Реєстрація</th>
    <th width="70" scope="col">Адмін</th>
    <th width="70" scope="col">Групи</th>
    <th width="70" scope="col">Комісія</th>
    <th width="100" scope="col">Предмети</th>
    <th width="100" scope="col">Видалення</th>
    <th width="100" scope="col">Параметри</th>
    <th width="100" scope="col">Видалити</th>
  </tr>
  ';
                    while (!$groups->EOF) {
                        if ($groups->fields ['sys_g_level'] >= $user ['admin'] or $groups->fields ['id'] == 1) {
                            $echo .= '
						<tr>
    <td align="center">' . $groups->fields ['id'] . '</td>
    <td>' . $groups->fields ['sys_g_name'] . '</td>
    <td align="center">' . $groups->fields ['sys_g_level'] . '</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_register']) . '>&nbsp;</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_admin']) . '>&nbsp;</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_groups']) . '>&nbsp;</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_coms']) . '>&nbsp;</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_courses']) . '>&nbsp;</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_delete']) . '>&nbsp;</td>
    <td align="center" ' . kkzoptions::get_view_color($groups->fields ['sys_g_options']) . '>&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>';
                        } else {
                            $echo .= '
						<tr>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['id'] . '</td>
    <td style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['sys_g_name'] . '</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';">' . $groups->fields ['sys_g_level'] . '</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_register']) . '>&nbsp;</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_admin']) . '>&nbsp;</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_groups']) . '>&nbsp;</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_coms']) . '>&nbsp;</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_courses']) . '>&nbsp;</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_delete']) . '>&nbsp;</td>
    <td align="center" style="cursor:pointer;" onclick="window.location=\'?do=options&f=edit&id=' . $groups->fields ['id'] . '\';" ' . kkzoptions::get_view_color($groups->fields ['sys_g_options']) . '>&nbsp;</td>
    <td align="center">'.(($groups->fields ['id'] != 0)?'[<a href="?do=options&f=del&id=' . $groups->fields ['id'] . '" onClick="return confirm(\'Ви дійсно хочете видалити ' . $groups->fields ['sys_g_name'] . '? \n Увага! При видаленні групи, користувачі що знаходятся у цій групі будуть заблоковані.\');">Видалити</a>]':'').'</td>
  </tr>';
                        }

                        $groups->MoveNext();
                    }
                    $echo .= '</table>';
                    return $echo;
                }
            } else {
                die("У Вас немає доступу до цього розділу.");
            }
        }
    }

    public function edit_view($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_options'] == 1) {
                $groups = $db->Execute('select * from `sys_groups` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $echo = '
					
					<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Базові налаштунки:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th width="150" scope="row">Назва:</th>
        <td align="left"><input name="sys_g_name" type="text" id="sys_g_name" value="' . $groups->fields ['sys_g_name'] . '" size="60" /></td>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <th width="150" scope="row">Доступ (API):</th>
        <td align="left"><input name="sys_g_old" type="text" id="sys_g_old" value="' . $groups->fields ['sys_g_old'] . '" size="60" /></td>
      </tr>
      <tr>
        <th scope="row">Рівень доступу:</th>
        <td align="left"><select name="sys_g_level" id="sys_g_level">
          ' . kkzoptions::get_select_level($group->fields ['sys_g_level'], $groups->fields ['sys_g_level']) . '
          </select></td>
      </tr>
      <tr>
        <th scope="row">Доступ до Iнтернет:</th>
        <td align="left"><select name="sys_g_internet" id="sys_g_internet">
          <option value="1" '.(($groups->fields ['sys_g_internet']==1)?'selected="selected"':'').'>Пiсля 14:00</option>
          <option value="2" '.(($groups->fields ['sys_g_internet']==2)?'selected="selected"':'').'>Завжди</option>
          <option value="3" '.(($groups->fields ['sys_g_internet']==3)?'selected="selected"':'').'>Нiколи</option>
          </select></td>
      </tr>
      <tr>
        <th width="150" scope="row">Мультикомiсiйнiсть:</th>
        <td align="left">
          ' . kkzoptions::get_radio('sys_g_allcoms', 1, $groups->fields ['sys_g_allcoms']) . '
        </td>
      </tr>
      ' . kkzoptions::get_radio_studid($groups->fields ['sys_g_studid']) . '
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Права доступу:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th width="150" scope="row">Адмін:</th>
        <td align="left">
          ' . kkzoptions::get_radio('sys_g_admin', $group->fields ['sys_g_admin'], $groups->fields ['sys_g_admin']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Групи:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_groups', $group->fields ['sys_g_groups'], $groups->fields ['sys_g_groups']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Комісія:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_coms', $group->fields ['sys_g_coms'], $groups->fields ['sys_g_coms']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Предмети:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_courses', $group->fields ['sys_g_courses'], $groups->fields ['sys_g_courses']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Можливiсть видалення:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_delete', $group->fields ['sys_g_delete'], $groups->fields ['sys_g_delete']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Параметри:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_options', $group->fields ['sys_g_options'], $groups->fields ['sys_g_options']) . '
        </td>
      </tr>
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Реєстрація:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
   		<tr>
        <th scope="row">Доступ:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_register', '1', $groups->fields ['sys_g_register']) . '
        </td>
        <tr>
        <th scope="row">Використовувати пароль:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_onpass', '1', $groups->fields ['sys_g_onpass']) . '
        </td>
      <tr>
        <th width="150" scope="row">Пароль:</th>
        <td align="left"><input name="sys_g_pass" type="text" id="sys_g_pass" value="' . $groups->fields ['sys_g_pass'] . '" size="60" /></td>
      </tr>
      </tr>
      
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Вхід:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
   		<tr>
        <th scope="row">Заборона:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_off', '1', $groups->fields ['sys_g_off']) . '
        </td>
        <tr>
      <tr>
        <th width="150" scope="row">Причина відключення:</th>
        <td align="left"><input name="sys_g_woff" type="text" id="sys_g_woff" value="' . $groups->fields ['sys_g_woff'] . '" size="60" /></td>
      </tr>
      </tr>
      
    </table></th>
  </tr>
</table>
<p align="center">
<input name="id" type="hidden" id="id" value="' . $groups->fields ['id'] . '" />
  <input type="submit" name="submit" id="submit" value="Зберегти" />
</p>
</form>';
                    return $echo;
                }
            }
        }
    }

    public function edit_submit($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_options'] == 1) {
                $groups = $db->Execute('select * from `sys_groups` where `id`=' . $_POST ['id']);
                if ($groups->RecordCount() > 0) {
                    $new .= ' `sys_g_name`=' . $db->qstr($_POST ['sys_g_name']) . ', ';
                    $new .= ' `sys_g_old`=' . $db->qstr($_POST ['sys_g_old']) . ', ';
                    $new .= ' `sys_g_level`=' . $db->qstr($_POST ['sys_g_level']) . ', ';
                    $new .= ' `sys_g_internet`=' . $db->qstr($_POST ['sys_g_internet']) . ', ';
                    $new .= ' `sys_g_allcoms`=' . $db->qstr($_POST ['sys_g_allcoms']) . ', ';
                    $new .= ' `sys_g_studid`=' . $db->qstr($_POST ['sys_g_studid']) . ', ';
                    $new .= ' `sys_g_admin`=' . $db->qstr($_POST ['sys_g_admin']) . ', ';
                    $new .= ' `sys_g_groups`=' . $db->qstr($_POST ['sys_g_groups']) . ', ';
                    $new .= ' `sys_g_coms`=' . $db->qstr($_POST ['sys_g_coms']) . ', ';
                    $new .= ' `sys_g_courses`=' . $db->qstr($_POST ['sys_g_courses']) . ', ';
                    $new .= ' `sys_g_delete`=' . $db->qstr($_POST ['sys_g_delete']) . ', ';
                    $new .= ' `sys_g_options`=' . $db->qstr($_POST ['sys_g_options']) . ', ';
                    //REGISTER
                    $new .= ' `sys_g_register`=' . $db->qstr($_POST ['sys_g_register']) . ', ';
                    $new .= ' `sys_g_onpass`=' . $db->qstr($_POST ['sys_g_onpass']) . ', ';
                    $new .= ' `sys_g_pass`=' . $db->qstr($_POST ['sys_g_pass']) . ', ';
                    //LOGIN
                    $new .= ' `sys_g_off`=' . $db->qstr($_POST ['sys_g_off']) . ', ';
                    $new .= ' `sys_g_woff`=' . $db->qstr($_POST ['sys_g_woff']) . ' ';

                    $sql = 'UPDATE `sys_groups` SET ' . $new . ' WHERE `sys_groups`.`id`=' . $_POST ['id'];

                    if ($db->Execute($sql) === false) {
                        die('error: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'sys_group','edit-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        //	echo $sql;
                        //	print_r($_POST); 
                        //	die();
                        header('Location: http://kkzcore.pp.ua/admin/?do=options&inform=Група оновлена');
                    }
                }
            }
        }
    }

    public function add_view() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_options'] == 1) {

                $echo = '
					
					<form id="form1" name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Базові налаштунки:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th width="150" scope="row">Назва:</th>
        <td align="left"><input name="sys_g_name" type="text" id="sys_g_name" value="" size="60" /></td>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <th width="150" scope="row">Доступ (API):</th>
        <td align="left"><input name="sys_g_old" type="text" id="sys_g_old" value="" size="60" /></td>
      </tr>
      <tr>
        <th scope="row">Рівень доступу:</th>
        <td align="left"><select name="sys_g_level" id="sys_g_level">
          ' . kkzoptions::get_select_level($group->fields ['sys_g_level']) . '
          </select></td>
      </tr>
      <tr>
        <th scope="row">Доступ до Iнтернет:</th>
        <td align="left"><select name="sys_g_internet" id="sys_g_internet">
          <option value="1">Пiсля 14:00</option>
          <option value="2">Завжди</option>
          <option value="3">Нiколи</option>
          </select></td>
      </tr>
      <tr>
        <th width="150" scope="row">Мультикомiсiйнiсть:</th>
        <td align="left">
          ' . kkzoptions::get_radio('sys_g_allcoms', 1) . '
        </td>
      </tr>
      ' . kkzoptions::get_radio_studid() . '
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Права доступу:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th width="150" scope="row">Адмін:</th>
        <td align="left">
          ' . kkzoptions::get_radio('sys_g_admin', $group->fields ['sys_g_admin']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Групи:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_groups', $group->fields ['sys_g_groups']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Комісія:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_coms', $group->fields ['sys_g_coms']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Предмети:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_courses', $group->fields ['sys_g_courses']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Можливiсть видалення:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_delete', $group->fields ['sys_g_delete']) . '
        </td>
      </tr>
      <tr>
        <th scope="row">Параметри:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_options', $group->fields ['sys_g_options']) . '
        </td>
      </tr>
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Реєстрація:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
   		<tr>
        <th scope="row">Доступ:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_register', '1', $groups->fields ['sys_g_register']) . '
        </td>
        <tr>
        <th scope="row">Використовувати пароль:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_onpass', '1', $groups->fields ['sys_g_onpass']) . '
        </td>
      <tr>
        <th width="150" scope="row">Пароль:</th>
        <td align="left"><input name="sys_g_pass" type="text" id="sys_g_pass" value="' . $groups->fields ['sys_g_pass'] . '" size="60" /></td>
      </tr>
      </tr>
      
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="row" style="border-right:2px solid #000">Вхід:</th>
    <th><table width="100%" border="0" cellspacing="0" cellpadding="5">
   		<tr>
        <th scope="row">Заборона:</th>
        <td align="left">
        ' . kkzoptions::get_radio('sys_g_off', '1', $groups->fields ['sys_g_off']) . '
        </td>
        <tr>
      <tr>
        <th width="150" scope="row">Причина відключення:</th>
        <td align="left"><input name="sys_g_pass" type="text" id="sys_g_pass" value="" size="60" /></td>
      </tr>
      </tr>
      
    </table></th>
  </tr>
</table>
<p align="center">
  <input type="submit" name="submit" id="submit" value="Додати" />
</p>
</form>';
                return $echo;
            }
        }
    }

    public function add_submit() {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_options'] == 1) {

                $sql = 'insert into `sys_groups` (`sys_g_name`,`sys_g_level`,`sys_g_internet`,`sys_g_allcoms`,`sys_g_studid`,`sys_g_admin`,`sys_g_groups`,`sys_g_coms`,`sys_g_courses`,`sys_g_options`,`sys_g_old`,`sys_g_register`,`sys_g_onpass`,`sys_g_pass`,`sys_g_off`,`sys_g_woff`, `sys_g_delete`) values (' . $db->qstr($_POST ['sys_g_name']) . ',' . $db->qstr($_POST ['sys_g_level']) . ',' . $db->qstr($_POST ['sys_g_internet']) . ',' . $db->qstr($_POST ['sys_g_allcoms']) . ',' . $db->qstr($_POST ['sys_g_studid']) . ',' . $db->qstr($_POST ['sys_g_admin']) . ',' . $db->qstr($_POST ['sys_g_groups']) . ',' . $db->qstr($_POST ['sys_g_coms']) . ',' . $db->qstr($_POST ['sys_g_courses']) . ',' . $db->qstr($_POST ['sys_g_options']) . ',' . $db->qstr($_POST ['sys_g_old']) . ',' . $db->qstr($_POST ['sys_g_register']) . ',' . $db->qstr($_POST ['sys_g_onpass']) . ',' . $db->qstr($_POST ['sys_g_pass']) . ',' . $db->qstr($_POST ['sys_g_off']) . ',' . $db->qstr($_POST ['sys_g_woff']) . ',' . $db->qstr($_POST ['sys_g_delete']) . ')';

                if ($db->Execute($sql) === false) {
                    die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                } else {
                    $id = $db->Insert_ID();
                    $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'sys_group','add-" . $id . "-success','0');";
                    $db->Execute($sql2);
                    header('Location: http://kkzcore.pp.ua/admin/?do=options&inform=Група додана');
                }
            }
        }
    }

    public function delete($id) {
        global $db, $user;
        $group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
        if ($group->RecordCount() > 0) {
            if ($group->fields ['sys_g_groups'] == 1) {
                $groups = $db->Execute('select * from `sys_groups` where `id`=' . $id);
                if ($groups->RecordCount() > 0) {
                    $sql = 'delete from `sys_groups` WHERE `sys_groups`.`id`=' . $id;
                    $sql2 = 'delete from `users` WHERE `users`.`cheked`=0 and `users`.`sys_group`=' . $id;
                    $sql3 = 'UPDATE `users` SET `cheked`=0, `sys_group`=0, `comments`=' . $db->qstr('Видалена системна група: ' . $groups->fields ['sys_g_name']) . ' WHERE `users`.`cheked`=1 and `users`.`sys_group`=' . $id;

                    if ($db->Execute($sql) === false or $db->Execute($sql2) === false or $db->Execute($sql3) === false) {
                        die('error: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'sys_group','del-" . $_POST ['id'] . "-success','0');";
                        $db->Execute($sql2);
                        header('Location: http://kkzcore.pp.ua/admin/?do=options&inform=Група видалена');
                    }
                }
            }
        }
    }

}

class kkzusers {

    public function deact_users() {
        global $db, $user;
        $deact_users = $db->Execute('select `users`.`id` as userid, `users`.*, `sys_groups`.* from `users`,`sys_groups` where `sys_groups`.`id`=`users`.`sys_group` and (`users`.cheked=0 OR `users`.active=0) ORDER BY `users`.`id` DESC');
        if ($deact_users->RecordCount() > 0) {
            $deactive_users = '<h3 align="center">Не активовані та заблокованi користувачі</h3>
<table width="100%" border="0" cellspacing="0" class="tablecolored">
  <tr>
    <th  width="50" scope="col">ID</th>
    <th  scope="col">ПІП</th>
    <th  width="140" scope="col">Дата реєстрації</th>
    <th  width="140" scope="col">Системна група</th>
    <th  width="130" scope="col">№ Студенського</th>
    <th  width="100" scope="col">Видалити</th>
  </tr>';
            while (!$deact_users->EOF) {
                $deactive_users .= '<tr ' . (($deact_users->fields ['active'] == 0) ? ' bgColor="#F99"' : '') . '>
    <td  align=center style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $deact_users->fields ['userid'] . '\';">' . $deact_users->fields ['userid'] . '</td>
    <td   style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $deact_users->fields ['userid'] . '\';">' . $deact_users->fields ['lastname'] . ' ' . $deact_users->fields ['name'] . ' ' . $deact_users->fields ['fathername'] . ' </td>
    <td  align=center style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $deact_users->fields ['userid'] . '\';">' . $db->UserTimeStamp($deact_users->fields ['register'], 'd-m-Y H:i:s') . '</td>    
    <td  align=center style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $deact_users->fields ['userid'] . '\';">' . $deact_users->fields ['sys_g_name'] . '</td>
    <td  align=center style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $deact_users->fields ['userid'] . '\';">' . $deact_users->fields ['studid'] . '</td>
    <td  align=center>' . (($user['all']['sys_g_delete'] == 1 OR ($user['all']['sys_g_delete'] == 0 AND $deact_users->fields ['cheked'] == 0)) ? '[<a href="?do=users&f=del&id=' . $deact_users->fields ['userid'] . '" onClick="return confirm(\'Ви дійсно хочете видалити ' . $deact_users->fields ['lastname'] . ' ' . $deact_users->fields ['name'] . ' ' . $deact_users->fields ['fathername'] . '?\');">Видалити</a>]' : '') . '</td>
  </tr>';
                $deact_users->MoveNext();
            }
            $deactive_users .= '</table>';
            return $deactive_users;
        } else {
            $deactive_users = '';
            return $deactive_users;
        }
    }

    public function act_users() {
        global $db, $user;
        if (!isset($_GET ['date'])) {
            if (date('n') < 9) {
                $y_from = date('Y') - 1;
                $y_to = date('Y');
            } else {
                $y_from = date('Y');
                $y_to = date('Y') + 1;
            }
        } elseif ($_GET ['date'] == '') {
            $y_from = 2009;
            $y_to = date('Y') + 1;
        } else {
            (int) $y_from = $_GET ['date'];
            (int) $y_to = $_GET ['date'] + 1;
        }
        $sql_add = '';
        if (isset($_GET['group']) && $_GET['group'] != "") {
            $sql_add .= ' AND `users`.`group` = ' . $_GET['group'] . ' ';
        }

        if (isset($_GET['sys_g']) && $_GET['sys_g'] != "") {
            $sql_add .= ' AND `sys_groups`.`id` = ' . $_GET['sys_g'] . ' ';
        }

        if (isset($_GET['archive']) && $_GET['archive'] == "1") {
            $sql_add .= '';
        } elseif (isset($_GET['archive']) && $_GET['archive'] == "2") {
            $sql_add .= ' AND `users`.`archived` = 1';
        } else {
            $sql_add .= ' AND `users`.`archived` = 0 ';
        }

        $users = $db->Execute("select `users`.`id` as userid, `users`.*, `sys_groups`.* from `users`,`sys_groups` where `sys_groups`.`id`=`users`.`sys_group` and `users`.cheked=1 and `users`.active=1 and `users`.`register`>'" . $y_from . "-09-01 00:00:00' and `users`.`register`<'" . $y_to . "-09-01 00:00:00' " . $sql_add . " order by `users`.`id` DESC");
        if (!$users) {
            print $db->ErrorMsg();
            die();
        } else {
            if ($users->RecordCount() > 0) {
                if ($user['all']['sys_g_delete'] == 1) {
                    $h_delete = '<th width="100" scope="col">Видалити</th>';
                } else {
                    $h_delete = '';
                }
                $content = '<table width="100%" border="0" cellspacing="0" class="tablecolored">
  <tr>
    <th width="50" scope="col">ID</th>
    <th scope="col">ПІП</th>
    <th width="140" scope="col">Системна група</th>
    <th width="130" scope="col">№ Студенського</th>
    ' . $h_delete . '
  </tr>';
                while (!$users->EOF) {
                    if ($users->fields ['sys_g_studid'] == 0) {
                        $studid = $users->fields ['studid'];
                    } else {
                        $studid = '---';
                    }
                    if ($users->fields ['archived'] == 1) {
                        $tr_add = 'bgcolor="#87CEFA"';
                    } else {
                        $tr_add = '';
                    }
                    if ($users->fields ['sys_g_level'] >= $user ['admin']) {
                        $content .= '<tr ' . $tr_add . '>
    <td align=center>' . $users->fields ['userid'] . '</td>
    <td class="tableth">' . $users->fields ['lastname'] . ' ' . $users->fields ['name'] . ' ' . $users->fields ['fathername'] . '</td>
    <td align=center>' . $users->fields ['sys_g_name'] . '</td>
    <td align=center>' . $studid . '</td>
    <td class="tableth">&nbsp;</td>
  </tr>';
                    } else {
                        if ($user['all']['sys_g_delete'] == 1) {
                            $r_delete = '<td align=center>[<a href="?do=users&f=del&id=' . $users->fields ['userid'] . '" onClick="return confirm(\'Ви дійсно хочете видалити ' . $users->fields ['lastname'] . ' ' . $users->fields ['name'] . ' ' . $users->fields ['fathername'] . '?\');">Видалити</a>]</td>';
                        } else {
                            $r_delete = '';
                        }
                        $content .= '<tr ' . $tr_add . '>
    <td align=center  style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $users->fields ['userid'] . '\';">' . $users->fields ['userid'] . '</td>
    <td style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $users->fields ['userid'] . '\';">' . $users->fields ['lastname'] . ' ' . $users->fields ['name'] . ' ' . $users->fields ['fathername'] . '</td>
    <td align=center style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $users->fields ['userid'] . '\';">' . $users->fields ['sys_g_name'] . '</td>
    <td align=center style="cursor:pointer;" onclick="window.location=\'?do=users&f=edit&id=' . $users->fields ['userid'] . '\';">' . $studid . '</td>
    ' . $r_delete . '
</tr>';
                    }

                    $users->MoveNext();
                }
                $content .= '</table>';
                return $content;
            } else {
                $content = '<center>На даний час у базі немає ні одного користувача за заданими параметрами.</center>';
                return $content;
            }
        }
    }

    public function edit_show($id) {
        global $db, $user;
        $users = &$db->Execute("select `users`.`id` as userid, `users`.*, `sys_groups`.* from `users`,`sys_groups` where `sys_groups`.`id`=`users`.`sys_group` and `sys_groups`.`sys_g_level`<" . $user ['admin'] . " and `users`.`id`=" . $id);
        if (!$users) {
            print $db->ErrorMsg();
            die();
        } else {
            if ($users->RecordCount() > 0) {
                $sysgroups = &$db->Execute("select `sys_groups`.* from `sys_groups` where `sys_groups`.`sys_g_level`<" . $user ['admin'] . " order by `sys_g_studid`");
                if (!$sysgroups) {
                    print $db->ErrorMsg();
                    die();
                } else {
                    if ($sysgroups->RecordCount() < 1) {
                        echo "select `sys_groups`.* from `sys_groups` where `sys_groups`.`sys_g_level`<" . $user ['admin'] . " order by `sys_g_studid`";
                        die("База системних груп не може бути пустою або Ви не маєте доступу до користувача.");
                    }
                }
                while (!$sysgroups->EOF) {
                    $echo .= 'case \'' . $sysgroups->fields ['id'] . '\': ';
                    if ($sysgroups->fields ['sys_g_studid'] == 0) {
                        $echo .= '$(\'#userfields\').html(first); 
		break;
		';
                    } elseif ($sysgroups->fields ['sys_g_studid'] == 1) {
                        $echo .= '$(\'#userfields\').html(second); 
		break;
		';
                    } elseif ($sysgroups->fields ['sys_g_studid'] == 3) {
                        $echo .= '$(\'#userfields\').html(third); 
		break;
		';
                    } elseif ($sysgroups->fields ['sys_g_studid'] == 4) {
                        $echo .= '$(\'#userfields\').html(\'\');
		break;
		';
                    }

                    $sysgroups->MoveNext();
                }
                $sysgroups->MoveFirst();
                if ($users->fields ['cheked'] != 0) {
                    $cheked = 'Так';
                } else {
                    $cheked = '<a href="/admin/?do=users&f=activate&id=' . $id . '" onClick=\'return confirm("Ви впевнені, що хочете активувати даного користувача?\n-----УВАГА!-----\nПеред тим як активувати корустувачча звірте його дані з студентським білетом!")\'>Перевiрити</a>';
                }
                $active = ($users->fields ['active'] == 1) ? '<input name="active" type="checkbox" id="active" value="1" checked="checked" /> Активований' : '<input name="active" type="checkbox" id="active" value="1" /> Активований';

                if($user['all']['sys_g_options'] == 1){
                    $internet = '<tr>
        <th scope="row">Доступ до Iнтернет:</th>
        <td align="left"><select name="internet" id="internet">
          <option value="1" '.(($users->fields ['internet']==1)?'selected="selected"':'').'>Згiдно системної групи</option>
          <option value="2" '.(($users->fields ['internet']==2)?'selected="selected"':'').'>Нiколи</option>
          </select></td>
      </tr>';
                } else {
                    $internet = '';
                }
                $content = '<script>
function sysgroup(ins){
	first = \'\
	<table width="100%" border="0" cellspacing="0" cellpadding="5">\
      <tr>\
        <td width="150" align="right" scope="row">Номер студенського:</td>\
        <td align="left"><input name="studid" type="text" id="studid" size="50" value="' . $users->fields ['studid'] . '" /></td>\
      </tr>\
      <tr>\
        <td align="right" scope="row">Група:</td>\
        <td align="left"><select name="group" id="group">\
          ' . str_replace("'", "\'", kkzgroups::getgroups_select($users->fields ['group'])) . '\
        </select></td>\
      </tr>\
    </table>\
    \';
    second = \'\
    <table width="100%" border="0" cellspacing="0" cellpadding="5">\
      <tr>\
        <td align="right" scope="row" width="150">Комісія:</td>\
        <td align="left"><select name="com" id="com">\
          ' . str_replace("'", "\'", kkzcoms::getcoms_select($users->fields ['com'])) . '\
        </select></td>\
      </tr>\
    </table>\
    \';
    third = \'\
    <table width="100%" border="0" cellspacing="0" cellpadding="5">\
      <tr>\
        <td align="right" scope="row" width="150">Група:</td>\
        <td align="left"><select name="group" id="group">\
          ' . str_replace("'", "\'", kkzgroups::getgroups_select($users->fields ['group'])) . '\
        </select></td>\
      </tr>\
    </table>\
    \';
    
	switch(ins){
		' . $echo . '
		default: alert("Error in JS (sysgroup function)! ins="+ins);
	}
}
$(document).ready(
  function()
  {
  	sysgroup(\'' . $users->fields ['sys_group'] . '\');
  }
);

</script>
<form id="user" name="user" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="col" style="border-right:2px solid #000;">Базові налаштунки:</th>
    <th scope="col"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="150" align="right" scope="row">Логін:</td>
        <td align="left">' . $users->fields ['login'] . '</td>
      </tr>
      <tr>
        <td width="150" align="right" scope="row">Дата реєстрації:</td>
        <td align="left">' . $db->UserTimeStamp($users->fields ['register'], 'd-m-Y H:i:s') . '</td>
      </tr>
      <tr>
        <td width="150" align="right" scope="row">Останнiй вхiд:</td>
        <td align="left">' . $db->UserTimeStamp($users->fields ['lastvisit'], 'd-m-Y H:i:s') . '</td>
      </tr>
      <tr>
        <td align="right" scope="row">Код збросу:</td>
        <td align="left"><a href="#" onClick="$.post(\'?do=ajax_genpass\', {id: \'' . $users->fields ["userid"] . '\'}, function(data) {alert(data);});">Згенерувати</a></td>
      </tr>
      <tr>
        <td align="right" scope="row">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" scope="row">Прізвище:</td>
        <td align="left"><input name="lastname" type="text" id="lastname" size="50" value=' . str_replace("'", "&#39;", $users->fields ['lastname']) . ' /></td>
      </tr>
      <tr>
        <td align="right" scope="row">Ім\'я:</td>
        <td align="left"><input name="firstname" type="text" id="firstname" size="50" value=' . str_replace("'", "&#39;", $users->fields ['name']) . ' /></td>
      </tr>
      <tr>
        <td align="right" scope="row">По батькові:</td>
        <td align="left"><input name="fathername" type="text" id="fathername" size="50" value=' . str_replace("'", "&#39;", $users->fields ['fathername']) . ' /></td>
      </tr>
      <tr>
        <td align="right" scope="row">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" scope="row">Нотатки:</td>
        <td align="left"><textarea name="comments" id="comments" cols="45" rows="5">' . $users->fields ['comments'] . '</textarea></td>
      </tr>
    </table></th>
  </tr>
</table>
<br />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="200" scope="col" style="border-right:2px solid #000;">Налаштунки прав доступу:</th>
    <th scope="col">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td width="150" align="right" scope="row">Системна група:</td>
        <td align="left"><select name="sys_group" id="sys_group" onchange="sysgroup(this.value);">
           ' . kkzgroups::get_sys_groups_select($users->fields ['sys_group']) . '
          </select></td>
      </tr>
      <tr>
        <td align="right" scope="row">Активність:</td>
        <td align="left">
        ' . $active . '
          
          </td>
      </tr>
      <tr>
        <td align="right" scope="row">Перевiрений:</td>
        <td align="left">
        ' . $cheked . '
          
          </td>
      </tr>
      <tr>
        <td align="right" scope="row">Архiв:</td>
        <td align="left">
        <input type="checkbox" name="archived" ' . (($users->fields['archived'] == 1) ? 'checked="checked"' : '') . ' /> Архiвний
          </td>
      </tr>
      '.$internet.'
      <tr>
        <td align="right" scope="row">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
    </table>
    
    <div id=\'userfields\'>
    
    </div>
    
    
    </th>
  </tr>
</table>
<p align="center">
	<input name="id" type="hidden" value="' . $users->fields ['userid'] . '" />
	<input type="submit" name="submit" id="submit" value="Зберегти" />
</p>
</form>';
            } else {
                $content = 'Ви не маєте прав на редагування даного користувача.';
            }
            return $content;
        }
    }

    public function edit_submit() {
        global $db, $user;
        $users = &$db->Execute("select `users`.`id` as userid, `users`.*, `sys_groups`.* from `users`,`sys_groups` where `sys_groups`.`id`=`users`.`sys_group` and `sys_groups`.`sys_g_level`<" . $user ['admin'] . " and `users`.`id`=" . $_POST ['id']);
        if (!$users) {
            print $db->ErrorMsg();
            die();
        } else {
            if ($users->RecordCount() > 0) {

                $sysgroups = &$db->Execute("select `sys_groups`.* from `sys_groups` where `sys_groups`.`sys_g_level`<" . $user ['admin'] . " and `sys_groups`.`id`=" . $_POST ['sys_group']);
                if (!$sysgroups) {
                    print $db->ErrorMsg();
                    die();
                } else {
                    if ($sysgroups->RecordCount() > 0) {

                        if (strlen($_POST ['pass']) > 5) {
                            $new .= ' `pass` = ' . $db->qstr(md5($_POST ['pass'])) . ', ';
                        }

                        if ($sysgroups->fields ['sys_g_studid'] == 0) {
                            $new .= ' `studid` = ' . $db->qstr($_POST ['studid']) . ', ';
                            $new .= ' `group` = ' . $db->qstr($_POST ['group']) . ', ';
                            $new .= ' `com` = ' . $db->qstr('') . ', ';
                        } elseif ($sysgroups->fields ['sys_g_studid'] == 3) {
                            $new .= ' `studid` = ' . $db->qstr('') . ', ';
                            $new .= ' `group` = ' . $db->qstr($_POST ['group']) . ', ';
                            $new .= ' `com` = ' . $db->qstr('') . ', ';
                        } elseif ($sysgroups->fields ['sys_g_studid'] == 1) {
                            $new .= ' `com` = ' . $db->qstr($_POST ['com']) . ', ';
                            $new .= ' `studid` = ' . $db->qstr('') . ', ';
                            $new .= ' `group` = ' . $db->qstr('') . ', ';
                        } elseif ($sysgroups->fields ['sys_g_studid'] == 4) {
                            $new .= ' `com` = ' . $db->qstr('') . ', ';
                            $new .= ' `studid` = ' . $db->qstr('') . ', ';
                            $new .= ' `group` = ' . $db->qstr('') . ', ';
                        }
                        
                        if($user['all']['sys_g_options'] == 1){
                            $new .= ' `internet` = ' . $db->qstr($_POST ['internet']) . ', ';
                        }

                        $new .= ' `name` = ' . $db->qstr($_POST ['firstname']) . ', ';
                        $new .= ' `lastname` = ' . $db->qstr($_POST ['lastname']) . ', ';
                        $new .= ' `fathername` = ' . $db->qstr($_POST ['fathername']) . ', ';

                        $new .= ' `comments` = ' . $db->qstr($_POST ['comments']) . ', ';

                        $new .= ' `active` = ' . $db->qstr(($_POST ['active']) ? 1 : 0) . ', ';
                        $new .= ' `archived` = ' . (($_POST['archived'] == 'on') ? 1 : 0) . ', ';
                        $new .= ' `sys_group` = ' . $db->qstr($_POST ['sys_group']) . ' ';

                        $sql = 'UPDATE `users` SET ' . $new . ' WHERE `users`.`id`=' . $_POST ['id'];

                        if ($db->Execute($sql) === false) {
                            die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                        } else {
                            $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'user','edit-" . $_POST ['id'] . "-success','0');";
                            $db->Execute($sql2);
                            header('Location: http://kkzcore.pp.ua/admin/?do=users&inform=Користувач+оновлений');
                        }
                    } else {
                        die('Ви не маєте прав для додавання користувача в дану групу.');
                    }
                }
            } else {
                die('Ви не маєте прав на редагування даного користувача.');
            }
        }
    }

    public function activate($id) {
        global $db, $user;
        $users = &$db->Execute("select `users`.`id` as userid, `users`.*, `sys_groups`.* from `users`,`sys_groups` where `sys_groups`.`id`=`users`.`sys_group` and `sys_groups`.`sys_g_level`<" . $user ['admin'] . " and `users`.`id`=" . $_GET ['id']);
        if (!$users) {
            print $db->ErrorMsg();
            die();
        } else {
            if ($users->RecordCount() > 0) {
                $new .= ' `cheked` = 1 ';

                $sql = 'UPDATE `users` SET ' . $new . ' WHERE `users`.`id`=' . $_GET ['id'];

                if ($db->Execute($sql) === false) {
                    die('error inserting: ' . $db->ErrorMsg() . '<BR>');
                } else {
                    $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'user','activate-" . $_GET ['id'] . "-success','0');";
                    $db->Execute($sql2);
                    //echo $sql;
                    header('Location: http://kkzcore.pp.ua/admin/?do=users&inform=Користувач+перевiрений+та+активований');
                    die();
                }
            } else {
                die('Ви не маєте прав на редагування даного користувача.');
	            die();
            }
        }
    }

    public function delete($id) {
        global $db, $user;
        $users = &$db->Execute("select `users`.`id` as userid, `users`.*, `sys_groups`.* from `users`,`sys_groups` where `sys_groups`.`id`=`users`.`sys_group` and `sys_groups`.`sys_g_level`<" . $user ['admin'] . " and `users`.`id`=" . $id);
        if (!$users) {
            print $db->ErrorMsg();
            die();
        } else {
            if ($users->RecordCount() > 0) {
                if ($user['all']['sys_g_delete'] == 1 OR $users->fields['cheked'] == 0) {
                    $sql = 'DELETE from `users` where `users`.`id`=' . $id;
                    if ($db->Execute($sql) === false) {
                        die('error: ' . $db->ErrorMsg() . '<BR>');
                    } else {
                        //echo $sql;
                        $sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $user ['id'] . ",'user','user-" . $id . "-success','0');";
                        $db->Execute($sql2);
                        header('Location: http://kkzcore.pp.ua/admin/?do=users&inform=Користувач+видалений');
	                    die();
                    }
                } else {
                    header('Location: http://kkzcore.pp.ua/admin/?do=users&inform=Недостатньо+прав.');
	                die();
                }
            }
        }
    }

}

//KKZCORE


class kkzcore {

    public function home() {
        global $db, $user;
        $users = $db->Execute('select count(*) as users from users WHERE `archived`=0');
        $archived_users = $db->Execute('select count(*) as users from users WHERE `archived`=1');
        $deact_users = $db->Execute('select count(*) as users from users where cheked=0');
        $today_users = $db->Execute('select count(*) as users from users where lastvisit>\'' . date('Y-m-d 00:00:00') . '\' and lastvisit<\'' . date('Y-m-d 23:59:59') . '\'');

        $groups = $db->Execute('select count(*) as groups from groups WHERE `archived`=0');
        $cour = $db->Execute('select count(*) as cour from courses');
        $com = $db->Execute('select count(*) as com from commissions');

        $content = '
		<center>
                <a href="/?do=cabinet&p=go&site=1"> <img src="images/logos_01.png" width="276" height="60" border="0" /></a> 
			<a href="/?do=cabinet&p=go&site=2"> <img src="images/logos_02.png" width="262" height="60" border="0" /></a> 
			 <!--<a href="http://kkzdls.pp.ua/?key="> <img src="images/logos_03.png"width="272" height="60" border="0" /></a>-->
                         <a href="/?do=cabinet&p=go&site=3"> <img src="images/logos_04.png" width="272" height="60" border="0" /></a> 
                         </center>
			<br /><br />
	<table width="303" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <td width="230">Загальная кількість користувачів:</td>
    <td width="53">' . $users->fields ['users'] . '</td>
  </tr>
  <tr>
    <td width="230">+ заархiвованих:</td>
    <td width="53">' . $archived_users->fields ['users'] . '</td>
  </tr>
  <tr>
    <td>З них не активованих:</td>
    <td>' . $deact_users->fields ['users'] . '</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Сьогодні до системи зайшло:</td>
    <td>' . $today_users->fields ['users'] . '</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Групп:</td>
    <td>' . $groups->fields ['groups'] . '</td>
  </tr>
  <tr>
    <td>Предметів:</td>
    <td>' . $cour->fields ['cour'] . '</td>
  </tr>
  <tr>
    <td>Комісій:</td>
    <td>' . $com->fields ['com'] . '</td>
  </tr>
</table>';
        return $content;
    }

    public function users() {
        global $db;
        switch (@$_GET ['f']) {
            case 'edit' :
                if (isset($_POST ['submit']))
                    kkzusers::edit_submit();
                $content = kkzusers::edit_show($_GET ['id']);
                break;
            case 'activate':
                kkzusers::activate();
                break;
            case 'del' :
                kkzusers::delete($_GET ['id']);
            default :
                if (isset($_GET ['inform'])) {
                    $b_inform = '<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th bgcolor="#006600" style="color:#FFF">' . $_GET ['inform'] . '</th>
  </tr>
</table><br />';
                }
                $content = '<script language="javascript">
function lookup(inputString) {
   if(inputString.length == 0) {
      $(\'#suggestions\').fadeOut(); // Hide the suggestions box
   } else {
      $.post("?do=ajax", {queryString: ""+inputString+"", do: "users_search"}, function(data) { // Do an AJAX call
         $(\'#suggestions\').fadeIn(); // Show the suggestions box
         $(\'#suggestions\').html(data); // Fill the suggestions box
      });
   }
}
</script>
' . $b_inform . '
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <th width="40%" align="right" valign="top">Пошук за номером студентського:</th>
    <th align="left" valign="top">
    <input name="search" type="text" id="search" style="font-size:18px; width:400px;" value="" onkeyup="lookup(this.value);" />
    <div id="suggestions" style="position: relative; left:0px; width:400px; display:none;">ff</div>
    </th>
  </tr>
  
</table>
' . kkzusers::deact_users() . '
<h3 align="center">Активовані користувачі</h3>
<form action="" method="GET">
<input type="hidden" name="do" value="users">
<p align="center">';
                if (date('n') < 9) {
                    $y = date('Y') - 1;
                } else {
                    $y = date('Y');
                }

                if (!isset($_GET ['date'])) {
                    $to_check = $y;
                } else {
                    $to_check = $_GET ['date'];
                }
                $content .= '<select name="date" onchange="this.form.submit();">';
                if (isset($_GET ['date']) && $_GET ['date'] == '') {
                    $content .= "<option value=\"\" selected='selected'>Усi роки</option>";
                } else {
                    $content .= "<option value=\"\">Усi роки</option>";
                }
                for ($i = 2009; $i <= $y; $i++) {
                    if ($i == $to_check) {
                        $content .= "<option value=" . $i . " selected='selected'>" . $i . "/" . ($i + 1) . "</option>";
                    } else {
                        $content .= "<option value=" . $i . ">" . $i . "/" . ($i + 1) . "</option>";
                    }
                }
                $content .= '</select>';

                // GROUPS

                $content .= '<select name="group" onchange="this.form.submit();">';

                $groups = $db->Execute('select * from `groups` WHERE `archived`=0 order by `name`');
                if (!$groups) {
                    print $db->ErrorMsg();
                    die();
                }
                if (isset($_GET['group']) && ($_GET ['group'] == '')) {
                    $content .= "<option value=\"\" selected='selected'>Усi групи</option>";
                } else {
                    $content .= "<option value=\"\">Усi групи</option>";
                }
                if (isset($_GET['group']) && ($_GET ['group'] == '0')) {
                    $content .= "<option value=\"0\" selected='selected'>Без групи</option>";
                } else {
                    $content .= "<option value=\"0\">Без групи</option>";
                }

                while (!$groups->EOF) {
                    if (isset($_GET['group']) && ($groups->fields['id'] == $_GET['group'])) {
                        $content .= "<option value=" . $groups->fields['id'] . " selected='selected'>" . $groups->fields['name'] . "</option>";
                    } else {
                        $content .= "<option value=" . $groups->fields['id'] . ">" . $groups->fields['name'] . "</option>";
                    }
                    $groups->MoveNext();
                }
                $content .='</select>';

                // SYSTEM GROUPS

                $content .= '<select name="sys_g" onchange="this.form.submit();">';

                $groups = $db->Execute('select * from `sys_groups` order by `sys_g_level`');
                if (!$groups) {
                    print $db->ErrorMsg();
                    die();
                }
                if (!isset($_GET ['sys_g']) OR $_GET ['sys_g'] == '') {
                    $content .= "<option value=\"\" selected='selected'>Усi системнi групи</option>";
                } else {
                    $content .= "<option value=\"\">Усi системнi групи</option>";
                }
                while (!$groups->EOF) {
                    if (isset($_GET['sys_g']) && ($groups->fields['id'] == $_GET['sys_g'])) {
                        $content .= "<option value=" . $groups->fields['id'] . " selected='selected'>" . $groups->fields['sys_g_name'] . "</option>";
                    } else {
                        $content .= "<option value=" . $groups->fields['id'] . ">" . $groups->fields['sys_g_name'] . "</option>";
                    }
                    $groups->MoveNext();
                }
                $content .='</select>';

                // ARCHIVED

                $content .= '<select name="archive" onchange="this.form.submit();">';
                if (isset($_GET ['archive']) && $_GET ['archive'] == '1') {
                    $content .= "<option value=\"0\">Без архiвованих</option>";
                    $content .= "<option value=\"1\" selected='selected'>З архiвованими</option>";
                    $content .= "<option value=\"2\">Тiльки архiвованi</option>";
                } elseif (isset($_GET ['archive']) && $_GET ['archive'] == '2') {
                    $content .= "<option value=\"0\">Без архiвованих</option>";
                    $content .= "<option value=\"1\">З архiвованими</option>";
                    $content .= "<option value=\"2\" selected='selected'>Тiльки архiвованi</option>";
                } else {
                    $content .= "<option value=\"0\" selected='selected'>Без архiвованих</option>";
                    $content .= "<option value=\"1\">З архiвованими</option>";
                    $content .= "<option value=\"2\">Тiльки архiвованi</option>";
                }
                $content .= '</form>';
                $content .= '</p>';
                $content .= kkzusers::act_users();
                break;
        }

        return $content;
    }

    public function groups() {
        switch ($_GET ['f']) {
            case 'edit' :
                if (isset($_POST ['submit']))
                    kkzgroups::edit_submit($_POST ['id']);
                $content = kkzgroups::edit_view($_GET ['id']);
                break;
            case 'add' :
                if (isset($_POST ['submit']))
                    kkzgroups::add_submit();
                $content = kkzgroups::add_view();
                break;
            case 'del' :
                kkzgroups::delete($_GET ['id']);
            case 'archive' :
                kkzgroups::archive($_GET ['id']);
            default :
                $content = kkzgroups::home();
        }
        return $content;
    }

    public function coms() {
        switch ($_GET ['f']) {
            case 'edit' :
                if (isset($_POST ['submit']))
                    kkzcoms::edit_submit($_POST ['id']);
                $content = kkzcoms::edit_view($_GET ['id']);
                break;
            case 'add' :
                if (isset($_POST ['submit']))
                    kkzcoms::add_submit();
                $content = kkzcoms::add_view();
                break;
            case 'del' :
                kkzcoms::delete($_GET ['id']);
            default :
                $content = kkzcoms::home();
        }
        return $content;
    }

    public function courses() {
        switch ($_GET ['f']) {
            case 'edit' :
                if (isset($_POST ['submit']))
                    kkzcourses::edit_submit($_POST ['id']);
                $content = kkzcourses::edit_view($_GET ['id']);
                break;
            case 'add' :
                if (isset($_POST ['submit']))
                    kkzcourses::add_submit();
                $content = kkzcourses::add_view();
                break;
            case 'del' :
                kkzcourses::delete($_GET ['id']);
            default :
                $content = kkzcourses::home();
        }
        return $content;
    }

	public function stats() {
        switch ($_GET ['f']) {
            case 'get_csv' :
                kkzstats::get_csv($_GET ['sf']);
                break;
            default :
                $content = kkzstats::home();
        }
        return $content;
    }

    public function options() {
        switch ($_GET ['f']) {
            case 'edit' :
                if (isset($_POST ['submit']))
                    kkzoptions::edit_submit($_POST ['id']);
                $content = kkzoptions::edit_view($_GET ['id']);
                break;
            case 'add' :
                if (isset($_POST ['submit']))
                    kkzoptions::add_submit();
                $content = kkzoptions::add_view();
                break;
            case 'del' :
                kkzoptions::delete($_GET ['id']);
            default :
                $content = kkzoptions::home();
        }
        return $content;
    }

    public function exit_site() {
        global $db, $user;
        header('Location: http://kkzcore.pp.ua/');
        die();
    }

}

?>