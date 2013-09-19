<?php
require_once("inc/init.inc.php");
if(isset($G_SESSION['userid'])) {
	if($G_SESSION['access_gradingsystems'] > 0) { 
 $page_title = $lngstr['page_title_grades'];
if(isset($_GET['action'])) {
 switch($_GET['action']) {
 case 'create': 
 if($G_SESSION['access_gradingsystems'] > 1) { 
 include_once($DOCUMENT_PAGES."grades-4.inc.php");
} else {
 gotoLocation('grades.php');
}
break;
case 'delete': 
 if($G_SESSION['access_gradingsystems'] > 1) {
 $f_confirmed = readGetVar('confirmed'); 
 if($f_confirmed==1) {
 if(isset($_GET['gscaleid']) || isset($_POST["box_grades"])) { 
 include_once($DOCUMENT_PAGES."grades-5.inc.php");
} else {
 gotoLocation('grades.php');
}
} else if($f_confirmed=='0') {
 gotoLocation('grades.php');
} else { 
 $i_confirm_header = $lngstr['page_grades_delete_grade'];
$i_confirm_request = $lngstr['qst_delete_grade'];
$i_confirm_url = 'grades.php'.getURLAddon();
include_once($DOCUMENT_PAGES."confirm.inc.php");
}
} else {
 gotoLocation('grades.php');
}
break;
case 'settings':  
 $page_title = $lngstr['page_title_grades_edit'].$lngstr['item_separator'].$page_title;
if(isset($_GET['gscaleid'])) {
 if(isset($_POST['bsubmit'])) {
 if($G_SESSION['access_gradingsystems'] > 1) {
 include_once($DOCUMENT_PAGES."grades-3.inc.php");
} else {
 gotoLocation('grades.php');
}
} else if(isset($_POST['bcancel'])) {
 gotoLocation('grades.php');
} else {
 include_once($DOCUMENT_PAGES."grades-2.inc.php");
}
}
break;
case 'edit': 
 $page_title = $lngstr['page_title_gradescales'].$lngstr['item_separator'].$page_title;
if(isset($_GET['gscaleid'])) {
 include_once($DOCUMENT_PAGES."grades-6.inc.php");
}
break;
case 'creates': 
 if($G_SESSION['access_gradingsystems'] > 1) { 
 include_once($DOCUMENT_PAGES."grades-9.inc.php");
} else {
 gotoLocation('grades.php?action=edit&gscaleid='.(int)$_GET['gscaleid']);
}
break;
case 'deletes': 
 if($G_SESSION['access_gradingsystems'] > 1) {
 $f_confirmed = readGetVar('confirmed'); 
 if($f_confirmed==1) {
 if(isset($_GET['gscale_gradeid']) || isset($_POST["box_gradescales"])) { 
 include_once($DOCUMENT_PAGES."grades-10.inc.php");
} else {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
}
} else if($f_confirmed=='0') {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
} else { 
 $i_confirm_header = $lngstr['page_gradescales_delete_grade'];
$i_confirm_request = $lngstr['qst_delete_gradescale'];
$i_confirm_url = 'grades.php'.getURLAddon();
include_once($DOCUMENT_PAGES."confirm.inc.php");
}
} else {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action', 'confirmed')));
}
break;
case 'moveup': 
 if($G_SESSION['access_gradingsystems'] > 1) {
 if(isset($_GET['gscaleid']) && isset($_GET['gscale_gradeid']))
 include_once($DOCUMENT_PAGES."grades-11.inc.php");
} else {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
}
break;
case 'movedown': 
 if($G_SESSION['access_gradingsystems'] > 1) {
 if(isset($_GET['gscaleid']) && isset($_GET['gscale_gradeid']))
 include_once($DOCUMENT_PAGES."grades-12.inc.php");
} else {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
}
break;
case 'edits': 
 $page_title = $lngstr['page_title_grade_settings'].$lngstr['item_separator'].$page_title;
if(isset($_GET['gscaleid']) && isset($_GET['gscale_gradeid'])) {
 if(isset($_POST['bsubmit'])) {
 if($G_SESSION['access_gradingsystems'] > 1) {
 include_once($DOCUMENT_PAGES."grades-8.inc.php");
} else {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
}
} else if(isset($_POST['bcancel'])) {
 gotoLocation('grades.php'.getURLAddon('?action=edit', array('action')));
} else {
 include_once($DOCUMENT_PAGES."grades-7.inc.php");
}
}
break;
default:
 include_once($DOCUMENT_PAGES."grades-1.inc.php");
}
} else {
 include_once($DOCUMENT_PAGES."grades-1.inc.php");
}
} else { 
 $input_inf_msg = $lngstr['inf_cant_access_grades'];
include_once($DOCUMENT_PAGES."home.inc.php");
}
} else { 
	$page_title = $lngstr['page_title_signin'];
include_once($DOCUMENT_PAGES."signin-1.inc.php");
}
?>
