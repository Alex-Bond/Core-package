<?php
$rf = '/var/www/kkzfiles.pp.ua/files/'; // With end slash
$max_space = 1024 * 1024 * 100; //100 Mb

if( isset($_GET['user'])){
$user['id'] = $_GET['user'];
}else{
header ( "Location:  ./" );
die();
}
if (isset ( $_GET ['folder'] )) {
	$dir = $rf.$user['id'].$_GET ['folder'].'/';
	$dir = str_replace ( "../", "", $dir );
	$dir = str_replace ( "/.", "", $dir );
	$dir = str_replace ( "///", "", $dir );
	$dir = str_replace ( "//", "", $dir );
	$dir = str_replace ( "./", "", $dir );
	$dir = str_replace ( "..", "", $dir );
} else {
	$dir = $rf.$user['id'].'/';
	$dir = str_replace ( "///", "", $dir );
	$dir = str_replace ( "//", "", $dir );
}


if (!empty($_FILES) AND $_FILES['Filedata']['size']<=(1024*1024*10)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $dir.'/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
		
	move_uploaded_file($tempFile,$targetFile);
}

	
echo '1';

?>