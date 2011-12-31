<?php

require_once ($DOCUMENT_INC . "top.inc.php");
$f_testid = (int) readGetVar('testid');
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">' . $lngstr['page_header_edittests'] .
    '</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="grades.php">' . $lngstr['page_header_grades'] .
    '</a>', 0));
array_push($i_items, array(0 => $lngstr['page_header_test_questions'], 1));
writePanel2($i_items);
echo '<h2>' . $lngstr['page_header_test_questions'] . '</h2>';
writeErrorsWarningsBar();
writeInfoBar($lngstr['tooltip_tests_questions']);
$i_pagewide_id = 0;

$i_subjectid_addon = "";
$i_sql_where_addon = "";
if (isset($_GET["subjectid"]) && $_GET["subjectid"] != "") {
    $i_subjectid_addon .= "&subjectid=" . (int) $_GET["subjectid"];
    $i_sql_where_addon .= $srv_settings['table_prefix'] . "questions.subjectid=" . (int)
            $_GET["subjectid"] . " AND ";
}

$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(array($lngstr["label_editquestions_hdr_test_questionid"],
        $lngstr["label_editquestions_hdr_test_questionid_hint"], $srv_settings['table_prefix'] .
        "tests_questions.test_questionid"), array($lngstr["label_editquestions_hdr_questionid"],
        $lngstr["label_editquestions_hdr_questionid_hint"], $srv_settings['table_prefix'] .
        "questions.questionid"), array($lngstr["label_editquestions_hdr_subjectid"], $lngstr["label_editquestions_hdr_subjectid_hint"],
        $srv_settings['table_prefix'] . "questions.subjectid"), array($lngstr["label_editquestions_hdr_question_text"],
        $lngstr["label_editquestions_hdr_question_text_hint"], ""), array($lngstr["label_editquestions_hdr_question_type"],
        $lngstr["label_editquestions_hdr_question_type_hint"], $srv_settings['table_prefix'] .
        "questions.question_type"), array($lngstr["label_editquestions_hdr_question_points"],
        $lngstr["label_editquestions_hdr_question_points_hint"], $srv_settings['table_prefix'] .
        "questions.question_points"),);
$i_order_no = isset($_GET["order"]) ? (int) $_GET["order"] : 0;
if ($i_order_no >= count($i_tablefields))
    $i_order_no = -1;
if ($i_order_no >= 0) {
    $i_direction = (isset($_GET["direction"]) && $_GET["direction"]) ? "DESC" : "";
    $i_order_addon = "&order=" . $i_order_no . "&direction=" . $i_direction;
    $i_sql_order_addon = " ORDER BY " . $i_tablefields[$i_order_no][2] . " " . $i_direction;
}

$i_url_limitto_addon = "";
$i_url_pageno_addon = "";
$i_url_limit_addon = "";
$i_pageno = 0;
$i_limitcount = isset($_GET["limitto"]) ? (int) $_GET["limitto"] : $G_SESSION['config_itemsperpage'];
if ($i_limitcount > 0) {
    $i_recordcount = getRecordCount($srv_settings['table_prefix'] .
            'tests_questions, ' . $srv_settings['table_prefix'] . 'questions', $i_sql_where_addon .
            "" . $srv_settings['table_prefix'] . "tests_questions.testid=" . $f_testid .
            " AND " . $srv_settings['table_prefix'] . "tests_questions.questionid=" . $srv_settings['table_prefix'] .
            "questions.questionid");
    $i_pageno = isset($_GET["pageno"]) ? (int) $_GET["pageno"] : 1;
    if ($i_pageno < 1)
        $i_pageno = 1;
    $i_limitfrom = ($i_pageno - 1) * $i_limitcount;
    $i_pageno_count = floor(($i_recordcount - 1) / $i_limitcount) + 1;
    if ($i_limitfrom > $i_recordcount) {
        $i_pageno = $i_pageno_count;
        $i_limitfrom = ($i_pageno - 1) * $i_limitcount;
    }
    $i_url_limitto_addon .= "&limitto=" . $i_limitcount;
    $i_url_pageno_addon .= "&pageno=" . $i_pageno;
    $i_url_limit_addon .= $i_url_limitto_addon . $i_url_pageno_addon;
} else {
    $i_url_limitto_addon = "&limitto=";
    $i_url_limit_addon .= $i_url_limitto_addon;
    $i_limitfrom = -1;
    $i_limitcount = -1;
}

