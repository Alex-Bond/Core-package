<?php
require_once($DOCUMENT_INC."top.inc.php");
$f_gscaleid = (int)readGetVar('gscaleid');
$f_gscale_gradeid = (int)readGetVar('gscale_gradeid');
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">'.$lngstr['page_header_edittests'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="grades.php">'.$lngstr['page_header_grades'].'</a>', 0));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_grade_settings'].'</h2>';
writeErrorsWarningsBar();
 
$i_rSet1 = $g_db->Execute("SELECT * FROM ".$srv_settings['table_prefix']."gscales_grades WHERE gscaleid=".$f_gscaleid." AND gscale_gradeid=".$f_gscale_gradeid);
if(!$i_rSet1) {
	showDBError(__FILE__, 1);
} else {
	if(!$i_rSet1->EOF) {
 echo '<p><form method=post action="grades.php?action=edits&gscaleid='.$f_gscaleid.'&gscale_gradeid='.$f_gscale_gradeid.'">';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
$i_rowno = 0;
writeTR2($lngstr['page_grade_gscaleid'], $i_rSet1->fields["gscale_gradeid"]);
writeTR2($lngstr['page_grade_gradename'], getInputElement('grade_name', $i_rSet1->fields["grade_name"]));
writeTR2($lngstr['page_grade_gradedescription'], getTextArea('grade_description', $i_rSet1->fields["grade_description"]));
writeTR2($lngstr['page_grade_gradefrom'], getInputElement('grade_from', $i_rSet1->fields["grade_from"]));
writeTR2($lngstr['page_grade_gradeto'], getInputElement('grade_to', $i_rSet1->fields["grade_to"]));
echo '</table>'; 
 echo '<p class=center><input class=btn type=submit name=bsubmit value=" '.$lngstr['button_update'].' "> <input class=btn type=submit name=bcancel value=" '.$lngstr['button_cancel'].' "></form>';
}
$i_rSet1->Close();
}
require_once($DOCUMENT_INC."btm.inc.php");
?>
