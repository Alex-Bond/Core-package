<?php
$f_gscaleid = (int)readGetVar('gscaleid');
 
$f_gscale_name = readPostVar('gscale_name');
$f_gscale_name = $g_db->qstr($f_gscale_name, get_magic_quotes_gpc());
$f_gscale_description = readPostVar('gscale_description');
$f_gscale_description = $g_db->qstr($f_gscale_description, get_magic_quotes_gpc());
 
  
if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."gscales SET gscale_name=$f_gscale_name, gscale_description=$f_gscale_description WHERE gscaleid=$f_gscaleid")===false)
 showDBError(__FILE__, 2);
gotoLocation('grades.php');
?>
