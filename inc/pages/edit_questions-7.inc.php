<?php
$f_testid = (int)readGetVar('testid');
if($f_testid) { 
	$i_subjectid = 0;
$i_rSet1 = $g_db->Execute("SELECT subjectid FROM ".$srv_settings['table_prefix']."tests WHERE testid={$f_testid}");
if(!$i_rSet1) {
 showDBError(__FILE__, 1);
} else {
 if(!$i_rSet1->EOF)
 $i_subjectid = (int)$i_rSet1->fields["subjectid"];
$i_rSet1->Close();
} 
	if($g_db->Execute("INSERT INTO ".$srv_settings['table_prefix']."questions (subjectid) VALUES(".$i_subjectid.")")===false)
 showDBError(__FILE__, 2);
$i_questionid = (int)$g_db->Insert_ID(); 
	createQuestionLink($f_testid, $i_questionid); 
	gotoLocation('question-bank.php'.getURLAddon('?action=editq&questionid='.$i_questionid, array('action', 'questionid')));
} else { 
	if($g_db->Execute("INSERT INTO ".$srv_settings['table_prefix']."questions (question_text) VALUES('')")===false)
 showDBError(__FILE__, 3);
$i_questionid = (int)$g_db->Insert_ID(); 
	gotoLocation('question-bank.php'.getURLAddon('?action=editq&questionid='.$i_questionid, array('action', 'questionid')));
}
?>
