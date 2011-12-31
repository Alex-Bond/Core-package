<?php
$f_testid = (int)readGetVar('testid');
$f_set = isset($_GET["set"]) ? (int)$_GET["set"] : 0; 
if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."tests SET test_enabled=$f_set WHERE testid=$f_testid")===false)
 showDBError(__FILE__, 1);
 
gotoLocation('test-manager.php'.getURLAddon('', array('action')));
?>
