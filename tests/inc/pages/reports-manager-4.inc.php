<?php
if(isset($_POST["box_results"])) {
	foreach($_POST["box_results"] as $f_resultid) {
 deleteResultRecord((int)$f_resultid);
}
} else {
	$f_resultid = (int)readGetVar('resultid');
deleteResultRecord($f_resultid);
}
 
gotoLocation('reports-manager.php');
?>