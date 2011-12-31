<?php
$f_gscaleid = (int)readGetVar('gscaleid');
$f_gscale_gradeid = (int)readGetVar('gscale_gradeid');
 
$i_gradecount = getRecordCount($srv_settings['table_prefix'].'gscales_grades', "gscaleid=".$f_gscaleid);
if($f_gscale_gradeid < $i_gradecount) { 
	$g_db->Execute("LOCK TABLES ".$srv_settings['table_prefix']."gscales_grades WRITE");
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."gscales_grades SET gscale_gradeid=0 WHERE gscale_gradeid=".($f_gscale_gradeid + 1)." AND gscaleid=".$f_gscaleid);
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."gscales_grades SET gscale_gradeid=gscale_gradeid+1 WHERE gscale_gradeid=".$f_gscale_gradeid." AND gscaleid=".$f_gscaleid);
$g_db->Execute("UPDATE ".$srv_settings['table_prefix']."gscales_grades SET gscale_gradeid=".$f_gscale_gradeid." WHERE gscale_gradeid=0 AND gscaleid=".$f_gscaleid);
$g_db->Execute("UNLOCK TABLES");
}
 
gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
?>
