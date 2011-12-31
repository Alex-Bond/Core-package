<?php

require_once ($DOCUMENT_INC . "top.inc.php");
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">' . $lngstr['page_header_edittests'] .
    '</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="grades.php">' . $lngstr['page_header_grades'] .
    '</a>', 0));
writePanel2($i_items);
//echo '<h2>' . $lngstr['page_header_test_assignto_tests'] . '</h2>';
writeErrorsWarningsBar();
writeInfoBar($lngstr['tooltip_tests_groups']);
$i_pagewide_id = 0;

$f_testids = array();
$i_testids_addon = "";
$i_sql_where_addon = "";
if (isset($_POST["box_tests"]) && is_array($_POST["box_tests"])) {
    foreach ($_POST["box_tests"] as $f_testid) {
        array_push($f_testids, $f_testid);
    }
} else
if (isset($_GET["testids"]) && $_GET["testids"] != "") {
    $f_testids = explode(SYSTEM_ARRAY_ITEM_SEPARATOR, readGetVar('testids'));
} else {
    array_push($f_testids, readGetVar('testid'));
}
$i_testids_addon .= "&testids=" . implode(SYSTEM_ARRAY_ITEM_SEPARATOR, $f_testids);
reset($f_testids);
if (list(, $val) = each($f_testids))
    $i_sql_where_addon .= "testid=" . (int) $val;
while (list(, $val) = each($f_testids)) {
    $i_sql_where_addon .= " OR testid=" . (int) $val;
}
if ($i_sql_where_addon != "")
    $i_sql_where_addon = '(' . $i_sql_where_addon . ') AND ';

$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(
    array(
        $lngstr["label_edittests_hdr_testid"],
        $lngstr["label_edittests_hdr_testid_hint"],
        $srv_settings['table_prefix'] . "tests.testid"
    ),
    array($lngstr["label_edittests_hdr_test_notes"],
        $lngstr["label_edittests_hdr_test_notes_hint"],
        ""
    ),
    array($lngstr["label_edittests_hdr_test_name"],
        $lngstr["label_edittests_hdr_test_name_hint"],
        $srv_settings['table_prefix'] . "tests.test_name"
    ),
    array($lngstr["label_edittests_hdr_subjectid"],
        $lngstr["label_edittests_hdr_subjectid_hint"],
        $srv_settings['table_prefix'] . "tests.subjectid"
    ),
    array(
        $lngstr["label_edittests_hdr_test_datestart"],
        $lngstr["label_edittests_hdr_test_datestart_hint"],
        $srv_settings['table_prefix'] . "tests.test_datestart"
    ),
    array(
        $lngstr["label_edittests_hdr_test_dateend"],
        $lngstr["label_edittests_hdr_test_dateend_hint"],
        $srv_settings['table_prefix'] . "tests.test_dateend"
    ),
    array(
        $lngstr["label_edittests_hdr_test_enabled"],
        $lngstr["label_edittests_hdr_test_enabled_hint"],
        $srv_settings['table_prefix'] . "tests.test_enabled"
    ),
);
$i_order_no = isset($_GET["order"]) ? (int) $_GET["order"] : 0;
if ($i_order_no >= count($i_tablefields))
    $i_order_no = -1;
if ($i_order_no >= 0) {
    $i_direction = (isset($_GET["direction"]) && $_GET["direction"]) ? "DESC" : "";
    $i_order_addon = "&order=" . $i_order_no . "&direction=" . $i_direction;
    $i_sql_order_addon = " ORDER BY " . $i_tablefields[$i_order_no][2] . " " . $i_direction;
}

$i_2_direction = "";
$i_2_order_addon = "";
$i_2_sql_order_addon = "";
$i_2_tablefields = array(
    array($lngstr["label_managegroups_hdr_groupid"],
        $lngstr["label_managegroups_hdr_groupid_hint"],
        ''
        ), 
    array($lngstr["label_managegroups_hdr_group_name"],
        $lngstr["label_managegroups_hdr_group_name_hint"],
        ''
        ),
    );
$i_2_order_no = isset($_GET["order2"]) ? (int) $_GET["order2"] : 0;
if ($i_2_order_no >= count($i_2_tablefields))
    $i_2_order_no = -1;
