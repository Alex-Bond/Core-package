<?php

require_once ("const.inc.php");
require_once ("config.inc.php");

if (!isset($_SESSION)) {
	session_name ( SYSTEM_SESSION_ID );
	session_start ();
}
if (! isset ( $_SESSION ['MAIN'] ))
	$_SESSION ['MAIN'] = array ();
$G_SESSION = &$_SESSION ['MAIN'];
ob_start();

$DOCUMENT_ROOT = $srv_settings ['dir_root_full'];
$DOCUMENT_INC = $DOCUMENT_ROOT . "inc/";
$DOCUMENT_LANG = $DOCUMENT_INC . "languages/";
$DOCUMENT_PAGES = $DOCUMENT_ROOT . "inc/pages/";
$DOCUMENT_FPDF = $DOCUMENT_ROOT . "inc/fpdf/";
require_once ($DOCUMENT_LANG . $srv_settings ['language'] . ".lng.php");
require_once ($DOCUMENT_INC . "functions.inc.php");
require_once ($DOCUMENT_INC . "adodb/adodb.inc.php");
require_once ($DOCUMENT_INC . "rediska/Rediska.php");
require_once ($DOCUMENT_INC . "core.api.php");
$core = new core_api('http://11.1.1.245/api/?');
require_once ($DOCUMENT_INC . "connect.inc.php");
require_once ($DOCUMENT_INC . "logs.inc.php");
//$g_db->debug = true;

if (! isset ( $G_SESSION ['config_itemsperpage'] )) {
	$G_SESSION ['config_itemsperpage'] = getConfigItem ( CONFIG_list_length );
	if (! $G_SESSION ['config_itemsperpage'])
		$G_SESSION ['config_itemsperpage'] = 30;
}
if (! isset ( $G_SESSION ['config_editortype'] )) {
	$G_SESSION ['config_editortype'] = getConfigItem ( CONFIG_editor_type );
	if (! $G_SESSION ['config_editortype'])
		$G_SESSION ['config_editortype'] = 2;
}

$input_err_msg = "";
$input_inf_msg = "";
$page_title = "";
$page_meta = "";
$page_body_tag = "";
?>
