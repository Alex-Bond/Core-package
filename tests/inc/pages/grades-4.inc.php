<?php
 
if($g_db->Execute("INSERT INTO ".$srv_settings['table_prefix']."gscales (gscale_name) VALUES ('')")===false)
 showDBError(__FILE__, 1);
$i_gscaleid = (int)$g_db->Insert_ID();
gotoLocation('grades.php?gscaleid='.$i_gscaleid.'&action=settings');
?>
