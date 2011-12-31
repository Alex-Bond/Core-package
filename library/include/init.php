<?php
/*
 * Init file
 * v. 2.1
 * Autor: Alex Bond
 */

if (! defined ( 'KKZSYSTEM' )) {
	die ( "Error." );
}


include ("./include/setting.inc.php");
include ("./include/adodb5/adodb.inc.php");
include ("./include/core.api.php"); // Core systems API
include ("./include/core.inc.php"); // Core auth class
include ("./include/error.inc.php"); //Errors class
include ("./include/stats.inc.php"); //Stats class


// соединяемся с БД
$db = &ADONewConnection ( 'mysql' );
$db->Connect ( 'localhost', 'root', '', 'core_library' );
$db->query ( "SET NAMES utf8;" );

$core_api = new core_api ( "http://11.1.1.245/api/?" );
$core = new core ( $db, $core_api, "http://kkzcore.pp.ua/", "./", 1 );

if (isset ( $_POST ['do'] ) or isset ( $_POST ['f'] )) {
	$do = $_POST ['do'];
	$f = $_POST ['f'];
} elseif (isset ( $_GET ['do'] ) or isset ( $_GET ['f'] )) {
	$do = $_GET ['do'];
	if (isset ( $_GET ['f'] )) {
		$f = $_GET ['f'];
	} else {
		$f = "";
	}
} else {
	$do = "";
	$f = "";
}

$user = $core->check ( $do );

function test_perms() {
	global $user, $settings;
	if ($user ['access'] < $settings ['admin_access']) {
		core_error::access_error ();
	}
}

include_once ("./include/ajax.php");
include_once ("./include/base.php");


if ($do == 'stats') {
	if (($f == 'home') or ($f == "")) {
		lib_stats::home_html();
	}
	if($f == 'report'){
		lib_stats::report_html();
	}
	if($f == 'print_report'){
		lib_stats::report_print($_GET['start'], $_GET['end'], $_GET['fio']);
	}
	if ($f == 'students') {
		if(!isset($_GET['id'])){
			lib_stats::students_home();
		}else{
			lib_stats::students_id();
		}
	}
	if($f == 'on_flash'){
		lib_stats::on_flash();
	}
}

if (($do == 'base') or ! $do) {
	
	if (($f == 'base') or ($f == "")) {
		home_vis ();
	}
	if ($f == 'listiner') {
		listiner ( $_GET ['com'] );
	}
	if ($f == 'litview') {
		litview ();
	}
	if ($f == 'logout') {
		logout ();
	}
	
	if ($f == 'litadd') {
		test_perms ();
		litadd ();
	}
	if ($f == 'litdel') {
		test_perms ();
		litdel ();
	}
	if ($f == 'litedit') {
		test_perms ();
		litedit ();
	}
	if ($f == 'onflash_admin') {
		test_perms ();
		onflash_admin ();
	}
	if ($f == 'all_edit') {
		test_perms ();
		if ($_GET ['recal'] == "1") {
			edit_all_recals ();
		}
		if (isset ( $_POST ['todo'] )) {
			if ($_POST ['todo'] == "move") {
				edit_all_move ();
			} elseif ($_POST ['todo'] == "del") {
				edit_all_del ();
			}
		} else {
			edit_all_home ();
		}
	}

}

include_once ("./include/header.php");

echo $content;

include_once ("./include/footer.php");

?>