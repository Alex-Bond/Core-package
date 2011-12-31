<?php
$f_testid = (int)readGetVar('testid');
$f_test_questionid = (int)readGetVar('test_questionid');
 
$i_questioncount = getRecordCount($srv_settings['table_prefix'].'tests_questions', "testid=".$f_testid);
if($f_test_questionid < $i_questioncount) { 
	$g_db->Execute("LOCK TABLES ".$srv_settings['table_prefix']."tests_questions WRITE");
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."tests_questions SET test_questionid=0 WHERE test_questionid=".($f_test_questionid + 1)." AND testid=".$f_testid);
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."tests_questions SET test_questionid=test_questionid+1 WHERE test_questionid=".$f_test_questionid." AND testid=".$f_testid);
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."tests_questions SET test_questionid=".$f_test_questionid." WHERE test_questionid=0 AND testid=".$f_testid);
$g_db->Execute("UNLOCK TABLES");
}
 
gotoLocation('test-manager.php'.getURLAddon('?action=editt', array('action')));
?>
