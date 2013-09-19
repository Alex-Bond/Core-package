<?php
$f_gscaleid = (int)readGetVar('gscaleid');
 
$i_gscale_gradeid = createGrade($f_gscaleid);
gotoLocation('grades.php?action=edits&gscaleid='.$f_gscaleid.'&gscale_gradeid='.$i_gscale_gradeid);
?>