<?php

$rf = '/var/www/kkzfiles.pp.ua/files/'; // With end slash
$max_space = 1024 * 1024 * 100; //100 Mb


include_once './include/kkz.api.php';

$kkz = new kkzapi ();

include_once './include/ndl.class.php';


include_once './include/adodb5/adodb.inc.php';

$db = &ADONewConnection ( 'mysql' );
$db->Connect ( 'localhost', 'root', '', 'core_files' );
$db->query ( "SET NAMES utf8;" );
//$db->debug = true;
$db->Execute ( 'DELETE FROM `sessions` WHERE `time`<(NOW()-1200);' );

if (isset ( $_COOKIE ['PHPSESSID'] )) {
	$sesuid = $_COOKIE ['PHPSESSID'];
	
	$recordSet = &$db->Execute ( "select sessions.* from sessions WHERE sessions.name=" . $db->qstr ( $sesuid ) );
	
	if (! $recordSet)
		die ( $db->ErrorMsg () );
	else {
		if ($recordSet->RecordCount () > 0) {
			$apiuser = $kkz->getuser ( $recordSet->fields ['user'] );
			$user ['id'] = $apiuser ['userid'];
			$user ['name'] = $apiuser ['firstname'];
			$user ['lastname'] = $apiuser ['lastname'];
			$user ['fathername'] = $apiuser ['middlename'];
			$user ['group'] = $apiuser ['usergroup'];
			$user ['access'] = $apiuser ['access'];
			$user ['access'] = $apiuser ['access'];
			$db->Execute ( "update sessions set `time`=NOW() WHERE sessions.name=" . $db->qstr ( $sesuid ) );
		} else {
			setcookie ( 'PHPSESSID', null );
			header ( 'Location: http://kkzcore.pp.ua/?site=4' );
		}
	}
} else {
	if (isset ( $_GET ['key'] )) {
		@session_start ();
		$name = session_id ();
		$apiuser = $kkz->login ( $_GET ['key'] );
		$user ['id'] = $apiuser ['userid'];
		$user ['name'] = $apiuser ['firstname'];
		$user ['lastname'] = $apiuser ['lastname'];
		$user ['fathername'] = $apiuser ['middlename'];
		$user ['group'] = $apiuser ['usergroup'];
		$user ['access'] = $apiuser ['access'];
		$user ['access'] = $apiuser ['access'];
		$db->Execute ( "insert into sessions (`name`,`user`,`time`) VALUES (" . $db->qstr ( $name ) . "," . $db->qstr ( $user ['id'] ) . ",NOW())" );
		
		header ( "Location:  ./" );
	} else {
		header ( 'Location: http://kkzcore.pp.ua/?site=4' );
	}
}
if (isset ( $_GET ['dir'] )) {
	$dir = $_GET ['dir'];
	$dir = str_replace ( "../", "", $dir );
	$dir = str_replace ( "/.", "", $dir );
	$dir = str_replace ( "//", "", $dir );
	$dir = str_replace ( "./", "", $dir );
	$dir = str_replace ( "..", "", $dir );
	$dir_ns = $dir;
	$dir = $dir;
} else {
	$dir = '';
}
//echo $_SERVER['HTTP_REFERER'];
$refm = explode('/',$_SERVER['HTTP_REFERER']);
//print_r($refm);
//die();
if($refm[2]!='kkzfiles.pp.ua'){
	if($refm[2]!='www.kkzfiles.pp.ua'){
	header('Location: http://kkzfiles.pp.ua/');
	die();
	}
}



if ((isset ( $_GET ["f"] )) && ! empty ( $_GET ["f"] ) && (is_dir ( $rf . $user ['id'] . '/' . $dir )) && (is_file ( $rf . $user ['id'] . '/' . $dir . '/' . $_GET ['f'] ))) {
	
	//echo $rf . $user ['id'] . '/' . $dir . $_GET ['f'];
	$ndl = new NDL ($_GET["f"], $rf.$user['id'].'/'.$_GET['dir'], $_GET['f'], CD_DISPLAY);
	$ndl->send();
} else {
	//echo $rf . $user ['id'] . '/' . $dir . $_GET ['f'];
	header('Location: http://kkzfiles.pp.ua/');
}

?>