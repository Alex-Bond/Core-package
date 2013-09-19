<?php

require_once($DOCUMENT_INC . "top.inc.php");
writePanel2(array(
    array(0 => '<a class=bar2 href="question-bank.php">' . $lngstr['page_header_questionbank'] . '</a>', 0)
));
echo '<h2>' . $lngstr['page_header_question_stats'] . '</h2>';
writeErrorsWarningsBar();

function getGauge($i_percentage, $i_color='blue') {
    global $srv_settings, $srv_settings;
    return '<img src="images/gauge-' . $i_color . '-left.gif" width=3 height=12><img src="images/gauge-' . $i_color . '.gif" width=' . round($i_percentage * 300 / 100) . ' height=12><img src="images/gauge-' . $i_color . '-right.gif" width=3 height=12>';
}

function writeQuestionStats($i_questionid) {
    global $g_db, $i_counter, $lngstr, $srv_settings;

    $i_rSet1 = $g_db->SelectLimit("SELECT * FROM " . $srv_settings['table_prefix'] . "questions WHERE questionid=" . $i_questionid, 1);
    if (!$i_rSet1) {
        showDBError(__FILE__, 1);
    } else {
        if (!$i_rSet1->EOF) {
            $i_question_text = $i_rSet1->fields["question_text"];
            $i_question_type = $i_rSet1->fields["question_type"];
        }
        $i_rSet1->Close();
    }

    $i_answers_text = array();
    $i_answercount = 0;
    $i_answers_correct = array();
    $answers_clicks = array();
    $answers_clicks_total = 0;
    $question_views_total = 0;
    $question_correct = 0;
    $question_incorrect = 0;
    $question_partially = 0;
    $question_undefined = 0;
    $i_rSet2 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "answers WHERE questionid=" . $i_questionid . " ORDER BY answerid");
    if (!$i_rSet2) {
        showDBError(__FILE__, 2);
    } else {
        while (!$i_rSet2->EOF) {
            $i_answercount++;
            $i_answers_text[$i_answercount] = $i_rSet2->fields["answer_text"];
            $i_answers_correct[$i_answercount] = $i_rSet2->fields["answer_correct"];
            $answers_clicks[$i_answercount] = 0;
            $i_rSet2->MoveNext();
        }
        $i_rSet2->Close();
    }

    $i_rSet3 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "results_answers WHERE questionid=" . $i_questionid . " ORDER BY result_answerid");
    if (!$i_rSet2) {
        showDBError(__FILE__, 3);
    } else {
        switch ($i_question_type) {
            case QUESTION_TYPE_MULTIPLECHOICE:
            case QUESTION_TYPE_TRUEFALSE:
                while (!$i_rSet3->EOF) {
                    $answers_clicks[(int) $i_rSet3->fields["result_answer_text"]]++;
                    switch ($i_rSet3->fields["result_answer_iscorrect"]) {
                        case 0:
                            $question_incorrect++;
                            break;
                        case 1:
                            $question_partially++;
                            break;
                        case 2:
                            $question_correct++;
                            break;
                    }
                    $answers_clicks_total++;
                    $question_views_total++;
                    $i_rSet3->MoveNext();
                }
                break;
            case QUESTION_TYPE_MULTIPLEANSWER:
                while (!$i_rSet3->EOF) {
                    if ($i_rSet3->fields["result_answer_text"]) {
                        $i_answers_given = explode(QUESTION_TYPE_MULTIPLEANSWER_BREAK, $i_rSet3->fields["result_answer_text"]);
                        foreach ($i_answers_given as $i_answer_given) {
                            $answers_clicks[(int) $i_answer_given]++;
                            $answers_clicks_total++;
                        }
                    }
                    switch ($i_rSet3->fields["result_answer_iscorrect"]) {
                        case 0:
                            $question_incorrect++;
                            break;
                        case 1:
                            $question_partially++;
                            break;
                        case 2:
                            $question_correct++;
                            break;
                    }
                    $question_views_total++;
                    $i_rSet3->MoveNext();
                }
                break;
            case QUESTION_TYPE_FILLINTHEBLANK:
                $i_fillintheblank_text = array();
                $i_fillintheblank_clicks = array();
                $i_fillintheblank_count = 0;
                while (!$i_rSet3->EOF) {
                    $i_found = array_search($i_rSet3->fields["result_answer_text"], $i_fillintheblank_text);
                    if (!$i_found) {
                        $i_fillintheblank_count++;
                        $i_fillintheblank_text[$i_fillintheblank_count] = $i_rSet3->fields["result_answer_text"];
                        $i_fillintheblank_clicks[$i_fillintheblank_count] = 1;
                    } else {
                        $i_fillintheblank_clicks[$i_found]++;
                    }
                    switch ($i_rSet3->fields["result_answer_iscorrect"]) {
                        case 0:
                            $question_incorrect++;
                            break;
                        case 1:
                            $question_partially++;
                            break;
                        case 2:
                            $question_correct++;
                            break;
                    }
                    $answers_clicks_total++;
                    $question_views_total++;
                    $i_rSet3->MoveNext();
                }
                break;
            case QUESTION_TYPE_ESSAY:
                while (!$i_rSet3->EOF) {
                    switch ($i_rSet3->fields["result_answer_iscorrect"]) {
                        case 0:
                            $question_incorrect++;
                            break;
                        case 1:
                            $question_partially++;
                            break;
                        case 2:
                            $question_correct++;
                            break;
                        case 3:
                            $question_undefined++;
                            break;
                    }
                    $answers_clicks_total++;
                    $question_views_total++;
                    $i_rSet3->MoveNext();
                }
                break;
        }
        $i_rSet3->Close();
    }

    switch ($i_question_type) {
        case QUESTION_TYPE_MULTIPLECHOICE:
        case QUESTION_TYPE_TRUEFALSE:
        case QUESTION_TYPE_MULTIPLEANSWER:
        case QUESTION_TYPE_ESSAY:

            echo '<tr id=tr_' . $i_counter . ' class=rowone>';
            echo '<td rowspan=' . ($i_answercount + 5 + ($i_question_type == QUESTION_TYPE_ESSAY ? 1 : 0)) . ' align=right>' . $i_questionid . '</td>';
            echo '<td colspan=4><b>' . getTruncatedHTML($i_question_text, SYSTEM_TRUNCATED_LENGTH_LONG) . '</b></td>';
            echo '<td rowspan=' . ($i_answercount + 5 + ($i_question_type == QUESTION_TYPE_ESSAY ? 1 : 0)) . ' align=center><a href="question-bank.php?questionid=' . $i_questionid . '&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' . $lngstr['label_action_question_edit'] . '"></a></td>';
            echo '</tr>';

            foreach ($i_answers_text as $key => $val) {
                echo '<tr class=rowone>';
                echo '<td>' . getTruncatedHTML($val, SYSTEM_TRUNCATED_LENGTH_LONG) . '</td>';
                if ($answers_clicks_total <> 0) {
                    $i_answers_clicks_percentage = $answers_clicks[(int) $key] * 100 / $answers_clicks_total;
                    echo '<td>' . getGauge($i_answers_clicks_percentage) . '</td>';
                    echo '<td align=right>' . $answers_clicks[(int) $key] . '</td>';
                    echo '<td align=right>' . sprintf("%.2f", $i_answers_clicks_percentage) . '%</td>';
                } else {
                    echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
                }
                echo '</tr>';
            }
            break;
        case QUESTION_TYPE_FILLINTHEBLANK:

            array_multisort($i_fillintheblank_clicks, SORT_DESC, SORT_NUMERIC, $i_fillintheblank_text);
            $i_answercount = min(sizeof($i_fillintheblank_clicks), MAX_STATS_ANSWERS_FILLINTHEBLANK) + 1;

            echo '<tr id=tr_' . $i_counter . ' class=rowone>';
            echo '<td rowspan=' . ($i_answercount + 5) . ' align=right>' . $i_questionid . '</td>';
            echo '<td colspan=4><b>' . getTruncatedHTML($i_question_text, SYSTEM_TRUNCATED_LENGTH_LONG) . '</b></td>';
            echo '<td rowspan=' . ($i_answercount + 5) . ' align=center><a href="question-bank.php?questionid=' . $i_questionid . '&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' . $lngstr['label_action_question_edit'] . '"></a></td>';
            echo '</tr>';

            $i = 0;
            $i_others_clicks = 0;
            $i_others_count = 0;
            foreach ($i_fillintheblank_clicks as $key => $val) {
                $i++;

                if ($i <= MAX_STATS_ANSWERS_FILLINTHEBLANK) {
                    echo '<tr class=rowone>';
                    echo '<td>' . getTruncatedHTML($i_fillintheblank_text[$key], SYSTEM_TRUNCATED_LENGTH_LONG) . '</td>';
                    if ($answers_clicks_total <> 0) {
                        $i_answers_clicks_percentage = $val * 100 / $answers_clicks_total;
                        echo '<td>' . getGauge($i_answers_clicks_percentage) . '</td>';
                        echo '<td align=right>' . $val . '</td>';
                        echo '<td align=right>' . sprintf("%.2f", $i_answers_clicks_percentage) . '%</td>';
                    } else {
                        echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
                    }
                    echo '</tr>';
                } else {

                    $i_others_clicks += $val;
                    $i_others_count++;
                }
            }
            echo '<tr class=rowone>';
            echo '<td>' . sprintf($lngstr['page_questionstats_fillintheblank_others'], $i_others_count) . '</td>';
            if ($answers_clicks_total <> 0) {
                $i_answers_clicks_percentage = $i_others_clicks * 100 / $answers_clicks_total;
                echo '<td>' . getGauge($i_answers_clicks_percentage) . '</td>';
                echo '<td align=right>' . $i_others_clicks . '</td>';
                echo '<td align=right>' . sprintf("%.2f", $i_answers_clicks_percentage) . '%</td>';
            } else {
                echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
            }
            echo '</tr>';
            break;
        case QUESTION_TYPE_RANDOM:

            echo '<tr id=tr_' . $i_counter . ' class=rowone>';
            echo '<td rowspan=5 align=right>' . $i_questionid . '</td>';
            echo '<td colspan=4><b></b></td>';
            echo '<td rowspan=5 align=center><a href="question-bank.php?questionid=' . $i_questionid . '&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="' . $lngstr['label_action_question_edit'] . '"></a></td>';
            echo '</tr>';
            break;
    }
    echo '<tr class=rowone><td colspan=4><img src="images/1x1.gif" width=1 height=5></td></tr>';

    echo '<tr class=rowone>';
    echo '<td>' . $lngstr['page_questionstats_correct_count'] . '</td>';
    if ($question_views_total <> 0) {
        $question_correct_percentage = $question_correct * 100 / $question_views_total;
        echo '<td>' . getGauge($question_correct_percentage, 'green') . '</td>';
        echo '<td align=right>' . $question_correct . '</td>';
        echo '<td align=right>' . sprintf("%.2f", $question_correct_percentage) . '%</td>';
    } else {
        echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
    }
    echo '</tr>';

    echo '<tr class=rowone>';
    echo '<td>' . $lngstr['page_questionstats_partially_count'] . '</td>';
    if ($question_views_total <> 0) {
        $question_partially_percentage = $question_partially * 100 / $question_views_total;
        echo '<td>' . getGauge($question_partially_percentage, 'yellow') . '</td>';
        echo '<td align=right>' . $question_partially . '</td>';
        echo '<td align=right>' . sprintf("%.2f", $question_partially_percentage) . '%</td>';
    } else {
        echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
    }
    echo '</tr>';

    echo '<tr class=rowone>';
    echo '<td>' . $lngstr['page_questionstats_incorrect_count'] . '</td>';
    if ($question_views_total <> 0) {
        $question_incorrect_percentage = $question_incorrect * 100 / $question_views_total;
        echo '<td>' . getGauge($question_incorrect_percentage, 'red') . '</td>';
        echo '<td align=right>' . $question_incorrect . '</td>';
        echo '<td align=right>' . sprintf("%.2f", $question_incorrect_percentage) . '%</td>';
    } else {
        echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
    }
    echo '</tr>';
    if ($i_question_type == QUESTION_TYPE_ESSAY) {

        echo '<tr class=rowone>';
        echo '<td>' . $lngstr['page_questionstats_undefined_count'] . '</td>';
        if ($question_views_total <> 0) {
            $question_undefined_percentage = $question_undefined * 100 / $question_views_total;
            echo '<td>' . getGauge($question_undefined_percentage, 'gray') . '</td>';
            echo '<td align=right>' . $question_undefined . '</td>';
            echo '<td align=right>' . sprintf("%.2f", $question_undefined_percentage) . '%</td>';
        } else {
            echo '<td colspan=3 align=center class=gray>' . $lngstr['label_notapplicable'] . '</td>';
        }
        echo '</tr>';
    }
}

