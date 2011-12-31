<?php

// 2010 (c) Alex Bond.


$rf = '/var/www/kkzfiles.pp.ua/files/'; // With end slash
$max_space = 1024 * 1024 * 100; //100 Mb


include_once './include/kkz.api.php';

$kkz = new kkzapi ();

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
			$db->Execute ( "update sessions set `time`=NOW() WHERE sessions.name=" . $db->qstr ( $sesuid ) );
		} else {
			setcookie ( 'PHPSESSID', null );
			header ( 'Location: http://kkzcore.pp.ua/?site=4' );
			die();
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
		$db->Execute ( "insert into sessions (`name`,`user`,`time`) VALUES (" . $db->qstr ( $name ) . "," . $db->qstr ( $user ['id'] ) . ",NOW())" );
		
		header ( "Location:  ./" );
	} else {
		header ( 'Location: http://kkzcore.pp.ua/?site=4' );
		die();
	}
}

if($_GET['do']=='exit'){
	$db->Execute ( 'DELETE FROM `sessions` WHERE `ses`='.$db->qstr($_COOKIE['PHPSESSID']).';' );
		setcookie('PHPSESSID', null);
		header('Location: http://kkzcore.pp.ua/');
		die();
}
if (! is_dir ( $rf . $user ['id'] )) {
	mkdir ( $rf . $user ['id'] );
	header ( "Location:  ./" );
}

if (isset ( $_GET ['dir'] ) and $_GET ['dir'] != '') {
	$dir = $rf . $user ['id'] . '/' . $_GET ['dir'];
	$dir_user = $_GET ['dir'] . '/';
} else {
	$dir = $rf . $user ['id'] . '/';
	$dir_user = '';
}

$dir = str_replace ( "../", "", $dir );
$dir = str_replace ( "/.", "", $dir );
$dir = str_replace ( "//", "", $dir );
$dir = str_replace ( "./", "", $dir );
$dir = str_replace ( "..", "", $dir );

$dir = addslashes ( trim ( $dir ) );

if (is_dir ( $dir )) {
	$files = scandir ( $dir );
} else {
	$error = 'Dir not found';
}
//print_r($files);

function deleteAll($directory, $empty = false) {
    if(substr($directory,-1) == "/") {
        $directory = substr($directory,0,-1);
    }

    if(!file_exists($directory) || !is_dir($directory)) {
        return false;
    } elseif(!is_readable($directory)) {
        return false;
    } else {
        $directoryHandle = opendir($directory);
       
        while ($contents = readdir($directoryHandle)) {
            if($contents != '.' && $contents != '..') {
                $path = $directory . "/" . $contents;
               
                if(is_dir($path)) {
                    deleteAll($path);
                } else {
                    unlink($path);
                }
            }
        }
       
        closedir($directoryHandle);

        if($empty == false) {
            if(!rmdir($directory)) {
                return false;
            }
        }
       
        return true;
    }
} 

if($_GET['do']=='delete'){
	if(isset($_GET['file'])){
		unlink($dir.'/'.$_GET['file']);
	}else{
		deleteAll($dir.'/'.$_GET['dir_d'].'/');
	}
	header('Location: http://kkzfiles.pp.ua/?dir='.$_GET['dir']);
}

foreach ( $files as $item ) {
	if ($item != '.' and $item != '..') {
		if (is_dir ( $dir . '/' . $item )) {
			$dirs [] = $item;
		} else {
			$file [] = $item;
		}
	}
}

function getDirectorySize($path) {
	$totalsize = 0;
	if ($handle = opendir ( $path )) {
		while ( false !== ($file = readdir ( $handle )) ) {
			$nextpath = $path . '/' . $file;
			if ($file != '.' && $file != '..' && ! is_link ( $nextpath )) {
				if (is_dir ( $nextpath )) {
					$result = getDirectorySize ( $nextpath );
					$totalsize += $result ['size'];
				} elseif (is_file ( $nextpath )) {
					$totalsize += filesize ( $nextpath );
				}
			}
		}
	}
	closedir ( $handle );
	$total = $totalsize;
	return $total;
}

