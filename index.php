<?php

if (! file_exists ( "inc/config.inc.php" )) {
	header ( 'Location: install.php' );
	exit ();
}
require_once ("inc/init.inc.php");
if (isset ( $G_SESSION ['userid'] )) {
	
	$page_title = $lngstr ['page_title_panel'];
	include_once ($DOCUMENT_PAGES . "home.inc.php");
} else if (isset ( $_GET ['key'] )) {
	
	include_once ($DOCUMENT_PAGES . "signin-2.inc.php");
} else {
	
	$page_title = $lngstr ['page_title_signin'];
	include_once ($DOCUMENT_PAGES . "signin-1.inc.php");
}
?>
