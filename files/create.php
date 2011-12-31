<?php
$rf = '/var/www/kkzfiles.pp.ua/files/'; // With end slash
$max_space = 1024 * 1024 * 100; //100 Mb

if (isset ( $_POST ['user'] )) {
	$user ['id'] = $_POST ['user'];
} else {
	header ( "Location:  ./" );
	die ();
}
if (isset ( $_POST ['folder'] )) {
	$dir = $rf . $user ['id'] . '/' . $_POST ['folder'] . '/';
	$dir = str_replace ( "../", "", $dir );
	$dir = str_replace ( "/.", "", $dir );
	$dir = str_replace ( "///", "", $dir );
	$dir = str_replace ( "//", "", $dir );
	$dir = str_replace ( "./", "", $dir );
	$dir = str_replace ( "..", "", $dir );
} else {
	$dir = $rf . $user ['id'] . '/';
	$dir = str_replace ( "///", "", $dir );
	$dir = str_replace ( "//", "", $dir );
	$dir = $dir . '/';
}

if ($_GET ['do'] == 'cfolder') {
	$_POST ['name'] = str_replace ( "'", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( '"', '', $_POST ['name'] );
	$_POST ['name'] = str_replace ( "`", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( "../", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( "/.", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( "///", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( "//", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( "./", "", $_POST ['name'] );
	$_POST ['name'] = str_replace ( "..", "", $_POST ['name'] );

	$up = $dir . '/' . utf8_decode($_POST ['name']);
	$up = str_replace ( "//", "", $up );
		//echo $up;
	if (! is_dir ( $up ) and ! is_file ( $up )) {
		mkdir ( $up );
		echo '1';
	}
}

?>