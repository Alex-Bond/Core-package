<?php

require_once($DOCUMENT_INC . "top.inc.php");
$i_items = array();

array_push($i_items, array(0 => '<a class=bar2 href="config.php">' . $lngstr['page_header_config'] . '</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="email-templates.php">' . $lngstr['page_header_emailtemplates'] . '</a>', 0));
array_push($i_items, array(0 => '<a class=bar2 href="report-templates.php">' . $lngstr['page_header_rtemplates'] . '</a>', 0));
array_push($i_items, array(0 => $lngstr['page_header_visitors'], 1));
writePanel2($i_items);
echo '<h2>' . $lngstr['page_header_visitors'] . '</h2>';
writeErrorsWarningsBar();
writeInfoBar($lngstr['tooltip_visitors']);
$i_pagewide_id = 0;

$i_direction = "";
$i_order_addon = "";
$i_sql_order_addon = "";
$i_tablefields = array(
    array($lngstr['label_visitors_hdr_visitorid'], $lngstr['label_visitors_hdr_visitorid_hint'], $srv_settings['table_prefix'] . "visitors.visitorid"),
    array($lngstr['label_visitors_hdr_startdate'], $lngstr['label_visitors_hdr_startdate_hint'], $srv_settings['table_prefix'] . "visitors.startdate"),
    array($lngstr['label_visitors_hdr_username'], $lngstr['label_visitors_hdr_username_hint'], $srv_settings['table_prefix'] . "visitors.userid"),
    array($lngstr['label_visitors_hdr_hits'], $lngstr['label_visitors_hdr_hits_hint'], $srv_settings['table_prefix'] . "visitors.hits"),
    array($lngstr['label_visitors_hdr_ip'], $lngstr['label_visitors_hdr_ip_hint'], ""),
    array($lngstr['label_visitors_hdr_host'], $lngstr['label_visitors_hdr_host_hint'], $srv_settings['table_prefix'] . "visitors.host"),
    array($lngstr['label_visitors_hdr_referer'], $lngstr['label_visitors_hdr_referer_hint'], $srv_settings['table_prefix'] . "visitors.referer"),
);
$i_order_no = isset($_GET["order"]) ? (int) $_GET["order"] : 0;
if ($i_order_no >= count($i_tablefields))
    $i_order_no = -1;
if ($i_order_no >= 0) {
    $i_direction = (isset($_GET["direction"]) && $_GET["direction"]) ? "DESC" : "";
    $i_order_addon = "&order=" . $i_order_no . "&direction=" . $i_direction;
    $i_sql_order_addon = " ORDER BY " . $i_tablefields[$i_order_no][2] . " " . $i_direction;
}

$i_url_limitto_addon = "";
$i_url_pageno_addon = "";
$i_url_limit_addon = "";
$i_pageno = 0;
$i_limitcount = isset($_GET["limitto"]) ? (int) $_GET["limitto"] : $G_SESSION['config_itemsperpage'];
if ($i_limitcount > 0) {
    $i_recordcount = getRecordCount($srv_settings['table_prefix'] . 'visitors');
    $i_pageno = isset($_GET["pageno"]) ? (int) $_GET["pageno"] : 1;
    if ($i_pageno < 1)
        $i_pageno = 1;
    $i_limitfrom = ($i_pageno - 1) * $i_limitcount;
    $i_pageno_count = floor(($i_recordcount - 1) / $i_limitcount) + 1;
    if ($i_limitfrom > $i_recordcount) {
        $i_pageno = $i_pageno_count;
        $i_limitfrom = ($i_pageno - 1) * $i_limitcount;
    }
    $i_url_limitto_addon .= "&limitto=" . $i_limitcount;
    $i_url_pageno_addon .= "&pageno=" . $i_pageno;
    $i_url_limit_addon .= $i_url_limitto_addon . $i_url_pageno_addon;
} else {
    $i_url_limitto_addon = "&limitto=";
    $i_url_limit_addon .= $i_url_limitto_addon;
    $i_limitfrom = -1;
    $i_limitcount = -1;
}

