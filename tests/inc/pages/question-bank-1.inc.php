<?php

require_once ($DOCUMENT_INC . "top.inc.php");
/* writePanel2(array(array(0 => $lngstr['page_header_questionbank'], 1), array(0 =>
  '<a class=bar2 href="subjects.php">' . $lngstr['page_header_subjects'] . '</a>',
  0)));
 */
echo '<h2>' . $lngstr ['page_header_questionbank'] . '</h2>';
writeErrorsWarningsBar();
writeInfoBar($lngstr ['tooltip_questionbank']);
$i_pagewide_id = 0;

$i_subjectid_addon = "";
$i_sql_where_addon = "";
if (isset($_GET ["subjectid"]) && $_GET ["subjectid"] != "") {
    $i_subjectid_addon .= "&subjectid=" . (int) $_GET ["subjectid"];
    $i_sql_where_addon .= $srv_settings ['table_prefix'] . "questions.subjectid=" . (int) $_GET ["subjectid"] . " AND ";
}

$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(array($lngstr ["label_editquestions_hdr_questionid"], $lngstr ["label_editquestions_hdr_questionid_hint"], $srv_settings ['table_prefix'] . "questions.questionid"), array($lngstr ["label_editquestions_hdr_subjectid"], $lngstr ["label_editquestions_hdr_subjectid_hint"], $srv_settings ['table_prefix'] . "questions.subjectid"), array($lngstr ["label_editquestions_hdr_question_text"], $lngstr ["label_editquestions_hdr_question_text_hint"], ""), array($lngstr ["label_editquestions_hdr_question_type"], $lngstr ["label_editquestions_hdr_question_type_hint"], $srv_settings ['table_prefix'] . "questions.question_type"), array($lngstr ["label_editquestions_hdr_question_points"], $lngstr ["label_editquestions_hdr_question_points_hint"], $srv_settings ['table_prefix'] . "questions.question_points"));
$i_order_no = isset($_GET ["order"]) ? (int) $_GET ["order"] : 0;
if ($i_order_no >= count($i_tablefields))
    $i_order_no = - 1;
if ($i_order_no >= 0) {
    if (!isset($_GET["direction"])) {
        $i_direction = 'DESC';
    }else
        $i_direction = (isset($_GET["direction"]) && $_GET["direction"] == 'DESC') ? "DESC" : "";
    $i_order_addon = "&order=" . $i_order_no . "&direction=" . $i_direction;
    $i_sql_order_addon = " ORDER BY " . $i_tablefields [$i_order_no] [2] . " " . $i_direction;
}

$i_url_limitto_addon = "";
$i_url_pageno_addon = "";
$i_url_limit_addon = "";
$i_pageno = 0;
$i_limitcount = isset($_GET ["limitto"]) ? (int) $_GET ["limitto"] : $G_SESSION ['config_itemsperpage'];
if ($i_limitcount > 0) {
    $i_recordcount = getRecordCount($srv_settings ['table_prefix'] . 'questions', $i_sql_where_addon . "1=1");
    $i_pageno = isset($_GET ["pageno"]) ? (int) $_GET ["pageno"] : 1;
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
    $i_limitfrom = - 1;
    $i_limitcount = - 1;
}

echo '<p><form name=qbankForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td><td width=32><a href="question-bank.php?action=createq"><img src="images/button-new-big.gif" border=0 title="' . $lngstr ['label_action_create_question'] . '"></a></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-stats-big.gif" border=0 title="' . $lngstr ['label_action_questions_stats'] . '" style="cursor: hand;" onclick="f=document.qbankForm;f.action=\'question-bank.php?action=statsq\';f.submit();"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-cross-big.gif" border=0 title="' . $lngstr ['label_action_questions_delete'] . '" style="cursor: hand;" onclick="f=document.qbankForm;if (confirm(\'' . $lngstr ['qst_delete_questions'] . '\')) { f.action=\'question-bank.php?action=deleteq&confirmed=1\';f.submit();}"></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=3><img src="images/1x1.gif" width=3 height=1></td><td width=32>';