if ($i_2_order_no >= 0) {
    $i_2_direction = (isset($_GET["direction2"]) && $_GET["direction2"]) ? "DESC" :
            "";
    $i_2_order_addon = "&order2=" . $i_2_order_no . "&direction2=" . $i_2_direction;
    $i_2_sql_order_addon = " ORDER BY " . $i_2_tablefields[$i_2_order_no][2] . " " .
            $i_2_direction;
}

$i_2_url_limitto_addon = "";
$i_2_url_pageno_addon = "";
$i_2_url_limit_addon = "";
$i_2_pageno = 0;
$i_2_limitcount = isset($_GET["limitto2"]) ? (int) $_GET["limitto2"] : $G_SESSION['config_itemsperpage'];
if ($i_2_limitcount > 0) {
    $i_2_recordcount = getRecordCount($srv_settings['table_prefix'] . 'groups');
    $i_2_pageno = isset($_GET["pageno2"]) ? (int) $_GET["pageno2"] : 1;
    if ($i_2_pageno < 1)
        $i_2_pageno = 1;
    $i_2_limitfrom = ($i_2_pageno - 1) * $i_2_limitcount;
    $i_2_pageno_count = floor(($i_2_recordcount - 1) / $i_2_limitcount) + 1;
    if ($i_2_limitfrom > $i_2_recordcount) {
        $i_2_pageno = $i_2_pageno_count;
        $i_2_limitfrom = ($i_2_pageno - 1) * $i_2_limitcount;
    }
    $i_2_url_limitto_addon .= "&limitto2=" . $i_2_limitcount;
    $i_2_url_pageno_addon .= "&pageno2=" . $i_2_pageno;
    $i_2_url_limit_addon .= $i_2_url_limitto_addon . $i_2_url_pageno_addon;
} else {
    $i_2_url_limitto_addon = "&limitto2=";
    $i_2_url_limit_addon .= $i_2_url_limitto_addon;
    $i_2_limitfrom = -1;
    $i_2_limitcount = -1;
}

