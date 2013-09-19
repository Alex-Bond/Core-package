<?php
$f_resultid = (int)readGetVar('resultid');
$f_answerid = (int)readGetVar('answerid');
$f_points = (int)readPostVar('points');
$f_feedback = readPostVar('feedback');
$f_feedback = $g_db->qstr($f_feedback, get_magic_quotes_gpc());
 
$i_testid = 0;
$i_gscaleid = 0;
$i_rSet2 = $g_db->Execute("SELECT testid, gscaleid FROM ".$srv_settings['table_prefix']."results WHERE resultid=".$f_resultid);
if(!$i_rSet2) {
	showDBError(__FILE__, 2);
} else {
	if(!$i_rSet2->EOF) {
 $i_testid = $i_rSet2->fields["testid"];
$i_gscaleid = $i_rSet2->fields["gscaleid"];
}
$i_rSet2->Close();
} 
$i_questionid = 0;
$i_rSet3 = $g_db->Execute("SELECT questionid, result_answer_text, result_answer_points, result_answer_iscorrect FROM ".$srv_settings['table_prefix']."results_answers WHERE resultid=".$f_resultid." AND result_answerid=".$f_answerid);
if(!$i_rSet3) {
	showDBError(__FILE__, 3);
} else {
	if(!$i_rSet3->EOF) {
 $i_questionid = (int)$i_rSet3->fields["questionid"];
$i_result_answer_text = $i_rSet3->fields["result_answer_text"];
$i_result_answer_points = $i_rSet3->fields["result_answer_points"];
$i_result_answer_iscorrect = $i_rSet3->fields["result_answer_iscorrect"];
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
$f_notdefined = 0;
if($f_points>$i_questionpoints)
 $f_points = $i_questionpoints;
if($f_points==-1) {
	$f_points = 0;
$f_notdefined = 1;
} 
if($i_questiontype==QUESTION_TYPE_ESSAY) { 
	if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."results_answers SET result_answer_points=".$f_points.", result_answer_iscorrect=".($f_notdefined ? 3 : ($f_points>=$i_questionpoints ? 2 : ($f_points>0 ? 1 : 0))).", result_answer_feedback=".$f_feedback." WHERE resultid=".$f_resultid." AND result_answerid=".$f_answerid)===false)
 showDBError(__FILE__, 5); 
	$i_testpoints_max = 0;
$i_rSet6 = $g_db->Execute("SELECT result_pointsmax FROM ".$srv_settings['table_prefix']."results WHERE resultid=".$f_resultid);
if(!$i_rSet6) {
 showDBError(__FILE__, 6);
} else {
 if(!$i_rSet6->EOF) {
 $i_testpoints_max = $i_rSet6->fields["result_pointsmax"];
}
$i_rSet6->Close();
} 
	$i_rSet7 = $g_db->Execute("SELECT SUM(result_answer_points) as testpoints FROM ".$srv_settings['table_prefix']."results_answers WHERE resultid=".$f_resultid);
if(!$i_rSet7) {
 showDBError(__FILE__, 7);
} else {
 if(!$i_rSet7->EOF) {
 $i_testpoints_new = $i_rSet7->fields['testpoints'];
}
$i_rSet7->Close();
} 
	$i_gscale_gradeid = 0;
$i_grade_name = '';
if($i_testpoints_max<>0)
 $i_percents = ($i_testpoints_new / $i_testpoints_max) * 100;
else $i_percents = 100;
$i_rSet8 = $g_db->SelectLimit("SELECT gscale_gradeid, grade_name FROM ".$srv_settings['table_prefix']."gscales_grades WHERE gscaleid=".$i_gscaleid." AND grade_from<=".$i_percents." ORDER BY gscaleid ASC", 1);
if(!$i_rSet8) {
 showDBError(__FILE__, 8);
} else {
 if(!$i_rSet8->EOF) {
 $i_gscale_gradeid = $i_rSet8->fields["gscale_gradeid"];
}
$i_rSet8->Close();
} 
	if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."results SET result_points=".$i_testpoints_new.", gscaleid=".$i_gscaleid.", gscale_gradeid=".$i_gscale_gradeid." WHERE resultid=".$f_resultid)===false)
 showDBError(__FILE__, 9);
}
 
gotoLocation('reports-manager.php?resultid='.$f_resultid.'&action=viewq');
?>