echo getSelectSubj('subjectid', ((isset($_GET['subjectid']) ? $_GET['subjectid'] : '')), array('onchange' => 'document.location.href=\'question-bank.php?subjectid=\'+this.value+\'' . $i_order_addon . $i_url_limit_addon . '\';'));

echo '</td>';
echo '<td width="100%">&nbsp;</td>';
if ($i_limitcount > 0) {
    if ($i_pageno > 1) {
        echo '<td width=32><a href="question-bank.php?pageno=1' . $i_url_limitto_addon . $i_order_addon . $i_subjectid_addon . '"><img src="images/button-first-big.gif" border=0 title="' . $lngstr ['button_first_page'] . '"></a></td>';
        echo '<td width=32><a href="question-bank.php?pageno=' . max(($i_pageno - 1), 1) . $i_url_limitto_addon . $i_order_addon . $i_subjectid_addon . '"><img src="images/button-prev-big.gif" border=0 title="' . $lngstr ['button_prev_page'] . '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="' . $lngstr ['button_first_page'] . '"></td>';
        echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="' . $lngstr ['button_prev_page'] . '"></td>';
    }
    if ($i_pageno < $i_pageno_count) {
        echo '<td width=32><a href="question-bank.php?pageno=' . min(($i_pageno + 1), $i_pageno_count) . $i_url_limitto_addon . $i_order_addon . $i_subjectid_addon . '"><img src="images/button-next-big.gif" border=0 title="' . $lngstr ['button_next_page'] . '"></a></td>';
        echo '<td width=32><a href="question-bank.php?pageno=' . $i_pageno_count . $i_url_limitto_addon . $i_order_addon . $i_subjectid_addon . '"><img src="images/button-last-big.gif" border=0 title="' . $lngstr ['button_last_page'] . '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-next-big-inactive.gif" border=0 title="' . $lngstr ['button_next_page'] . '"></td>';
        echo '<td width=32><img src="images/button-last-big-inactive.gif" border=0 title="' . $lngstr ['button_last_page'] . '"></td>';
    }
}
echo '<td width=2><img src="images/toolbar-right.gif" width=2 height=32></td></tr></table>';
echo '</td></tr><tr><td>';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr><td class=rowhdr1 title="' . $lngstr ['label_hdr_select_hint'] . '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('question-bank.php?action=' . $i_subjectid_addon . $i_url_limit_addon, $i_tablefields, $i_order_no, $i_direction);
echo '<td class=rowhdr1 colspan=3>' . $lngstr ['label_hdr_action'] . '</td></tr>';
$i = 0;
if (!isset($_GET ['subjectid']) OR $_GET ['subjectid'] == '') {
    if ($G_SESSION['allcoms'] != 1) {
        $courses = $core->getcourses_id($G_SESSION['usercom']);
        foreach ($courses as $course) {
            $courses_apply[$course['id']] = $course['id'];
        }
        $sqladd .= " WHERE kkztquestions.subjectid IN (" . implode(',', $courses_apply) . ")";
    } else
        $sqladd .= '';
} else {
    $sqladd .= " WHERE kkztquestions.subjectid = " . $_GET ['subjectid'];
}

$courses_all = $core->getcourses_all();

