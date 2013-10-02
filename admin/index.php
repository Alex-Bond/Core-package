<?php
session_name('core');
	session_start();

	$corelib_version = "Core Systems 2011.\\nCore version: 1.2.\\nCore Admin version: 1.0.";

	include ("../include/adodb5/adodb.inc.php");

	$db = &ADONewConnection('mysql');
	$db->Connect('localhost', 'root', '', 'core_admin');
	$db->query("SET NAMES utf8;");

	//$db->debug = true;
	if (isset($_SESSION['user']['id'])) {
		$recordSet = &$db->Execute('select users.*, sys_groups.id as sys_g_id, sys_groups.* from users, sys_groups where sys_groups.id=users.sys_group and users.id=' . $_SESSION['user']['id'] . ' and sys_groups.sys_g_admin>0');

		if (!$recordSet) {
			print $db->ErrorMsg();
			die();
		} else {
			if ($recordSet->RecordCount() > 0) {
				$user ['id'] = $recordSet->fields ['id'];
				$user ['name'] = $recordSet->fields ['name'];
				$user ['admin'] = $recordSet->fields ['sys_g_level'];
				$user ['admin_id'] = $recordSet->fields ['sys_g_id'];
				$user ['all'] = $recordSet->fields;
			} else {
				session_destroy();
				header('Location: http://kkzcore.pp.ua/');
				die();
			}
		}

		$recordSet->Close();
	} else {
		header('Location: http://kkzcore.pp.ua/');
		die();
	}

	include ("./include/functions.php");
	include ("./include/ajax.php");
	if (isset($_GET ['do'])) {
		switch ($_GET ['do']) {
			case 'ajax' :
				kkzajax::home();
				die();
			case 'ajax_genpass' :
				kkzajax::genPass();
				die();
			case 'home' :
				$content = kkzcore::home();
				break;
			case 'users' :
				$content = kkzcore::users();
				break;
			case 'groups' :
				$content = kkzcore::groups();
				break;
			case 'coms' :
				$content = kkzcore::coms();
				break;
			case 'courses' :
				$content = kkzcore::courses();
				break;
			case 'stats' :
				$content = kkzcore::stats();
				break;
			case 'options' :
				$content = kkzcore::options();
				break;
			case 'exit' :
				kkzcore::exit_site();
				break;
			default :
				$content = kkzcore::home();
				break;
		}
	} else {
		$content = kkzcore::home();
	}

	$sys_group = $db->Execute('select * from `sys_groups` where `id` = ' . $user ['admin_id'] . ' ');
	if (!$sys_group) {
		print $db->ErrorMsg();
		die();
	}
	if ($sys_group->RecordCount() > 0) {
		$menu [] = '<a href="?">На головну</a>';
		if ($sys_group->fields ['sys_g_admin'] == 1) {
			$menu [] = '<a href="?do=users">Користувачi</a>';
		}
		if ($sys_group->fields ['sys_g_groups'] == 1) {
			$menu [] = '<a href="?do=groups">Групи</a>';
		}
		if ($sys_group->fields ['sys_g_coms'] == 1) {
			$menu [] = '<a href="?do=coms">Комiсiї</a>';
		}
		if ($sys_group->fields ['sys_g_courses'] == 1) {
			$menu [] = '<a href="?do=courses">Предмети</a>';
		}
		if ($sys_group->fields ['sys_g_admin'] == 1) {
			$menu [] = '<a href="?do=stats">Статистика</a>';
		}
		if ($sys_group->fields ['sys_g_options'] == 1) {
			$menu [] = '<a href="?do=options">Системні групи</a>';
		}
		$perc = number_format(100 / count($menu));
		$e_menu = '';
		foreach ($menu as $ins) {
			$e_menu .= '<td align=center width=' . $perc . '%><b>' . $ins . '</b></td>';
		}
	} else {
		die('Sys group error!');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
    <head>
        <title>ККЗ Core</title>
        <meta http-equiv="Content-Language" content="ua">
        <meta content="text/html; charset=utf-8" http-equiv=Content-Type>

    </head>
    <script type="text/javascript" src="/js/jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="/js/jquery.corner.js?v2.01"></script>

    <style type="text/css">
        body {
	        margin: 5px;
	        background: url('/images/bg.jpg');
	        margin-top: 0px;
        }

        body, td, th {
	        font-family: calibri, "segoe UI", arial;
        }

        .content {
	        display: block;
	        background-color: #ececec;
	        padding: 10px;
	        margin-left: 19px;
	        margin-right: 19px;
	        box-shadow: 0 2px 5px #000;
	        -moz-box-shadow: 0 2px 5px #000;
	        -webkit-box-shadow: 0 2px 5px #000;
        }

        .tablecolored tr:hover {
	        background-color: #FFF
        }

        .tablecolored td {
	        border-bottom: 1px dotted #000000
        }
    </style>
    <script type="text/javascript">  
        function uAct() {
	        $.getJSON("/?do=ajax&cron=1");
	        setTimeout("uAct()", 15000);
        }
        uAct();
    </script>
    <body bgcolor="#ffffff">

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="31"><img src="images/gead_bg_01.png" width="31"
                                    height="138" /></td>
                <td valign="top" background="images/gead_bg_02.png">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td height="20" align="right" valign="bottom"><img
		                            src="images/exit.png" width="12" height="12" hspace="5"
		                            align="texttop" /><a href="?do=exit"
                                                         style="color: #000; font-size: 11px; text-decoration: none">Вихід</a></td>
                        </tr>

                        <tr>
                            <td height="70"><img src="/images/core_logo.png" /></td>
                        </tr>
                        <tr>
                            <td height="30">
                                <table cellpadding=0 cellspacing=0 border=0 width="100%">
                                    <tr>
                                        <?php
                                        echo $e_menu;
	                                        ?>
                                    </tr>
                                </table>
                            </td>

                        </tr>
                    </table>
                </td>
                <td width="32"><img src="images/gead_bg_03.png" width="32"
                                    height="138" /></td>
            </tr>
        </table>




        <table cellpadding=0 cellspacing=0 border=0 width="100%" align="center">
            <tr>
                <td class=box_area>
                    <div class="content">

                        <?php
                        echo $content;
	                        ?>

                    </div>

                </td>
            </tr>
        </table>


        <p align="center"
           style="padding-left: 15px; font-family: Tahoma, Geneva, sans-serif; font-size: 10px;"
           onclick="alert('<?php
                        $corelib_version;
	           echo $corelib_version;
           ?>');">
            ККЗ Core &copy; 2009-<?php
           echo date("Y");
	        ?>.</p>

        <script type="text/javascript">
            $('.content').corner();
        </script>
    </body>
</html>
