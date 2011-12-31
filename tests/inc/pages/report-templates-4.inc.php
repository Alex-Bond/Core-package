<?php
 
if($g_db->Execute("INSERT INTO ".$srv_settings['table_prefix']."rtemplates (rtemplate_name) VALUES ('')")===false)
 showDBError(__FILE__, 1);
$i_rtemplateid = (int)$g_db->Insert_ID();
gotoLocation('report-templates.php?rtemplateid='.$i_rtemplateid.'&action=edit');
?>