echo '<p><form name=visitorsForm class=iactive method=post><table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%" style="background: url(images/toolbar-background.gif) repeat-x"><tr vAlign=center><td width=2><img src="images/toolbar-left.gif" width=2 height=32></td><td width=32><img src="images/button-cross-big.gif" border=0 title="' . $lngstr['label_action_visitors_delete'] . '" style="cursor: hand;" onclick="f=document.visitorsForm;if (confirm(\'' . $lngstr['qst_delete_visitors'] . '\')) { f.action=\'visitors.php?action=delete&confirmed=1\';f.submit();}"></td><td width="100%">&nbsp;</td>';
if ($i_limitcount > 0) {
    if ($i_pageno > 1) {
        echo '<td width=32><a href="visitors.php?pageno=1' . $i_url_limitto_addon . $i_order_addon . '"><img src="images/button-first-big.gif" border=0 title="' . $lngstr['button_first_page'] . '"></a></td>';
        echo '<td width=32><a href="visitors.php?pageno=' . max(($i_pageno - 1), 1) . $i_url_limitto_addon . $i_order_addon . '"><img src="images/button-prev-big.gif" border=0 title="' . $lngstr['button_prev_page'] . '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-first-big-inactive.gif" border=0 title="' . $lngstr['button_first_page'] . '"></td>';
        echo '<td width=32><img src="images/button-prev-big-inactive.gif" border=0 title="' . $lngstr['button_prev_page'] . '"></td>';
    }
    if ($i_pageno < $i_pageno_count) {
        echo '<td width=32><a href="visitors.php?pageno=' . min(($i_pageno + 1), $i_pageno_count) . $i_url_limitto_addon . $i_order_addon . '"><img src="images/button-next-big.gif" border=0 title="' . $lngstr['button_next_page'] . '"></a></td>';
        echo '<td width=32><a href="visitors.php?pageno=' . $i_pageno_count . $i_url_limitto_addon . $i_order_addon . '"><img src="images/button-last-big.gif" border=0 title="' . $lngstr['button_last_page'] . '"></a></td>';
    } else {
        echo '<td width=32><img src="images/button-next-big-inactive.gif" border=0 title="' . $lngstr['button_next_page'] . '"></td>';
        echo '<td width=32><img src="images/button-last-big-inactive.gif" border=0 title="' . $lngstr['button_last_page'] . '"></td>';
    }
}
echo '<td width=2><img src="images/toolbar-right.gif" width=2 height=32></td></tr></table>';
echo '</td></tr><tr><td>';
echo '<table class=rowtable2 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr><td class=rowhdr1 title="' . $lngstr['label_hdr_select_hint'] . '" width=22><input type=checkbox name=toggleAll onclick="toggleCBs(this);"></td>';
writeQryTableHeaders('visitors.php?action=' . $i_url_limit_addon, $i_tablefields, $i_order_no, $i_direction);
echo '<td class=rowhdr1 colspan=2>' . $lngstr['label_hdr_action'] . '</td></tr>';

$i_rSet1 = $g_db->SelectLimit("SELECT *, ip1, ip2, ip3, ip4 FROM " . $srv_settings['table_prefix'] . "visitors " . $i_sql_order_addon, $i_limitcount, $i_limitfrom);
if (!$i_rSet1) {
    showDBError(__FILE__, 1);
} else {
    $i_counter = 0;
    while (!$i_rSet1->EOF) {
        $rowname = ($i_counter % 2) ? "rowone" : "rowtwo";
        $user = getUserName($i_rSet1->fields["userid"]);
        echo '<tr id=tr_' . $i_pagewide_id . ' class=' . $rowname . ' onmouseover="rollTR(' . $i_pagewide_id . ',1);" onmouseout="rollTR(' . $i_pagewide_id . ',0);"><td align=center width=22><input id=cb_' . $i_pagewide_id . ' type=checkbox name=box_visitors[] value="' . $i_rSet1->fields["visitorid"] . '" onclick="toggleCB(this);"></td><td align=right>' . $i_rSet1->fields["visitorid"] . '</td><td>' . date($lngstr['date_format'], $i_rSet1->fields["startdate"]) . '</td><td>' . $i_rSet1->fields["userid"] . ' -' . $user['lastname'] . ' ' . $user['name'] . ' ' . $user['fathername'] . '</td><td>' . ($i_rSet1->fields["hits"]) . '</td><td>' . $i_rSet1->fields["ip1"] . '.' . $i_rSet1->fields["ip2"] . '.' . $i_rSet1->fields["ip3"] . '.' . $i_rSet1->fields["ip4"] . '</td><td>' . truncateString(convertTextValue($i_rSet1->fields["host"])) . '</td><td>' . truncateString(convertTextValue($i_rSet1->fields["referer"])) . '</td>';
        echo '<td align=center width=22><a href="visitors.php?action=view&visitorid=' . $i_rSet1->fields["visitorid"] . $i_order_addon . $i_url_limit_addon . '"><img width=20 height=20 border=0 src="images/button-view.gif" title="' . $lngstr['label_action_visitors_view'] . '"></a></td><td align=center width=22><a href="visitors.php?action=delete&visitorid=' . $i_rSet1->fields["visitorid"] . $i_order_addon . $i_url_limit_addon . '" onclick="return confirmMessage(this, \'' . $lngstr['qst_delete_visitor'] . '\')"><img width=20 height=20 border=0 src="images/button-cross.gif" title="' . $lngstr['label_action_visitor_delete'] . '"></a></td></tr>';
        $i_counter++;
        $i_pagewide_id++;
        $i_rSet1->MoveNext();
    }
    $i_rSet1->Close();
}
echo '</table>';
echo '</td></tr></table></form>';
require_once($DOCUMENT_INC . "btm.inc.php");
?>
