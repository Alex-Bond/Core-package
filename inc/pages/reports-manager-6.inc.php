<?php
$f_testid = (int)readGetVar('testid');
$f_userid = (int)readGetVar('userid');
 
if((int)readGetVar('set')) {
	$g_db->Execute("INSERT INTO ".$srv_settings['table_prefix']."tests_attempts (testid, userid, test_attempt_count) VALUES (".$f_testid.", ".$f_userid.", 0)");
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."tests_attempts SET test_attempt_count=999999 WHERE testid=".$f_testid." AND userid=".$f_userid);
} else {
	$g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."tests_attempts WHERE testid=".$f_testid." AND userid=".$f_userid);
}
 
gotoLocation('reports-manager.php'.getURLAddon('?action=', array('action', 'testid', 'userid', 'set')));
?>
