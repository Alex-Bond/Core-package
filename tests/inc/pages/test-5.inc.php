<?php

$g_nocontrolpanel = true;
include_once($DOCUMENT_INC . "top-2.inc.php");
echo '<p><form method=post action="test.php"><table cellpadding=0 cellspacing=5 border=0 width="100%">';
echo '<tr><td>';
$i_testtime = readDiffTime(0, $G_SESSION["yt_testtime"]);
echo '<table class=rowtable3 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr class=rowtwo valign=top><td id=vtimer align=center title="' . $lngstr['label_test_timer_hint'] . '" width=150>' . ($G_SESSION["yt_testtime"] > 0 ? sprintf("<b>%02d:%02d:%02d</b>", $i_testtime["hours"], $i_testtime["minutes"], $i_testtime["seconds"]) : $lngstr['label_no_time_limit']) . '</td><td id=qcounter align=center title="' . sprintf($lngstr['label_questionsindicator_hint'], $G_SESSION["yt_questionno"], $G_SESSION["yt_questioncount"]) . '"><nobr>&nbsp;' . sprintf($lngstr['label_questionsindicator'], $G_SESSION["yt_questionno"], $G_SESSION["yt_questioncount"]) . '&nbsp;</nobr></td><td width="60%" id=testname align=center title="' . $lngstr['label_test_testname_hint'] . '">' . convertTextValue($G_SESSION["yt_name"]) . '</td></tr>';
echo '<tr><td class=rowone colspan=3>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr vAlign=top><td width=1><img src="images/1x1.gif" width=1 height=260></td><td>';
$i_rSet1 = $g_db->SelectLimit("SELECT test_instructions FROM " . $srv_settings['table_prefix'] . "tests WHERE testid=" . $G_SESSION["testid"], 1);
if (!$i_rSet1) {
    showDBError(__FILE__, 1);
} else {
    if (!$i_rSet1->EOF) {

        echo $i_rSet1->fields["test_instructions"];
    }
    $i_rSet1->Close();
}
echo '</td></tr></table></td></tr>';
echo '<tr><td class=rowtwo colspan=3><input class=btn type=submit name=bstarttest value=" ' . $lngstr['button_starttest'] . ' "></td></tr></table>';
echo '</td></tr></table></form>';
include_once($DOCUMENT_INC . "btm-2.inc.php");
?>
