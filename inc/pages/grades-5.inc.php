<?php
if(isset($_POST["box_grades"])) {
	foreach($_POST["box_grades"] as $f_gscaleid) {
 deleteGrade((int)$f_gscaleid);
}
} else {
	$f_gscaleid = (int)readGetVar('gscaleid');
deleteGrade($f_gscaleid);
}
 
$i_url_addon = "";
foreach($_GET as $key=>$val) {
	if($key<>"action" && $key<>"confirmed") {
 $i_url_addon .= $i_url_addon ? "&" : "?";
$i_url_addon .= urlencode($key)."=".urlencode($val);
}
}
gotoLocation('grades.php'.$i_url_addon);
function deleteGrade($i_gscaleid) {
global $g_db, $srv_settings; 
	if($i_gscaleid > SYSTEM_GRADES_MAX_INDEX) {
 if($g_db->Execute("UPDATE ".$srv_settings['table_prefix']."tests SET gscaleid=1 WHERE gscaleid=".$i_gscaleid)===false)
 showDBError(__FILE__, 1);
if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."gscales_grades WHERE gscaleid=$i_gscaleid")===false)
 showDBError(__FILE__, 2);
if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."gscales WHERE gscaleid=$i_gscaleid")===false)
 showDBError(__FILE__, 3);
}
}
?>
