<?php

$g_nocontrolpanel = true;
require_once($DOCUMENT_INC . "top.inc.php");
$i_now = $G_SESSION["yt_teststoppedat"];
$i_timespent_total = $i_now - $G_SESSION['yt_teststart'];
$i_timeexceeded = ($G_SESSION["yt_teststop"] > 0) && ($G_SESSION["yt_teststop"] < $i_now) ? 1 : 0;

$i_gscale_gradeid = 0;
$i_grade_name = '';
if ($G_SESSION["yt_pointsmax"] <> 0)
    $i_percents = ($G_SESSION["yt_got_points"] / $G_SESSION["yt_pointsmax"]) * 100;
else
    $i_percents = 100;
$i_rSet1 = $g_db->SelectLimit("SELECT gscale_gradeid, grade_name FROM " . $srv_settings['table_prefix'] . "gscales_grades WHERE gscaleid=" . $G_SESSION["yt_gscaleid"] . " AND grade_from<=" . $i_percents . " ORDER BY gscale_gradeid ASC", 1);
if (!$i_rSet1) {
    showDBError(__FILE__, 1);
} else {
    if (!$i_rSet1->EOF) {
        $i_gscale_gradeid = $i_rSet1->fields["gscale_gradeid"];
        $i_grade_name = $i_rSet1->fields["grade_name"];
    }
    $i_rSet1->Close();
}

echo '<p><form method=post action="test.php"><table cellpadding=0 cellspacing=5 border=0 width="100%">';
echo '<tr><td>';
echo '<table class=rowtable3 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr class=rowtwo valign=top><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td class=rowone colspan=2>';
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr vAlign=top><td width=1><img src="images/1x1.gif" width=1 height=260></td><td>';
echo '<h2>' . $lngstr['page_test_results'] . '</h2>';
echo "<p>" . sprintf($lngstr['label_result_testname'], convertTextValue($G_SESSION["yt_name"]));
echo "<br>" . sprintf($lngstr['label_result_testdate'], date($lngstr['date_format_full'], $G_SESSION["yt_teststart"]));
echo "<br>" . sprintf($lngstr['label_result_timespent'], getTimeFormatted($i_timespent_total));
echo "<p>";
if ($G_SESSION["yt_result_showanswers"] || $G_SESSION["yt_result_showpoints"] || $G_SESSION["yt_result_showgrade"]) {
    if ($G_SESSION["yt_result_showgrade"])
        echo sprintf($lngstr['label_result_got_grade'], $i_grade_name ? $i_grade_name : '[' . $i_gscale_gradeid . ']') . "<br>";
    if ($G_SESSION["yt_result_showanswers"])
        echo sprintf($lngstr['label_result_got_answers'], $G_SESSION["yt_got_answers"], $G_SESSION["yt_questioncount"]) . "<br>";
    if ($G_SESSION["yt_result_showpoints"]) {
        echo sprintf($lngstr['label_result_got_points'], $G_SESSION["yt_got_points"], $G_SESSION["yt_pointsmax"], ($G_SESSION["yt_pointsmax"] <> 0 ? round(($G_SESSION["yt_got_points"] / $G_SESSION["yt_pointsmax"]) * 100) : 0)) . "<br>";
        if ($G_SESSION["yt_points_pending"] > 0)
            echo sprintf($lngstr['label_result_points_pending'], $G_SESSION["yt_points_pending"]);
    }
} else {
    echo $lngstr['label_result_do_not_show'];
}
if ($G_SESSION["yt_result_showpdf"] && (($G_SESSION["yt_reportgradecondition"] == 0) || ($G_SESSION["yt_reportgradecondition"] >= $i_gscale_gradeid))) {
    echo '<p><a href="getfile.php?action=tpdf&resultid=' . $G_SESSION["resultid"] . '&file=test-results.pdf" target=_blank>' . $lngstr['label_result_showpdf'] . '</a>';
}
echo '</td></tr></table></td></tr>';
/*if ($G_SESSION['access_reportsmanager'] > 0) {
    echo '<tr class=rowtwo><td align=center>';
    writeATag('index.php', $lngstr['page_test_results_homepage']);
    echo "</td><td align=center>";
    writeATag('reports-manager.php?action=viewq&resultid=' . $G_SESSION["resultid"], $lngstr['page_test_results_viewresults']);
} else {
    echo '<tr class=rowtwo><td colspan=2 align=center>';
    writeATag('index.php', $lngstr['page_test_results_homepage']);
}
echo '</td></tr>'; 
 */
echo '</table>';
echo '</td></tr></table></form>';
require_once($DOCUMENT_INC . "btm.inc.php");
?>
