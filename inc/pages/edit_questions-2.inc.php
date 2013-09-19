<?php
initTextEditor($G_SESSION['config_editortype'], array('question_text'));
require_once ($DOCUMENT_INC . "top.inc.php");


$f_testid = (int) readGetVar('testid');
$f_questionid = (int) readGetVar('questionid');
$f_answercount = (int) readGetVar('answercount');
$f_question_type = readGetVar('question_type');
$G_SESSION['ckfinder'] = array(
    'type' => 'question',
    'id' => $f_questionid
);
$i_items = array();
if ($f_testid) {
    array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">' . $lngstr['page_header_edittests'] . '</a>', 0));
    array_push($i_items, array(0 => '<a class=bar2 href="grades.php">' . $lngstr['page_header_grades'] . '</a>', 0));
    array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php?action=editt&testid=' . $f_testid . '">' . $lngstr['page_header_test_questions'] . '</a>', 0));
} else {
    array_push($i_items, array(0 => '<a class=bar2 href="question-bank.php">' . $lngstr['page_header_questionbank'] . '</a>', 0));
}writePanel2($i_items);
echo '<h2>' . $lngstr['page_header_edit_question'] . '</h2>';
writeErrorsWarningsBar();
$i_rSet1 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "questions WHERE questionid=" . $f_questionid);
if (!$i_rSet1) {
    showDBError(__file__, 1);
} else {
    if (!$i_rSet1->EOF) {
        if (!is_numeric($f_question_type) || $f_question_type < 0 || $f_question_type > QUESTION_TYPE_COUNT)
            $f_question_type = $i_rSet1->fields["question_type"]; echo '<p><form method=post action="question-bank.php' . getURLAddon() . '" onsubmit="return submitForm();">';
        echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
        $i_rowno = 0;
        $f_subjectid = isset($_GET['subjectid']) ? (int) readGetVar('subjectid') : $i_rSet1->fields["subjectid"];
        writeTR2($lngstr['page_editquestion_type'], getSelectElement('question_type', $f_question_type, $m_question_types, ' onchange="updateQuestion();"'));
        echo '<tr class=rowone valign=top><td>Предмет питання:</td><td>';
        echo getSelectSubj('subjectid', $f_subjectid, array('class' => 'ins', 'id' => 'subjectid'), FALSE);
        echo '</td></tr>';
        $i = 0;
        $i_rSet3 = $g_db->Execute("SELECT * FROM " . $srv_settings['table_prefix'] . "answers WHERE questionid=$f_questionid ORDER BY answerid");
        if (!$i_rSet1) {
            showDBError(__file__, 3);
        } else {
            $i_answercount = (int) $i_rSet3->RecordCount();
            $i_answercount_nonempty = 0;
            if ($f_answercount > 0)
                $i_answercount_nonempty = min($i_answercount, $f_answercount); else
                $i_answercount_nonempty = $i_answercount; switch ($f_question_type) {
                case QUESTION_TYPE_MULTIPLECHOICE:
                case QUESTION_TYPE_MULTIPLEANSWER:
                    if ($f_answercount <= 0 && $i_answercount > 0)
                        $f_answercount = $i_answercount; $m_answercount_items = array(0 => '');
                    for ($i = 2; $i <= MAX_ANSWER_COUNT; $i++)
                        $m_answercount_items[$i] = $i;
                    writeTR2($lngstr['page_editquestion_answer_count'], getSelectElement('answercount', $f_answercount, $m_answercount_items, ' onchange="updateQuestion();"'));
                    if ($f_answercount <= 0 && $i_answercount <= 0)
                        $f_answercount = DEFAULT_ANSWER_COUNT;
                    writeTR2($lngstr['page_editquestion_question_text'], getTextEditor($G_SESSION['config_editortype'], 'question_text', !empty($i_rSet1->fields["question_text"]) ? $i_rSet1->fields["question_text"] : $lngstr['page_editquestion_emptyquestion']));
                    $i = 1;
                    while (!$i_rSet3->EOF && $i <= $i_answercount_nonempty) {
                        writeTR2(sprintf($lngstr['label_choice_no'], $i) . '<br /><br />' . getCheckbox('answer_correct[' . $i . ']', $i_rSet3->fields["answer_correct"], $lngstr['label_accept_as_correct'], ' onclick="changeChoicePercents(this, ' . $i . ')" id="answer_correct"') . '<br />' . getInputElement('answer_percents[' . $i . ']', $i_rSet3->fields["answer_percents"], 3, 0, ' id="answer_perc" style="" ') . ' ' . $lngstr['label_answer_percents'], '<table cellpadding=0 cellspacing=1 border=0 width="100%"><tr vAlign=top><td width="100%">' . getTextEditor(5, 'answer_text[' . $i . ']', $i_rSet3->fields["answer_text"], 3) . '</td></tr></table>');
                        $i_rowno++;
                        //writeTR2(sprintf($lngstr['label_answer_feedback_no'], $i), getTextEditor(0, 'answer_feedback[' . $i . ']', $i_rSet3->fields["answer_feedback"], 3));
                        $i_rSet3->MoveNext();
                        $i++;
                    } for ($i = $i_answercount_nonempty + 1; $i <= $f_answercount; $i++) {
                        writeTR2(sprintf($lngstr['label_choice_no'], $i) . '<br /><br />' . getCheckbox('answer_correct[' . $i . ']', 0, $lngstr['label_accept_as_correct'], ' onclick="changeChoicePercents(this, ' . $i . ')" id="answer_correct"') . '<br />' . getInputElement('answer_percents[' . $i . ']', '0', 3, 0, ' id="answer_perc" ') . ' ' . $lngstr['label_answer_percents'] . '', '<table cellpadding=0 cellspacing=1 border=0 width="100%"><tr vAlign=top><td width="100%">' . getTextEditor(5, 'answer_text[' . $i . ']', '', 3) . '</td></tr></table>');
                        $i_rowno++;
                        //writeTR2(sprintf($lngstr['label_answer_feedback_no'], $i), getTextEditor(0, 'answer_feedback[' . $i . ']', '', 3));
                    } break;
                case QUESTION_TYPE_TRUEFALSE: writeTR2($lngstr['page_editquestion_answer_count'], '2');
                    writeTR2($lngstr['page_editquestion_question_text'], getTextEditor($G_SESSION['config_editortype'], 'question_text', !empty($i_rSet1->fields["question_text"]) ? $i_rSet1->fields["question_text"] : $lngstr['page_editquestion_emptyquestion']));
                    $i = 1;
                    $i_answer_text = $lngstr['label_atype_truefalse_true'];
                    $i_answer_feedback = '';
                    $i_answer_correct = false;
                    $i_answer_percents = 0;
                    if (!$i_rSet3->EOF) {
                        $i_answer_text = $i_rSet3->fields["answer_text"];
                        $i_answer_feedback = $i_rSet3->fields["answer_feedback"];
                        $i_answer_correct = $i_rSet3->fields["answer_correct"];
                        $i_answer_percents = $i_rSet3->fields["answer_percents"];
                        $i_rSet3->MoveNext();
                    }
                    writeTR2(sprintf($lngstr['label_choice_no'], $i) . '<br /><br />' . getOption('answer_correct', $i, $i_answer_correct, $lngstr['label_accept_as_correct'], ' onclick="changeChoicePercents(this, ' . $i . ')" id="answer_correct"') . '<br />' . getInputElement('answer_percents[' . $i . ']', $i_answer_percents, 3, 0, ' id="answer_perc" readonly="readonly"') . ' ' . $lngstr['label_answer_percents'] . '', '<table cellpadding=0 cellspacing=1 border=0 width="100%"><tr vAlign=top><td width="100%">' . getTextEditor(5, 'answer_text[' . $i . ']', $i_answer_text, 3) . '</td></tr></table>');
                    $i_rowno++;
                    //writeTR2(sprintf($lngstr['label_answer_feedback_no'], $i), getTextEditor(0, 'answer_feedback[' . $i . ']', $i_answer_feedback, 3));
                    $i = 2;
                    $i_answer_text = $lngstr['label_atype_truefalse_false'];
                    $i_answer_feedback = '';
                    $i_answer_correct = false;
                    $i_answer_percents = 0;
                    if (!$i_rSet3->EOF) {
                        $i_answer_text = $i_rSet3->fields["answer_text"];
                        $i_answer_feedback = $i_rSet3->fields["answer_feedback"];
                        $i_answer_correct = $i_rSet3->fields["answer_correct"];
                        $i_answer_percents = $i_rSet3->fields["answer_percents"];
                    } writeTR2(sprintf($lngstr['label_choice_no'], $i) . '<br /><br />' . getOption('answer_correct', $i, $i_answer_correct, $lngstr['label_accept_as_correct'], ' onclick="changeChoicePercents(this, ' . $i . ')" id="answer_correct"') . '<br />' . getInputElement('answer_percents[' . $i . ']', $i_answer_percents, 3, 0, ' id="answer_perc" readonly="readonly"') . ' ' . $lngstr['label_answer_percents'] . '</td>', '<table cellpadding=0 cellspacing=1 border=0 width="100%"><tr vAlign=top><td width="100%">' . getTextEditor(5, 'answer_text[' . $i . ']', $i_answer_text, 3) . '</td></tr></table>');
                    $i_rowno++;
                    //writeTR2(sprintf($lngstr['label_answer_feedback_no'], $i), getTextEditor(0, 'answer_feedback[' . $i . ']', $i_answer_feedback, 3));
                    break;
                case QUESTION_TYPE_FILLINTHEBLANK:
                    writeTR2($lngstr['page_editquestion_answer_count'], '1');
                    writeTR2($lngstr['page_editquestion_question_text'], getTextEditor($G_SESSION['config_editortype'], 'question_text', !empty($i_rSet1->fields["question_text"]) ? $i_rSet1->fields["question_text"] : $lngstr['page_editquestion_emptyquestion']));
                    $i = 1;
                    $i_answer_text = '';
                    if (!$i_rSet3->EOF)
                        $i_answer_text = $i_rSet3->fields["answer_text"];
                    writeTR2(sprintf($lngstr['label_answer_text'], $i), getTextEditor(0, 'answer_text[' . $i . ']', $i_answer_text, 3));
                    writeTR2('Важливi:', '
                        <input name="question_upper" type="checkbox" id="upper" ' . (($i_rSet1->fields['question_upper'] == 1) ? 'checked="checked"' : '') . ' /><label for="upper">Регистр букв</label><br />
                        <input name="question_spaces" type="checkbox" id="spaces" ' . (($i_rSet1->fields['question_spaces'] == 1) ? 'checked="checked"' : '') . ' onChange="toggle2spaces();" /><label for="spaces">Пробелы</label><br />
                        <input name="question_2spaces" type="checkbox" id="2spaces" ' . (($i_rSet1->fields['question_2spaces'] == 1) ? 'checked="checked"' : '') . ' ' . (($i_rSet1->fields['question_spaces'] == 0) ? 'disabled' : '') . ' /><label for="2spaces">Двойные пробелы</label><br />
                        <input name="question_comas" type="checkbox" id="comas" ' . (($i_rSet1->fields['question_comas'] == 1) ? 'checked="checked"' : '') . ' /><label for="comas">Запятые</label>');
                    break;
                case QUESTION_TYPE_ESSAY: writeTR2($lngstr['page_editquestion_answer_count'], $lngstr['label_notapplicable']);
                    writeTR2($lngstr['page_editquestion_question_text'], getTextEditor($G_SESSION['config_editortype'], 'question_text', !empty($i_rSet1->fields["question_text"]) ? $i_rSet1->fields["question_text"] : $lngstr['page_editquestion_emptyquestion']));
                    break;
                case QUESTION_TYPE_RANDOM: writeTR2($lngstr['page_editquestion_question_name'], getInputElement('question_text', !empty($i_rSet1->fields["question_text"]) ? $i_rSet1->fields["question_text"] : $lngstr['label_atype_random'] . ' (' . $i_subjects[$f_subjectid] . ')'));
                    break;
            }
            $i_rSet3->Close();
        } if ($f_question_type <> QUESTION_TYPE_RANDOM) {
            writeTR2($lngstr['page_editquestion_points'], getInputElement("question_points", $i_rSet1->fields["question_points"], 3));
        } echo '</table>';
        echo '<p class=center><input class=btn type=submit name=bsubmit value=" ' . $lngstr['button_update'] . ' "> <input class=btn type=submit name=bsubmit2 value=" ' . $lngstr['button_update_and_create_new_question'] . ' "> <input class=btn type=submit name=bcancel value=" ' . $lngstr['button_cancel'] . ' "></form>';
        ?>
        <script language=JavaScript type="text/javascript">
            function toggle2spaces(){
                var chkInput = document.getElementById('spaces');
                var txtInput = document.getElementById('2spaces');      
                txtInput.disabled = 1 - chkInput.checked; 
            }
            function updateQuestion() {
                ctlQuestionType = document.getElementById("question_type");
                nQuestionType = ctlQuestionType ? document.getElementById("question_type").options[document.getElementById("question_type").selectedIndex].value : "";
                ctlSubjectID = document.getElementById("subjectid");
                nSubjectID = ctlSubjectID ? ctlSubjectID.options[ctlSubjectID.selectedIndex].value : "";
                ctlAnswerCount = document.getElementById("answercount") ? document.getElementById("answercount") : null ;
                nAnswerCount = ctlAnswerCount ? ctlAnswerCount.options[ctlAnswerCount.selectedIndex].value : 1;
                window.open("question-bank.php<?php echo getURLAddon('', array('question_type', 'subjectid', 'answercount')); ?>&question_type="+nQuestionType+"&subjectid="+nSubjectID+"&answercount="+nAnswerCount,"_top");
            }
        </script>

        <?php
    } $i_rSet1->Close();
}require_once ($DOCUMENT_INC . "btm.inc.php");
?>