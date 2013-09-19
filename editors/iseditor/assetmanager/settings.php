<?php

require_once("../../../inc/const.inc.php");
require_once("../../../inc/config.inc.php");

// Start a session:
session_name(SYSTEM_SESSION_ID);
session_start();
session_register('MAIN');
if(!isset($_SESSION['MAIN']))
 $_SESSION['MAIN'] = array();
$G_SESSION = &$_SESSION['MAIN'];

$bReturnAbsolute=true;
$bIsReadOnly=false;

$sBaseVirtual0= substr($srv_settings['url_files'], 0, strlen($srv_settings['url_files']) - 1);
//Assuming that the path is http://yourserver/Editor/assets/ ("Relative to Root" Path is required)
$sBase0= substr($srv_settings['dir_files_full'], 0, strlen($srv_settings['dir_files_full']) - 1); //The real path
//$sBase0="/home/yourserver/web/Editor/assets"; //example for Unix server
$sName0="Assets";

$sBaseVirtual1="";
$sBase1="";
$sName1="";

$sBaseVirtual2="";
$sBase2="";
$sName2="";

$sBaseVirtual3="";
$sBase3="";
$sName3="";
?>