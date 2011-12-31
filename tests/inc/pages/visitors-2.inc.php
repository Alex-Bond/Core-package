<?php
require_once($DOCUMENT_INC."top.inc.php");
$f_visitorid = (int)readGetVar('visitorid');
$i_items = array();

array_push($i_items, array(0 => '<a class=bar2 href="config.php">'.$lngstr['page_header_config'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="email-templates.php">'.$lngstr['page_header_emailtemplates'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="report-templates.php">'.$lngstr['page_header_rtemplates'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="visitors.php">'.$lngstr['page_header_visitors'].'</a>', 0));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_visitordetails'].'</h2>';
writeErrorsWarningsBar();
 
$i_rSet1 = $g_db->Execute("SELECT * FROM ".$srv_settings['table_prefix']."visitors WHERE visitorid=$f_visitorid");
if(!$i_rSet1) {
	showDBError(__FILE__, 1);
} else {
	if(!$i_rSet1->EOF) {
 echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
$i_rowno = 0;
writeTR2($lngstr['page_visitordetails_visitorid'], $i_rSet1->fields["visitorid"]);
writeTR2($lngstr['page_visitordetails_timespent'], getTimeFormatted($i_rSet1->fields["enddate"] - $i_rSet1->fields["startdate"]));
$i_username = '';
$i_rSet2 = $g_db->Execute("SELECT * FROM ".$srv_settings['table_prefix']."users WHERE userid=".$i_rSet1->fields["userid"]);
if($i_rSet2) {
 if(!$i_rSet2->EOF) {
 $i_username = $i_rSet2->fields["user_name"];
}
}
$user = getUserName($i_rSet1->fields["userid"]);
$i_username = utf8_to_cp1251($user->user->lastname). " " .utf8_to_cp1251($user->user->name);
writeTR2($lngstr['page_visitordetails_username'], $i_username);
writeTR2($lngstr['page_visitordetails_hits'], $i_rSet1->fields["hits"]);
writeTR2($lngstr['page_visitordetails_startdate'], date($lngstr['date_format_full'], $i_rSet1->fields["startdate"]));
writeTR2($lngstr['page_visitordetails_inurl'], '<a href="'.$i_rSet1->fields["inurl"].'" target=_blank>'.$i_rSet1->fields["inurl"].'</a>');
writeTR2($lngstr['page_visitordetails_enddate'], date($lngstr['date_format_full'], $i_rSet1->fields["enddate"]));
writeTR2($lngstr['page_visitordetails_outurl'], '<a href="'.$i_rSet1->fields["outurl"].'" target=_blank>'.$i_rSet1->fields["outurl"].'</a>');
writeTR2($lngstr['page_visitordetails_ipaddress'], $i_rSet1->fields["ip1"].'.'.$i_rSet1->fields["ip2"].'.'.$i_rSet1->fields["ip3"].'.'.$i_rSet1->fields["ip4"]);
writeTR2($lngstr['page_visitordetails_host'], $i_rSet1->fields["host"]);
writeTR2($lngstr['page_visitordetails_referer'], '<a href="'.$i_rSet1->fields["referer"].'" target=_blank>'.$i_rSet1->fields["referer"].'</a>');
writeTR2($lngstr['page_visitordetails_useragent'], $i_rSet1->fields["useragent"]);
echo '</table>';
}
$i_rSet1->Close();
}
require_once($DOCUMENT_INC."btm.inc.php");
?>
