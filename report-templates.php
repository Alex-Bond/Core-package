<?php
require_once("inc/init.inc.php");
if(isset($G_SESSION['userid'])) {
	if($G_SESSION['access_reporttemplates'] > 0) { 
 $page_title = $lngstr['page_title_rtemplates'];
if(isset($_GET['action'])) {
 switch($_GET['action']) {
 case 'create':
 if($G_SESSION['access_reporttemplates'] > 1) { 
 include_once($DOCUMENT_PAGES."report-templates-4.inc.php");
} else {
 gotoLocation('report-templates.php');
}
break;
case 'delete':
 if($G_SESSION['access_reporttemplates'] > 1) {
 $f_confirmed = readGetVar('confirmed'); 
 if($f_confirmed==1) {
 if(isset($_GET['rtemplateid']) || isset($_POST["box_rtemplates"])) { 
 include_once($DOCUMENT_PAGES."report-templates-5.inc.php");
} else {
 gotoLocation('report-templates.php');
}
} else if($f_confirmed=='0') {
 gotoLocation('report-templates.php');
} else { 
 $i_confirm_header = $lngstr['page-rtemplates']['delete_rtemplate'];
$i_confirm_request = $lngstr['page-rtemplates']['qst_rtemplate_delete'];
$i_confirm_url = 'report-templates.php?rtemplateid='.(int)$_GET['rtemplateid'].'&action=delete';
include_once($DOCUMENT_PAGES."confirm.inc.php");
}
} else {
 gotoLocation('report-templates.php');
}
break;
case 'edit':
 $page_title = $lngstr['page_title_rtemplates_edit'].$lngstr['item_separator'].$page_title;
if(isset($_GET['rtemplateid'])) {
 if(isset($_POST['bsubmit'])) {
 if($G_SESSION['access_reporttemplates'] > 1) {
 include_once($DOCUMENT_PAGES."report-templates-3.inc.php");
} else {
 gotoLocation('report-templates.php');
}
} else if(isset($_POST['bcancel'])) {
 gotoLocation('report-templates.php');
} else {
 include_once($DOCUMENT_PAGES."report-templates-2.inc.php");
}
}
break;
default:
 include_once($DOCUMENT_PAGES."report-templates-1.inc.php");
}
} else {
 include_once($DOCUMENT_PAGES."report-templates-1.inc.php");
}
} else { 
 $input_inf_msg = $lngstr['inf_cant_access_reporttemplates'];
include_once($DOCUMENT_PAGES."home.inc.php");
}
} else { 
	$page_title = $lngstr['page_title_signin'];
include_once($DOCUMENT_PAGES."signin-1.inc.php");
}
?>