/*echo '<p><form name=testsForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td><td width=32><a href="test-manager.php?action=create"><img src="images/button-new-big.gif" width=32 height=32 border=0 title="' .
 $lngstr['label_action_create_test'] .
 '"></a></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-groups-big.gif" border=0 title="' .
 $lngstr['label_action_groups'] .
 '" style="cursor: hand;" onclick="f=document.testsForm;f.action=\'test-manager.php?action=groups\';f.submit();"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-cross-big.gif" border=0 title="' .
 $lngstr['label_action_tests_delete'] .
 '" style="cursor: hand;" onclick="f=document.testsForm;if (confirm(\'' . $lngstr['qst_delete_tests'] .
 '\')) { f.action=\'test-manager.php?action=delete&confirmed=1\';f.submit();}"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=3><img src="images/1x1.gif" width=3 height=1></td><td width=32>';
echo '<select name=subjectid onchange="document.location.href=\'test-manager.php?subjectid=\'+this.value+\'' .
 $i_order_addon . '\';">';
echo '<option value=""></option>';
$i_rSet2 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] .
        "subjects");
if (!$i_rSet2) {
    showDBError(__file__, 2);
} else {
    while (!$i_rSet2->EOF) {
        echo "<option value=" . $i_rSet2->fields["subjectid"];
        if (isset($_GET["subjectid"]) && ($_GET["subjectid"] == $i_rSet2->fields["subjectid"]))
            echo " selected=selected";
        echo ">" . convertTextValue($i_rSet2->fields["subject_name"]) . "</option>\n";
        $i_rSet2->MoveNext();
    }
    $i_rSet2->Close();
}
echo '</select>';
echo '</td><td width="100%">&nbsp;</td><td width=2><img src="images/toolbar-right.gif" width=2 height=32></td></tr></table>';
echo '</td></tr><tr><td>';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr><td class=rowhdr1 title="' . $lngstr['label_hdr_select_hint'] .
 '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('test-manager.php?action=groups' . $i_testids_addon . $i_2_order_addon .
        $i_2_url_limit_addon, $i_tablefields, $i_order_no, $i_direction);
echo '<td class=rowhdr1 colspan=4>' . $lngstr['label_hdr_action'] . '</td></tr>';
$i_rSet1 = $g_db->Execute("SELECT " . $srv_settings['table_prefix'] .
        "tests.testid, " . $srv_settings['table_prefix'] . "tests.test_name, " . $srv_settings['table_prefix'] .
        "tests.subjectid, " . $srv_settings['table_prefix'] . "tests.test_datestart, " .
        $srv_settings['table_prefix'] . "tests.test_dateend, " . $srv_settings['table_prefix'] .
        "tests.test_notes, " . $srv_settings['table_prefix'] . "tests.test_enabled, " .
        $srv_settings['table_prefix'] . "subjects.subject_name FROM " . $srv_settings['table_prefix'] .
        "tests, " . $srv_settings['table_prefix'] . "subjects WHERE " . $i_sql_where_addon .
        "" . $srv_settings['table_prefix'] . "tests.subjectid=" . $srv_settings['table_prefix'] .
        "subjects.subjectid" . $i_sql_order_addon);
if (!$i_rSet1) {
    showDBError(__file__, 1);
} else {
    $i_counter = 0;
    while (!$i_rSet1->EOF) {
        $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
        echo '<tr id=tr_' . $i_pagewide_id . ' class=' . $rowname .
        ' onmouseover="rollTR(' . $i_pagewide_id . ',1);" onmouseout="rollTR(' . $i_pagewide_id .
        ',0);"><td align=center width=22><input id=cb_' . $i_pagewide_id .
        ' type=checkbox name=box_tests[] value="' . $i_rSet1->fields["testid"] .
        '" onclick="toggleCB(this);"></td><td align=right>' . $i_rSet1->fields["testid"] .
        '</td><td align=center width=16 style="padding: 1px;"><a href="javascript:void(0)" onClick="showDialog(\'test-manager.php?testid=' .
        $i_rSet1->fields["testid"] . '&action=notes\', 300, 200)"><img src="images/button-notes.gif" width=16 height=20 title="' .
        convertTextValue($i_rSet1->fields["test_notes"]) . '" border=0></a></td><td>' .
        convertTextValue($i_rSet1->fields["test_name"]) .
        '</td><td><a href="test-manager.php?subjectid=' . (isset($_GET["subjectid"]) &&
        $_GET["subjectid"] != "" ? "" : $i_rSet1->fields["subjectid"]) . $i_order_addon .
        '">' . convertTextValue($i_rSet1->fields["subject_name"]) . '</a></td><td>' .
        date($lngstr['date_format'], $i_rSet1->fields["test_datestart"]) . '</td><td>' .
        date($lngstr['date_format'], $i_rSet1->fields["test_dateend"]) .
        '</td><td align=center><a href="test-manager.php?testid=' . $i_rSet1->fields["testid"] .
        $i_order_addon . '&action=enable&set=' . ($i_rSet1->fields["test_enabled"] ?
                '0"><img src="images/button-checkbox-2.gif" width=13 height=13 border=0 title="' .
                $lngstr['label_yes'] . '">' :
                '1"><img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="' .
                $lngstr['label_no'] . '">') . '</a></td>';
        echo '<td align=center width=22><a href="test-manager.php?testid=' . $i_rSet1->
        fields["testid"] . '&action=settings"><img width=20 height=20 border=0 src="images/button-gear.gif" title="' .
        $lngstr['label_action_test_settings'] .
        '"></a></td><td align=center width=22><a href="test-manager.php?testids=' . $i_rSet1->
        fields["testid"] . '&action=groups"><img width=20 height=20 border=0 src="images/button-groups.gif" title="' .
        $lngstr['label_action_test_groups_select'] .
        '"></a></td><td align=center width=22><a href="test-manager.php?testid=' . $i_rSet1->
        fields["testid"] . '&action=editt"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' .
        $lngstr['label_action_questions_edit'] .
        '"></a></td><td align=center width=22><a href="test-manager.php?testid=' . $i_rSet1->
        fields["testid"] . '&action=delete" onclick="return confirmMessage(this, \'' . $lngstr['qst_delete_test'] .
        '\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="' .
        $lngstr['label_action_test_delete'] . '"></a></td></tr>';
        $i_counter++;
        $i_pagewide_id++;
        $i_rSet1->MoveNext();
    }
    $i_rSet1->Close();
}
echo '</table>';
echo '</td></tr></table></form>';
*/

