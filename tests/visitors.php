<?php
require_once("inc/init.inc.php");
if(isset($G_SESSION['userid'])) {
	if($G_SESSION['access_visitors'] > 0) { 
 $page_title = $lngstr['page_title_visitors'];
if(isset($_GET['action'])) {
 switch($_GET['action']) {
 case 'view': 
 $page_title = $lngstr['page_title_visitordetails'].$lngstr['item_separator'].$page_title;
if(isset($_GET['visitorid'])) {
 include_once($DOCUMENT_PAGES."visitors-2.inc.php");
}
break;
case 'delete': 
 if($G_SESSION['access_visitors'] > 1) { 
 $f_confirmed = readGetVar('confirmed');
if($f_confirmed==1) {
 if(isset($_GET['visitorid']) || isset($_POST["box_visitors"])) { 
 include_once($DOCUMENT_PAGES."visitors-4.inc.php");
} else {
 gotoLocation('visitors.php'.getURLAddon('', array('action', 'visitorid')));
}
} else if($f_confirmed=='0') {
 gotoLocation('visitors.php'.getURLAddon('', array('action', 'visitorid')));
} else { 
 $i_confirm_header = $lngstr['page_visitors_delete_visitor'];
$i_confirm_request = $lngstr['qst_delete_visitor'];
$i_confirm_url = 'visitors.php'.getURLAddon();
include_once($DOCUMENT_PAGES."confirm.inc.php");
}
} else {
 gotoLocation('visitors.php'.getURLAddon('', array('action', 'visitorid')));
}
break;
default:
 include_once($DOCUMENT_PAGES."visitors-1.inc.php");
}
} else {
 include_once($DOCUMENT_PAGES."visitors-1.inc.php");
}
} else { 
 $input_inf_msg = $lngstr['inf_cant_access_visitors'];
include_once($DOCUMENT_PAGES."home.inc.php");
}
} else { 
	$page_title = $lngstr['page_title_signin'];
include_once($DOCUMENT_PAGES."signin-1.inc.php");
}
?>
