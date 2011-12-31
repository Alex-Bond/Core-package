<?php
$f_gscaleid = (int)readGetVar('gscaleid');
$f_gscale_gradeid = (int)readGetVar('gscale_gradeid');
 
$f_grade_name = readPostVar('grade_name');
$f_grade_name = $g_db->qstr($f_grade_name, get_magic_quotes_gpc());
$f_grade_description = readPostVar('grade_description');
$f_grade_description = $g_db->qstr($f_grade_description, get_magic_quotes_gpc());
$f_grade_from = (float)readPostVar('grade_from');
if($f_grade_from < 0)
 $f_grade_from = 0;
if($f_grade_from > 100)
 $f_grade_from = 100;
$f_grade_to = (float)readPostVar('grade_to');
if($f_grade_to < 0)
 $f_grade_to = 0;
if($f_grade_to > 100)
 $f_grade_to = 100;
 
  
if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."gscales_grades SET grade_name=$f_grade_name, grade_description=$f_grade_description, grade_from='$f_grade_from', grade_to='$f_grade_to' WHERE gscaleid=$f_gscaleid AND gscale_gradeid=$f_gscale_gradeid")===false)
 showDBError(__FILE__, 2);
gotoLocation('grades.php?action=edit&gscaleid='.$f_gscaleid);
?>