echo '<h2>' . $lngstr['page_header_test_assignto_groups'] . '</h2>';
echo '<p><form name=groupsForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td>
    <td width=32><img src="images/button-cross-big.gif" border=0 title="' .
 $lngstr['label_action_groups_delete'] .
 '" style="cursor: hand;" onclick="f=document.groupsForm;if (confirm(\'' . $lngstr['qst_delete_groups'] .
 '\')) { f.action=\'groups.php?action=delete&confirmed=1\';f.submit();}"></td><td width="100%">&nbsp;</td>';
if ($i_2_limitcount > 0) {
    if ($i_2_pageno > 1) {
        echo '<td width=32><a href="test-manager.php?action=groups&pageno2=1' . $i_testids_addon .
        $i_order_addon . $i_2_order_addon . $i_2_url_limitto_addon .
        '"><img src="images/button-first-big.gif" border=0 title="' . $lngstr['button_first_page'] .
        '"></a></td>';
        echo '<td width=32><a href="test-manager.php?action=groups&pageno2=' . max(($i_2_pageno -
                1), 1) . $i_testids_addon . $i_order_addon . $i_2_order_addon . $i_2_url_limitto_addon .
        '"><img src="images/button-prev-big.gif" border=0 title="' . $lngstr['button_prev_page'] .
        '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="' .
        $lngstr['button_first_page'] . '"></td>';
        echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="' .
        $lngstr['button_prev_page'] . '"></td>';
    }
    if ($i_2_pageno < $i_2_pageno_count) {
        echo '<td width=32><a href="test-manager.php?action=groups&pageno2=' . min(($i_2_pageno +
                1), $i_2_pageno_count) . $i_testids_addon . $i_order_addon . $i_2_order_addon .
        $i_2_url_limitto_addon .
        '"><img src="images/button-next-big.gif" border=0 title="' . $lngstr['button_next_page'] .
        '"></a></td>';
        echo '<td width=32><a href="test-manager.php?action=groups&pageno2=' . $i_2_pageno_count .
        $i_testids_addon . $i_order_addon . $i_2_order_addon . $i_2_url_limitto_addon .
        '"><img src="images/button-last-big.gif" border=0 title="' . $lngstr['button_last_page'] .
        '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-next-big-inactive.gif" border=0 title="' .
        $lngstr['button_next_page'] . '"></td>';
        echo '<td width=32><img src="images/button-last-big-inactive.gif" border=0 title="' .
        $lngstr['button_last_page'] . '"></td>';
    }
}
echo '<td width=2><img src="images/toolbar-right.gif" width=2 height=32></td></tr></table>';
echo '</td></tr><tr><td>';
echo '<table class=rowtable2 id=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr><td class=rowhdr1 title="' . $lngstr['label_hdr_select_hint'] .
 '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('test-manager.php?action=groups' . $i_testids_addon . $i_order_addon .
        $i_2_url_limit_addon, $i_2_tablefields, $i_2_order_no, $i_2_direction, '2');
echo '<td class=rowhdr1 title="' . $lngstr['page_assignto_hdr_assignto_hint'] .
 '" vAlign=top>' . $lngstr['page_assignto_hdr_assignto'] . '</td>';
echo '</tr>';
//����� ������ ��� ������� �����


$url = 'http://11.1.1.245/api/?do=groups';
$xml = simplexml_load_file($url);

foreach ($xml->group as $group) {

    $i_member_count = getRecordCount($srv_settings['table_prefix'] . 'groups_tests', $i_sql_where_addon . "groupid=" . $group->id);
    $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
    echo '<tr id=tr_' . $group->id . ' class=' . $rowname .
    ' onmouseover="rollTR(' . $group->id . ',1);" onmouseout="rollTR(' . $group->id . ',0);">
        <td align=center width=22 id=row1><input id=cb_' . $group->id . ' type=checkbox name=box_groups[] value="' . $group->id . '" onclick="toggleCB(this);"></td>
        <td align=right>' . $group->id .'</td>
            <td style="cursor: pointer;" onClick="toggleCB_name(' . $group->id . ', $(this).parent());">' . utf8_to_cp1251($group->name) . '</td><td align=center><a href="test-manager.php?action=assignto&groupid=' . $group->id . $i_order_addon . $i_testids_addon . $i_2_url_limit_addon .
    '&set=' . ($i_member_count >= sizeof($f_testids) ?
            '0"><img src="images/button-checkbox-2.gif" width=13 height=13 border=0 title="' .
            $lngstr['label_yes'] . '">' : ($i_member_count > 0 ?
                    '1"><img src="images/button-checkbox-1.gif" width=13 height=13 border=0 title="' .
                    $lngstr['label_partially'] . '">' :
                    '1"><img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="' .
                    $lngstr['label_no'] . '">')) . '</a></td>';
    echo '</tr>';
    $i_counter++;
    $i_pagewide_id++;
}



