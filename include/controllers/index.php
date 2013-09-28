<?php

	if (isset($_POST ['login']) and isset($_POST ['pass']) and strlen($_POST ['login']) > 3 and strlen($_POST ['pass']) > 3) {
		$login = $db->qstr($_POST ['login']);
		$pass = $db->qstr(md5($_POST ['pass']));
		$recordSet = &$db->Execute('SELECT `sys_groups`.`id` as sys_gid, `sys_groups`.*, `users`.* from `users`, `sys_groups` WHERE `sys_groups`.`id`=`users`.`sys_group` and `users`.`login`=' . $login . ' and `users`.`pass`=' . $pass . ' LIMIT 1');

		if (!$recordSet) {
			print $db->ErrorMsg();
			die();
		} else {
			if ($recordSet->RecordCount() > 0) {

				if ($recordSet->fields ['sys_g_off'] == 0) {
					if ($recordSet->fields ['active'] == 1) {
						if ($recordSet->fields ['archived'] == 0) {
							if ($recordSet->fields ['cheked'] != 1) {
								$splash->addError(403, 'Даний запис не активований.');
							} else {
								if (!isset($error)) {
									$return = md5(sha1(date("y") . rand(0, time()) . 'site'));
									//$sql = "insert into sessions (`ses`,`user`) values ('" . $return . "','" . $recordSet->fields ['userid'] . "');";
									$sql2 = "insert into stats (`userid`,`do`,`result`,`site`) values (" . $recordSet->fields ['id'] . ",'login','success',0);";
									$sql3 = "update `users`  SET `lastvisit`='" . date('Y-m-d H:i:s') . "' where `users`.`id`=" . $recordSet->fields ['id'] . ";";

									if (/*$db->Execute($sql) === false or*/ $db->Execute($sql2) === false or $db->Execute($sql3) === false) {
										die('error: ' . $db->ErrorMsg());
									} else {
										$_SESSION['session'] = $return;
										$_SESSION['user'] = $recordSet->fields;
										header('Location: /?do=cabinet');
										die();
									}
								}
							}
						} else {
							$splash->addError(403, 'Даний запис заархiвований.');
						}
					} else {
						$splash->addError(403, 'Даний запис деактивований. Звернiться до адмiнiстраторa.');
					}
				} else {
					$splash->addError(403, 'Даний запис деактивований. Причина: ' . $recordSet->fields ['sys_g_woff']);
				}
			} else {
				$splash->addError(403, 'Невірний логін або пароль.');
			}
		}
	} elseif (isset($_SESSION['session']) && $_SESSION['session'] !== NULL) {
		header('Location: /?do=cabinet');
		die();
	}

	include './include/views/index/index.php';
