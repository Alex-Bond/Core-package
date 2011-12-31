<?php

$g_nocontrolpanel = true;
require_once($DOCUMENT_INC . "top-2.inc.php");
if (!isset($G_SESSION["yt_teststop"])) {
    if ($G_SESSION["yt_testtime"] > 0) {

        $G_SESSION["yt_teststop"] = time() + $G_SESSION["yt_testtime"];
    } else {

        $G_SESSION["yt_teststop"] = 0;
    }
}
if ($G_SESSION["yt_teststop"] > 0) {
    $i_testtime = readDiffTime(time(), $G_SESSION["yt_teststop"]);
    writeVTimer($i_testtime["hours"], $i_testtime["minutes"], $i_testtime["seconds"]);
}
echo '<p><form method=post action="test.php' . ($G_SESSION["yt_state"] == TEST_STATE_QFEEDBACK ? '?action=results' : '') . '"><table cellpadding=0 cellspacing=5 border=0 width="100%">';
echo '<tr><td>';
echo '<table class=rowtable3 cellpadding=5 cellspacing=1 border=0 width="100%">';
echo '<tr class=rowtwo valign=top><td id=vtimer align=center title="' . $lngstr['label_test_timer_hint'] . '">' . $lngstr['label_no_time_limit'] . '</td><td width="80%" id=testname align=center title="' . $lngstr['label_test_testname_hint'] . '">' . convertTextValue($G_SESSION["yt_name"]) . '</td></tr>';
echo '<tr><td class=rowone colspan=2>';
writeErrorsWarningsBar();
if (isset($input_err_msg) && $input_err_msg)
    echo '</td></tr><tr><td class=rowone colspan=2>';
$i_internalerror = false;
echo '<table cellpadding=0 cellspacing=0 border=0 width="100%">';
for ($i_questionno = 1; $i_questionno <= $G_SESSION["yt_questioncount"]; $i_questionno++) {

    $i_questionno_real = $G_SESSION["yt_questions"][$i_questionno - 1];
    $i_questionid = $G_SESSION["yt_questionids"][$i_questionno_real];

    echo '<tr vAlign=top><td width=10 style="padding-top: 8px;"><b>' . $i_questionno . '.&nbsp;</b></td><td width="100%">';
    if (!showTestQuestion($i_questionno, $i_questionid)) {
        $i_internalerror = true;
        break;
    }
    echo '</td></tr>';
}
echo '</table>';
echo '</td></tr>';
if ($G_SESSION["yt_state"] == TEST_STATE_QFEEDBACK) {
    echo '<tr><td class=rowtwo colspan=2><input class=btn type=submit name=bresults value=" ' . $lngstr['button_showresults'] . ' "></td></tr></table>';
} else if ($i_internalerror) {
    unregisterTestData();
    echo '<tr><td class=rowtwo colspan=2><input class=btn type=submit name=bcancel value=" ' . $lngstr['button_cancel'] . ' "></td></tr></table>';
} else {
    echo '<tr><td class=rowtwo colspan=2><input class=btn type=submit name=bsubmit value=" ' . $lngstr['button_submit'] . ' "></td></tr></table>';
}
echo '</td></tr></table></form>';
require_once($DOCUMENT_INC . "btm-2.inc.php");
?>
