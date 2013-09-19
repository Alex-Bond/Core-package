<?php
$g_nocontrolpanel = true;
require_once($DOCUMENT_INC."top.inc.php");
$f_username = readPostVar('username');
$_SESSION['site'] = 2;
session_write_close();
header('HTTP/1.1 403 Forbidden');
header("Location: http://kkzcore.pp.ua/?site=". $_SESSION['site']);
die();
echo '<p><table cellpadding=0 cellspacing=5 border=0 width="100%">';
echo '<tr vAlign=top><td width="35%" height="100%" class=signin1>';
echo '<form action="index.php" method=post name=signinform>';
echo $lngstr['page_signin_box_signin_intro'].'<br>';
echo '<br>'.$lngstr['page_signin_box_signin'];
echo '<br><input name=username class=inp type=text value="'.convertTextValue($f_username).'" size=20>';
echo '<br>'.$lngstr['page_signin_box_password'];
echo '<br><input name=password class=inp type=password size=20><br>';
echo '<br><input class=btn type=submit name=bsubmit value=" '.$lngstr['button_signin'].' ">';
$i_cansigninasguest = getRecordCount($srv_settings['table_prefix'].'users', "userid=".SYSTEM_GROUP_GUEST_USERID." AND user_enabled=1") > 0;
if($i_cansigninasguest)
 echo '<br><br><input class=btn type=submit name=bguest value=" '.$lngstr['button_signin_as_guest'].' ">';
echo '</form></td>';
echo '<td height="100%" class=signin2>';
writeErrorsWarningsBar();
echo $lngstr['page_signin_box_intro'];
//echo '<p>'.$lngstr['page_signin_box_register_intro'];
//echo '<p>'.$lngstr['page_signin_box_lostpassword_intro'];
echo '</td></tr></table>';
require_once($DOCUMENT_INC."btm.inc.php");
?>
