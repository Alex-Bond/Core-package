<?php
 
if($g_db->Execute("INSERT INTO ".$srv_settings['table_prefix']."etemplates (etemplate_name) VALUES ('')")===false)
 showDBError(__FILE__, 1);
$i_etemplateid = (int)$g_db->Insert_ID();
gotoLocation('email-templates.php?etemplateid='.$i_etemplateid.'&action=edit');
?>
