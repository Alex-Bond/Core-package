<?php
if(isset($_POST["box_tests"])) {
	foreach($_POST["box_tests"] as $f_testid) {
 deleteTest((int)$f_testid);
}
} else {
	$f_testid = (int)readGetVar('testid');
deleteTest($f_testid);
}
 
gotoLocation('test-manager.php');
function deleteTest($i_testid) {
global $g_db, $srv_settings; 
	$g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."tests_attempts WHERE testid=".$i_testid);
if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."groups_tests WHERE testid=".$i_testid)===false)
 showDBError(__FILE__, 1);
if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."tests_questions WHERE testid=".$i_testid)===false)
 showDBError(__FILE__, 2);
if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."tests WHERE testid=".$i_testid)===false)
 showDBError(__FILE__, 3);
}
?>
