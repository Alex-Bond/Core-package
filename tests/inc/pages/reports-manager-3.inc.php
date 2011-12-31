<?php
require_once($DOCUMENT_INC."top.inc.php");
$f_resultid = (int)readGetVar('resultid');
$f_answerid = (int)readGetVar('answerid');
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="reports-manager.php">'.$lngstr['page_header_results'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="reports-manager.php?action=viewq&resultid='.$f_resultid.'">'.$lngstr['page_header_results_questions'].'</a>', 0));
writePanel2($i_items);
 
$i_can_access = false;
if($G_SESSION['access_reportsmanager'] > 1) {
	$i_can_access = true;
} else { 
	$i_rSet1 = $g_db->Execute("SELECT resultid FROM ".$srv_settings['table_prefix']."results WHERE userid=".$G_SESSION['userid']." AND resultid=".$f_resultid);
if(!$i_rSet1) {
 showDBError(__FILE__, 1);
} else {
 $i_can_access = $i_rSet1->RecordCount() > 0;
}
}
if(!$i_can_access)
 $input_inf_msg = $lngstr['inf_cant_view_this_test_details'];
writeErrorsWarningsBar();
if($i_can_access) { 
	$i_testid = 0;
$i_rSet2 = $g_db->Execute("SELECT testid FROM ".$srv_settings['table_prefix']."results WHERE resultid=".$f_resultid);
if(!$i_rSet2) {
 showDBError(__FILE__, 2);
} else {
 if(!$i_rSet2->EOF) {
 $i_testid = $i_rSet2->fields["testid"];
}
$i_rSet2->Close();
} 
	$i_questionid = 0;
$i_rSet3 = $g_db->Execute("SELECT questionid, result_answer_text, result_answer_points, result_answer_iscorrect, result_answer_feedback FROM ".$srv_settings['table_prefix']."results_answers WHERE resultid=".$f_resultid." AND result_answerid=".$f_answerid);
if(!$i_rSet3) {
 showDBError(__FILE__, 3);
} else {
 if(!$i_rSet3->EOF) {
 $i_questionid = (int)$i_rSet3->fields["questionid"];
$i_result_answer_text = $i_rSet3->fields["result_answer_text"];
$i_result_answer_points = $i_rSet3->fields["result_answer_points"];
$i_result_answer_iscorrect = $i_rSet3->fields["result_answer_iscorrect"];
$i_result_answer_feedback = $i_rSet3->fields["result_answer_feedback"];
}
$i_rSet3->Close();
} 
	$i_rSet4 = $g_db->Execute("SELECT * FROM ".$srv_settings['table_prefix']."questions WHERE questionid=".$i_questionid);
if(!$i_rSet4) {
 showDBError(__FILE__, 4);
} else {
 if(!$i_rSet4->EOF) {
 $i_questiontext = $i_rSet4->fields["question_text"];
$i_questiontype = $i_rSet4->fields["question_type"];
$i_questionpoints = $i_rSet4->fields["question_points"];
}
$i_rSet4->Close();
}
echo '<h2>'.$lngstr['page-report']['correct_answer'].'</h2>';
echo '<p><table cellpadding=0 cellspacing=5 border=0 width="100%">';
echo '<tr><td colspan=2>'.$i_questiontext.'</td></tr>'; 
	$i_answers = array();
$i_rSet5 = $g_db->Execute("SELECT answer_text, answer_correct FROM ".$srv_settings['table_prefix']."answers WHERE questionid=".$i_questionid." ORDER BY answerid");
if(!$i_rSet5) {
 showDBError(__FILE__, 5);
} else {
 $i_answerno = 1;
while(!$i_rSet5->EOF) {
 $i_answers[$i_answerno] = $i_rSet5->fields["answer_text"];
$i_answers_correct[$i_answerno] = $i_rSet5->fields["answer_correct"];
$i_answerno++;
$i_rSet5->MoveNext();
}
$i_rSet5->Close();
}
for($i=1;$i<$i_answerno;$i++) {
 echo '<tr>';
switch($i_questiontype) {
 case QUESTION_TYPE_MULTIPLECHOICE: 
 case QUESTION_TYPE_TRUEFALSE:
 echo '<td width=23 valign=top><nobr><img src="images/select_0'.($i_answers_correct[$i] ? '1' : '0').'.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td width="100%">'.$i_answers[$i].'</td></tr>';
break;
case QUESTION_TYPE_MULTIPLEANSWER: 
 echo '<td width=23 valign=top><nobr><img src="images/select_1'.($i_answers_correct[$i] ? '2' : '0').'.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td width="100%">'.$i_answers[$i].'</td></tr>';
break;
case QUESTION_TYPE_FILLINTHEBLANK:
 echo '<td width=23 valign=top><nobr><img src="images/select_12.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td width="100%">'.nl2br($i_answers[$i]).'</td></tr>';
break;
}
}
echo '</table>';
echo '<h2>'.$lngstr['page-report']['your_answer'].'</h2>';
echo '<p><table cellpadding=0 cellspacing=5 border=0 width="100%">';
switch($i_questiontype) {
 case QUESTION_TYPE_MULTIPLECHOICE:
 case QUESTION_TYPE_TRUEFALSE:
 $i_answers_given = (int)$i_result_answer_text;
echo '<tr><td width=23 valign=top><nobr><img src="images/select_0'.($i_answers_correct[$i_answers_given] ? '1' : '0').'.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td>'.$i_answers[$i_answers_given].'</td></tr>';
break;
case QUESTION_TYPE_MULTIPLEANSWER:
 $i_answers_given = explode(QUESTION_TYPE_MULTIPLEANSWER_BREAK, $i_result_answer_text);
foreach($i_answers_given as $i_answer_given) {
 echo '<tr><td width=23 valign=top><nobr><img src="images/select_1'.($i_answers_correct[$i_answer_given] ? '2' : '0').'.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td>'.$i_answers[$i_answer_given].'</td></tr>';
}
break;
case QUESTION_TYPE_FILLINTHEBLANK:
 echo '<tr><td width=23 valign=top><nobr><img src="images/select_1'.($i_answers[1]==$i_result_answer_text ? '2' : '0').'.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td>'.nl2br($i_result_answer_text).'</td></tr>';
break;
case QUESTION_TYPE_ESSAY:
 echo '<tr><td width=23 valign=top><nobr><img src="images/select_'.($i_result_answer_iscorrect==3 ? '13' : ($i_result_answer_iscorrect==2 ? '12' : ($i_result_answer_iscorrect==1 ? '11' : '10'))).'.gif" width=13 height=15><img src="image/1x1.gif" width=9 height=1></nobr></td>';
echo '<td>'.nl2br($i_result_answer_text).'</td></tr>';
if($G_SESSION['access_reportsmanager'] > 2) {
 echo '<tr>';
echo '<td width=23></td>';
echo '<td width="100%">';
echo '<p><form method=post action="reports-manager.php?resultid='.$f_resultid.'&answerid='.$f_answerid.'&action=setpoints">';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
$i_rowno = 0;
if($i_result_answer_iscorrect==3)
 $i_result_answer_points = -1;
$i_pointvalues = array(-1 => ' ');
for($i=0;$i<=$i_questionpoints;$i++)
 array_push($i_pointvalues, $i);
writeTR2($lngstr['page_editquestion_points'], getSelectElement('points', $i_result_answer_points, $i_pointvalues));
writeTR2($lngstr['page-reports']['answerfeedback'], getTextArea('feedback', $i_result_answer_feedback));
echo '</table>';
echo '<p class=center><input class=btn type=submit name=bsubmit value=" '.$lngstr['button_set'].' "></p></form>';
echo '</td></tr>';
} else { 
 echo '<tr>';
echo '<td width=23></td>';
echo '<td width="100%"><strong>';
echo nl2br($i_result_answer_feedback);
echo '</strong></td></tr>';
}
break;
}
echo '</table>';
}
require_once($DOCUMENT_INC."btm.inc.php");
?>
