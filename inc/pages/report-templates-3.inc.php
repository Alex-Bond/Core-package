<?php
$f_rtemplateid = (int)readGetVar('rtemplateid');
 
$f_rtemplate_name = readPostVar('rtemplate_name');
$f_rtemplate_name = $g_db->qstr($f_rtemplate_name, get_magic_quotes_gpc());
$f_rtemplate_description = readPostVar('rtemplate_description');
$f_rtemplate_description = $g_db->qstr($f_rtemplate_description, get_magic_quotes_gpc());
$f_rtemplate_body = readPostVar('rtemplate_body');
$f_rtemplate_body = $g_db->qstr($f_rtemplate_body, get_magic_quotes_gpc());
 
  
if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."rtemplates SET rtemplate_name=$f_rtemplate_name, rtemplate_description=$f_rtemplate_description, rtemplate_body=$f_rtemplate_body WHERE rtemplateid=$f_rtemplateid")===false)
 showDBError(__FILE__, 1);
gotoLocation('report-templates.php');
?>
