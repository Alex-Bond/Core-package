<?php
require_once($DOCUMENT_INC."top.inc.php");
$f_gscaleid = (int)readGetVar('gscaleid');
$i_items = array();
array_push($i_items, array(0 => '<a class=bar2 href="test-manager.php">'.$lngstr['page_header_edittests'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="grades.php">'.$lngstr['page_header_grades'].'</a>', 0));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_grades_edit'].'</h2>';
writeErrorsWarningsBar();
 
$i_rSet1 = $g_db->Execute("SELECT * FROM ".$srv_settings['table_prefix']."gscales WHERE gscaleid=$f_gscaleid");
if(!$i_rSet1) {
	showDBError(__FILE__, 1);
} else {
	if(!$i_rSet1->EOF) {
 echo '<p><form method=post action="grades.php?gscaleid='.$f_gscaleid.'&action=settings">';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
$i_rowno = 0;
writeTR2($lngstr['page_grades_gscaleid'], $i_rSet1->fields["gscaleid"]);
writeTR2($lngstr['page_grades_gradename'], getInputElement('gscale_name', $i_rSet1->fields["gscale_name"]));
writeTR2($lngstr['page_grades_gradedescription'], getTextArea('gscale_description', $i_rSet1->fields["gscale_description"]));
$i_scale_text = "";
$i_rSet3 = $g_db->Execute("SELECT grade_name, grade_from, grade_to FROM ".$srv_settings['table_prefix']."gscales_grades WHERE gscaleid=".$f_gscaleid);
if(!$i_rSet3) {
 showDBError(__FILE__, 3);
} else {
 while(!$i_rSet3->EOF) {
 $i_scale_text .= sprintf("%.1f", $i_rSet3->fields["grade_from"]).'% - '.sprintf("%.1f", $i_rSet3->fields["grade_to"]).'% <b>'.$i_rSet3->fields["grade_name"].'</b><br>';
$i_rSet3->MoveNext();
}
$i_rSet3->Close();
}
if($i_scale_text)
 $i_scale_text .= '<br>';
writeTR2($lngstr['page_grades_gradescale'], $i_scale_text.'<a href="grades.php?action=edit&gscaleid='.$i_rSet1->fields["gscaleid"].'">'.$lngstr['page_grades_gradescale_text'].'</a>');
echo '</table>'; 
 echo '<p class=center><input class=btn type=submit name=bsubmit value=" '.$lngstr['button_update'].' "> <input class=btn type=submit name=bcancel value=" '.$lngstr['button_cancel'].' "></form>';
}
$i_rSet1->Close();
}
require_once($DOCUMENT_INC."btm.inc.php");
?>
