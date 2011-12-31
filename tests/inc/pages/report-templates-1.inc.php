<?php
require_once($DOCUMENT_INC."top.inc.php");
$i_items = array();

array_push($i_items, array(0 => '<a class=bar2 href="config.php">'.$lngstr['page_header_config'].'</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="email-templates.php">'.$lngstr['page_header_emailtemplates'].'</a>', 0));
array_push($i_items, array(0 => $lngstr['page_header_rtemplates'], 1));
array_push($i_items, array(0 => '<a class=bar2 href="visitors.php">'.$lngstr['page_header_visitors'].'</a>', 0));
writePanel2($i_items);
echo '<h2>'.$lngstr['page_header_rtemplates'].'</h2>';
writeErrorsWarningsBar();
writeInfoBar($lngstr['tooltip_rtemplates']);
$i_pagewide_id = 0;
 
$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(
	array($lngstr['page-rtemplates']['hdr_rtemplateid'], $lngstr['page-rtemplates']['hdr_rtemplateid_hint'], $srv_settings['table_prefix']."rtemplates.rtemplateid"),
	array($lngstr['page-rtemplates']['hdr_rtemplate_name'], $lngstr['page-rtemplates']['hdr_rtemplate_name_hint'], $srv_settings['table_prefix']."rtemplates.rtemplate_name"),
	array($lngstr['page-rtemplates']['hdr_rtemplate_description'], $lngstr['page-rtemplates']['hdr_rtemplate_description_hint'], $srv_settings['table_prefix']."rtemplates.rtemplate_description"),
);
$i_order_no = isset($_GET["order"]) ? (int)$_GET["order"] : 0;
if($i_order_no>=count($i_tablefields))
 $i_order_no = -1;
if($i_order_no>=0) {
	$i_direction = (isset($_GET["direction"]) && $_GET["direction"]) ? "DESC" : "";
$i_order_addon = "&order=".$i_order_no."&direction=".$i_direction;
$i_sql_order_addon = " ORDER BY ".$i_tablefields[$i_order_no][2]." ".$i_direction;
}
 
$i_url_limitto_addon = "";
$i_url_pageno_addon = "";
$i_url_limit_addon = "";
$i_pageno = 0;
$i_limitcount = isset($_GET["limitto"]) ? (int)$_GET["limitto"] : $G_SESSION['config_itemsperpage'];
if($i_limitcount>0) {
	$i_recordcount = 0;
$i_recordcount = getRecordCount($srv_settings['table_prefix'].'rtemplates');
$i_pageno = isset($_GET["pageno"]) ? (int)$_GET["pageno"] : 1;
if($i_pageno < 1)
 $i_pageno = 1;
$i_limitfrom = ($i_pageno-1)*$i_limitcount;
$i_pageno_count = floor(($i_recordcount - 1) / $i_limitcount) + 1;
if($i_limitfrom > $i_recordcount) {
 $i_pageno = $i_pageno_count;
$i_limitfrom = ($i_pageno-1)*$i_limitcount;
}
$i_url_limitto_addon .= "&limitto=".$i_limitcount;
$i_url_pageno_addon .= "&pageno=".$i_pageno;
$i_url_limit_addon .= $i_url_limitto_addon.$i_url_pageno_addon;
} else {
	$i_url_limitto_addon = "&limitto=";
$i_url_limit_addon .= $i_url_limitto_addon;
$i_limitfrom = -1;
$i_limitcount = -1;
}
 