/*
  $i_rSet3 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] .
  "groups" . $i_2_sql_order_addon, $i_2_limitcount, $i_2_limitfrom);
  if (!$i_rSet3) {
  showDBError(__file__, 3);
  } else {
  $i_counter = 0;
  while (!$i_rSet3->EOF) {

  $i_member_count = getRecordCount($srv_settings['table_prefix'] . 'groups_tests',
  $i_sql_where_addon . "groupid=" . $i_rSet3->fields["groupid"]);
  $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
  echo '<tr id=tr_' . $i_pagewide_id . ' class=' . $rowname .
  ' onmouseover="rollTR(' . $i_pagewide_id . ',1);" onmouseout="rollTR(' . $i_pagewide_id .
  ',0);"><td align=center width=22' . ($i_rSet3->fields["groupid"] >
  SYSTEM_GROUP_MAX_INDEX ? '' : ' class=system') . '><input id=cb_' . $i_pagewide_id .
  ' type=checkbox name=box_groups[] value="' . $i_rSet3->fields["groupid"] .
  '" onclick="toggleCB(this);"></td><td align=right>' . $i_rSet3->fields["groupid"] .
  '</td><td>' . getTruncatedHTML($i_rSet3->fields["group_name"]) . '</td><td>' . $i_rSet3->
  fields["group_description"] .
  '</td><td align=center><a href="test-manager.php?action=assignto&groupid=' . $i_rSet3->
  fields["groupid"] . $i_order_addon . $i_testids_addon . $i_2_url_limit_addon .
  '&set=' . ($i_member_count >= sizeof($f_testids) ?
  '0"><img src="images/button-checkbox-2.gif" width=13 height=13 border=0 title="' .
  $lngstr['label_yes'] . '">' : ($i_member_count > 0 ?
  '1"><img src="images/button-checkbox-1.gif" width=13 height=13 border=0 title="' .
  $lngstr['label_partially'] . '">' :
  '1"><img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="' .
  $lngstr['label_no'] . '">')) . '</a></td>';
  echo '<td align=center width=22><a href="groups.php?groupid=' . $i_rSet3->
  fields["groupid"] . '&action=edit"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' .
  $lngstr['label_action_group_edit'] . '"></a></td><td align=center width=22>' . ($i_rSet3->
  fields["groupid"] > SYSTEM_GROUP_MAX_INDEX ? '<a href="groups.php?groupid=' . $i_rSet3->
  fields["groupid"] . '&action=delete" onclick="return confirmMessage(this, \'' .
  $lngstr['qst_delete_group'] . '\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="' .
  $lngstr['label_action_group_delete'] . '"></a>' :
  '<img width=20 height=20 border=0 src="images/button-cross-inactive.gif">') .
  '</td></tr>';
  $i_counter++;
  $i_pagewide_id++;
  $i_rSet3->MoveNext();
  }
  $i_rSet3->Close();
  }
 */
echo '</table>';
echo '</td></tr></table></form>';
?>
<script>
    var block = $('#rowtable2'); // работаем с отдельным элементом
    $(document).keydown(function(e){ // при нажатии клавиши
        if (!e.ctrlKey && !e.shiftKey) return;  // если клавиша не shift и не ctrl выходим из функции
        this.onselectstart = function(){return false}; // запрет выделения для IE
        block.css({'-moz-user-select':'none','-webkit-user-select':'none','user-select':'none'}); // и для всех остальных браузеров
        if (e.shiftKey) { // если нажата клавиша shift
            pressed = 1;
        }
    });
    $(document).keyup(function(e){ // при нажатии клавиши
        this.onselectstart = function(){return false}; // запрет выделения для IE
        block.css({'-moz-user-select':'','-webkit-user-select':'','user-select':''}); // и для всех остальных браузеров
        pressed = 0;
    });
</script>
<?php
require_once ($DOCUMENT_INC . "btm.inc.php");
?>
