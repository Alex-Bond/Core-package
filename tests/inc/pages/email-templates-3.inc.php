<?php
$f_etemplateid = (int)readGetVar('etemplateid');
 
$f_etemplate_name = readPostVar('etemplate_name');
$f_etemplate_name = $g_db->qstr($f_etemplate_name, get_magic_quotes_gpc());
$f_etemplate_description = readPostVar('etemplate_description');
$f_etemplate_description = $g_db->qstr($f_etemplate_description, get_magic_quotes_gpc());
$f_etemplate_from = readPostVar('etemplate_from');
$f_etemplate_from = $g_db->qstr($f_etemplate_from, get_magic_quotes_gpc());
$f_etemplate_subject = readPostVar('etemplate_subject');
$f_etemplate_subject = $g_db->qstr($f_etemplate_subject, get_magic_quotes_gpc());
$f_etemplate_body = readPostVar('etemplate_body');
$f_etemplate_body = $g_db->qstr($f_etemplate_body, get_magic_quotes_gpc());
 
  
if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."etemplates SET etemplate_name=$f_etemplate_name, etemplate_description=$f_etemplate_description, etemplate_from=$f_etemplate_from, etemplate_subject=$f_etemplate_subject, etemplate_body=$f_etemplate_body WHERE etemplateid=$f_etemplateid")===false)
 showDBError(__FILE__, 1);
header('Location: email-templates.php');
?>