$i_2_subjectid_addon = "";
$i_2_sql_where_addon = "";
if (isset($_GET["subjectid2"]) && $_GET["subjectid2"] != "") {
    $i_2_subjectid_addon .= "&subjectid2=" . (int) $_GET["subjectid2"];
    $i_2_sql_where_addon .= $srv_settings['table_prefix'] . "questions.subjectid=" . (int)
            $_GET["subjectid2"] . " AND ";
}

$i_2_direction = "";
$i_2_order_addon = "";
$i_2_sql_order_addon = "";
$i_2_tablefields = array(array($lngstr["label_editquestions_hdr_questionid"], $lngstr["label_editquestions_hdr_questionid_hint"],
        $srv_settings['table_prefix'] . "questions.questionid"), array($lngstr["label_editquestions_hdr_subjectid"],
        $lngstr["label_editquestions_hdr_subjectid_hint"], $srv_settings['table_prefix'] .
        "questions.subjectid"), array($lngstr["label_editquestions_hdr_question_text"],
        $lngstr["label_editquestions_hdr_question_text_hint"], ""), array($lngstr["label_editquestions_hdr_question_type"],
        $lngstr["label_editquestions_hdr_question_type_hint"], $srv_settings['table_prefix'] .
        "questions.question_type"), array($lngstr["label_editquestions_hdr_question_points"],
        $lngstr["label_editquestions_hdr_question_points_hint"], $srv_settings['table_prefix'] .
        "questions.question_points"),);
$i_2_order_no = isset($_GET["order2"]) ? (int) $_GET["order2"] : 0;
if ($i_2_order_no >= count($i_2_tablefields))
    $i_2_order_no = -1;