echo '<p><form name=rtemplatesForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td><td width=32><a href="report-templates.php?action=create"><img src="images/button-new-big.gif" border=0 title="'.$lngstr['page-rtemplates']['action_create_rtemplate'].'"></a></td><td width=3><img src="images/toolbar-separator.gif" width=3 height=32 border=0></td><td width=32><img src="images/button-cross-big.gif" border=0 title="'.$lngstr['page-rtemplates']['action_rtemplates_delete'].'" style="cursor: hand;" onclick="f=document.rtemplatesForm;if (confirm(\''.$lngstr['page-rtemplates']['qst_rtemplates_delete'].'\')) { f.action=\'report-templates.php?action=delete&confirmed=1\';f.submit();}"></td><td width="100%">&nbsp;</td>';
if($i_limitcount > 0) {
	if($i_pageno > 1) {
 echo '<td width=32><a href="report-templates.php?pageno=1'.$i_url_limitto_addon.$i_order_addon.'"><img src="images/button-first-big.gif" border=0 title="'.$lngstr['button_first_page'].'"></a></td>';
echo '<td width=32><a href="report-templates.php?pageno='.max(($i_pageno-1), 1).$i_url_limitto_addon.$i_order_addon.'"><img src="images/button-prev-big.gif" border=0 title="'.$lngstr['button_prev_page'].'"></a></td>';
} else {
 echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="'.$lngstr['button_first_page'].'"></td>';
echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="'.$lngstr['button_prev_page'].'"></td>';
}
if($i_pageno < $i_pageno_count) {
 echo '<td width=32><a href="report-templates.php?pageno='.min(($i_pageno+1), $i_pageno_count).$i_url_limitto_addon.$i_order_addon.'"><img src="images/button-next-big.gif" border=0 title="'.$lngstr['button_next_page'].'"></a></td>';
echo '<td width=32><a href="report-templates.php?pageno='.$i_pageno_count.$i_url_limitto_addon.$i_order_addon.'"><img src="images/button-last-big.gif" border=0 title="'.$lngstr['button_last_page'].'"></a></td>';
} else {
 echo '<td width=32><img src="images/button-next-big-inactive.gif" border=0 title="'.$lngstr['button_next_page'].'"></td>';
echo '<td width=32><img src="images/button-last-big-inactive.gif" border=0 title="'.$lngstr['button_last_page'].'"></td>';
}
}
echo '<td width=2><img src="images/toolbar-right.gif" width=2 height=32></td></tr></table>';
echo '</td></tr><tr><td>';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr><td class=rowhdr1 title="'.$lngstr['label_hdr_select_hint'].'" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('report-templates.php?action='.$i_url_limit_addon, $i_tablefields, $i_order_no, $i_direction);
echo '<td class=rowhdr1 colspan=2>'.$lngstr['label_hdr_action'].'</td></tr>';
$i_rSet1 = $g_db->SelectLimit("SELECT * FROM ".$srv_settings['table_prefix']."rtemplates WHERE 1=1".$i_sql_order_addon, $i_limitcount, $i_limitfrom);
if(!$i_rSet1) {
	showDBError(__FILE__, 1);
} else {
	$i_counter = 0;
while(!$i_rSet1->EOF) {
 $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
echo '<tr id=tr_'.$i_pagewide_id.' class='.$rowname.' onmouseover="rollTR('.$i_pagewide_id.',1);" onmouseout="rollTR('.$i_pagewide_id.',0);"><td align=center width=22'.($i_rSet1->fields["rtemplateid"] > SYSTEM_RTEMPLATES_MAX_INDEX ? '' : ' class=system').'><input id=cb_'.$i_pagewide_id.' type=checkbox name=box_rtemplates[] value="'.$i_rSet1->fields["rtemplateid"].'" onclick="toggleCB(this);"></td><td align=right>'.$i_rSet1->fields["rtemplateid"].'</td><td>'.getTruncatedHTML($i_rSet1->fields["rtemplate_name"]).'</td><td>'.$i_rSet1->fields["rtemplate_description"].'</td>';
echo '<td align=center width=22><a href="report-templates.php?rtemplateid='.$i_rSet1->fields["rtemplateid"].$i_order_addon.$i_url_limit_addon.'&action=edit"><img width=20 height=20 border=0 src="images/button-edit.gif" title="'.$lngstr['page-rtemplates']['action_rtemplates_edit'].'"></a></td><td align=center width=22>'.($i_rSet1->fields["rtemplateid"] > SYSTEM_RTEMPLATES_MAX_INDEX ? '<a href="report-templates.php?rtemplateid='.$i_rSet1->fields["rtemplateid"].$i_order_addon.$i_url_limit_addon.'&action=delete" onclick="return confirmMessage(this, \''.$lngstr['page-rtemplates']['qst_rtemplate_delete'].'\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="'.$lngstr['page-rtemplates']['action_rtemplate_delete'].'"></a>' : '<img width=20 height=20 border=0 src="images/button-cross-inactive.gif">').'</td></tr>';
$i_counter++;
$i_pagewide_id++;
$i_rSet1->MoveNext();
}
$i_rSet1->Close();
}
echo '</table>';
echo '</td></tr></table></form>';
require_once($DOCUMENT_INC."btm.inc.php");
?>
