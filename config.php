<?php
require_once("inc/init.inc.php");

if(isset($G_SESSION['userid'])) {
	if($G_SESSION['access_config'] > 0) {
 
 $page_title = $lngstr['page_title_config'];
$i_action = readGetVar('action');
switch($i_action) {
 default:
 if(isset($_POST['bsubmit'])) {
 if($G_SESSION['access_config'] > 1) {
 include_once($DOCUMENT_PAGES."config-3.inc.php");
} else {
 gotoLocation('config.php'.getURLAddon('', array('action')));
}
} else {
 include_once($DOCUMENT_PAGES."config-1.inc.php");
}
}
} else {
 
 $input_inf_msg = $lngstr['inf_cant_access_config'];
include_once($DOCUMENT_PAGES."home.inc.php");
}
} else {
 
	$page_title = $lngstr['page_title_signin'];
include_once($DOCUMENT_PAGES."signin-1.inc.php");
}
?>