function sizeFormat($size) {
	if ($size < 1024) {
		return $size . " байт";
	} else if ($size < (1024 * 1024)) {
		$size = round ( $size / 1024, 1 );
		return $size . " Kb";
	} else if ($size < (1024 * 1024 * 1024)) {
		$size = round ( $size / (1024 * 1024), 1 );
		return $size . " Mb";
	} else {
		$size = round ( $size / (1024 * 1024 * 1024), 1 );
		return $size . " Gb";
	}

}
/* 
echo 'Dirs:';
print_r ( $dirs );
echo 'Files:';
print_r ( $file );
*/
sort ( $dirs );
sort ( $file );

$fullsize_bytes = getDirectorySize ( $rf . $user ['id'] );
$fullsize = number_format ( $fullsize_bytes / ($max_space / 100) );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ККЗ Files</title>
<style type="text/css">
body {
	background: url('./images/bg.jpg');
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

body,td,th,input,select {
	font-family: calibri, "segoe UI", arial;
}

.content {
	display: block;
	background-color:#ececec;
	padding: 10px;
	margin-left: 19px;
	margin-right: 19px;
	box-shadow: 0 2px 5px #000;
	-moz-box-shadow: 0 2px 5px #000;
	-webkit-box-shadow: 0 2px 5px #000;	
}
a.visited {
	color: #000;
}

a:link {
	text-decoration: none;
}

a:visited {
	text-decoration: none;
	color: #00F;
}

a:hover {
	text-decoration: underline;
}

a:active {
	text-decoration: none;
}
</style>
<script type="text/javascript" src="./js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./js/jquery.corner.new.js"></script>
<script type="text/javascript" src="./js/jquery.uploadify.js"></script>
<script type="text/javascript">

function uAct(){
    $.getJSON("http://kkzcore.pp.ua/?do=ajax&cron=1&callback=?");
    setTimeout("uAct()",15000);
}
uAct();

$(document).ready(function() {
	$("#uploadForm").fileUpload({
		'queueID'        : 'fileQueue',
		'uploader': '/swf/uploader.swf',
		'cancelImg': '/images/cancel.png',
		'script': '/upload.php',
		'wmode'          : 'transparent',
		'folder': '<?php echo addslashes ( $dir_user ); ?>',
		'multi': false,
		'auto': true,
		'buttonImg': 'http://kkzfiles.pp.ua/images/upload.png',
		'width' : '397',
		'height' : '46', 
		'sizeLimit': '<?php
		echo $max_space - $fullsize_bytes - 2500;
		?>',
		'onComplete': function(){ window.location.reload(true); },
		'scriptData': {'user':<?php
		echo $user ['id'];
		?>}
	});
	$('.content').corner();
});

function create_folder(){
	var pr = prompt('Назва папки:');
	if(pr!=''){
		 $.post("/create.php?do=cfolder", {name: ""+pr+"",folder:"<?php echo $dir_user; ?>", user:"<?php echo $user ['id']; ?>"}, function(data) { // Do an AJAX call
			if(data=='1'){
				window.location.reload(true);
			}else{
				alert('Помилка створення папки! '+data);
			}
	      });
		}
}

</script>

</head>

<body>
<table width="800" border="0" align="center" cellpadding="0"
	cellspacing="0">
	<tr>
		<td width="31"><img src="./images/gead_bg_01.png" width="31"
			height="138" /></td>
		<td valign="top" background="./images/gead_bg_02.png">
		<table width="800" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td height="20" align="right" valign="bottom"><img
					src="images/exit.png" width="12" height="12" hspace="5"
					align="texttop" /><a href="./?do=exit"
					style="color: #000; font-size: 11px; text-decoration: none">Вихід</a></td>
			</tr>

			<tr>
				<td height="70"><img src="./images/logo.png" /></td>
			</tr>
			<tr>
				<td height="30">
				<table cellpadding=0 cellspacing=0 border=0 width="100%">
					<tr>
						<td align=center><b><a href="?">На головну</a></b></td>
					</tr>
				</table>
				</td>

			</tr>
		</table>
		</td>
		<td width="32"><img src="./images/gead_bg_03.png" width="32"
			height="138" /></td>
	</tr>
</table>

<br />
<table width="830" border="0" align="center" cellpadding="0"
	cellspacing="0">
	<tr>
		<td>

		<div class="content">

		<table width="100%" border="0" align="center" cellpadding="0"
			cellspacing="0">
			<tr>
				<td>
				<p align="center">Зайнято <?php
				echo number_format ( $fullsize_bytes / 1024 / 1024 );
				?> з <?php
				echo number_format ( $max_space / 1024 / 1024 );
				?> MB:</p>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td background="images/empty.png" height="19">
						<div
							style="height: 19px; width: <?php
							echo ($fullsize * 5);
							?>px; background: url(images/load.png);"></div>
						</td>
					</tr>
				</table>
				<br />
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
						<div id='uploadForm'></div>
						</td>
						<td align="right" valign="top"><img src="images/addfolder.png"
							width="396" height="46" style="cursor: pointer"
							onclick="create_folder();" /></td>
					</tr>
				</table>
				<br />
				<?php
				if ($dir != ($rf . $user ['id'] . '/')) {
					$nowm = explode ( $rf . $user ['id'] . '/', $dir );
					$nowm = implode ( $nowm, "/" );
					$nowm = explode ( '/', $nowm );
					//print_r($nowm); 
					unset ( $nowm [count ( $nowm ) - 1] );
					unset ( $nowm [0] );
					$link = implode ( $nowm, "/" );
					
					echo '<div onmouseover="$(this).css(\'background-color\',\'#fff\');"
					onmouseout="$(this).css(\'background-color\',\'\');"
					style="cursor: pointer">
				<table width="100%" border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td width="18" onclick="window.location=\'?dir=' . addslashes ( $link ) . '\';"><img
							src="images/arrow-curve-180-left.png" width="16" height="16" /></td>
						<td onclick="window.location=\'?dir=' . addslashes ( $link ) . '\';"><a href="#">На каталог вище</a></td>
					</tr>
				</table>
				</div>';
				}
				foreach ( $dirs as $item ) {
					echo '<div onmouseover="$(this).css(\'background-color\',\'#fff\');"
					onmouseout="$(this).css(\'background-color\',\'\');"
					style="cursor: pointer">
				<table width="100%" border="0" cellspacing="0" cellpadding="5">
					<tr> 
						<td width="18" onclick="window.location=\'?dir=' . $dir_user . addslashes ( $item ) . '\';"><img
							src="images/folder.png" width="16" height="16" /></td>
						<td onclick="window.location=\'?dir=' . $dir_user . addslashes ( $item ) . '\';"><a href=\'#\'>' . $item . '</a></td>
						<td onclick="window.location=\'?do=delete&dir_d=' . addslashes ( $item ) . '&dir=' . addslashes ( $dir_user ) . '\';" width="20"><a href=\'#\'><img
							src="images/fire.png" width="16" height="16" border="0" /></a></td>
					</tr>
				</table>
				</div>';
				}
				?>
				<br>
				<?php
				foreach ( $file as $item ) {
					echo '<div onmouseover="$(this).css(\'background-color\',\'#fff\');"
					onmouseout="$(this).css(\'background-color\',\'\');"
					style="cursor: pointer">
				<table width="100%" border="0" cellspacing="0" cellpadding="5">
					<tr>
						<td width="18" onclick="window.location=\'dl?dir=' . addslashes ( $dir_user ) . '&f=' . addslashes ( $item ) . '\';"><img
							src="images/document.png" width="16" height="16" /></td>
						<td onclick="window.location=\'dl?dir=' . addslashes ( $dir_user ) . '&f=' . addslashes ( $item ) . '\';"><a href="#">' . $item . '</a></td>
						<td width="200" onclick="window.location=\'dl?dir=' . addslashes ( $dir_user ) . '&f=' . addslashes ( $item ) . '\';">' . sizeFormat ( filesize ( $dir . '/' . $item ) ) . '</td>
						<td width="20" onclick="window.location=\'?do=delete&dir=' . addslashes ( $dir_user ) . '&file=' . addslashes ( $item ) . '\';"><a href="#"><img
							src="images/fire.png" width="16" height="16" border="0" /></a></td>
					</tr>
				</table>
				</div>';
				}
				?>
				
				<p>&nbsp;</p>
				
				</td>
			</tr>
		</table>

		</div>
		</td>
	</tr>
</table>
<br>
<center><font
	style="font-family: Tahoma, Geneva, sans-serif; font-size: 10px;">ККЗ
Core &copy; 2008-<?php echo date('Y'); ?>.</font></center>
<p>&nbsp;</p>

</body>
</html>
