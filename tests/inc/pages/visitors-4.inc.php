<?php
if(isset($_POST["box_visitors"])) {
	foreach($_POST["box_visitors"] as $f_visitorid) {
 deleteVisitor((int)$f_visitorid);
}
} else {
	$f_visitorid = (int)readGetVar('visitorid');
deleteVisitor($f_visitorid);
}
 
gotoLocation('visitors.php'.getURLAddon('', array('action', 'confirmed')));
function deleteVisitor($i_visitorid) {
global $g_db, $srv_settings; 
	if($g_db->Execute("DELETE FROM ".$srv_settings['table_prefix']."visitors WHERE visitorid=$i_visitorid")===false)
 showDBError(__FILE__, 1);
}
?>
