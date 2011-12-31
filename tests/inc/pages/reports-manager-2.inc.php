<?php
require_once($DOCUMENT_INC."top.inc.php");
$f_resultid = (int)readGetVar('resultid');
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="reports-manager.php">'.$lngstr['page_header_results'].'</a>', 0));
array_push($i_items, array(0 => $lngstr['page_header_results_questions'], 1));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_results_questions'].'</h2>';
$i_pagewide_id = 0;
$i_resultid_addon = "&resultid=".$f_resultid; 
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
	$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(
 array($lngstr['label_report_hdr2_result_answerid'], $lngstr['label_report_hdr2_result_answerid_hint'], $srv_settings['table_prefix']."results_answers.result_answerid"),
 array($lngstr['label_report_hdr2_test_questionid'], $lngstr['label_report_hdr2_test_questionid_hint'], $srv_settings['table_prefix']."results_answers.test_questionid"),
 array($lngstr['label_report_hdr2_result_answer_timespent'], $lngstr['label_report_hdr2_result_answer_timespent_hint'], $srv_settings['table_prefix']."results_answers.result_answer_timespent"),
 array($lngstr['label_report_hdr2_result_answer_text'], $lngstr['label_report_hdr2_result_answer_text_hint'], ""),
 array($lngstr['label_report_hdr2_result_answer_points'], $lngstr['label_report_hdr2_result_answer_points_hint'], $srv_settings['table_prefix']."results_answers.result_answer_points"),
 array($lngstr['label_report_hdr2_result_answer_timeexceeded'], $lngstr['label_report_hdr2_result_answer_timeexceeded_hint'], $srv_settings['table_prefix']."results_answers.result_answer_timeexceeded"),
 array($lngstr['label_report_hdr2_result_answer_iscorrect'], $lngstr['label_report_hdr2_result_answer_iscorrect_hint'], $srv_settings['table_prefix']."results_answers.result_answer_iscorrect"),
	);
$i_order_no = isset($_GET["order"]) ? (int)$_GET["order"] : 0;
if($i_order_no>=count($i_tablefields))
 $i_order_no = -1;
if($i_order_no>=0) {
 $i_direction = (isset($_GET["direction"]) && $_GET["direction"]) ? "DESC" : "";
$i_order_addon = "&order=".$i_order_no."&direction=".$i_direction;
$i_sql_order_addon = " ORDER BY ".$i_tablefields[$i_order_no][2]." ".$i_direction;
} 
	echo '<p><table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr>';
writeQryTableHeaders('reports-manager.php?action=viewq'.$i_resultid_addon, $i_tablefields, $i_order_no, $i_direction);
echo '<td class=rowhdr1 colspan=2 width=22>'.$lngstr['label_hdr_action'].'</td></tr>';
$i_rSet2 = $g_db->Execute("SELECT result_answerid, questionid, test_questionid, result_answer_text, result_answer_points, result_answer_iscorrect, result_answer_timespent, result_answer_timeexceeded FROM ".$srv_settings['table_prefix']."results_answers WHERE resultid=".$f_resultid.$i_sql_order_addon);
if(!$i_rSet2) {
 showDBError(__FILE__, 2);
} else {
 $i_counter = 0;
while(!$i_rSet2->EOF) {
 $rowname= ($i_counter % 2) ? "rowone" : "rowtwo";
echo '<tr id=tr_'.$i_pagewide_id.' class='.$rowname.' onmouseover="rollTR('.$i_pagewide_id.',1);" onmouseout="rollTR('.$i_pagewide_id.',0);"><td align=right>'.$i_rSet2->fields["result_answerid"].'</td><td align=right>'.$i_rSet2->fields["test_questionid"].'</td><td>'.makeTime($i_rSet2->fields["result_answer_timespent"]).'</td><td>'.convertTextValue($i_rSet2->fields["result_answer_text"]).'</td><td align=right>'.$i_rSet2->fields["result_answer_points"].'</td><td align=center>'.($i_rSet2->fields["result_answer_timeexceeded"] ? '<img src="images/button-checkbox-2.gif" width=13 height=13 border=0 title="'.$lngstr['label_yes'].'">' : '<img src="images/button-checkbox-0.gif" width=13 height=13 border=0 title="'.$lngstr['label_no'].'">').'</td><td align=center>'.($i_rSet2->fields["result_answer_iscorrect"]==3 ? '<a href="reports-manager.php?answerid='.$i_rSet2->fields["result_answerid"].$i_resultid_addon.'&action=viewa"><img width=13 height=13 border=0 src="images/button-checkbox-3.gif" title="'.$lngstr['label_undefined'].'"></a>' : ($i_rSet2->fields["result_answer_iscorrect"]==2 ? '<img width=13 height=13 border=0 src="images/button-checkbox-2.gif" title="'.$lngstr['label_yes'].'">' : ($i_rSet2->fields["result_answer_iscorrect"]==1 ? '<img width=13 height=13 border=0 src="images/button-checkbox-1.gif" title="'.$lngstr['label_partially'].'">' : '<img width=13 height=13 border=0 src="images/button-checkbox-0.gif" title="'.$lngstr['label_no'].'">'))).'</td>';
echo '<td align=center width=22><a href="reports-manager.php?answerid='.$i_rSet2->fields["result_answerid"].$i_resultid_addon.'&action=viewa"><img width=20 height=20 border=0 src="images/button-view.gif" title="'.$lngstr['label_action_view_question_result'].'"></a></td>';
if($G_SESSION['access_questionbank'] > 1)
 echo '<td align=center width=22><a href="question-bank.php?questionid='.$i_rSet2->fields["questionid"].'&action=editq"><img width=20 height=20 border=0 src="images/button-edit.gif" title="'.$lngstr['label_action_question_edit'].'"></a></td>';
echo '</tr>';
$i_counter++;
$i_pagewide_id++;
$i_rSet2->MoveNext();
}
$i_rSet2->Close();
}
echo '</table>';
}
require_once($DOCUMENT_INC."btm.inc.php");
?>