echo '<p><form name=qbankForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr>';
echo '<td class=rowhdr1 title="' . $lngstr['label_questionstats_hdr_questionid_hint'] . '">' . $lngstr['label_questionstats_hdr_questionid'] . '</td>';
echo '<td class=rowhdr1 title="' . $lngstr['label_questionstats_hdr_questiondata_hint'] . '" colspan=2>' . $lngstr['label_questionstats_hdr_questiondata'] . '</td>';
echo '<td class=rowhdr1 title="' . $lngstr['label_questionstats_hdr_answerclicks_hint'] . '">' . $lngstr['label_questionstats_hdr_answerclicks'] . '</td>';
echo '<td class=rowhdr1 title="' . $lngstr['label_questionstats_hdr_answerpercent_hint'] . '">' . $lngstr['label_questionstats_hdr_answerpercent'] . '</td>';
echo '<td class=rowhdr1 colspan=2>' . $lngstr['label_hdr_action'] . '</td></tr>';
$i_counter = 0;
if (isset($_POST["box_questions"])) {
    foreach ($_POST["box_questions"] as $f_questionid) {

        writeQuestionStats($f_questionid);
    }
} else {
    $f_questionid = (int) readGetVar('questionid');

    writeQuestionStats($f_questionid);
}
echo '</table>';
echo '</td></tr></table></form>';
require_once($DOCUMENT_INC . "btm.inc.php");
?>
