<?php
$f_gscaleid = (int)readGetVar('gscaleid');
if(isset($_POST["box_gradescales"])) { 
	$i_gradescales = $_POST["box_gradescales"];
rsort($i_gradescales, SORT_NUMERIC);
foreach($i_gradescales as $f_gscale_gradeid) { 
 deleteScaleGrade($f_gscaleid, (int)$f_gscale_gradeid);
}
} else {
	$f_gscale_gradeid = (int)readGetVar('gscale_gradeid'); 
	deleteScaleGrade($f_gscaleid, $f_gscale_gradeid);
}
 
$i_url_addon = "?action=edit";
foreach($_GET as $key=>$val) {
	if($key<>"action" && $key<>"confirmed") {
 $i_url_addon .= $i_url_addon ? "&" : "?";
$i_url_addon .= urlencode($key)."=".urlencode($val);
}
}
gotoLocation('grades.php'.$i_url_addon);
function deleteScaleGrade($i_gscaleid, $i_gscale_gradeid) {
global $g_db, $srv_settings; 
	if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."gscales_grades WHERE gscaleid=$i_gscaleid AND gscale_gradeid=$i_gscale_gradeid")===false)
 showDBError(__FILE__, 1); 
	if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."gscales_grades SET gscale_gradeid=gscale_gradeid-1 WHERE gscaleid=$i_gscaleid AND gscale_gradeid>$i_gscale_gradeid")===false)
 showDBError(__FILE__, 2);
}
?>