if ($i_2_order_no >= 0) {
    if (!isset($_GET["direction2"])) {
        $i_2_direction = 'DESC';
    }else
        $i_2_direction = (isset($_GET["direction2"]) && $_GET["direction2"] == 'DESC') ? "DESC" : "";
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
    $i_2_recordcount = 0;
    $i_2_recordcount = getRecordCount($srv_settings['table_prefix'] . 'questions', $i_2_sql_where_addon .
            "1=1");
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

echo '<p><form name=qlinksForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td><td width=32><a href="question-bank.php?testid=' .
 $f_testid . '&action=createq"><img src="images/button-add-big.gif" width=32 height=32 border=0 title="' .
 $lngstr['label_action_create_and_add_question'] .
 '"></a></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td>
        
<!--<td width=32><a href="test-manager.php?action=import&testid=' . $f_testid . '"><img src="images/button-import-big.gif" width=32 height=32 border=0 title="' . $lngstr['label_action_import_questions'] . '"></a></td>
            <td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td>-->
            
<td width=32><a href="test-manager.php?action=upload&testid=' . $f_testid . '"><img src="images/button-import-big.gif" width=32 height=32 border=0 title="' . $lngstr['label_action_upload_questions'] . '"></a></td>
            <td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td>
            
<td width=32><img src="images/button-cross-big.gif" border=0 title="' .
 $lngstr['label_action_question_links_delete'] .
 '" style="cursor: hand;" onclick="f=document.qlinksForm;if (confirm(\'' . $lngstr['qst_delete_question_links'] .
 '\')) { f.action=\'test-manager.php?testid=' . $f_testid . $i_subjectid_addon .
 $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon .
 $i_2_url_limit_addon . '&action=deleteq&confirmed=1\';f.submit();}"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=3><img src="images/1x1.gif" width=3 height=1></td><td width=32>';
//echo '<select name=subjectid onchange="document.location.href=\'test-manager.php?testid=' .$f_testid . '&subjectid=\'+this.value+\'' . $i_order_addon . $i_url_limit_addon .$i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon . '&action=editt\';">';
//echo '<option value="">Усi предмети</option>';
/*
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
 */

echo getSelectSubj('subjectid', ((isset($_GET['subjectid']) ? $_GET['subjectid'] : '')), array('onchange' => 'document.location.href=\'test-manager.php?testid=' . $f_testid . '&subjectid=\'+this.value+\'' . $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon . '&action=editt\';'));

/* $url = 'http://11.1.1.245/api/?do=courses_id&id=' . $G_SESSION['usercom'];
  $xml = simplexml_load_file($url);
  $i = 0;
  foreach ($xml->course as $course) {
  echo "<option value=" . $course->id[0];
  if (isset($_GET["subjectid"]) && ($_GET["subjectid"] == $course->id[0]))
  echo " selected=selected";
  echo ">" . utf8_to_cp1251($course->name[0]) . "</option>\n";
  $courses_apply[$i] = $course->id[0];
  $courses_apply[$i]['name'] = $course->name[0];
  $i++;
  } */

function getcourse($id) {
    $kkzapiurl = 'http://11.1.1.245/api/';
    $url = $kkzapiurl . '?do=course_id&id=' . $id;
    $xml = simplexml_load_file($url);
    return $xml->course->name;
}

//echo '</select>';
echo '</td>';
echo '<td width="100%">&nbsp;</td>';
if ($i_limitcount > 0) {
    if ($i_pageno > 1) {
        echo '<td width=32><a href="test-manager.php?action=editt&pageno=1&testid=' . $f_testid .
        $i_subjectid_addon . $i_order_addon . $i_url_limitto_addon . $i_2_subjectid_addon .
        $i_2_order_addon . $i_2_url_limit_addon .
        '"><img src="images/button-first-big.gif" border=0 title="' . $lngstr['button_first_page'] .
        '"></a></td>';
        echo '<td width=32><a href="test-manager.php?action=editt&pageno=' . max(($i_pageno -
                1), 1) . '&testid=' . $f_testid . $i_subjectid_addon . $i_order_addon . $i_url_limitto_addon .
        $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
        '"><img src="images/button-prev-big.gif" border=0 title="' . $lngstr['button_prev_page'] .
        '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="' .
        $lngstr['button_first_page'] . '"></td>';
        echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="' .
        $lngstr['button_prev_page'] . '"></td>';
    }
    if ($i_pageno < $i_pageno_count) {
        echo '<td width=32><a href="test-manager.php?action=editt&pageno=' . min(($i_pageno +
                1), $i_pageno_count) . '&testid=' . $f_testid . $i_subjectid_addon . $i_order_addon .
        $i_url_limitto_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
        '"><img src="images/button-next-big.gif" border=0 title="' . $lngstr['button_next_page'] .
        '"></a></td>';
        echo '<td width=32><a href="test-manager.php?action=editt&pageno=' . $i_pageno_count .
        '&testid=' . $f_testid . $i_subjectid_addon . $i_order_addon . $i_url_limitto_addon .
        $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
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
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr><td class=rowhdr1 title="' . $lngstr['label_hdr_select_hint'] .
 '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('test-manager.php?action=editt&testid=' . $f_testid . $i_subjectid_addon .
        $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon, $i_tablefields, $i_order_no, $i_direction);
echo '<td class=rowhdr1 colspan=3>' . $lngstr['label_hdr_action'] . '</td></tr>';
$i_rSet1 = $g_db->SelectLimit("SELECT " . $srv_settings['table_prefix'] .
        "tests_questions.test_questionid, " . $srv_settings['table_prefix'] .
        "tests_questions.questionid, " . $srv_settings['table_prefix'] .
        "questions.subjectid, " . $srv_settings['table_prefix'] .
        "questions.question_text, " . $srv_settings['table_prefix'] .
        "questions.question_time, " . $srv_settings['table_prefix'] .
        "questions.question_type, " . $srv_settings['table_prefix'] .
        "questions.question_points FROM " . $srv_settings['table_prefix'] .
        "tests_questions, " . $srv_settings['table_prefix'] . "questions WHERE " . $i_sql_where_addon . "" . $srv_settings['table_prefix'] .
        "tests_questions.testid=" . $f_testid . " AND " . $srv_settings['table_prefix'] .
        "tests_questions.questionid=" . $srv_settings['table_prefix'] .
        "questions.questionid " .
        $i_sql_order_addon, $i_limitcount, $i_limitfrom);
if (!$i_rSet1) {
    showDBError(__file__, 1);
} else {
    $i_counter = 0;
    while (!$i_rSet1->EOF) {
        $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
        echo '<tr id=tr_' . $i_pagewide_id . ' class=' . $rowname .
        ' onmouseover="rollTR(' . $i_pagewide_id . ',1);" onmouseout="rollTR(' . $i_pagewide_id .
        ',0);"><td align=center width=22><input id=cb_' . $i_pagewide_id .
        ' type=checkbox name=box_qlinks[] value="' . $i_rSet1->fields["test_questionid"] .
        '" onclick="toggleCB(this);"></td><td align=right>' . $i_rSet1->fields["test_questionid"] .
        '</td><td align=right>' . $i_rSet1->fields["questionid"] .
        '</td><td><a href="test-manager.php?testid=' . $f_testid . (isset($_GET["subjectid"]) &&
        $_GET["subjectid"] != "" ? "" : '&subjectid=' . $i_rSet1->fields["subjectid"]) .
        $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon .
        $i_2_url_limit_addon . '&action=editt">' . getcourse($i_rSet1->fields["subjectid"]) .
        '</a></td><td>' . getTruncatedHTML($i_rSet1->fields["question_text"]) .
        '</td><td>';
        switch ($i_rSet1->fields["question_type"]) {
            case QUESTION_TYPE_MULTIPLECHOICE:
                echo $lngstr['label_atype_multiple_choice'];
                break;
            case QUESTION_TYPE_TRUEFALSE:
                echo $lngstr['label_atype_truefalse'];
                break;
            case QUESTION_TYPE_MULTIPLEANSWER:
                echo $lngstr['label_atype_multiple_answer'];
                break;
            case QUESTION_TYPE_FILLINTHEBLANK:
                echo $lngstr['label_atype_fillintheblank'];
                break;
            case QUESTION_TYPE_ESSAY:
                echo $lngstr['label_atype_essay'];
                break;
            case QUESTION_TYPE_RANDOM:
                echo $lngstr['label_atype_random'];
                break;
        }
        echo '</td><td align=right>' . (($i_rSet1->fields["question_type"] <>
        QUESTION_TYPE_RANDOM) ? $i_rSet1->fields["question_points"] : '') .
        '</td><td align=center width=22><a href="question-bank.php?testid=' . $f_testid .
        '&questionid=' . $i_rSet1->fields["questionid"] .
        '&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' .
        $lngstr['label_action_question_edit'] .
        '"></a></td><td align=center width=22><a href="test-manager.php?testid=' . $f_testid .
        '&test_questionid=' . $i_rSet1->fields["test_questionid"] . $i_subjectid_addon .
        $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
        '&action=moveup"><img width=20 height=10 border=0 src="images/button-up.gif" title="' .
        $lngstr['label_action_question_moveup'] .
        '"></a><br><a href="test-manager.php?testid=' . $f_testid . '&test_questionid=' .
        $i_rSet1->fields["test_questionid"] . $i_subjectid_addon . $i_url_limit_addon .
        $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
        '&action=movedown"><img width=20 height=10 border=0 src="images/button-down.gif" title="' .
        $lngstr['label_action_question_movedown'] .
        '"></a></td><td align=center width=22><a href="test-manager.php?testid=' . $f_testid .
        '&test_questionid=' . $i_rSet1->fields["test_questionid"] . $i_subjectid_addon .
        $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon .
        $i_2_url_limit_addon . '&action=deleteq" onclick="return confirmMessage(this, \'' .
        $lngstr['qst_delete_question_link'] . '\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="' .
        $lngstr['label_action_question_link_delete'] . '"></a></td></tr>';
        $i_counter++;
        $i_pagewide_id++;
        $i_rSet1->MoveNext();
    }
    $i_rSet1->Close();
}
echo '</table>';
echo '</td></tr></table></form>';

echo '<h2>' . $lngstr['page_header_questionbank'] . '</h2>';
echo '<p><form name=qbankForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td><td width=32><a href="question-bank.php?action=createq"><img src="images/button-new-big.gif" border=0 title="' .
 $lngstr['label_action_create_question'] .
 '"></a></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-plus-big.gif" border=0 title="' .
 $lngstr['label_action_questions_append'] .
 '" style="cursor: hand;" onclick="f=document.qbankForm;f.action=\'test-manager.php?testid=' .
 $f_testid . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon .
 $i_2_order_addon . $i_2_url_limit_addon . '&action=append\';f.submit();"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-cross-big.gif" border=0 title="' .
 $lngstr['label_action_questions_delete'] .
 '" style="cursor: hand;" onclick="f=document.qbankForm;if (confirm(\'' . $lngstr['qst_delete_questions'] .
 '\')) { f.action=\'question-bank.php?testid=' . $f_testid . $i_subjectid_addon .
 $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon .
 $i_2_url_limit_addon . '&action=deleteq&confirmed=1\';f.submit();}"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=3><img src="images/1x1.gif" width=3 height=1></td><td width=32>';

/*
  echo '<select name=subjectid2 onchange="document.location.href=\'test-manager.php?testid=' . $f_testid . '&subjectid2=\'+this.value+\'' . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon . $i_2_order_addon . $i_2_url_limit_addon . '&action=editt\';">';
  echo '<option value="">Усi предмети</option>';
  $url = 'http://11.1.1.245/api/?do=courses_id&id=' . $G_SESSION['usercom'];
  $xml = simplexml_load_file($url);
  $i = 0;
  foreach ($xml->course as $course) {
  echo "<option value=" . $course->id[0];
  if (isset($_GET["subjectid2"]) && ($_GET["subjectid2"] == $course->id[0]))
  echo " selected=selected";
  echo ">" . utf8_to_cp1251($course->name[0]) . "</option>\n";
  $courses_apply[$i] = $course->id[0];
  $courses_apply[$i]['name'] = $course->name[0];
  $i++;
  }
  echo '</select>';

 */
echo getSelectSubj('subjectid2', (isset($_GET['subjectid2']) ? $_GET['subjectid2'] : null), array('onchange' => 'document.location.href=\'test-manager.php?testid=' . $f_testid . '&subjectid2=\'+this.value+\'' . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon . $i_2_order_addon . $i_2_url_limit_addon . '&action=editt\';'));
echo '</td>';
$sqladd = '';
$i = 0;
if (!isset($_GET["subjectid2"]) OR $_GET["subjectid2"] == "") {
    if ($G_SESSION['allcoms'] != 1) {
        $courses = $core->getcourses_id($G_SESSION['usercom']);
        foreach ($courses as $course) {
            echo "<option value=" . $course['id'];
            if (isset($_GET["subjectid2"]) && ($_GET["subjectid2"] == $course['id']))
                echo " selected=selected";
            echo ">" . $course['name'] . "</option>\n";
            $courses_apply[] = array($course['id'], 'name' => $course['name']);
        }
        $sqladd .= " AND kkzttests.subjectid IN (" . implode(',', $courses_apply) . ")";
    } else
        $sqladd .= '';
} else {
    $sqladd .= " AND kkztquestions.subjectid =" . $_GET["subjectid2"];
}

echo '<td width="100%">&nbsp;</td>';
if ($i_2_limitcount > 0) {
    if ($i_2_pageno > 1) {
        echo '<td width=32><a href="test-manager.php?action=editt&pageno2=1&testid=' . $f_testid .
        $i_subjectid_addon . $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon .
        $i_2_order_addon . $i_2_url_limitto_addon .
        '"><img src="images/button-first-big.gif" border=0 title="' . $lngstr['button_first_page'] .
        '"></a></td>';
        echo '<td width=32><a href="test-manager.php?action=editt&pageno2=' . max(($i_2_pageno -
                1), 1) . '&testid=' . $f_testid . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon .
        $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limitto_addon .
        '"><img src="images/button-prev-big.gif" border=0 title="' . $lngstr['button_prev_page'] .
        '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="' .
        $lngstr['button_first_page'] . '"></td>';
        echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="' .
        $lngstr['button_prev_page'] . '"></td>';
    }
    if ($i_2_pageno < $i_2_pageno_count) {
        echo '<td width=32><a href="test-manager.php?action=editt&pageno2=' . min(($i_2_pageno +
                1), $i_2_pageno_count) . '&testid=' . $f_testid . $i_subjectid_addon . $i_order_addon .
        $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limitto_addon .
        '"><img src="images/button-next-big.gif" border=0 title="' . $lngstr['button_next_page'] .
        '"></a></td>';
        echo '<td width=32><a href="test-manager.php?action=editt&pageno2=' . $i_2_pageno_count .
        '&testid=' . $f_testid . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon .
        $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limitto_addon .
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
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%" id=table2>';
echo '<tr><td class=rowhdr1 title="' . $lngstr['label_hdr_select_hint'] . '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('test-manager.php?action=editt&testid=' . $f_testid . $i_subjectid_addon .
        $i_order_addon . $i_url_limit_addon . $i_2_subjectid_addon . $i_2_url_limit_addon, $i_2_tablefields, $i_2_order_no, $i_2_direction, '2');
echo '<td class=rowhdr1 colspan=3>' . $lngstr['label_hdr_action'] . '</td></tr>';
$i_rSet2 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] .
        "questions  WHERE 1=1 " . $sqladd . $i_2_sql_order_addon, $i_2_limitcount, $i_2_limitfrom);
if (!$i_rSet2) {
    showDBError(__file__, 2);
} else {
    $i_counter = 0;
    while (!$i_rSet2->EOF) {
        $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
        echo '<tr id=tr_' . $i_rSet2->fields["questionid"] . ' class=' . $rowname . ' onmouseover="rollTR(' . $i_rSet2->fields["questionid"] . ',1);" onmouseout="rollTR(' . $i_rSet2->fields["questionid"] . ',0);">
            <td align=center width=22 id=row1>
            <input id="cb_' . $i_rSet2->fields["questionid"] . '" type=checkbox name=box_questions[] value="' . $i_rSet2->fields["questionid"] . '" onclick="toggleCB(this);" />
                </td>
                <td align=right>' . $i_rSet2->fields["questionid"] . '</td>
                    <td>
         <a href="test-manager.php?testid=' . $f_testid . (isset($_GET["subjectid2"]) && $_GET["subjectid2"] != "" ? "" : '&subjectid2=' . $i_rSet2->fields["subjectid"]) . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon . $i_2_order_addon . $i_2_url_limit_addon . '&action=editt">' . getcourse($i_rSet2->fields["subjectid"]) . '
             </a>
             </td>
             <td style="cursor: pointer;" onClick="toggleCB_name(' . $i_rSet2->fields["questionid"] . ', $(this).parent());">' . getTruncatedHTML($i_rSet2->fields["question_text"]) . '</td><td>';
        switch ($i_rSet2->fields["question_type"]) {
            case QUESTION_TYPE_MULTIPLECHOICE:
                echo $lngstr['label_atype_multiple_choice'];
                break;
            case QUESTION_TYPE_TRUEFALSE:
                echo $lngstr['label_atype_truefalse'];
                break;
            case QUESTION_TYPE_MULTIPLEANSWER:
                echo $lngstr['label_atype_multiple_answer'];
                break;
            case QUESTION_TYPE_FILLINTHEBLANK:
                echo $lngstr['label_atype_fillintheblank'];
                break;
            case QUESTION_TYPE_ESSAY:
                echo $lngstr['label_atype_essay'];
                break;
            case QUESTION_TYPE_RANDOM:
                echo $lngstr['label_atype_random'];
                break;
        }
        echo '</td><td align=right>' . ($i_rSet2->fields["question_type"] <>
        QUESTION_TYPE_RANDOM ? $i_rSet2->fields["question_points"] : '') .
        '</td><td align=center width=22><a href="test-manager.php?testid=' . $f_testid .
        '&questionid=' . $i_rSet2->fields["questionid"] . $i_subjectid_addon . $i_order_addon .
        $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
        '&action=append"><img width=20 height=20 border=0 src="images/button-plus.gif" title="' .
        $lngstr['label_action_question_append'] .
        '"></a></td><td align=center width=22><a href="question-bank.php?testid=' . $f_testid .
        '&questionid=' . $i_rSet2->fields["questionid"] .
        '&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' .
        $lngstr['label_action_question_edit'] .
        '"></a></td><td align=center width=22><a href="question-bank.php?testid=' . $f_testid .
        '&questionid=' . $i_rSet2->fields["questionid"] . $i_subjectid_addon . $i_order_addon .
        $i_url_limit_addon . $i_2_subjectid_addon . $i_2_order_addon . $i_2_url_limit_addon .
        '&action=deleteq" onclick="return confirmMessage(this, \'' . $lngstr['qst_delete_question'] .
        '\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="' .
        $lngstr['label_action_question_delete'] . '"></a></td></tr>';
        $i_counter++;
        $i_pagewide_id++;
        $i_rSet2->MoveNext();
    }
    $i_rSet2->Close();
}
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