//echo "SELECT " . $srv_settings ['table_prefix'] . "questions.questionid, " . $srv_settings ['table_prefix'] . "questions.subjectid, " . $srv_settings ['table_prefix'] . "questions.question_text, " . $srv_settings ['table_prefix'] . "questions.question_time, " . $srv_settings ['table_prefix'] . "questions.question_type, " . $srv_settings ['table_prefix'] . "questions.question_points from kkztquestions WHERE " . $sqladd . $i_sql_order_addon, $i_limitcount, $i_limitfrom ;
$i_rSet1 = $g_db->SelectLimit("SELECT " . $srv_settings ['table_prefix'] . "questions.questionid, " . $srv_settings ['table_prefix'] . "questions.subjectid, " . $srv_settings ['table_prefix'] . "questions.question_text, " . $srv_settings ['table_prefix'] . "questions.question_time, " . $srv_settings ['table_prefix'] . "questions.question_type, " . $srv_settings ['table_prefix'] . "questions.question_points from kkztquestions " . $sqladd . $i_sql_order_addon, $i_limitcount, $i_limitfrom);
if (!$i_rSet1) {
    showDBError(__file__, 1);
} else {
    $i_counter = 0;
    while (!$i_rSet1->EOF) {
        $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
        echo '<tr id=tr_' . $i_pagewide_id . ' class=' . $rowname . ' onmouseover="rollTR(' . $i_pagewide_id . ',1);" onmouseout="rollTR(' . $i_pagewide_id . ',0);"><td align=center width=22><input id=cb_' . $i_pagewide_id . ' type=checkbox name=box_questions[] value="' . $i_rSet1->fields ["questionid"] . '" onclick="toggleCB(this);"></td><td align=right>' . $i_rSet1->fields ["questionid"] . '</td><td><a href="question-bank.php?action=editt' . (isset($_GET ["subjectid"]) && $_GET ["subjectid"] != "" ? "" : '&subjectid=' . $i_rSet1->fields ["subjectid"]) . $i_order_addon . $i_url_limit_addon . '">' . $courses_all[$i_rSet1->fields ["subjectid"]]['name'] . '</a></td><td><a href="question-bank.php?questionid=' . $i_rSet1->fields ["questionid"] . '&action=editq">' . getTruncatedHTML($i_rSet1->fields ["question_text"]) . '</a></td><td>';
        switch ($i_rSet1->fields ["question_type"]) {
            case QUESTION_TYPE_MULTIPLECHOICE :
                echo $lngstr ['label_atype_multiple_choice'];
                break;
            case QUESTION_TYPE_TRUEFALSE :
                echo $lngstr ['label_atype_truefalse'];
                break;
            case QUESTION_TYPE_MULTIPLEANSWER :
                echo $lngstr ['label_atype_multiple_answer'];
                break;
            case QUESTION_TYPE_FILLINTHEBLANK :
                echo $lngstr ['label_atype_fillintheblank'];
                break;
            case QUESTION_TYPE_ESSAY :
                echo $lngstr ['label_atype_essay'];
                break;
            case QUESTION_TYPE_RANDOM :
                echo $lngstr ['label_atype_random'];
                break;
        }
        echo '</td><td align=right>' . ($i_rSet1->fields ["question_type"] != QUESTION_TYPE_RANDOM ? $i_rSet1->fields ["question_points"] : '') . '</td><td align=center width=22>' . ($i_rSet1->fields ["question_type"] != QUESTION_TYPE_RANDOM ? '<a href="question-bank.php?questionid=' . $i_rSet1->fields ["questionid"] . '&action=statsq"><img width=20 height=20 border=0 src="images/button-stats.gif" title="' . $lngstr ['label_action_question_stats'] . '"></a>' : '<img width=20 height=20 border=0 src="images/button-stats-inactive.gif">') . '</td><td align=center width=22><a href="question-bank.php?questionid=' . $i_rSet1->fields ["questionid"] . '&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' . $lngstr ['label_action_question_edit'] . '"></a></td><td align=center width=22><a href="question-bank.php?questionid=' . $i_rSet1->fields ["questionid"] . $i_subjectid_addon . $i_order_addon . $i_url_limit_addon . '&action=deleteq" onclick="return confirmMessage(this, \'' . $lngstr ['qst_delete_question'] . '\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="' . $lngstr ['label_action_question_delete'] . '"></a></td></tr>';
        $i_counter++;
        $i_pagewide_id++;
        $i_rSet1->MoveNext();
    }
    $i_rSet1->Close();
}
echo '</table>';
echo '</td></tr></table></form>';
echo '<br/><center><font style="font-size:16px;">';
echo getPageNav($i_pageno, $i_pageno_count, '/question-bank.php?', $queryvars = $i_url_limitto_addon . $i_order_addon . $i_subjectid_addon, $i_url_limitto_addon = "&limitto=" . $i_limitfrom);
echo '</font></center>';
require_once ($DOCUMENT_INC . "btm.inc.php");
?>
